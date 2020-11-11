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

interface Sitemap
{
	/**
	 * Adds an entry to the sitemap.
	 * @param string $sitemap_id The internal identifier of the page.
	 * @param string|null $parent_id_or_null The internal identifier of the parent page, or null if it is a root page.
	 * @param string $url The URL to the page. It must begin with a slash (/) and is understood to be relative to the site root.
	 * @param string $title The page title.
	 * @param string $description An optional description of the page.
	 */
	public function addPage($sitemap_id, $parent_id_or_null, $url, $title, $description = '', $is_system_page = false);

	/**
	 * Updates the URL of a sitemap entry by replacing place holders of the form "%(variableName)" by the specified value.
	 * @param string $sitemap_id The internal identifier of the page.
	 * @param string $variables An associative array of variables, where the key represents the variable name.
	 */
	public function setPageUrlPlaceholders($sitemap_id, $variables);

	/**
	 * Changes the title of a sitemap entry.
	 * @param string $sitemap_id The internal identifier of the page.
	 * @param string $new_title The new page title.
	 */
	public function updatePageTitle($sitemap_id, $new_title);

	/**
	 * Changes the title of a sitemap entry.
	 * @param string $sitemap_id The internal identifier of the page.
	 * @param string $new_format_args The new page title formatting arguments (passed to sprintf). Use null to disable use of sprintf().
	 */
	public function updatePageTitleFormatArgs($sitemap_id, $new_format_args);

	/**
	 * Gets a collection of child pages of the given page.
	 * @param string|null $sitemap_id The internal identifier of the page. Use null to get all root pages.
	 * @return object[] Returns a collection of stdclass objects, each containing the fields 'id', 'title', 'description', 'is_system_page' and 'url' (which is absolute).
	 */
	public function getChildPages($sitemap_id);

	/**
	 * Gets the parent page of the specified page.
	 * @param string $sitemap_id The internal identifier of the page.
	 * @return null|object Null, if the page is a root page, otherwise a stdclass object containing the fields 'id', 'title', 'description', 'is_system_page' and 'url' (which is absolute).
	 */
	public function getParentPage($sitemap_id);

	/**
	 * Gets a stdclass object containing the fields 'id', 'title', 'description', 'is_system_page' and 'url' (which is absolute).
	 * @return object
	 */
	public function getPage($sitemap_id);

}

?>