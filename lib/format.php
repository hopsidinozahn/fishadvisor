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

namespace Octarine;
require_once SYS_LIB_ROOT . 'i18n/localizer.php';

class Format
{
	public static function parseDate($date)
	{
		if ($date instanceof \DateTime)
			return $date;
		elseif (is_numeric($date))
		{
			// When specifying a UNIX timestamp, the used timezone is always UTC
			$d = new \DateTime('@' . (int)$date);
			$d->setTimezone(new \DateTimeZone(date_default_timezone_get()));
			return $d;
		}
		elseif (is_string($date))
			return new \DateTime($date);
		else
			throw new \Exception('Bad input format for parameter $date');
	}

	public static function date($date)
	{
		return self::parseDate($date)->format('d.m.Y, H:i');
	}

	public static function relativeDate($date, \Octarine\I18n\Localizer $localizer)
	{
		$diff = self::parseDate($date)->diff(new \DateTime());
		if ($diff->invert) return null; // in the future

		$dh = $diff->days * 24 + $diff->h;
		$dm = $dh * 60 + $diff->i;

		if ($diff->days > 30)
			return null;
		if ($dh > 48) // "48" must be at least 1.5 days
			return $localizer->format('%d days ago', array(round($dh / 24)));
		if ($dm > 120) // "120" must be at least 1.5 hours
			return $localizer->format('%d hours ago', array(round($dm / 60)));
		if ($dm >= 3)
			return $localizer->format('%d minutes ago', array($dm));
		return $localizer->get('just now');
	}

	public static function dateAndRelativeDate($date, \Octarine\I18n\Localizer $localizer)
	{
		$str1 = self::date($date);
		$str2 = self::relativeDate($date, $localizer);
		if ($str2)
			return sprintf('%s (%s)', $str1, $str2);
		else
			return $str1;
	}
}

?>