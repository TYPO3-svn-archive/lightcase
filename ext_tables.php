<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$tempColumns = array (
	'tx_lightcase_slideshow' => array (		
		'exclude' => 0,
		'label' => 'LLL:EXT:lightcase/locallang_db.xml:tt_content.tx_lightcase_slideshow',	
		'config' => array (
			'type' => 'check',
			'items' => array(
				array(
				 	'LLL:EXT:lightcase/locallang_db.xml:tt_content.tx_lightcase_slideshow.enabled',
				)
			)
		)
	),
);

global $_EXTKEY;
t3lib_div::loadTCA('tt_content');
t3lib_extMgm::addTCAcolumns('tt_content', $tempColumns, 1);
t3lib_extMgm::addStaticFile($_EXTKEY, 'static/', 'Lightcase');

$GLOBALS['TCA']['tt_content']['palettes']['imagelinks']['showitem'] = 'image_link, image_zoom, tx_lightcase_slideshow';
?>