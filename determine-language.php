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

require_once 'root.php';
require_once SYS_LIB_ROOT . 'i18n/languagemanager.php';

$language_manager = new \Octarine\I18n\LanguageManager(explode(',', SYS_LANGUAGES));
$best_language = $language_manager->chooseBest();

$relative_uri = '/';
if (strpos($_SERVER['REQUEST_URI'], SYS_WEB_ROOT) === 0)
{
	$relative_uri = '/' . ltrim(substr($_SERVER['REQUEST_URI'], strlen(SYS_WEB_ROOT)), '/');
}

$relative_uri = sprintf('%s/%s%s', rtrim(SYS_WEB_ROOT, '/'), $best_language, $relative_uri);

// The use of 307 is important here, as we want the client to follow the redirect using the same (!) HTTP verb.
header('HTTP/1.1 307 Temporary Redirect');
header('Location: ' . $relative_uri);

?>