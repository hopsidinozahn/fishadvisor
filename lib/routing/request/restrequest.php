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

require_once SYS_LIB_ROOT . 'routing/_constants.php';
require_once SYS_LIB_ROOT . 'routing/request/request.php';
require_once SYS_LIB_ROOT . 'routing/request/arrayparameters.php';
require_once SYS_LIB_ROOT . 'rfc/http/headerqvaluepicker.php';

class RestRequest implements Request
{
	private function __construct($web_root)
	{
		// Make sure $web_root has trailing slash
		$web_root = rtrim($web_root, '/') . '/';

		switch ($m = strtoupper($_SERVER['REQUEST_METHOD']))
		{
			case 'DELETE': $this->method = \Octarine\Routing\METHOD_DELETE; break;
			case 'GET': $this->method = \Octarine\Routing\METHOD_GET; break;
			case 'HEAD': $this->method = \Octarine\Routing\METHOD_HEAD; break;
			case 'OPTIONS': $this->method = \Octarine\Routing\METHOD_OPTIONS; break;
			case 'POST': $this->method = \Octarine\Routing\METHOD_POST; break;
			case 'PUT': $this->method = \Octarine\Routing\METHOD_PUT; break;
			default: $this->method = \Octarine\Routing\METHOD_UNKNOWN; break;
		}

		$path = parse_url('scheme://host' . $_SERVER['REQUEST_URI'], PHP_URL_PATH);
		if (strpos($path, $web_root) !== 0)
			$this->path = null;
		else
			$this->path = '/' . substr($path, strlen($web_root));
	}

	private $method, $path;
	private $args = null;

	public static function fromEnvironment($web_root)
	{
		return new self($web_root);
	}

	public function getMethod()
	{
		return $this->method;
	}

	public function matchContentType(array $possible_content_types)
	{
		$value_picker = new \Octarine\Rfc\Http\HeaderQValuePicker();
		foreach ($possible_content_types as $type)
			$value_picker->addSupportedValue($type);
		return $value_picker->chooseBestContentType($_SERVER['HTTP_ACCEPT']);
	}

	public function getParameters()
	{
		// Use cached version if it exists.
		// This is crucial, since we can read the PUT arguments only once!
		if (isset($this->args))
			return $this->args;

		switch ($this->method)
		{
			case \Octarine\Routing\METHOD_PUT:
				// For PUT requests, we have to read the arguments from
				// the PHP input pipe, and then parse them.
				// We finally handle the PUT case in the same way as the other ones.
				$_POST = array();
				$h = fopen('php://input', 'r');
				parse_str(fgets($h), $_POST); // write everything to $_POST
				fclose($h);
				// step to next case
			default:
				// POST overrides GET for security reasons; indeed,
				// otherwise a manipulated URL could override form data.
				$data = array_merge($_GET, $_POST);
				return $this->args = new ArrayParameters($data);
		}
	}

	public function getPath()
	{
		return $this->path;
	}
}

?>