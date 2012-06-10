<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Cornel Boppart <cornel@bopp-art.com>
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(t3lib_extMgm::extPath('lightcase') . 'classes/class.tx_lightcase_init.php');

class tx_lightcase_tslib_content extends tx_lightcase_init implements tslib_content_PostInitHook {
	/**
	 * Called from tslib_content::postInit
	 *
	 * @param	tslib_cObj	&$parentObject	Referenced cObject
	 * @return	Hooks into the postProcess of cObjects
	 */
	public function postProcessContentObjectInitialization(tslib_cObj &$parentObject) {
		$imageZoomEnabled = intval($parentObject->data['image_zoom']);

		if ($imageZoomEnabled) {
			$this->init();
		} else {
			return;
		}

			// Unset hook to force execution once
		unset($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['postInit'][$this->extKey]);
	}
}
?>