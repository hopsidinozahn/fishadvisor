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
require_once SYS_LIB_ROOT . 'routing/request/requesthandlerbase.php';
require_once SYS_LIB_ROOT . 'routing/response/htmlresponse.php';
require_once SYS_LIB_ROOT . 'i18n/localizer.php';
require_once SYS_LIBEXT_ROOT . 'toontown/ttrdefinitions.php';
require_once SYS_LIBEXT_ROOT . 'toontown/fishstatscomputer.php';
require_once SYS_LIBEXT_ROOT . 'toontown/helper.php';
require_once SYS_LIBEXT_ROOT . 'document/ui/rodlistrenderer.php';
require_once SYS_LIBEXT_ROOT . 'document/ui/fishstatsrenderer.php';
require_once SYS_RH_ROOT . 'meta.php';

class rh_get_html_ponds_single extends \Octarine\Routing\Request\RequestHandlerBase
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
		$p->defineString('pid');
		$p->defineString('rid', false); // not required, assume "twig" if none specified
		return $p;
	}

	public function handle(\Octarine\Routing\Request\ValidatedRequest $request)
	{
		$def = new \Octarine\Toontown\TTRDefinitions();
		$rod = $def->getRod($request->getParameters()->getOrDefault('rid', 'tg'));
		$pond = $def->getPond($request->getParameters()->get('pid'));

		$stats_computer = new \Octarine\Toontown\FishStatsComputer($def, $def, $def);
		$stats = $stats_computer->aggregateByPond($rod->id, $pond->id);

		$site = $this->site_factory->create($request->getUnhandledRequest());
		$site->setTitle($pond->name); // already i18n
		$site->noMargin();
		$site->document->addInlineScript(getInitJavascript($this->engine, $rod->id));

		// Render site navigation

		renderNavigation($this->engine, $this->localizer, $site, $rod->id, 'ponds', $pond->group);

		// Render header and rod list

		$header = $site->appendElement('header')->setAttr('class', 'pondgroup pondgroup_' . $pond->group);
		$renderer = new \Octarine\Document\UI\RodListRenderer($this->localizer, $def, $rod, '?rid=%s');
		$renderer->render($header);
		$header->appendElement('h1')->appendText($this->localizer->get($pond->name));
		$p = $header->appendElement('p');
		list($in_prep, $in_pg) = \Octarine\Toontown\Helper::getParentPlaygroundTexts($pond->id);
		$p->appendText($this->localizer->get($in_prep));
		$p->appendElement('em')->appendText($this->localizer->get($in_pg));
		if (null !== ($to_pg = \Octarine\Toontown\Helper::getArrivalPlaygroundName($pond->id)))
		{
			$p->appendText(', ');
			$p->appendText($this->localizer->get('heading to '));
			$p->appendElement('em')->appendText($this->localizer->get($to_pg));
		}
		$p = $header->appendElement('p');
		$p->appendText($this->localizer->get('Only showing uncaught species of:'));
		$p->appendElement('span')->setAttr('id', 'toon_selection');

		// Render statistics table

		$renderer = new \Octarine\Document\UI\FishStatsRenderer($this->localizer, $stats,
			$this->engine->getAbsoluteDocumentUrl(sprintf('/fishes/%%d.html?rid=%s', $rod->id)),
			$this->engine->getAbsoluteDocumentUrl(sprintf('/ponds/%%s.html?rid=%s', $rod->id)),
			array('pid' => $pond->id));
		$renderer->render($site);

		return new \Octarine\Routing\Response\HtmlResponse(200, $site->getDocument());
	}

}

?>