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

namespace Octarine\Routing\Request;

define(__NAMESPACE__ . '\PARAM_ERROR_MISSING', 1); // parameter is required but missing
define(__NAMESPACE__ . '\PARAM_ERROR_SIZE', 2); // provided value is far too large (hacking attempt?)
define(__NAMESPACE__ . '\PARAM_ERROR_OCCUR', 3); // provided value has bad occurrence (is array when it should not, or vice-versa)
define(__NAMESPACE__ . '\PARAM_ERROR_FORMAT', 4); // provided value has bad format (e.g. not an integer, regex mismatch, ...)

?>