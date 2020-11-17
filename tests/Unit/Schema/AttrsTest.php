<?php

namespace LaravelJsonApi\Encoder\Neomerx\Tests\Unit\Schema;

use LaravelJsonApi\Core\Resources\JsonApiResource;
use LaravelJsonApi\Core\Resources\ConditionalAttr;
use LaravelJsonApi\Core\Resources\ConditionalAttrs;
use LaravelJsonApi\Encoder\Neomerx\Schema\Attrs;
use Neomerx\JsonApi\Contracts\Schema\ContextInterface;
use PHPUnit\Framework\TestCase;

class AttrsTest extends TestCase
{

    public function test(): void
    {
        $attrs = [
            'foo' => 'bar',
            'baz' => new ConditionalAttr(true, 'bat'),
            'foobar' => new ConditionalAttr(false, 'bazbat'),
            new ConditionalAttrs(true, [
                'a' => 'b',
                'c' => static function () {
                    return 'd';
                },
            ]),
            new ConditionalAttrs(false, [
                'e' => 'f',
            ]),
        ];

        $resource = $this->createMock(JsonApiResource::class);
        $resource->expects($this->once())->method('attributes')->willReturn($attrs);
        $context = $this->createMock(ContextInterface::class);

        $this->assertSame([
            'foo' => 'bar',
            'baz' => $attrs['baz'],
            'a' => 'b',
            'c' => 'd',
        ], iterator_to_array(new Attrs($resource, $context)));
    }
}
