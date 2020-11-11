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

namespace Octarine\Routing\Request;
require_once SYS_LIB_ROOT . 'routing/request/validatedrequest.php';

interface RequestHandler
{
	/**
	 * Gets an (integer-valued) bit mask, each bit of which is required for this handler to become usable.
	 * For instance, if the required access flags are 0b1101 and the requester has flags 0b1111, he may access
	 * the resource, whereas if he only has 0b0111 or 0b1100, he may not.
	 *
	 * @return int
	 */
	public function getRequiredAccessFlags();

	/**
	 * Gets a validator specifying the parameters which are allowed/required by this request handler.
	 *
	 * @return \Octarine\Routing\Request\ParameterValidator
	 */
	public function getParameterValidator();

	/**
	 * Handles the request.
	 *
	 * @param \Octarine\Routing\Request\ValidatedRequest $request The (validated, i.e., access and parameter definitions have been verified) request which shall be handled.
	 * @return \Octarine\Routing\Response\Response The response which shall be sent back to the requester.
	 */
	public function handle(ValidatedRequest $request);
}

?>