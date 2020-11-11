<?php
/*
 * Copyright 2014 by Steve Muller <steve.muller@outlook.com>
 * 
 * This file is part of Fish Advisor.
 * 
 * Fish Advisor is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Fish Advisor is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Fish Advisor.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Octarine\Document;

require_once SYS_LIB_ROOT . 'document/i18nurlresolvingxmldomloader.php';

class I18nUrlResolvingXmlDomLoaderWithTypography extends I18nUrlResolvingXmlDomLoader
{
	protected function processText($text)
	{
		$text = str_replace('``', '„', $text);
		$text = str_replace('~', ' ', $text); // non-breaking space
		$text = str_replace(array('"', '\'\''), '“', $text);
		$text = str_replace(array('`', '\''), '’', $text);
		$text = str_replace('--', '–', $text);
		return parent::processText($text);
	}
}

?>