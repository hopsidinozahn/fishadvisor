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

namespace Octarine\Engine;

require_once SYS_LIB_ROOT . 'engine/restengine.php';
require_once SYS_LIB_ROOT . 'access/principalmanager.php';
require_once SYS_LIB_ROOT . 'routing/response/response.php';
require_once SYS_LIB_ROOT . 'i18n/localizer.php';

class I18nRestEngine extends RestEngine
{
	public function __construct($generic_web_root, $document_web_root, \Octarine\Access\PrincipalManager $principalMgr, \Octarine\I18n\Localizer $localizer)
	{
		$this->localizer = $localizer;
		parent::__construct($generic_web_root, $document_web_root, $principalMgr);
	}

	protected function reply_headers(\Octarine\Routing\Response\Response $response)
	{
		parent::reply_headers($response);
		header('Content-Language: ' . $this->localizer->getCurrentLanguage());
	}
}

?>