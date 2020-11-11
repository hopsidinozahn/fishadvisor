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

namespace Octarine\Document;

require_once SYS_LIB_ROOT . 'document/dom/dommanipulation.php';
require_once SYS_LIB_ROOT . 'document/ui/control.php';

interface Site extends \Octarine\Document\Dom\DomManipulation
{
	/**
	 * Specifies the currently displayed page.
	 * @param string $sitemap_id The id of the page.
	 * @return void
	 */
	public function setWhereabouts($sitemap_id);

	/**
	 * Appends a UI control to the site.
	 * @param \Octarine\Document\UI\Control $control The control to append.
	 */
	public function appendControl(\Octarine\Document\UI\Control $control);

	/**
	 * Returns the generated document.
	 * @param \Octarine\Document\HtmlDocument
	 */
	public function getDocument();
}

?>