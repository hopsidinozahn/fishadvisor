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

class FiniteStateMachine
{
	public function __construct()
	{
		$this->states = array();
		$this->transitions = array();
	}

	private $states, $transitions;
	private $current = null;

	public function enterInitial($state)
	{
		// This is always possible, even if we are already in a state.
		// If using enterInitial() no transition event is raised
		if (!in_array($state, $this->states))
			throw new \Exception(sprintf('No such state: %s', $state));
		$this->current = $state;
	}

	public function enter($state)
	{
		if (!isset($this->current))
		{
			throw new \Exception('Not in a state - use enterInitial() first');
		}
		else
		{
			if (!isset($this->transitions[$this->current]) || !in_array($state, $this->transitions[$this->current]))
				throw new \Exception(sprintf('Transition from %s to %s was not defined', $this->current, $state));
			$old = $this->current;
			$this->current = $state;
			$this->onEnterState($old, $state);
		}
	}

	public function canEnter($state)
	{
		if (!isset($this->current))
			return false; // must be in some state first
		else
			return isset($this->transitions[$this->current]) && in_array($state, $this->transitions[$this->current]);
	}

	public function defineState($state)
	{
		if (in_array($state, $this->states))
			throw new \Exception(sprintf('State %s already defined', $state));
		else
			$this->states[] = $state;
	}

	public function defineTransition($from_state, $to_state)
	{
		if (is_array($to_state))
		{
			foreach ($to_state as $s)
				$this->defineTransition($from_state, $s);
		}
		else
		{
			if (!isset($this->transitions[$from_state]))
				$this->transitions[$from_state] = array($to_state);
			elseif (in_array($to_state, $this->transitions[$from_state]))
				throw new \Exception(sprintf('Transition from %s to %s already defined', $from_state, $to_state));
			else
				$this->transitions[$from_state][] = $to_state;
		}
	}

	protected function onEnterState($from_state, $to_state)
	{
		// virtual
	}
}

?>