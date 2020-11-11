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

namespace Octarine\Toontown;

class Helper
{
	public static function getProbabilityString($p)
	{
		if ($p <= 0.0001)
			return '0.01%';

		$n = $p >= 0.05 ? 0 : ($p >= 0.005 ? 1 : 2);
		return sprintf('%.' . $n . 'f%%', $p * 100);
	}

	public static function getBucketFactorAcrossRods($old_probability, $new_probability)
	{
		// The mathematical model used here is the geometric distribution. Note that P[numtries = k] = p(1-p)^k.
		// What we are trying to achieve here is converting the failed catches with a previous rod
		// into failed catches with the new rod.
		//
		// Example:
		// If a player buys a new rod, the success probabilities change. The new required number of catches
		// is then only with respect to the new rod - so basically the player has to 'forget' about all his
		// earlier catches. We are trying to circumvent this problem by computing the equivalent
		// number of buckets he would have caught with the new rod in such a way that his overall failure
		// probability remains the same.
		//
		// The value computed here is the factor to multiply the number of already caught buckets with
		// in order to get the analogue number of buckets with the new rod -- so the target number of buckets
		// decreases by this analogue number.
		//
		// Mathematically, we try to find the number of failures x with success probability $new_probability
		// that correspond to the number of failures y with success probability $old_probability.
		// So the equation reads (1-new_probability)^x = (1-old_probability)^y
		// <=> x = y * log(1-old_probability) / log(1-new_probability).
		return log(1 - $old_probability) / log(1 - $new_probability);
	}

	public static function getNumberOfRequiredBuckets($success_probability)
	{
		// We want the number of times we have to try until we succeed with probability at least A=95%.
		// Mathematically, we are dealing with the geometric distribution and want to determine when its
		// cumulative distribution function (CDF) achieves the 'A' level. But the CDF of the geom. distrib.
		// is just P[numtries <= k] = 1-(1-p)^k where p is the success probability and k is the number of tries.
		// Thus, k = log_(1-p)(1 - A)
		$num_tries = ceil(log(0.1, 1 - $success_probability));

		return max(1, round($num_tries / 20.0));
	}

	public static function getParentPlaygroundTexts($pond_id)
	{
		if ($pond_id == 'ee') return array('at your ', 'estate');
		if (strlen($pond_id) == 2) return array('on the ', 'playground');
		switch (substr($pond_id, 0, 2))
		{
			case 'tc': return array('in ', 'Toontown Central');
			case 'dd': return array('in ', 'Donald\'s Dock');
			case 'dg': return array('in ', 'Daisy Gardens');
			case 'mm': return array('in ', 'Minnie\'s Melodyland');
			case 'tb': return array('in ', 'The Brrrgh');
			case 'dl': return array('in ', 'Donald\'s Dreamland');
			case 'ee': return array('at your ', 'estate');
			default: throw new \Exception('Unknown pond id: ' . $pond_id);
		}
	}

	public static function getArrivalPlaygroundName($pond_id)
	{
		switch ($pond_id)
		{
			default:
				return null;
			case 'dd_bb':
			case 'dg_es':
			case 'mm_aa':
				return 'Toontown Central';
			case 'tc_pp':
			case 'dg_ms':
			case 'tb_ww':
				return 'Donald\'s Dock';
			case 'tc_ss':
			case 'dd_ss':
				return 'Daisy Gardens';
			case 'tc_ll':
			case 'tb_ss':
			case 'dl_ll':
				return 'Minnie\'s Melodyland';
			case 'dd_ll':
			case 'mm_bb':
				return 'The Brrrgh';
			case 'mm_tt':
				return 'Donald\'s Dreamland';
			case 'dg_os':
				return 'Sellbot HQ';
			case 'dl_pp':
				return 'Cashbot HQ';
			case 'tb_pp':
				return 'Lawbot HQ';
		}
	}
}

?>