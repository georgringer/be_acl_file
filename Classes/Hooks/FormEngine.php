<?php

namespace GeorgRinger\BeAclFile\Hooks;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

class FormEngine {

	/**
	 * Render the INI configuration as plain text
	 *
	 * @param array $parameters Configuration of the field
	 * @return string HTML input field
	 */
	public function renderIniConfiguration(array $parameters) {
		return '<pre style="margin:5px;padding:5px;border:1px solid #ccc;line-height: 9px;font-family: Courier">'
		. nl2br(htmlspecialchars($parameters['itemFormElValue']))
		. '</pre>';
	}
}