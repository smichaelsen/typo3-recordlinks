<?php
namespace Smichaelsen\Recordlinks\Form\Container;

use TYPO3\CMS\Backend\Form\Container\InlineControlContainer;
use TYPO3\CMS\Backend\Form\InlineStackProcessor;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Lang\LanguageService;

class RecordListContainer extends InlineControlContainer
{

    /**
     * Handler for single nodes
     *
     * @return array As defined in initializeResultArray() of AbstractNode
     */
    public function render()
    {
        $parameterArray = $this->data['parameterArray'];
        if (
            !isset($parameterArray['fieldConf']['config']['subType']) ||
            $parameterArray['fieldConf']['config']['subType'] !== 'links'
        ) {
            return parent::render();
        }

        /** @var InlineStackProcessor $inlineStackProcessor */
        $inlineStackProcessor = GeneralUtility::makeInstance(InlineStackProcessor::class);
        $this->inlineStackProcessor = $inlineStackProcessor;
        $inlineStackProcessor->initializeByGivenStructure($this->data['inlineStructure']);
        $nameObject = $inlineStackProcessor->getCurrentStructureDomObjectIdPrefix($this->data['inlineFirstPid']);
        $row = $this->data['databaseRow'];
        $config = $parameterArray['fieldConf']['config'];
        $resultArray = $this->initializeResultArray();
        $childrenHtml = '';
        foreach ($this->data['parameterArray']['fieldConf']['children'] as $options) {
            $options['inlineParentUid'] = $row['uid'];
            $options['inlineFirstPid'] = $this->data['inlineFirstPid'];
            // @todo: this can be removed if this container no longer sets additional info to $config
            $options['inlineParentConfig'] = $config;
            $options['inlineData'] = $this->inlineData;
            $options['inlineStructure'] = $inlineStackProcessor->getStructure();
            $options['inlineExpandCollapseStateArray'] = $this->data['inlineExpandCollapseStateArray'];
            $options['renderType'] = 'inlineRecordLink';
            $childResult = $this->nodeFactory->create($options)->render();
            $childrenHtml .= $childResult['html'];
            $childArray['html'] = '';
            $resultArray = $this->mergeChildReturnIntoExistingResult($resultArray, $childResult);
            if (!$options['isInlineDefaultLanguageRecordInLocalizedParentContext']) {
                // Don't add record to list of "valid" uids if it is only the default
                // language record of a not yet localized child
                $sortableRecordUids[] = $options['databaseRow']['uid'];
            }
        }
        $title = $this->getLanguageService()->sL(trim($parameterArray['fieldConf']['label']));
        $markup =
<<<'MARKUP'
    <div class="form-group" id="%1$s">
        <div class="panel-group panel-hover" data-title="%2$s" id="%1$s_records">
            %3$s
        </div>
        %4$s
    </div>
MARKUP;

        $resultArray['html'] = sprintf(
            $markup,
            $nameObject,
            htmlspecialchars($title),
            $childrenHtml,
            $this->getLevelInteractionLink('newRecord', $nameObject . '-' . $config['foreign_table'], $config)
        );
        return $resultArray;
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }

}
