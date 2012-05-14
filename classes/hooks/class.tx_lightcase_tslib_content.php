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

class tx_lightcase_tslib_content extends tslib_pibase implements tslib_content_PostInitHook {
	public $prefixId		= 'tx_lightcase';
	public $extKey			= 'lightcase';
	
	/**
	 * Called from tslib_content::postInit
	 *
	 * @param	tslib_cObj	&$parentObject	Referenced cObject
	 * @return	Hooks into the postProcess of cObjects
	 */
	public function postProcessContentObjectInitialization(tslib_cObj &$parentObject) {
		$imageZoomEnabled = intval($parentObject->data['image_zoom']);
		$beUserLogin = intval($GLOBALS['TSFE']->beUserLogin);
		
		if ($imageZoomEnabled) {
			$pluginConf = $GLOBALS['TSFE']->tmpl->setup['plugin.'][$this->prefixId . '.'];
				// If no plugin configuration found, display flashmessage
			if (!$pluginConf && $beUserLogin) {
				echo $this->renderFlashMessage(
					'No configuration found: plugin.' . $this->prefixId
					,'Please make sure that the static configuration of this plugin is included.'
					,t3lib_FlashMessage::ERROR
				);
			}
			
			$extConf = $this->getExtConf();
			$this->addJsCssFiles($pluginConf, $extConf);
			
			$piLabels = $this->getPiLabels();
			
			if (is_array($piLabels) && !empty($piLabels)) {
				$this->writeJsonFile($piLabels);
			}
		} else {
			return;
		}
		
			// Unset hook to force execution once
		unset($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['postInit'][$this->extKey]);
	}
	
	/**
	 * Gets labels from locallang.xlf
	 *
	 * @return	array	$piLabels
	 */
    protected function getPiLabels() {
		$LLfile = t3lib_extMgm::siteRelPath($this->extKey) . 'locallang.xlf';
		$LOCAL_LANG = t3lib_div::readLLfile($LLfile, $this->LLkey, $GLOBALS['TSFE']->renderCharset);
		$piLabels = !empty($LOCAL_LANG[$this->LLkey]) ? $LOCAL_LANG[$this->LLkey] : $LOCAL_LANG['default'];
		
		if (is_array($piLabels)) {
			foreach ($piLabels as $labelKey => $label) {
				if (is_array($label) && array_key_exists(0, $label)) {
					$piLabels[$labelKey] = $label[0]['target'];
				} else {
					$piLabels[$labelKey] = $label;
				}
			}
		}
		
		return $piLabels;
	}
	
	/**
	 * Gets extension configuration values
	 *
	 * @return	array	$extConf configuration
	 */
    protected function getExtConf() {
		return unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
	}
	
	/**
	 * Writes a file with json content
	 *
	 * @param	array	$jsonArray
	 * @return	.json file in typo3temp/
	 */
	protected function writeJsonFile(array $jsonArray) {
		$file = $this->LLkey . '.locallang.json';
		$filePath = PATH_site . 'typo3temp/' . $this->extKey . '/' . $filePath.$file;
		
		$content = json_encode($jsonArray);
		if (!file_exists($filePath)) {
			t3lib_div::writeFileToTypo3tempDir($filePath, $content);
		}
	}
	
	/**
	 * Displays a flash message
	 *
	 * @param	string	$title
	 * @param	string	$message
	 * @param	string	t3lib_FlashMessage::WARNING
	 *
	 * @retun	string	flash message as html content
	 */
	protected function renderFlashMessage($title, $message, $type = t3lib_FlashMessage::WARNING) {
			// Add css files
		$GLOBALS['TSFE']->getPageRenderer()->addCssFile(t3lib_extMgm::siteRelPath('t3skin') . 'stylesheets/visual/element_message.css');
		$GLOBALS['TSFE']->getPageRenderer()->addCssFile(t3lib_extMgm::siteRelPath($this->extKey) . '/resources/css/flashMessage.css');
		
		$flashMessage = t3lib_div::makeInstance('t3lib_FlashMessage', $message, $title, $type);
		
		return $flashMessage->render();
	}
	
	/**
	 * Adds js and css files from the plugin conf
	 *
	 * @return	JS and CSS file inclusion
	 */
    protected function addJsCssFiles($pluginConf, $extConf) {
		$include = $pluginConf['include.'];
		
		foreach ($include as $includePart => $includeCode) {
			$include = is_array($GLOBALS['TSFE']->pSetup[$includePart]) ? $GLOBALS['TSFE']->pSetup[$includePart] : array();
			
				// Continue with next if plugin.tx_lightcase.dontIncludeJsLibs is set
			if ($extConf['dontIncludeJsLibs'] && ($includePart === 'includeJSlibs.' || $includePart === 'includeJSFooterlibs.')) {
				continue;
			} else {
				$GLOBALS['TSFE']->pSetup[$includePart] = t3lib_div::array_merge_recursive_overrule($include, $includeCode);
			}
		}
	}
}
?>