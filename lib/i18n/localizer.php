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

interface Localizer
{
	/*
	 * Localizes the given string into the currently selected language.
	 * @param string $key The string to be localized.
	 * @return string The localized string.
	 */
	public function get($key);

	/*
	 * Localizes the given formatted string into the currently selected language.
	 * @param string $key The string to be localized (if $format_args is non-null, this string is passed to sprintf()).
	 * @param array|null $format_args Formatting arguments passed to sprintf(). The individual values will be localized as well.
	 * @return string The localized string.
	 */
	public function format($key, $format_args = null);

	/*
	 * Gets the localized name of the given language.
	 * @param string $language_code The ISO 639-1 language code.
	 * @return string The localized language name.
	 */
	public function getLanguageName($language_code);

	/*
	 * Gets the ISO 639-1 codes of all supported languages.
	 * @return string[]
	 */
	public function getAllLanguages();

	/*
	 * Indicates whether the given language has been defined within this localizer.
	 * @param string $language_code The ISO 639-1 code of the language.
	 * @return bool
	 */
	public function hasLanguage($language_code);

	/*
	 * Gets the ISO 639-1 codes of the currently selected language.
	 * @return string
	 */
	public function getCurrentLanguage();

	/*
	 * Selects the given language.
	 * @param string $language_code The ISO 639-1 code of the language to select.
	 */
	public function select($language_code);
}

?>