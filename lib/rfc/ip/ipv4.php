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

namespace Octarine\Rfc;

define('REGEX_IP4', '#^(?!\.)((\.|^)(0|[1-9][0-9]{0,2})){4}$#');
define('REGEX_IP4_WILDCARD', '#^(?!\.)((\.|^)(0|\*|[1-9][0-9]{0,2})){4}$#');
define('REGEX_IP4_MASK', '#^(?!\.)((\.|^)(0|[1-9][0-9]{0,2})){1,4}(/[1-9][0-9]*)?$#');

function parseIp4($ip_str)
{
	$i = strpos($ip_str, '/');
	$mask = 32;
	if ($i !== false)
	{
		$mask = min(32, max(0, intval(substr($ip_str, $i + 1))));
		$ip_str = substr($ip_str, 0, $i);
	}

	list($ip3, $ip2, $ip1, $ip0) = array_merge(explode('.', $ip_str), array(0,0,0,0));
	return applyIp4Mask((object)array('ip3' => $ip3, 'ip2' => $ip2, 'ip1' => $ip1, 'ip0' => $ip0), $mask);
}
function applyIp4Mask(\stdclass $ip, $mask)
{
	$mask3 = (0xFF << min(8, max(0, 8 - $mask))) & 0xFF;
	$mask2 = (0xFF << min(8, max(0, 16 - $mask))) & 0xFF;
	$mask1 = (0xFF << min(8, max(0, 24 - $mask))) & 0xFF;
	$mask0 = (0xFF << min(8, max(0, 32 - $mask))) & 0xFF;
	$ip3 = $ip->ip3 & $mask3;
	$ip2 = $ip->ip2 & $mask2;
	$ip1 = $ip->ip1 & $mask1;
	$ip0 = $ip->ip0 & $mask0;

	$str_ip = sprintf('%d.%d.%d.%d', $ip3, $ip2, $ip1, $ip0);
	$str_ip_mask = sprintf('%d.%d.%d.%d/%d', $ip3, $ip2, $ip1, $ip0, $mask);
	return (object)array(
		'ip3' => $ip3,
		'ip2' => $ip2,
		'ip1' => $ip1,
		'ip0' => $ip0,
		'mask' => $mask,
		'ip' => $str_ip,
		'ip_mask' => $str_ip_mask,
		'str' => $mask < 32 ? $str_ip_mask : $str_ip
	);
}

?>