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

require_once SYS_LIB_ROOT . 'engine/engine.php';
require_once SYS_LIB_ROOT . 'routing/request/requesthandlerbase.php';
require_once SYS_LIB_ROOT . 'routing/response/jsonresponse.php';
require_once SYS_LIB_ROOT . 'i18n/localizer.php';
require_once SYS_LIBEXT_ROOT . 'toontown/ttrdefinitions.php';
require_once SYS_LIBEXT_ROOT . 'toontown/helper.php';

class rh_get_json_buckets extends \Octarine\Routing\Request\RequestHandlerBase
{
	public function __construct(\Octarine\Engine\Engine $engine, \Octarine\I18n\Localizer $localizer)
	{
		$this->engine = $engine;
		$this->localizer = $localizer;
	}

	private $engine, $localizer;

	public function getParameterValidator()
	{
		$p = parent::getParameterValidator();
		$p->defineString('rid');
		$p->defineString('pid');
		$p->defineString('fid');
		return $p;
	}

	public function handle(\Octarine\Routing\Request\ValidatedRequest $request)
	{
		$def = new \Octarine\Toontown\TTRDefinitions();
		$rod = $def->getRod($request->getParameters()->get('rid'));
		$fish = $def->getFish($request->getParameters()->get('fid'));
		$pond = $def->getPond($request->getParameters()->get('pid'));

		$can_catch;
		$probability = $def->getProbability($fish->id, $rod->id, $pond->id, $can_catch);
		if (!$can_catch)
			throw new \Exception(sprintf('Fish "%s" cannot be caught at pond "%s" with rod "%s".', $fish->id, $pond->id, $rod->id));

		$data = array();
		foreach ($def->getRods() as $previous_rod)
		{
			if ($previous_rod->order > $rod->order) continue;
			$can_catch;
			$previous_probability = $def->getProbability($fish->id, $previous_rod->id, $pond->id, $can_catch);
			if (!$can_catch) continue;

			$data[$previous_rod->id] = array(
				'buckets' => \Octarine\Toontown\Helper::getNumberOfRequiredBuckets($previous_probability),
				'bucket_factor' => round(\Octarine\Toontown\Helper::getBucketFactorAcrossRods($previous_probability, $probability), 2)
			);
		}

		return new \Octarine\Routing\Response\JsonResponse(200, $data);
	}
}

?>