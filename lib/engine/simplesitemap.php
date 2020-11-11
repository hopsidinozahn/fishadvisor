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

namespace Octarine\Engine;

require_once SYS_LIB_ROOT . 'engine/sitemap.php';

class SimpleSitemap implements Sitemap
{
	private $pages = array();
	private $tree = array();

	public function addPage($sitemap_id, $parent_id_or_null, $url, $title, $description = '', $is_system_page = false)
	{
		if (!isset($sitemap_id))
			throw new \Exception('$sitemap_id must not be null');
		if (isset($this->pages[$sitemap_id]))
			throw new \Exception('Page already defined: ' . $sitemap_id);
		if (isset($parent_id_or_null) && !isset($this->pages[$parent_id_or_null]))
			throw new \Exception('Parent page is not defined: ' . $sitemap_id);

		$page = (object)array(
			'id' => $sitemap_id,
			'parent_id' => $parent_id_or_null,
			'url' => $url,
			'url_vars' => array(),
			'title' => strval($title),
			'title_format_args' => null,
			'description' => strval($description),
			'is_system_page' => !!$is_system_page,
			'children' => array()
		);

		$this->pages[$sitemap_id] = $page;
		if (isset($parent_id_or_null))
			$this->pages[$parent_id_or_null]->children[] = $page;
		else
			$this->tree[] = $page;
	}

	public function setPageUrlPlaceholders($sitemap_id, $variables)
	{
		if (!isset($sitemap_id))
			throw new \Exception('$sitemap_id must not be null');
		if (!isset($this->pages[$sitemap_id]))
			throw new \Exception('Page is not defined: ' . $sitemap_id);
		if (!is_array($variables))
			throw new \Exception('$variables must be an array');

		$url_replace = array();
		foreach ($page->url_vars as $key => $value)
		{
			$url_replace['%(' . $key . ')'] = $value;
		}
		$this->pages[$sitemap_id]->url_vars = $url_replace;
	}

	public function updatePageTitle($sitemap_id, $new_title)
	{
		if (!isset($sitemap_id))
			throw new \Exception('$sitemap_id must not be null');
		if (!isset($this->pages[$sitemap_id]))
			throw new \Exception('Page is not defined: ' . $sitemap_id);
		$this->pages[$sitemap_id]->title = strval($new_title);
	}

	public function updatePageTitleFormatArgs($sitemap_id, $new_format_args)
	{
		if (!isset($sitemap_id))
			throw new \Exception('$sitemap_id must not be null');
		if (!isset($this->pages[$sitemap_id]))
			throw new \Exception('Page is not defined: ' . $sitemap_id);
		if ($new_format_args !== null && !is_array($new_format_args))
			throw new \Exception('$new_format_args must be null or an array.');
		$this->pages[$sitemap_id]->title_format_args = $new_format_args;
	}

	public function getChildPages($sitemap_id)
	{
		$selected_page_list;
		if ($sitemap_id === null)
		{
			$selected_page_list = $this->tree;
		}
		else
		{
			if (!isset($sitemap_id))
				throw new \Exception('$sitemap_id must not be null');
			if (!isset($this->pages[$sitemap_id]))
				throw new \Exception('Page is not defined: ' . $sitemap_id);
			$selected_page_list = $this->pages[$sitemap_id]->children;
		}

		return array_map(array($this, 'makePageObject'), $selected_page_list);
	}

	public function getParentPage($sitemap_id)
	{
		if (!isset($sitemap_id))
			throw new \Exception('$sitemap_id must not be null');
		if (!isset($this->pages[$sitemap_id]))
			throw new \Exception('Page is not defined: ' . $sitemap_id);
		$page_id = $this->pages[$sitemap_id]->parent_id;
		return $page_id === null ? null : $this->getPage($page_id);
	}

	public function getPage($sitemap_id)
	{
		if (!isset($sitemap_id))
			throw new \Exception('$sitemap_id must not be null');
		if (!isset($this->pages[$sitemap_id]))
			throw new \Exception('Page is not defined: ' . $sitemap_id);

		return $this->makePageObject($this->pages[$sitemap_id]);
	}

	protected function makePageObject($internal_data)
	{
		$url_replace = array_keys($internal_data->url_vars);
		$url_replace_by = array_values($internal_data->url_vars);
		return (object)array(
			'id' => $internal_data->id,
			'title' => sprintf($internal_data->title, $internal_data->title_format_args),
			'description' => $internal_data->description,
			'is_system_page' => $internal_data->is_system_page,
			'url' => str_replace($url_replace, $url_replace_by, $internal_data->url));
	}
}

?>