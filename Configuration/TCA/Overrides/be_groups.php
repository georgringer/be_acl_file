<?php
$fields = array(
	'ini_export' => array(
		'label' => 'LLL:EXT:be_acl_file/Resources/Private/Language/locallang.xml:be_groups.ini_export',
		'config' => array(
			'type' => 'user',
			'userFunc' => 'GeorgRinger\\BeAclFile\\Hooks\\FormEngine->renderIniConfiguration',
			'readOnly' => TRUE
		)
	)
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('be_groups', $fields);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('be_groups', 'ini_export', '', 'after:allowed_languages');