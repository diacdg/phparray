<?php

namespace Tests\TypedArray;

use PHPUnit\Framework\TestCase;
use Diacdg\TypedArray\ArrayMap;

class ArrayMapTest extends TestCase
{
    /**
     * @dataProvider mapDataProvider
     */
    public function testCreateList(string $offsetType, string $valueType, $offset, $value): void
    {
        $list = new ArrayMap($offsetType, $valueType, [$offset => $value]);

        $this->assertSame($value, $list[$offset]);
    }

    public function testCreateInvalidList(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new ArrayMap('boolean', 'integer');
    }

    public function mapDataProvider(): array
    {
        return [
            ['integer', 'integer', 0, 1],
            ['string', 'integer', 'valid-offset', 1],
            ['string', \stdClass::class, 'valid-offset', new \stdClass()],
        ];
    }

    public function testInvalidValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $list = new ArrayMap('integer', 'integer');
        $list[1] = 'invalid-value';
    }

    public function testInvalidOffset(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $list = new ArrayMap('integer', 'integer');
        $list['invalid-offset'] = 1;
    }
}
