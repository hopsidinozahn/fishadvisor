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

interface Principal
{
	/**
	 * Gets the identifier of this principal.
	 *
	 * @return int
	 */
	public function getId();

	/**
	 * Gets the name associated with this principal.
	 *
	 * @return string|null
	 */
	public function getName();

	/**
	 * Gets an (integer-valued) bitmask, each bit of which represents an access right that this principal owns.
	 *
	 * @return int
	 */
	public function getAccessRights();

	/**
	 * Checks whether this principal can access a resource which requires the specified access rights.
	 *
	 * @param int $requiredAccessRights A bitmask, each bit of which specifies a flag which is required for accessing the resource
	 * @return int
	 */
	public function canAccess($requiredAccessRights);
}

?>