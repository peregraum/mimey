<?php

namespace Mimey;

use JetBrains\PhpStorm\Pure;

/**
 * Class for converting MIME types to file extensions and vice versa.
 *
 * @psalm-type MimeTypeMap = array{mimes: array<non-empty-string, list<non-empty-string>>, extensions: array<non-empty-string, list<non-empty-string>>}
 */
class MimeTypes implements MimeTypesInterface
{
	/** @var MimeTypeMap The cached built-in mapping array. */
	private static ?array $built_in = null;

	/** @var MimeTypeMap The mapping array. */
	protected ?array $mapping = null;

	/**
	 * Create a new mime types instance with the given mappings.
	 *
	 * If no mappings are defined, they will default to the ones included with this package.
	 *
	 * @param MimeTypeMap|null $mapping An associative array containing two entries.
	 * Entry "mimes" being an associative array of extension to array of MIME types.
	 * Entry "extensions" being an associative array of MIME type to array of extensions.
	 * Example:
	 * <code>
	 * [
	 *   'extensions' => [
	 *     'application/json' => ['json'],
	 *     'image/jpeg'       => ['jpg', 'jpeg'],
	 *     ...
	 *   ],
	 *   'mimes' => [
	 *     'json' => ['application/json'],
	 *     'jpeg' => [image/jpeg'],
	 *     ...
	 *   ]
	 * ]
	 * </code>
	 */
	public function __construct(?array $mapping = null)
	{
		if ($mapping === null) {
			$this->mapping = self::getBuiltIn();
		} else {
			$this->mapping = $mapping;
		}
	}

	#[Pure]
	public function getMimeType($extension): ?string
	{
		$extension = $this->cleanInput($extension);
		if (!empty($this->mapping['mimes'][$extension])) {
			return $this->mapping['mimes'][$extension][0];
		}

		return null;
	}

	#[Pure]
	public function getExtension($mime_type): ?string
	{
		$mime_type = $this->cleanInput($mime_type);
		if (!empty($this->mapping['extensions'][$mime_type])) {
			return $this->mapping['extensions'][$mime_type][0];
		}

		return null;
	}

	#[Pure]
	public function getAllMimeTypes($extension): array
	{
		$extension = $this->cleanInput($extension);

		return $this->mapping['mimes'][$extension] ?? [];
	}

	#[Pure]
	public function getAllExtensions($mime_type): array
	{
		$mime_type = $this->cleanInput($mime_type);

		return $this->mapping['extensions'][$mime_type] ?? [];
	}

	/**
	 * Get the built-in mapping.
	 *
	 * @return MimeTypeMap The built-in mapping.
	 */
	protected static function getBuiltIn(): array
	{
		if (self::$built_in === null) {
			self::$built_in = require(dirname(__DIR__) . '/mime.types.php');
		}

		return self::$built_in;
	}

	/**
	 * Normalize the input string using lowercase/trim.
	 *
	 * @param string $input The string to normalize.
	 *
	 * @return string The normalized string.
	 */
	private function cleanInput(string $input): string
	{
		$input = trim($input);

		if (function_exists('mb_strtolower')) {
			$input = mb_strtolower($input);
		} else {
			$input = strtolower($input);
		}

		return $input;
	}
}
