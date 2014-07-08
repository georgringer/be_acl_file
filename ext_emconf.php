<?php

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Backend Usergroup access with files',
	'description' => 'Move access configuration of backend users to files',
	'category' => 'be',
	'author' => 'Georg Ringer',
	'author_email' => 'typo3@ringerge.org',
	'author_company' => 'www.montagmorgen.at',
	'shy' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => '0',
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'version' => '0.0.2',
	'constraints' => array(
		'depends' => array(
			'typo3' => '6.2.0-6.2.99',
		),
		'conflicts' => array(),
		'suggests' => array(),
	),
);