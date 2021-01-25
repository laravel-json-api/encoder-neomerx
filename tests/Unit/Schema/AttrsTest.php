<?php
/*
 * Copyright 2021 Cloud Creativity Limited
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace LaravelJsonApi\Encoder\Neomerx\Tests\Unit\Schema;

use LaravelJsonApi\Core\Resources\ConditionalAttr;
use LaravelJsonApi\Core\Resources\ConditionalAttrs;
use LaravelJsonApi\Core\Resources\JsonApiResource;
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
