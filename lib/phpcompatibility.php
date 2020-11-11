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

if (!function_exists('http_response_code'))
{
	function http_response_code($code)
	{
		$desc = 'Unknown Status';
		switch ($code)
		{
			case 100: $desc = 'Continue'; break;
			case 101: $desc = 'Switching Protocols'; break;
			case 200: $desc = 'OK'; break;
			case 201: $desc = 'Created'; break;
			case 202: $desc = 'Accepted'; break;
			case 203: $desc = 'Non-Authoritative Information'; break;
			case 204: $desc = 'No Content'; break;
			case 205: $desc = 'Reset Content'; break;
			case 206: $desc = 'Partial Content'; break;
			case 300: $desc = 'Multiple Choices'; break;
			case 301: $desc = 'Moved Permanently'; break;
			case 302: $desc = 'Moved Temporarily'; break;
			case 303: $desc = 'See Other'; break;
			case 304: $desc = 'Not Modified'; break;
			case 305: $desc = 'Use Proxy'; break;
			case 400: $desc = 'Bad Request'; break;
			case 401: $desc = 'Unauthorized'; break;
			case 402: $desc = 'Payment Required'; break;
			case 403: $desc = 'Forbidden'; break;
			case 404: $desc = 'Not Found'; break;
			case 405: $desc = 'Method Not Allowed'; break;
			case 406: $desc = 'Not Acceptable'; break;
			case 407: $desc = 'Proxy Authentication Required'; break;
			case 408: $desc = 'Request Time-out'; break;
			case 409: $desc = 'Conflict'; break;
			case 410: $desc = 'Gone'; break;
			case 411: $desc = 'Length Required'; break;
			case 412: $desc = 'Precondition Failed'; break;
			case 413: $desc = 'Request Entity Too Large'; break;
			case 414: $desc = 'Request-URI Too Large'; break;
			case 415: $desc = 'Unsupported Media Type'; break;
			case 500: $desc = 'Internal Server Error'; break;
			case 501: $desc = 'Not Implemented'; break;
			case 502: $desc = 'Bad Gateway'; break;
			case 503: $desc = 'Service Unavailable'; break;
			case 504: $desc = 'Gateway Time-out'; break;
			case 505: $desc = 'HTTP Version not supported'; break;
		}
		$protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
		header(sprintf('%s %s %s', $protocol, $code, $desc));
	}
}

?>