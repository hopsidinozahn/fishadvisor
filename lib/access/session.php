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

namespace Octarine\Access;

class Session
{
	private static $hasStarted = false;
	private static $sessionKey = 'OctarineCMS';

	private static function start()
	{
		if (!self::$hasStarted)
		{
			if (headers_sent())
			{
				throw new \Exception('Cannot start session, as the headers have already been sent.');
			}
			self::$hasStarted = true;
			session_start();
		}
	}

	public static function destroy()
	{
		if (headers_sent())
		{
			throw new \Exception('Cannot destroy session, as the headers have already been sent.');
		}
		
		// Reset globals
		if (isset($_SESSION[self::$sessionKey]))
		{
			$_SESSION[self::$sessionKey] = array();
			unset($_SESSION[self::$sessionKey]);
		}

		// Destroy session
		session_destroy();
		
		// Remove cookie
		if (ini_get('session.use_cookies'))
		{
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
		}
		
		self::$hasStarted = false;
	}
	
	public static function getPrincipalId()
	{
		if (!self::$hasStarted)
			self::start();

		if (isset($_SESSION[self::$sessionKey]) && isset($_SESSION[self::$sessionKey]['id']))
			return $_SESSION[self::$sessionKey]['id'];
		else
			return false;
	}
	
	public static function create($principalId)
	{
		if ((int)$principalId <= 0)
		{
			throw new \Exception('No valid principal id is given (should be positive integer).');
		}
		
		// Make sure that session is started
		self::start();
		
		// Store login information
		$_SESSION[self::$sessionKey] = array
		(
			'id' => (int)$principalId,
			'ip' => $_SERVER['REMOTE_ADDR'],
			'time' => time()
		);
	}
}

?>