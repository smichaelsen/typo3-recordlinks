<?php
namespace Smichaelsen\Recordlinks\Form\Element;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Imaging\Icon;

class InlineRecordLink extends AbstractFormElement
{

    /**
     * Handler for single nodes
     *
     * @return array As defined in initializeResultArray() of AbstractNode
     */
    public function render()
    {
        $resultArray = $this->initializeResultArray();
        $resultArray['html'] = sprintf(
            '<div class="panel panel-default">
                <div class="panel-heading">
                    %s
                    <a href="#" onclick="%s">%s</a>
                </div>
            </div>',
            $this->iconFactory->getIconForRecord($this->data['tableName'], $this->data['databaseRow'], Icon::SIZE_SMALL)->render(),
            BackendUtility::editOnClick('&edit[' . $this->data['tableName'] . '][' . $this->data['vanillaUid'] . ']=edit'),
            $this->data['recordTitle']
        );
        return $resultArray;
    }
}
