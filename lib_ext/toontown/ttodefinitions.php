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

require_once SYS_LIBEXT_ROOT . 'toontown/ttgenericdefinitions.php';

class TTODefinitions extends TTGenericDefinitions
{
	public function getGlobalRarityDialBase()
	{
		return 4.3;
	}

	public function getFishDict()
	{
		$pg_anywhere = '*';
		$pg_ToontownCentral = 'tc';
		$pg_PunchlinePlace = 'tc_pp';
		$pg_SillyStreet = 'tc_ss';
		$pg_LoopyLane = 'tc_ll';
		$pg_DonaldsDock = 'dd';
		$pg_LighthouseLane = 'dd_ll';
		$pg_BarnacleBoulevard = 'dd_bb';
		$pg_SeaweedStreet = 'dd_ss';
		$pg_DaisyGardens = 'dg';
		$pg_ElmStreet = 'dg_es';
		$pg_MapleStreet = 'dg_ms';
		$pg_OakStreet = 'dg_os';
		$pg_MinniesMelodyland = 'mm';
		$pg_AltoAvenue = 'mm_aa';
		$pg_BaritoneBoulevard = 'mm_bb';
		$pg_TenorTerrace = 'mm_tt';
		$pg_TheBrrrgh = 'tb';
		$pg_SleetStreet = 'tb_ss';
		$pg_WalrusWay = 'tb_ww';
		$pg_PolarPlace = 'tb_pp';
		$pg_DonaldsDreamland = 'dl';
		$pg_LullabyLane = 'dl_ll';
		$pg_PajamaPlace = 'dl_pp';
		$pg_Estate = 'ee';

		return array(
			// genus id => array( list of species )
			0 => array(
				// species index => array( name, min weight, max weight, rarity, list of zones )
				// NOTE: names are loaded below
				array('', 1, 3, 1, array($pg_anywhere)),
				array('', 1, 1, 4, array($pg_ToontownCentral, $pg_anywhere)),
				array('', 3, 5, 5, array($pg_PunchlinePlace, $pg_TheBrrrgh)),
				array('', 3, 5, 3, array($pg_SillyStreet, $pg_DaisyGardens)),
				array('', 1, 5, 2, array($pg_LoopyLane, $pg_ToontownCentral)),
			),
			32 => array(
				array('', 1, 5, 2, array($pg_ToontownCentral, $pg_Estate, $pg_anywhere)),
				array('', 1, 5, 3, array($pg_TheBrrrgh, $pg_Estate, $pg_anywhere)),
				array('', 1, 5, 4, array($pg_DaisyGardens, $pg_Estate)),
				array('', 1, 5, 5, array($pg_DonaldsDreamland, $pg_Estate)),
				array('', 1, 5, 10, array($pg_TheBrrrgh, $pg_DonaldsDreamland))
			),
			2 => array(
				array('', 2, 6, 1, array($pg_DaisyGardens, $pg_anywhere)),
				array('', 2, 6, 9, array($pg_ElmStreet, $pg_DaisyGardens)),
				array('', 5, 11, 4, array($pg_LullabyLane)),
				array('', 2, 6, 3, array($pg_DaisyGardens, $pg_Estate)),
				array('', 5, 11, 2, array($pg_DonaldsDreamland, $pg_Estate))
			),
			4 => array(
				array('', 2, 8, 1, array($pg_ToontownCentral, $pg_anywhere)),
				array('', 2, 8, 4, array($pg_ToontownCentral, $pg_anywhere)),
				array('', 2, 8, 2, array($pg_ToontownCentral, $pg_anywhere)),
				array('', 2, 8, 6, array($pg_ToontownCentral, $pg_MinniesMelodyland))
			),
			6 => array(
				array('', 8, 12, 1, array($pg_TheBrrrgh))
			),
			8 => array(
				array('', 1, 5, 1, array($pg_anywhere)),
				array('', 2, 6, 2, array($pg_MinniesMelodyland, $pg_anywhere)),
				array('', 5, 10, 5, array($pg_MinniesMelodyland, $pg_anywhere)),
				array('', 1, 5, 7, array($pg_Estate, $pg_anywhere)),
				array('', 1, 5, 10, array($pg_Estate, $pg_anywhere))
			),
			10 => array(
				array('', 6, 10, 9, array($pg_Estate, $pg_anywhere))
			),
			12 => array(
				array('', 7, 15, 1, array($pg_DonaldsDock, $pg_anywhere)),
				array('', 18, 20, 6, array($pg_DonaldsDock, $pg_Estate)),
				array('', 1, 5, 5, array($pg_DonaldsDock, $pg_Estate)),
				array('', 3, 7, 4, array($pg_DonaldsDock, $pg_Estate)),
				array('', 1, 2, 2, array($pg_DonaldsDock, $pg_anywhere))
			),
			34 => array(
				array('', 1, 20, 10, array($pg_DonaldsDreamland, $pg_anywhere))
			),
			14 => array(
				array('', 2, 6, 1, array($pg_DaisyGardens, $pg_Estate, $pg_anywhere)),
				array('', 2, 6, 3, array($pg_DaisyGardens, $pg_Estate))
			),
			16 => array(
				array('', 4, 12, 5, array($pg_MinniesMelodyland, $pg_anywhere)),
				array('', 4, 12, 7, array($pg_BaritoneBoulevard, $pg_MinniesMelodyland)),
				array('', 4, 12, 8, array($pg_TenorTerrace, $pg_MinniesMelodyland))
			),
			18 => array(
				array('', 2, 4, 3, array($pg_DonaldsDock, $pg_anywhere)),
				array('', 5, 8, 7, array($pg_TheBrrrgh)),
				array('', 4, 6, 8, array($pg_LighthouseLane))
			),
			20 => array(
				array('', 4, 6, 1, array($pg_DonaldsDreamland)),
				array('', 14, 18, 10, array($pg_DonaldsDreamland)),
				array('', 6, 10, 8, array($pg_LullabyLane)),
				array('', 1, 1, 3, array($pg_DonaldsDreamland)),
				array('', 2, 6, 6, array($pg_LullabyLane)),
				array('', 10, 14, 4, array($pg_DonaldsDreamland, $pg_DaisyGardens))
			),
			22 => array(
				array('', 12, 16, 2, array($pg_Estate, $pg_DaisyGardens, $pg_anywhere)),
				array('', 14, 18, 3, array($pg_Estate, $pg_DaisyGardens, $pg_anywhere)),
				array('', 14, 20, 5, array($pg_Estate, $pg_DaisyGardens)),
				array('', 14, 20, 7, array($pg_Estate, $pg_DaisyGardens))
			),
			24 => array(
				array('', 9, 11, 3, array($pg_anywhere)),
				array('', 8, 12, 5, array($pg_DaisyGardens, $pg_DonaldsDock)),
				array('', 8, 12, 6, array($pg_DaisyGardens, $pg_DonaldsDock)),
				array('', 8, 16, 7, array($pg_DaisyGardens, $pg_DonaldsDock))
			),
			26 => array(
				array('', 10, 18, 2, array($pg_TheBrrrgh)),
				array('', 10, 18, 3, array($pg_TheBrrrgh)),
				array('', 10, 18, 4, array($pg_TheBrrrgh)),
				array('', 10, 18, 5, array($pg_TheBrrrgh)),
				array('', 12, 20, 6, array($pg_TheBrrrgh)),
				array('', 14, 20, 7, array($pg_TheBrrrgh)),
				array('', 14, 20, 8, array($pg_SleetStreet, $pg_TheBrrrgh)),
				array('', 16, 20, 10, array($pg_WalrusWay, $pg_TheBrrrgh))
			),
			28 => array(
				array('', 2, 10, 2, array($pg_DonaldsDock, $pg_anywhere)),
				array('', 4, 10, 6, array($pg_BarnacleBoulevard, $pg_DonaldsDock)),
				array('', 4, 10, 7, array($pg_SeaweedStreet, $pg_DonaldsDock))
			),
			30 => array(
				array('', 13, 17, 5, array($pg_MinniesMelodyland, $pg_anywhere)),
				array('', 16, 20, 10, array($pg_AltoAvenue, $pg_MinniesMelodyland)),
				array('', 12, 18, 9, array($pg_TenorTerrace, $pg_MinniesMelodyland)),
				array('', 12, 18, 6, array($pg_MinniesMelodyland)),
				array('', 12, 18, 7, array($pg_MinniesMelodyland))
			),
		);
	}
}

?>