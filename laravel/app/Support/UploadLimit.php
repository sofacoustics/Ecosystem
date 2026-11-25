<?php

namespace App\Support;

/*
 * Effective maximal size of a single uploaded file.
 * It is the smallest of
 *  - PHP upload_max_filesize,
 *  - PHP post_max_size (each file is uploaded in its own request),
 *  - the 'max:' rule (in kilobytes) of the Livewire temporary file uploads (config/livewire.php).
 * The PHP values are read at runtime, so they include overrides from public/.htaccess.
 * Note: in the CLI (artisan, tinker), PHP may use a different php.ini than the web server.
 */
class UploadLimit
{
	/*
	 * Maximal size in bytes, or null if no limit is set at all.
	 */
	public static function maxBytes(): ?int
	{
		$limits = [
			self::iniToBytes(ini_get('upload_max_filesize')),
			self::iniToBytes(ini_get('post_max_size')),
			self::livewireMaxBytes(),
		];
		$limits = array_filter($limits, fn ($v) => $v !== null && $v > 0);
		return empty($limits) ? null : min($limits);
	}

	/*
	 * Maximal size as human readable text, e.g. "3 GB" or "1.5 GB" (binary units).
	 */
	public static function formatted(): string
	{
		$bytes = self::maxBytes();
		if ($bytes === null)
			return 'unlimited';

		$units = ['bytes', 'kB', 'MB', 'GB', 'TB'];
		$i = 0;
		$value = $bytes;
		while ($value >= 1024 && $i < count($units) - 1)
		{
			$value /= 1024;
			$i++;
		}
		return round($value, 2) . ' ' . $units[$i];
	}

	/*
	 * Convert PHP ini shorthand (e.g. "2048M", "3G", "512K", "1000") to bytes.
	 * Returns null for empty, 0 or negative values (= no limit).
	 */
	private static function iniToBytes($value): ?int
	{
		if ($value === false || $value === null)
			return null;
		$value = trim((string) $value);
		if ($value === '')
			return null;

		$number = (int) $value;
		if ($number <= 0)
			return null;

		switch (strtolower(substr($value, -1)))
		{
			case 'g': $number *= 1024;
			// no break
			case 'm': $number *= 1024;
			// no break
			case 'k': $number *= 1024;
		}
		return $number;
	}

	/*
	 * The 'max:' value of the Livewire upload rules is in kilobytes.
	 */
	private static function livewireMaxBytes(): ?int
	{
		$rules = config('livewire.temporary_file_upload.rules');
		if (is_string($rules))
			$rules = explode('|', $rules);
		if (!is_array($rules))
			return null;

		foreach ($rules as $rule)
		{
			if (is_string($rule) && preg_match('/^max:(\d+)$/', trim($rule), $m))
				return (int) $m[1] * 1024;
		}
		return null;
	}
}
