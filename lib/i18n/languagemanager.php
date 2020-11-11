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

namespace Octarine\I18n;

require_once SYS_LIB_ROOT . 'rfc/http/headerqvaluepicker.php';

class LanguageManager
{
	public function __construct(/* ...$params */)
	{
		$this->value_picker = new \Octarine\Rfc\Http\HeaderQValuePicker();
		foreach (func_get_args() as $code)
		{
			if (is_array($code))
			{
				foreach ($code as $c)
					$this->define($c);
			}
			else
			{
				$this->define($code);
			}
		}
	}

	private $value_picker;
	private $default = null;
	private static $cookieKey = 'OctarineCMS_language';

	public function define($language)
	{
		$this->value_picker->addSupportedValue($language = strtolower(strval($language)));
		if (!isset($this->default))
			$this->default = $language;
	}

	public function getDefined()
	{
		return $this->value_picker->getSupportedValues();
	}

	public function setDefault($language)
	{
		if (!$this->value_picker->isValueSupported($language))
			throw new \Exception('Language ' . $language . ' is not defined');
		else
			$this->default = $language;
	}

	public function chooseBest()
	{
		if ($lang = $this->fromSession())
			return $lang;
		elseif ($lang = $this->fromUserAgent())
			return $lang;
		else
			return $this->default;
	}

	private function fromUserAgent()
	{
		return $this->value_picker->chooseBestLanguage($_SERVER['HTTP_ACCEPT_LANGUAGE'], true, $this->default);
	}

	public function fromSession()
	{
		if (isset($_COOKIE[self::$cookieKey]))
		{
			$lang = strtolower($_COOKIE[self::$cookieKey]);
			if (in_array($lang, $this->supported))
				return $lang;
		}
		return null;	
	}

	public static function saveToSession($language, $generic_web_root = '/')
	{
		if (headers_sent())
			throw new \Exception('Cannot save language to session -- headers already sent.');
		setcookie(self::$cookieKey, $language, time() + 86400 * 30, $generic_web_root);
	}
}

?>