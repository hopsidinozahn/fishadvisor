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

class FullExceptionRenderer implements ExceptionRenderer
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
		$html_trace_points = array_map(array($this, 'renderTracePoint'), $exception->getTrace());
		return $this->buildHtml('Octarine CMS Error', $exception->getMessage(), $html_trace_points);
	}

	private function renderTracePoint($pt)
	{
		$location = '<global scope>';
		if (!empty($pt['function']))
		{
			$location = $pt['function'] . '()';
			if (!empty($pt['class']))
				$location = $pt['class'] . $pt['type'] . $location;
		}
		return sprintf('<section class="trace"><header>%s</header><footer>Line <em>%d</em> in file <em>%s</em>.</footer></section>',
			htmlspecialchars($location),
			$pt['line'],
			htmlspecialchars($pt['file']));
	}

	private function buildHtml($title, $exception_message, $html_trace_points)
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
<p>An internal error occurred while processing the request.</p>
<p>This debugging information should under no cirumstances be enabled in a production environment.</p>
<section id="message">%3$s</section>
<h2>Stack trace</h2>
%4$2s
</div>
</body>
</html>', htmlspecialchars($title), $this->buildCss(), htmlspecialchars(strval($exception_message)), implode('', $html_trace_points));
	}

	private function buildCss()
	{
		return '
body{font-family:sans-serif; font-size:14px;background:#fff;width:700px;margin:30px auto;color:#444;background:#ff4136;}
#wrap{background:#fff;padding:10px 50px;border-radius:5px;}
#message{padding:10px 5px;background:#ff4136;color:#fff;}
.trace{margin-bottom:30px;}
.trace header{background:#fff;padding:3px;background:#444;color:#fff;font-family:monospace;}
.trace footer{margin-top:3px;}
em{font-style:normal;font-weight:bold;}
';
	}
}

?>