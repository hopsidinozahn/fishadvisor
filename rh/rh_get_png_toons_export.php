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
require_once SYS_LIB_ROOT . 'routing/response/pngresponse.php';
require_once SYS_LIBEXT_ROOT . 'phpqrcode/qrlib.php';

class rh_get_png_toons_export extends \Octarine\Routing\Request\RequestHandlerBase
{
	public function __construct(\Octarine\Engine\Engine $engine)
	{
		$this->engine = $engine;
	}

	private $engine;

	public function getParameterValidator()
	{
		$p = parent::getParameterValidator();
		$p->defineString('data');
		return $p;
	}

	public function handle(\Octarine\Routing\Request\ValidatedRequest $request)
	{
		$data = $request->getParameters()->get('data');
		$import_url = sprintf('%s://%s%s?data=%s',
			isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https' : 'http',
			$_SERVER['HTTP_HOST'],
			$this->engine->getAbsoluteDocumentUrl('/toons/import.html'),
			urlencode($data));

		ob_start();
		\QRcode::png($import_url, false, \QR_ECLEVEL_L, 4 /*size multiplier*/);
		$img = ob_get_contents();
		ob_end_clean();

		return new \Octarine\Routing\Response\PngResponse(200, $img);
	}
}

?>