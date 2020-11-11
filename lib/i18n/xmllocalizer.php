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
require_once SYS_LIB_ROOT . 'i18n/localizer.php';

class XmlLocalizer implements Localizer
{
	private $data = array();
	private $current_lang = false;

	public function get($key)
	{
		if ($this->current_lang)
		{
			$dict = $this->data[$this->current_lang];
			if (isset($dict[$key]))
				return $dict[$key];
		}
		return $key;
	}

	public function format($key, $format_args = null)
	{
		$text = $this->get($key);
		if ($format_args !== null)
		{
			$sprintf_args = array_map(array($this, 'get'), $format_args);
			array_unshift($sprintf_args, $text);
			return call_user_func_array('sprintf', $sprintf_args);
		}
		else
		{
			return $text;
		}
	}

	public function getLanguageName($language_code)
	{
		if (isset($this->data[$language_code]))
		{
			$dict = $this->data[$language_code];
			if (isset($dict['__NAME__']))
				return $dict['__NAME__'];
		}
		return $language_code;
	}

	public function getAllLanguages()
	{
		return array_keys($this->data);
	}

	public function hasLanguage($language_code)
	{
		return strlen($language_code) < 100 && isset($this->data[$language_code]);
	}

	public function getCurrentLanguage()
	{
		return $this->current_lang;
	}

	public function select($lang_id)
	{
		if (isset($this->data[$lang_id]))
			$this->current_lang = $lang_id;
		else
			throw new \Exception(sprintf('No such language: %s', $lang_id));
	}

	public function load($language_xml_file)
	{
		if (!is_file($language_xml_file) || !is_readable($language_xml_file))
		{
			throw new \Exception('Unable to read language file "' . basename($language_xml_file) . '".');
		}

		$xml = simplexml_load_file($language_xml_file);
		$code = "$xml[code]";
		$name = "$xml[name]";
		$dict = array('__NAME__' => $name);
		foreach ($xml->children() as $entry)
		{
			$key = strval($entry['key']);
			$dict[$key] = strval($entry);
		}
		$this->data[$code] = $dict;
	}

	public function addEmptyLanguage($language_code, $language_name = null)
	{
		$this->data[$language_code] = array(
			'__NAME__' => isset($language_name) ? $language_name : $language_code
		);
	}

	public function set($key, $value)
	{
		if ($this->current_lang)
			$this->data[$this->current_lang][$key] = strval($value);
		else
			throw new \Exception('No language selected.');
	}
}

?>