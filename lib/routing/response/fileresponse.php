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
require_once SYS_LIB_ROOT . 'routing/response/response.php';

class FileResponse implements Response
{
	public function __construct($status, $filepath, $original_filename, $mimetype)
	{
		if ((int)$status <= 0)
			throw new \Exception('Status out of range: ' . (int)$status);
		$this->status = (int)$status;
		$this->filepath = $filepath;
		$this->original_filename = $original_filename;
		$this->mimetype = $mimetype;
	}
	public function getStatus()
	{
		return $this->status;
	}
	public function getContentType()
	{
		return $this->mimetype;
	}
	public function getContent()
	{
		// This is handled as a special case in restapi.php.
		// Not elegant, but simple. And it works.
		return null;
	}
	public function getAdditionalHeaders()
	{
		$filename = str_replace(array('/', '\\', '"'), '', iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $this->original_filename));
		return array('Content-Disposition' => 'attachment; filename="' . $filename . '"');
	}
	public function enableCacheControl()
	{
		// No caching for downloads
		return false;
	}
}

?>