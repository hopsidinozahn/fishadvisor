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

require_once SYS_LIB_ROOT . 'access/principal.php';

class DbPrincipal implements Principal
{
	public function __construct($id, $name, $accessRightsBitMask)
	{
		$this->id = $id;
		$this->name = $name;
		$this->accessRightsBitMask = (int)$accessRightsBitMask;
	}

	private $connection, $name, $accessRightsBitMask;

	/**
	 * Gets the identifier of this principal.
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Gets the name associated with this principal.
	 *
	 * @return string|null
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Gets an (integer-valued) bitmask, each bit of which represents an access right that this principal owns.
	 *
	 * @return int
	 */
	public function getAccessRights()
	{
		return $this->accessRightsBitMask;
	}

	/**
	 * Checks whether this principal can access a resource which requires the specified access rights.
	 *
	 * @param int $requiredAccessRights A bitmask, each bit of which specifies a flag which is required for accessing the resource
	 * @return int
	 */
	public function canAccess($requiredAccessRights)
	{
		return ($this->accessRightsBitMask & $requiredAccessRights) == $requiredAccessRights;
	}
}

?>