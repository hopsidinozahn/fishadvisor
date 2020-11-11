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

require_once SYS_LIB_ROOT . 'routing/request/parametervalidator.php';
require_once SYS_LIB_ROOT . 'routing/request/parameters.php';
require_once SYS_LIB_ROOT . 'routing/request/arrayparameters.php';
require_once SYS_LIB_ROOT . 'routing/request/_constants.php';

class GenericParameterValidator implements ParameterValidator
{
	private $parameters = array();

	/*
	 * Defines a new parameter.
	 * @param string $parameter_name The unique name of this parameter.
	 * @param bool $is_required Whether this parameter must be provided by the client.
	 * @param bool $is_array Whether this parameter is provided as array.
	 * @param null|int $size The maximum size of the string representation of the value (or of each value in the array).
	 * @param callback $filter The filter to be applied to (each of) the provided value(s). If it returns null, the value is considered invalid. Otherwise, it is passed on to the API using the same type, so consider strong-typing the value(s). Meant to be used with filter_var().
	 */
	protected function define($parameter_name, $is_required, $is_array, $size, $filter)
	{
		if (isset($this->parameters[$parameter_name]))
			throw new \Exception('GenericParameterValidator: Parameter already defined: ' . $parameter_name);
		if ($size < 0)
			throw new \Exception('GenericParameterValidator: $size must be a positive integer or null.');

		$this->parameters[$parameter_name] = (object)array
		(
			'name' => $parameter_name,
			'is_required' => !!$is_required,
			'is_array' => !!$is_array,
			'size' => $size === null ? null : intval($size),
			'filter' => $filter
		);
	}

	public function defineString($parameter_name, $is_required = true, $is_array = false)
	{
		$this->define($parameter_name, $is_required, $is_array, 1000, function($val)
		{
			// Remove non-printable characters except TAB, CR, LF
			return preg_replace('#[\x00-\x08\x0B\x0C\x0E-\x1F]#u', '', strval($val));
		});
	}

	public function defineInt($parameter_name, $is_required = true, $is_array = false)
	{
		$this->define($parameter_name, $is_required, $is_array, 20, function($val)
		{
			return filter_var($val, FILTER_VALIDATE_INT, array('options' => array('default' => null)));
		});
	}

	public function defineId($parameter_name, $is_required = true, $is_array = false)
	{
		$this->define($parameter_name, $is_required, $is_array, 20, function($val)
		{
			$id = filter_var($val, FILTER_VALIDATE_INT, array('options' => array('default' => null)));
			return $id !== null && $id > 0 ? $id : null;
		});
	}

	public function defineBool($parameter_name, $is_required = true, $is_array = false)
	{
		$this->define($parameter_name, $is_required, $is_array, 20, function($val)
		{
			return filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
		});
	}

	public function defineDate($parameter_name, $is_required = true, $is_array = false)
	{
		$this->define($parameter_name, $is_required, $is_array, 11, function($val)
		{
			// '#' matches any separator;
			// pipe '|' sets time to midnight (current timezone);
			// DateTime will be with respect to the current timezone
			return \DateTime::createFromFormat('Y#m#d|', $val);
		});
	}

	public function defineRegex($parameter_name, $regex, $is_required = true, $is_array = false, $max_length = null)
	{
		$this->define($parameter_name, $is_required, $is_array, $max_length, function($val) use($regex)
		{
			return preg_match($regex, $val) ? $val : null;
		});
	}

	public function defineList($parameter_name, array $allowed_values, $is_required = true, $is_array = false)
	{
		$max_size = max(array_map('strlen', array_map('strval', $allowed_values))) + 10;
		$this->define($parameter_name, $is_required, $is_array, $max_size, function($val) use ($allowed_values)
		{
			foreach ($allowed_values as $allowed_value)
				if ($val == $allowed_value) // weak-type check
					return $allowed_value; // but make sure to return the actually allowed value
			return null;
		});
	}

	public function validate(Parameters $parameters, &$erroneous_parameter, &$error_reason)
	{
		$validated_parameters = array();

		foreach ($this->parameters as $parameter)
		{
			// Check if parameter is there
			if (!$parameters->has($parameter->name))
			{
				if ($parameter->is_required)
				{
					$erroneous_parameter = $parameter->name;
					$error_reason = PARAM_ERROR_MISSING;
					return false;
				}

				// The parameter was defined as optional, but no value has been provided. This is ok.
				$validated_parameters[$parameter->name] = null;
				continue;
			}

			// Get value
			$value = $parameters->get($parameter->name);

			// Make sure parameter is an array iff it should
			if (is_array($value) !== $parameter->is_array)
			{
				$erroneous_parameter = $parameter->name;
				$error_reason = PARAM_ERROR_OCCUR;
				return false;
			}

			// Check if provided values are not too long
			$size = $parameter->is_array ? max(array_map('strlen', array_map('strval', $value))) : strlen(strval($value));
			if ($parameter->size !== null && $size > $parameter->size)
			{
				$erroneous_parameter = $parameter->name;
				$error_reason = PARAM_ERROR_SIZE;
				return false;
			}

			// Validate value(s) using the specified filter
			$validated_value;
			if ($parameter->is_array)
			{
				$validated_value = array_map($parameter->filter, $value);
				if (in_array(null, $validated_value, true))
				{
					$erroneous_parameter = $parameter->name;
					$error_reason = PARAM_ERROR_FORMAT;
					return false;
				}
			}
			else
			{
				$f = $parameter->filter;
				$validated_value = $f($value);
				if ($validated_value === null)
				{
					$erroneous_parameter = $parameter->name;
					$error_reason = PARAM_ERROR_FORMAT;
					return false;
				}
			}

			// Add to collection of validated values
			$validated_parameters[$parameter->name] = $validated_value;
		}

		$erroneous_parameter = null;
		$error_reason = null;
		return new \Octarine\Routing\Request\ArrayParameters($validated_parameters);
	}
}

?>