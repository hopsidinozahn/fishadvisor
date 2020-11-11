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
require_once SYS_LIBEXT_ROOT . 'document/ui/rodlistrenderer.php';
require_once SYS_LIBEXT_ROOT . 'document/ui/pondstatsrenderer.php';
require_once SYS_RH_ROOT . 'meta.php';

class rh_get_html_fishes_single extends \Octarine\Routing\Request\RequestHandlerBase
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
		$p->defineString('fid');
		$p->defineString('rid', false); // not required, assume "twig" if none specified
		return $p;
	}

	public function handle(\Octarine\Routing\Request\ValidatedRequest $request)
	{
		$def = new \Octarine\Toontown\TTRDefinitions();
		$rod = $def->getRod($request->getParameters()->getOrDefault('rid', 'tg'));
		$fish = $def->getFish($request->getParameters()->get('fid'));

		$stats_computer = new \Octarine\Toontown\FishStatsComputer($def, $def, $def);
		$stats = $stats_computer->aggregateByFish($rod->id, $fish->id);

		$site = $this->site_factory->create($request->getUnhandledRequest());
		$site->setTitle($fish->name); // already i18n
		$site->noMargin();
		$site->document->addInlineScript(getInitJavascript($this->engine, $rod->id));

		// Render site navigation

		renderNavigation($this->engine, $this->localizer, $site, $rod->id, 'fishes', 'fish');

		// Render header and rod list

		$header = $site->appendElement('header')->setAttr('class', 'fishgroup fishgroup_' . $fish->group);
		$renderer = new \Octarine\Document\UI\RodListRenderer($this->localizer, $def, $rod, '?rid=%s');
		$renderer->render($header);
		$header->appendElement('h1')->appendText($fish->name);
		$p = $header->appendElement('p');

		$text = $this->localizer->get('This is a $ species, which can weigh up to $ lb. You\'ll need at least the $ rod.');
		list($part1, $part2, $part3, $part4) = explode('$', $text);
		$p->appendText($part1);
		$p->appendElement('em')->appendText($this->getRarityText($stats));
		$p->appendText($part2);
		$p->appendElement('em')->appendText(strval($fish->max_weight));
		$p->appendText($part3);
		$p->appendElement('em')->appendText($this->localizer->get($this->getMinRod($def, $fish)->name));
		$p->appendText($part4);
		$p = $header->appendElement('p');
		$p->appendElement('span')->setAttr('id', 'toon_selection');
		$p->appendElement('span')->setAttr('id', 'toon_fish_status')->setAttr('data-fishid', $fish->id);

		// Render statistics table

		$renderer = new \Octarine\Document\UI\PondStatsRenderer($this->localizer, $stats,
			$this->engine->getAbsoluteDocumentUrl(sprintf('/ponds/%%s.html?rid=%s', $rod->id)),
			$this->engine->getAbsoluteDocumentUrl(sprintf('/fishes/%d.html?rid=%%s', $fish->id)),
			array('fid' => $fish->id));
		$renderer->render($site);

		return new \Octarine\Routing\Response\HtmlResponse(200, $site->getDocument());
	}

	private function getRarityText($stats)
	{
		$maxprob = 0;
		foreach ($stats as $entry)
			$maxprob = max($maxprob, $entry->prob);

		if ($maxprob < .001)
			return $this->localizer->get('very rare');
		elseif ($maxprob < .01)
			return $this->localizer->get('rare');
		else
			return $this->localizer->get('common');
	}

	private function getMinRod($def, $fish)
	{
		foreach ($def->getRods() as $rod)
			if ($rod->max_weight >= $fish->min_weight)
				return $rod;
	}
}

?>