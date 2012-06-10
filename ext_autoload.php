<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$incPath = t3lib_extMgm::extPath('lightcase') . 'classes/';

return array (
	'tx_lightcase_init' => $incPath . 'class.tx_lightcase_init.php'
);

?>