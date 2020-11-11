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
require_once SYS_LIB_ROOT . 'document/i18nurlresolvingxmldomloaderwithtypography.php';
require_once SYS_LIB_ROOT . 'routing/request/requesthandlerbase.php';
require_once SYS_LIB_ROOT . 'routing/response/htmlresponse.php';
require_once SYS_LIB_ROOT . 'i18n/localizer.php';
require_once SYS_LIBEXT_ROOT . 'toontown/ttrdefinitions.php';
require_once SYS_RH_ROOT . 'meta.php';

class rh_get_html_insight extends \Octarine\Routing\Request\RequestHandlerBase
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
		$site->document->addStylesheet('/style/all-insight.min.css');
		$site->setTitle('Insight'); // already i18n

		// Render site navigation

		renderNavigation($this->engine, $this->localizer, $site, $rod->id, 'insight');

		// Render page

		$available_languages = array('en', 'de');
		$language = $this->localizer->getCurrentLanguage();
		if (!in_array($language, $available_languages))
		{
			$language = $available_languages[0];
			$site->appendElement('p')->setAttr('class', 'error')->appendText($this->localizer->get('There is no translation of this document in your language. The English version is displayed instead.'));
		}
		$dom_loader = new \Octarine\Document\I18nUrlResolvingXmlDomLoaderWithTypography(SYS_RH_ROOT . 'insight.xml', $language, $this->engine);
		$dom_loader->load($site);

		/*
		foreach($this->getFishesByRarity($def) as $r => $list)
		{
			echo "<tr>\n";
			echo "\t<td>$r</td>\n";
			echo "\t<td>\n";
			foreach ($list as $f)
			echo "\t\t<span class=\"inlinefish fishgroup_$f->group\">$f->name</span>\n";
			echo "\t</td>\n";
			echo "</tr>\n";
		}
		print_r($this->getFishesByRarity($def));exit;
		*/
		/*
		$list=[];
		foreach ($def->getFishGroups() as $group)
			foreach ($def->getFishes($group->id) as $fish)
			{
				$r=[];
				$def->getProbability_($fish->id, 'gd', 'dg_es', $can_catch,$r);
				if ($can_catch)
				foreach ($r as $rr)
					$list[$rr][] = $fish;
			}
		ksort($list);
		foreach ($list as $r => $fishes)
		{
			echo "<tr>\n";
			echo "\t<td class=\"rarity rarity$r\">$r</td>\n";
			echo "\t<td>\n";
			foreach ($fishes as $f)
			{
				$g = $f->id >> 4;
				echo "\t\t<span class=\"inlineblockfish fishgroup_$g\">$f->name</span>\n";
			}
			echo "\t</td>\n";
			echo "</tr>\n";
		}
		exit;
		*/

		return new \Octarine\Routing\Response\HtmlResponse(200, $site->getDocument());
	}

	private function getFishesByRarity($def)
	{
		$dict = array();
		for ($r = 1; $r <= 10; $r++)
			$dict[$r] = array();
		foreach ($def->getFishGroups() as $group)
			foreach ($def->getFishes($group->id) as $fish)
			{
				$fish->group = $group->id;
				$dict[$fish->rarity][] = $fish;
			}
		return $dict;
	}
}

?>