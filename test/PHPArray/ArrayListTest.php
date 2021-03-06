<?php

namespace Tests\TypedArray;

use PHPUnit\Framework\TestCase;
use Diacdg\TypedArray\ArrayList;

class ArrayListTest extends TestCase
{
    /**
     * @dataProvider listDataProvider
     */
    public function testCreateList(string $type, $value): void
    {
        $list = new ArrayList($type);
        $list[] = $value;

        $this->assertSame($value, $list[0]);
    }

    public function listDataProvider(): array
    {
        return [
            ['integer', 1],
            ['boolean', true],
            ['double', 2.2],
            ['string', 'valid-value'],
            ['array', []],
            ['object', new \stdClass()],
            ['callable', [$this, 'listDataProvider']],
            [\stdClass::class, new \stdClass()],
        ];
    }

    public function testInvalidValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $list = new ArrayList('integer');
        $list[] = 'invalid-value';
    }

    public function testExchangeArray(): void
    {
        $elements = [1, 2, 3];
        $list = new ArrayList('integer');
        $list->exchangeArray($elements);

        $this->assertSame($elements, (array) $list);

        $this->expectException(\InvalidArgumentException::class);

        $list->exchangeArray(['invalid-offset'=>1]);
    }

    public function testInvalidOffset(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $list = new ArrayList('integer');
        $list['offset'] = 1;
    }

    public function testRemoveOffset(): void
    {
        $list = new ArrayList('integer');
        $list[] = 1;
        $list[] = 2;
        $list[] = 3;

        $this->assertCount(3, $list);

        unset($list[1]);

        $this->assertCount(2, $list);
    }

    public function testCallableList(): void
    {
        $list = new ArrayList('callable', ['gettype', [$this, 'testCallableList'], function() {}]);

        $this->assertCount(3, $list);
    }
}
