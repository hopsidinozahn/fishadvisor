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

require_once SYS_LIB_ROOT . 'routing/request/validatedrequest.php';
require_once SYS_LIB_ROOT . 'routing/response/htmlresponse.php';
require_once SYS_LIB_ROOT . 'routing/request/requesthandler.php';
require_once SYS_LIB_ROOT . 'routing/request/genericparametervalidator.php';
require_once SYS_LIB_ROOT . 'document/domloader.php';
require_once SYS_LIB_ROOT . 'document/jumptonavigationgenerator.php';
require_once SYS_LIB_ROOT . 'document/sitefactory.php';

class StaticSiteRequestHandlerWithJumpTo implements RequestHandler
{
	public function __construct(\Octarine\Document\SiteFactory $site_factory, \Octarine\Document\DomLoader $dom_loader)
	{
		$this->site_factory = $site_factory;
		$this->dom_loader = $dom_loader;
	}

	private $site_factory, $dom_loader;

	public function getParameterValidator()
	{
		return new GenericParameterValidator();
	}

	public function getRequiredAccessFlags()
	{
		return 0; // by default no access flags are required
	}

	public function handle(\Octarine\Routing\Request\ValidatedRequest $request)
	{
		$site = $this->site_factory->create($request->getUnhandledRequest());
		$jumptoNavGenerator = new \Octarine\Document\JumpToNavigationGenerator();
		$jumptoNavGenerator->create($site);
		$this->dom_loader->load($site);
		$jumptoNavGenerator->generate($site);
		return new \Octarine\Routing\Response\HtmlResponse(200, $site->getDocument());
	}
}

?>