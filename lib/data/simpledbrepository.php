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

namespace Octarine\Data;

class SimpleDbRepository
{
	public function __construct(\PDOStatement $get, \PDOStatement $getAll, \PDOStatement $create, \PDOStatement $update, \PDOStatement $delete)
	{
		$this->stmt_get = $get;
		$this->stmt_get_all = $getAll;
		$this->stmt_create = $create;
		$this->stmt_update = $update;
		$this->stmt_delete = $delete;
	}

	private $stmt_get, $stmt_get_all, $stmt_create, $stmt_update, $stmt_delete;

	public function get($id)
	{
		if (!$this->stmt_get->execute(array($id)))
		{
			$err = $this->stmt_get->errorInfo();
			throw new \Exception('Database query failed: ' . $err[2]);
		}
		return $this->stmt_get->fetch(\PDO::FETCH_OBJ);
	}

	public function getAll()
	{
		if (!$this->stmt_get_all->execute())
		{
			$err = $this->stmt_get_all->errorInfo();
			throw new \Exception('Database query failed: ' . $err[2]);
		}
		return $this->stmt_get_all->fetchAll(\PDO::FETCH_OBJ);
	}

	public function create($params)
	{
		if (!$this->stmt_create->execute($params))
		{
			$err = $this->stmt_create->errorInfo();
			return $err[2];
		}
		if ($this->stmt_create->rowCount() > 0)
			return true;
		else
			return 'No database entry was created (but no error was raised, either)';
	}

	public function update($params)
	{
		if (!$this->stmt_update->execute($params))
		{
			$err = $this->stmt_update->errorInfo();
			return $err[2];
		}
		// No update happens if the fields did not change.
		// We still consider the operation as being successful, though.
		return true;
	}

	public function delete($id)
	{
		if (!$this->stmt_delete->execute(array($id)))
			// This should not happen (unless the SQL statement is invalid, of course)
			return false;
		return $this->stmt_delete->rowCount() > 0;
	}
}

?>