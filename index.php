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

require_once 'root.php';
require_once SYS_LIB_ROOT . 'phpcompatibility.php'; // ensures compatibility with PHP versions before 5.4

mb_internal_encoding('utf-8');
date_default_timezone_set(SYS_TIMEZONE);
ini_set('html_errors', false);
error_reporting(SYS_DEBUG_ERRORS ? E_ALL : 0);

// Set error header in case an error occurs while including dependencies
http_response_code(500);
header('Content-Type: text/plain;charset=utf-8');

// Include dependencies
require_once SYS_LIB_ROOT . 'engine/restengine.php';
require_once SYS_LIB_ROOT . 'engine/i18nrestengine.php';
require_once SYS_LIB_ROOT . 'engine/simplesitemap.php';
require_once SYS_LIB_ROOT . 'engine/i18nsitemap.php';
require_once SYS_LIB_ROOT . 'access/dbtrivialprincipalmanager.php';
require_once SYS_LIB_ROOT . 'routing/errorhandling/simplenotfounderrorhandler.php';
require_once SYS_LIB_ROOT . 'routing/errorhandling/simpleforbiddenerrorhandler.php';
require_once SYS_LIB_ROOT . 'routing/errorhandling/simplenoaccesserrorhandler.php';
require_once SYS_LIB_ROOT . 'routing/errorhandling/simplebadrequesterrorhandler.php';
require_once SYS_LIB_ROOT . 'routing/errorhandling/simplenotacceptableerrorhandler.php';
require_once SYS_LIB_ROOT . 'routing/response/htmlresponse.php';
require_once SYS_LIB_ROOT . 'i18n/languagemanager.php';
require_once SYS_LIB_ROOT . 'i18n/xmllocalizer.php';
require_once SYS_LIBEXT_ROOT . 'document/localsitefactory.php';
require_once SYS_RH_ROOT . 'setup.php';

try
{
	// Load languages
	$localizer = new \Octarine\I18n\XmlLocalizer();
	foreach (explode(',', SYS_LANGUAGES) as $langCode)
		$localizer->load(SYS_ROOT . 'lang/' . $langCode . '.xml');

	// Select the specified language, or choose the best one (based on HTTP) if none was specified
	$language_code;
	if (defined('SYS_FORCE_LANGUAGE'))
		$language_code = SYS_FORCE_LANGUAGE;
	else
	{
		$language_code = new \Octarine\I18n\LanguageManager(explode(',', SYS_LANGUAGES));
		$language_code = $language_code->chooseBest();
	}
	if (!$language_code || !$localizer->hasLanguage($language_code))
		throw new \Exception(sprintf('Language code "%s" not recognized or no translation available.', $language_code));
	$localizer->select($language_code);

	// Set up principal manager
	$principalMgr = new \Octarine\Access\DbTrivialPrincipalManager();

	// Use compact HTML output (no indentation).
	\Octarine\Routing\Response\HtmlResponse::$shallIndentHtml = !!SYS_DEBUG_INDENT_HTML;

	// Initialize REST engine
	if (!defined('SYS_USE_I18N_SUBSITE') || SYS_USE_I18N_SUBSITE)
	{
		$document_web_root = sprintf('%s/%s/', rtrim(SYS_WEB_ROOT, '/'), $language_code);
		$engine = new \Octarine\Engine\I18nRestEngine(SYS_WEB_ROOT, $document_web_root, $principalMgr, $localizer);
	}
	else
	{
		$engine = new \Octarine\Engine\RestEngine(SYS_WEB_ROOT, SYS_WEB_ROOT, $principalMgr);
	}

	// Setup error handlers
	$site_factory = new \Octarine\Document\LocalSiteFactory($engine, $localizer);
	$engine->getApi()->setNoHandlerErrorHandler($error_handler_no_handler = new \Octarine\Routing\ErrorHandling\SimpleNotFoundErrorHandler($site_factory));
	$engine->getApi()->setNoAccessErrorHandler($error_handler_no_access = new \Octarine\Routing\ErrorHandling\SimpleNoAccessErrorHandler($site_factory));
	$engine->getApi()->setBadRequestErrorHandler($error_handler_bad_request = new \Octarine\Routing\ErrorHandling\SimpleBadRequestErrorHandler($site_factory));
	$engine->getApi()->setNotAcceptableErrorHandler($error_handler_not_acceptable = new \Octarine\Routing\ErrorHandling\SimpleNotAcceptableErrorHandler($site_factory));

	// Setup pages (by defining routes)
	$setup = new \Setup($engine, $localizer, $site_factory);
	$setup->pages();

	switch (defined('SYS_FORCE_ERROR_DOCUMENT') ? SYS_FORCE_ERROR_DOCUMENT : 0)
	{
		case 400:
			$engine->runWithErrorHandler($error_handler_bad_request, (object)array());
			break;
		case 401:
			$engine->runWithErrorHandler($error_handler_no_access, (object)array());
			break;
		case 403:
			$error_handler_forbidden = new \Octarine\Routing\ErrorHandling\SimpleForbiddenErrorHandler($site_factory);
			$engine->runWithErrorHandler($error_handler_forbidden, (object)array());
			break;
		case 404:
			$engine->runWithErrorHandler($error_handler_no_handler, (object)array());
			break;
		default:
			$engine->run();
			break;
	}
}
catch (\Exception $exception)
{
	$exception_renderer;
	if (!SYS_DEBUG_ERRORS)
	{
		require_once SYS_LIB_ROOT . 'document/tacitexceptionrenderer.php';
		$exception_renderer = new \Octarine\Document\TacitExceptionRenderer();
	}
	elseif (!SYS_DEBUG_STACKTRACE)
	{
		require_once SYS_LIB_ROOT . 'document/simpleexceptionrenderer.php';
		$exception_renderer = new \Octarine\Document\SimpleExceptionRenderer();
	}
	else
	{
		require_once SYS_LIB_ROOT . 'document/fullexceptionrenderer.php';
		$exception_renderer = new \Octarine\Document\FullExceptionRenderer();
	}
	$exception_renderer->output($exception);
}

?>