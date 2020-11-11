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

require_once SYS_LIB_ROOT . 'engine/urlresolver.php';
require_once SYS_LIB_ROOT . 'document/dom/dommanipulation.php';
require_once SYS_LIB_ROOT . 'i18n/localizer.php';

function getInitJavascript(\Octarine\Engine\UrlResolver $url_resolver, $rod_id)
{
	return sprintf('$(function(){init(%s,%s);});',
		json_encode($url_resolver->getAbsoluteDocumentUrl('/')),
		json_encode($rod_id));
}

function renderNavigation(\Octarine\Engine\UrlResolver $url_resolver, \Octarine\I18n\Localizer $localizer, \Octarine\Document\Dom\DomManipulation $parent, $rod_id, $current_page, $class = null)
{
	$nav_info = $parent->appendElement('aside')->setAttr('id', 'infobanner')->appendText($localizer->get('The probabilities of the Fish Advisor now match those of the new Toontown Rewritten update!'));

	$nav = $parent->appendElement('nav');
	if ($class)
		$nav->setAttr('class', 'nav_' . $class);

	$pages = array(
		'ponds' => array('/ponds.html', 'Fishing ponds'),
		'fishes' => array('/fishes.html', 'Fish species'),
		'pond_advisor' => array('/pond_advisor.html', 'Advise me: where?'),
		'fish_advisor' => array('/fish_advisor.html', 'Advise me: what?'),
		'toons' => array('/toons.html', 'My toons'),
		'insight' => array('/insight.html', 'Insight'),
	);

	foreach ($pages as $id => $_)
	{
		list($url, $text) = $_;
		$url = $url_resolver->getAbsoluteDocumentUrl($url . '?rid=' . $rod_id);
		$a = $nav->appendElement('a')->setAttr('href', $url);
		$a->appendText($localizer->get($text));
		if ($id == $current_page)
			$a->setAttr('class', 'current');
	}

	$current_language = $localizer->getCurrentLanguage();
	$nav->appendElement('a')
		->setAttr('href', $url_resolver->getAbsoluteDocumentUrl('/language.html?rid=' . $rod_id))
		->setAttr('class', 'language language_' . $current_language)
		->appendText($localizer->getLanguageName($current_language));
	return $nav;
}

?>