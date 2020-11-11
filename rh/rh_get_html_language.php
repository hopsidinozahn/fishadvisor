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
require_once SYS_LIBEXT_ROOT . 'document/ui/pondlistrenderer.php';
require_once SYS_RH_ROOT . 'meta.php';

class rh_get_html_language extends \Octarine\Routing\Request\RequestHandlerBase
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
		$site->setTitle('Welcome'); // already i18n
		$site->document->addInlineScript(getInitJavascript($this->engine, $rod->id));

		// Render site navigation

		renderNavigation($this->engine, $this->localizer, $site, $rod->id, 'welcome');

		// Render page

		$site->appendElement('h2')->appendText($this->localizer->get('Translations'));
		$site->appendElement('p')->appendText($this->localizer->get('The Fish Advisor is available in these languages:'));
		$ul = $site->appendElement('ul');
		foreach (explode(',', SYS_LANGUAGES) as $language_code)
		{
			$url = $this->engine->getAbsoluteGenericUrl(sprintf('/%s/language.html?rid=%s', $language_code, $rod->id));
			$ul->appendElement('li')->appendElement('a')->setAttr('href', $url)->appendText($this->localizer->getLanguageName($language_code));
		}

		$site->appendElement('h2')->appendText($this->localizer->get('Do you want to localize this site into another language?'));

		list($t1, $t2, $t3) = explode('$', $this->localizer->get('Just head over to the $ directory, grab a copy of any pair of language files (.xml and .js) and translate them. When you\'re done, send them to $ and I\'ll add them as soon as I can.'));
		$p = $site->appendElement('p');
		$p->appendText($t1);
		$p->appendElement('a')->setAttr('href', $this->engine->getAbsoluteGenericUrl('/lang/'))->appendText('/lang/');
		$p->appendText($t2);
		$p->appendElement('a')->setAttr('class', 'omx')->setAttr('href', 'mailto:')->appendText('steveDOTmullerAToutlookDOTcom');
		$p->appendText($t3);
	
		list($t1, $t2) = explode('$', $this->localizer->get('The first file is in the $ format. Language entries are of the form:'));
		$p = $site->appendElement('p');
		$p->appendText($t1);
		$p->appendElement('a')->setAttr('href', '//www.w3schools.com/xml/')->appendText('XML');
		$p->appendText($t2);
	
		$code = $site->appendElement('blockquote')->appendElement('code');
		$code->appendText('<entry key="');
		$code->appendElement('span')->setAttr('style', 'font-weight:bold;color:#f00;')->appendText($this->localizer->get('Original (English) text'));
		$code->appendText('">');
		$code->appendElement('span')->setAttr('style', 'font-weight:bold;color:#f00;')->appendText($this->localizer->get('Translated text'));
		$code->appendText('</entry>');

		list($t1, $t2) = explode('$', $this->localizer->get('The second file is in the $ format. Language entries are of the form:'));
		$p = $site->appendElement('p');
		$p->appendText($t1);
		$p->appendElement('a')->setAttr('href', '//www.w3schools.com/json/')->appendText('JSON');
		$p->appendText($t2);

		$code = $site->appendElement('blockquote')->appendElement('code');
		$code->appendText('"');
		$code->appendElement('span')->setAttr('style', 'font-weight:bold;color:#f00;')->appendText($this->localizer->get('Original (English) text'));
		$code->appendText('": "');
		$code->appendElement('span')->setAttr('style', 'font-weight:bold;color:#f00;')->appendText($this->localizer->get('Translated text'));
		$code->appendText('",');

		return new \Octarine\Routing\Response\HtmlResponse(200, $site->getDocument());
	}
}

?>