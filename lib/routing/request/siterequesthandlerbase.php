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

namespace Octarine\Routing\Request;

require_once SYS_LIB_ROOT . 'document/sitefactory.php';
require_once SYS_LIB_ROOT . 'routing/request/requesthandler.php';
require_once SYS_LIB_ROOT . 'routing/request/validatedrequest.php';
require_once SYS_LIB_ROOT . 'routing/request/genericparametervalidator.php';

abstract class SiteRequestHandlerBase implements RequestHandler
{
	public function __construct(\Octarine\Document\SiteFactory $site_factory)
	{
		$this->site_factory = $site_factory;
	}

	private $site_factory;

	public function getParameterValidator()
	{
		return new GenericParameterValidator();
	}

	public function getRequiredAccessFlags()
	{
		return 0; // by default no access flags are required
	}

	protected function createSite(\Octarine\Routing\Request\ValidatedRequest $request)
	{
		return $this->site_factory->create($request->getUnhandledRequest());
	}
}

?>