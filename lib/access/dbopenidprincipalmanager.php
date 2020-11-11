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

require_once SYS_LIB_ROOT . 'access/principalmanager.php';
require_once SYS_LIB_ROOT . 'access/dbprincipal.php';

class DbOpenIdPrincipalManager implements PrincipalManager
{
	public function __construct(\PDO $connection, $table_users)
	{
		$this->connection = $connection;
		$this->stmt_get = $connection->prepare(sprintf('SELECT * FROM `%s` WHERE `id`=?', $table_users));
		$this->stmt_auth = $connection->prepare(sprintf('SELECT * FROM `%s` WHERE `openid_url`=? AND `openid_id`=?', $table_users));
		$this->defaultPrincipal = new DbPrincipal(-1, 'guest', 0);
	}

	private $connection, $stmt_get, $stmt_auth;
	private $defaultPrincipal;

	public function authenticate()
	{
		list($openid_url, $openid_id) = func_get_args();
		if (!$this->stmt_auth->execute(array($openid_url, $openid_id)))
		{
			$err = $this->stmt_auth->errorInfo(); $err = $err[2];
			throw new \Exception('Database query failed: ' . $err);
		}
		$o = $this->stmt_auth->fetch(\PDO::FETCH_OBJ);
		if (!$o)
			return null;
		else
			return new DbPrincipal($o->id, $o->openid_id, $o->access);
	}

	public function get($id)
	{
		if (!$this->stmt_get->execute(array($id)))
		{
			$err = $this->stmt_get->errorInfo(); $err = $err[2];
			throw new \Exception('Database query failed: ' . $err);
		}
		$o = $this->stmt_get->fetch(\PDO::FETCH_OBJ);
		if (!$o)
			return null;
		else
			return new DbPrincipal($o->id, $o->openid_id, $o->access);
	}

	public function getOrDefault($id)
	{
		if ($o = $this->get($id))
			return $o;
		else
			return $this->getDefault();
	}

	public function getDefault()
	{
		return $this->defaultPrincipal;
	}
}

?>