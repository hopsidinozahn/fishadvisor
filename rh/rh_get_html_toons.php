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

require_once SYS_LIB_ROOT . 'engine/engine.php';
require_once SYS_LIB_ROOT . 'document/sitefactory.php';
require_once SYS_LIB_ROOT . 'document/dom/rawnode.php';
require_once SYS_LIB_ROOT . 'routing/request/requesthandlerbase.php';
require_once SYS_LIB_ROOT . 'routing/response/htmlresponse.php';
require_once SYS_LIB_ROOT . 'i18n/localizer.php';
require_once SYS_LIBEXT_ROOT . 'toontown/ttrdefinitions.php';
require_once SYS_RH_ROOT . 'meta.php';

class rh_get_html_toons extends \Octarine\Routing\Request\RequestHandlerBase
{
	public function __construct(\Octarine\Engine\Engine $engine, \Octarine\I18n\Localizer $localizer, \Octarine\Document\SiteFactory $site_factory)
	{
		$this->engine = $engine;
		$this->localizer = $localizer;
		$this->site_factory = $site_factory;
	}

	private $engine, $localizer, $site_factory;

	public function getParameterValidator()
	{
		$p = parent::getParameterValidator();
		$p->defineString('rid', false); // not required, assume "twig" if none specified
		return $p;
	}

	public function handle(\Octarine\Routing\Request\ValidatedRequest $request)
	{
		$def = new \Octarine\Toontown\TTRDefinitions();
		$rod = $def->getRod($request->getParameters()->getOrDefault('rid', 'tg'));

		$site = $this->site_factory->create($request->getUnhandledRequest());
		$site->setTitle('Toons'); // already i18n
		$site->document->addInlineScript(getInitJavascript($this->engine, $rod->id));

		// Render site navigation

		renderNavigation($this->engine, $this->localizer, $site, $rod->id, 'toons');

		// Render page

		$site->appendElement('h2')->appendText($this->localizer->get('Pick a toon'));
		$site->appendElement('p')->appendText($this->localizer->get('Clicking on a toon\'s panel makes the Fish Advisor adapt the various statistics on this site for this specific toon.'));
		$site->appendElement('p')->appendText($this->localizer->get('You can edit the selected toon below.'));
		$site->appendElement('p')->appendElement('a')->setAttr('class', 'exportbutton')->setAttr('href', $this->engine->getAbsoluteDocumentUrl('/toons/export.html?rid=' . $rod->id))->appendText($this->localizer->get('Export my toons to another device'));
		$site->appendElement('hr', true);
		$site->appendElement('section')->setAttr('id', 'toons_mgmt_control');

		return new \Octarine\Routing\Response\HtmlResponse(200, $site->getDocument());
	}
}

?>