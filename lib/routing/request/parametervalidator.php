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

require_once SYS_LIB_ROOT . 'routing/request/parameters.php';

interface ParameterValidator
{
	/*
	 * Validates the given request by checking the provided parameters against the definitions.
	 * @param string $erroneous_parameter Will contain the name of the parameter which failed validation.
	 * @param int $error_reason Will contain the reason why validation failed. One of the PARAM_ERROR_* constants.
	 * @return false|\Octarine\Routing\Request\Parameters False if validation failed, a Parameters instance otherwise.
	 */
	public function validate(Parameters $parameters, &$erroneous_parameter, &$error_reason);
}

?>