<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

	// Include pageTSConfig
t3lib_extMgm::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:lightcase/static/pageTSConfig.txt">');

	// Hook
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['postInit']['lightcase'] = 'EXT:lightcase/classes/hooks/class.tx_lightcase_tslib_content.php:tx_lightcase_tslib_content';

?>