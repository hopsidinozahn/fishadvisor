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
require_once SYS_LIB_ROOT . 'routing/_constants.php';
require_once SYS_LIB_ROOT . 'routing/staticroute.php';
require_once SYS_LIB_ROOT . 'routing/regexroute.php';
require_once SYS_LIB_ROOT . 'routing/request/staticsiterequesthandlerwithjumpto.php';
require_once SYS_LIB_ROOT . 'document/sitefactory.php';
require_once SYS_LIB_ROOT . 'i18n/localizer.php';

class Setup
{
	public function __construct(\Octarine\Engine\Engine $engine, \Octarine\I18n\Localizer $localizer, \Octarine\Document\SiteFactory $site_factory)
	{
		$this->engine = $engine;
		$this->localizer = $localizer;
		$this->site_factory = $site_factory;
	}

	private $engine, $localizer, $site_factory;

	public function pages()
	{
		foreach (glob(SYS_RH_ROOT . 'rh_*.php') as $f)
			require_once $f;

		// html
		$this->static_get_html('language', '/language.html', new rh_get_html_language($this->engine, $this->localizer, $this->site_factory));
		$this->static_get_html('insight', '/insight.html', new rh_get_html_insight($this->engine, $this->localizer, $this->site_factory));
		$this->static_get_html('toons', '/toons.html', new rh_get_html_toons($this->engine, $this->localizer, $this->site_factory));
		$this->static_get_html('toons-export', '/toons/export.html', new rh_get_html_toons_export($this->engine, $this->localizer, $this->site_factory));
		$this->static_get_html('toons-import', '/toons/import.html', new rh_get_html_toons_import($this->engine, $this->localizer, $this->site_factory));
		$this->static_get_html('ponds', '/ponds.html', new rh_get_html_ponds($this->engine, $this->localizer, $this->site_factory));
		$this->static_get_html('fishes', '/fishes.html', new rh_get_html_fishes($this->engine, $this->localizer, $this->site_factory));
		$this->static_get_html('pond_advisor', '/pond_advisor.html', new rh_get_html_pond_advisor($this->engine, $this->localizer, $this->site_factory));
		$this->static_get_html('fish_advisor', '/fish_advisor.html', new rh_get_html_fish_advisor($this->engine, $this->localizer, $this->site_factory));
		$this->regex_get_html('ponds_single', '#^/ponds/(?<pid>[a-z_]+)\\.html$#', new rh_get_html_ponds_single($this->engine, $this->localizer, $this->site_factory));
		$this->regex_get_html('fishes_single', '#^/fishes/(?<fid>[0-9]+)\\.html$#', new rh_get_html_fishes_single($this->engine, $this->localizer, $this->site_factory));
		$this->regex_get_html('fishes_table', '#^/fishes/table\\.html$#', new rh_get_html_fishes_table($this->engine, $this->localizer, $this->site_factory));
		$this->regex_get_html('fishes_syscomp', '#^/fishes/syscomp\\.html$#', new rh_get_html_fishes_syscomp($this->engine, $this->localizer, $this->site_factory));
		// json
		$this->regex_get_json('ponds_single', '#^/ponds/(?<pid>[a-z_]+)\\.json$#', new rh_get_json_ponds_single($this->engine, $this->localizer));
		$this->regex_get_json('fishes_single', '#^/fishes/(?<fid>[0-9]+)\\.json$#', new rh_get_json_fishes_single($this->engine, $this->localizer));
		$this->regex_get_json('pond_advisor', '#^/pond_advisor\\.json$#', new rh_get_json_pond_advisor($this->engine, $this->localizer));
		$this->regex_get_json('fish_advisor', '#^/fish_advisor\\.json$#', new rh_get_json_fish_advisor($this->engine, $this->localizer));
		$this->regex_get_json('buckets', '#^/buckets\\.json$#', new rh_get_json_buckets($this->engine, $this->localizer));
		// png
		$this->static_get_png('toons-export', '/toons/export.png', new rh_get_png_toons_export($this->engine));
	}

	private function static_get_html($id, $url, $request_handler)
	{
		// StaticRoute(id, method, url, [content types], request handler)
		$this->engine->getApi()->addRoute(new \Octarine\Routing\StaticRoute("html:$id", \Octarine\Routing\METHOD_GET, $url, array('text/html'), $request_handler));
	}

	private function static_get_png($id, $url, $request_handler)
	{
		// StaticRoute(id, method, url, [content types], request handler)
		$this->engine->getApi()->addRoute(new \Octarine\Routing\StaticRoute("png:$id", \Octarine\Routing\METHOD_GET, $url, array('image/png'), $request_handler));
	}

	private function regex_get_html($id, $path_regex, $request_handler)
	{
		// RegexRoute(id, method, url_regex, [content types], request handler)
		$this->engine->getApi()->addRoute(new \Octarine\Routing\RegexRoute("html:$id", \Octarine\Routing\METHOD_GET, $path_regex, array('text/html'), $request_handler));
	}

	private function regex_get_json($id, $path_regex, $request_handler)
	{
		// RegexRoute(id, method, url_regex, [content types], request handler)
		$this->engine->getApi()->addRoute(new \Octarine\Routing\RegexRoute("json:$id", \Octarine\Routing\METHOD_GET, $path_regex, array('application/json'), $request_handler));
	}
}

?>