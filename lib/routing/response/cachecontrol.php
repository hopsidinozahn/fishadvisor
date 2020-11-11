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

namespace Octarine\Routing\Response;

class CacheControl
{
	public function __construct($content, $lastModified = false)
	{
		$this->content = $content;
		$this->etag = md5($content);
		$this->length = strlen($content); // we need the amount of bytes (strlen) here, not the amount of characters (mb_strlen)!
		$this->lastModified = is_int($lastModified) ? $lastModified : false;
	}

	private $content, $etag, $length, $lastModified;

	public function shallDeliverContent()
	{
		// Check if browser sends ETag and it differs from the current one
		if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) != $this->etag)
		{
			// In that case we know content has changed
			return true;
		}
		// Check if browser sends LastModified and it is earlier than the time of last modification
		if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $this->lastModified !== false)
		{
			$time = @strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
			if ($time && $time < $this->lastModified)
				// In that case we know content has changed
				return true;
		}
		// If the browser sent both flags and they both passed the conditions above,
		// than we can assume content has not changed
		if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && isset($_SERVER['HTTP_IF_NONE_MATCH']))
		{
			return false;
		}
		// If the client did not send all of them, then we do not know
		// To stay on the safe side, deliver the content
		return true;
	}

	public function sendCacheHeaders()
	{
		if ($this->lastModified !== false)
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $this->lastModified) . ' GMT');
		header('Etag: ' . $this->etag);
		header('Cache-Control: public');
		header('Content-Length: ' . $this->length);
	}

	public function notModified()
	{
		header("HTTP/1.1 304 Not Modified");
	}

	public function deliver()
	{
		if (headers_sent())
		{
			throw new \Exception('Headers already sent - cannot write cache headers.');
		}
		if ($this->shallDeliverContent())
		{
			$this->sendCacheHeaders();
			echo $this->content;
		}
		else
		{
			$this->notModified();
		}
	}
}

?>