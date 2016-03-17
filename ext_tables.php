<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][] = [
    'nodeName' => 'inline',
    'priority' => 60,
    'class' => \Smichaelsen\Recordlinks\Form\Container\RecordListContainer::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][] = [
    'nodeName' => 'inlineRecordLink',
    'priority' => 60,
    'class' => \Smichaelsen\Recordlinks\Form\Element\InlineRecordLink::class,
];
