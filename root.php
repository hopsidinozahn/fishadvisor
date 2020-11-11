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

/*
 * General settings. Disable under ALL CIRCUMSTANCES the debugging information in a production
 * environment, as it may reveal sensitive information (such as the database password).
 */
define('SYS_DEBUG_ERRORS', true);
define('SYS_DEBUG_STACKTRACE', true);
define('SYS_DEBUG_INDENT_HTML', true);
define('SYS_LANGUAGES', 'en,de'); // comma-separated ISO 639-1 language codes; first one is default language

/*
 * Root directories
 */
define('SYS_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('SYS_TIMEZONE', 'UTC');
define('SYS_LIB_ROOT', SYS_ROOT . 'lib' . DIRECTORY_SEPARATOR);
define('SYS_LIBEXT_ROOT', SYS_ROOT . 'lib_ext' . DIRECTORY_SEPARATOR);
define('SYS_RH_ROOT', SYS_ROOT . 'rh' . DIRECTORY_SEPARATOR);
define('SYS_WEB_ROOT', '/fishadvisor/');

?>