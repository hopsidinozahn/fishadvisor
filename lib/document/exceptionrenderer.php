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

interface ExceptionRenderer
{
	/**
	 * Renders the given exception and returns the generated HTML code.
	 * @return string Complete HTML5 source code of the generated page.
	 */
	public function render(\Exception $exception);

	/**
	 * Renders the given exception and outputs the generated HTML code, including all necessary HTTP headers.
	 * @return void
	 */
	public function output(\Exception $exception);
}

?>