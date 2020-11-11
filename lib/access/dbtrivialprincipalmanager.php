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

class DbTrivialPrincipalManager implements PrincipalManager
{
	public function __construct()
	{
		$this->defaultPrincipal = new DbPrincipal(-1, 'guest', 0);
	}

	private $defaultPrincipal;

	public function authenticate()
	{
		return null;
	}

	public function get($id)
	{
		return null;
	}

	public function getOrDefault($id)
	{
		return $this->getDefault();
	}

	public function getDefault()
	{
		return $this->defaultPrincipal;
	}
}

?>