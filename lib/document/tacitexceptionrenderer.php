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

namespace Octarine\Document;

require_once SYS_LIB_ROOT . 'document/exceptionrenderer.php';

class TacitExceptionRenderer implements ExceptionRenderer
{
	public function output(\Exception $exception)
	{
		$html = $this->render($exception);

		http_response_code(500);
		header('Content-Type: text/html;charset=utf-8');
		header('Content-Length: ' . strlen($html));
		echo $html;
	}

	public function render(\Exception $exception)
	{
		return $this->buildHtml('Octarine CMS Error');
	}

	private function buildHtml($title)
	{
		return sprintf('<!DOCTYPE html>
<html>
<head>
<title>%1$s</title>
<style type="text/css">%2$s</style>
</head>
<body>
<div id="wrap">
<h1>%1$s</h1>
<section id="message">An internal error occurred while processing the request.</section>
<p>Please contact the webmaster if the problem persists. Further information have been written to the server log files.</p>
</div>
</body>
</html>', htmlspecialchars($title), $this->buildCss());
	}

	private function buildCss()
	{
		return '
body{font-family:sans-serif; font-size:14px;background:#fff;width:700px;margin:30px auto;color:#444;background:#ff4136;}
#wrap{background:#fff;padding:10px 50px;border-radius:5px;}
#message{padding:10px 5px;background:#ff4136;color:#fff;}
';
	}
}

?>