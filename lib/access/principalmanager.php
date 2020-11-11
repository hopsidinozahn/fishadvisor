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

interface PrincipalManager
{
	/**
	 * Authenticates a principal by its credentials and returns it, or null, if the authentication failed.
	 *
	 * @param mixed $data1,... Parameters containing authentication data (e.g. username, password).
	 * @return null|\Octarine\Access\Principal
	 */
	public function authenticate(/* $data1,... */);

	/**
	 * Gets the principal with the given identifer, or null, if none was found.
	 *
	 * @param int $id The principal identifier.
	 * @return null|\Octarine\Access\Principal
	 */
	public function get($id);

	/**
	 * Gets the principal with the given identifer. If none was found, gets the default principal.
	 *
	 * @param int $id The principal identifier.
	 * @return \Octarine\Access\Principal
	 */
	public function getOrDefault($id);

	/**
	 * Gets the default principal, also called "guest" principal.
	 *
	 * @return \Octarine\Access\Principal
	 */
	public function getDefault();
}

?>