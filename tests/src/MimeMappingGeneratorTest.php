<?php

namespace Mimey\Tests;

use Mimey\MimeMappingGenerator;
use PHPUnit\Framework\TestCase;

class MimeMappingGeneratorTest extends TestCase
{
	public function testGenerateMapping()
	{
		$generator = new MimeMappingGenerator(
			"#ignore\tme\n" .
			"application/json\t\t\tjson\n" .
			"image/jpeg\t\t\tjpeg jpg #ignore this too\n\n" .
			"foo\tbar baz\n" .
			"qux\tbar\n"
		);
		$mapping = $generator->generateMapping();
		$expected = [
			'mimes' => [
				'json' => ['application/json'],
				'jpeg' => ['image/jpeg'],
				'jpg' => ['image/jpeg'],
				'bar' => ['foo', 'qux'],
				'baz' => ['foo'],
			],
			'extensions' => [
				'application/json' => ['json'],
				'image/jpeg' => ['jpeg', 'jpg'],
				'foo' => ['bar', 'baz'],
				'qux' => ['bar'],
			],
		];
		$this->assertEquals($expected, $mapping);
	}
}
