<?php

namespace App\Service\File;

use App\Service\Util\LoggerTrait;

class FileDownloader
{

	use LoggerTrait;

	public function downloadFile(string $url, string $savePath): bool
	{
		$this->getLogger()->info("Starting downloading file from URL: " . $url);
		$dest = fopen($savePath, 'w');

		$options = array(
			CURLOPT_FILE => is_resource($dest) ? $dest : fopen($dest, 'w'),
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_URL => $url,
			CURLOPT_FAILONERROR => true, // HTTP code > 400 will throw curl error
		);

		$ch = curl_init();
		curl_setopt_array($ch, $options);
		$return = curl_exec($ch);
		curl_close($ch);

		if ($return === false) {
			$msg = curl_error($ch);
			$this->getLogger()->error("Unable to download file from '$url' to '$savePath'. Error: $msg");
		}

		$this->getLogger()->info("File downloaded from URL: " . $url);

		return $return === true;
	}
	
}