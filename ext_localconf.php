<?php

if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

if (TYPO3_MODE === 'BE') {
	// Hooks for processing custom acl
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_userauthgroup.php']['fetchGroups_postProcessing'][$_EXTKEY] =
		'GeorgRinger\\BeAclFile\\Hooks\BackendUserAuthentication->get';

	// Hook to have original configuration as copyable text
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$_EXTKEY] =
		'EXT:' . $_EXTKEY . '/Classes/Hooks/DataHandler.php:GeorgRinger\\BeAclFile\\Hooks\\DataHandler';
}