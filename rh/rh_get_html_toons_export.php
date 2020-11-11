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

class rh_get_html_toons_export extends \Octarine\Routing\Request\RequestHandlerBase
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
		$site->setTitle('Export toons'); // already i18n
		$site->document->addScript('/js/export.min.js');
		$import_url = sprintf('%s://%s%s',
			isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https' : 'http',
			$_SERVER['HTTP_HOST'],
			$this->engine->getAbsoluteGenericUrl('/toons/import.html'));
		$qr_code_url = $this->engine->getAbsoluteGenericUrl('/toons/export.png');
		$site->document->addInlineScript('$(function(){init_export(' . json_encode($import_url) . ',' . json_encode($qr_code_url) . ');});');

		// Render site navigation

		renderNavigation($this->engine, $this->localizer, $site, $rod->id, 'toons');

		// Render page

		$site->appendElement('h2')->appendText($this->localizer->get('Export toons'));
		$site->appendElement('p')->appendText($this->localizer->get('Check all the toons you want to export.'));
		$site->appendElement('section')->setAttr('id', 'toons_export_control');
		$site->appendElement('p')->appendText($this->localizer->get('Copy the export link below and open it on your other device.'));
		$site->appendElement('section')->setAttr('id', 'toons_export_url_control');
		$site->appendElement('p')->appendText($this->localizer->get('Or scan the QR code with your mobile device.'));
		$site->appendElement('section')->setAttr('id', 'toons_export_qr_control');

		return new \Octarine\Routing\Response\HtmlResponse(200, $site->getDocument());
	}
}

?>