<?php
/*
 * Copyright 2022 Cloud Creativity Limited
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

use LaravelJsonApi\Core\Query\FieldSets;
use LaravelJsonApi\Core\Query\IncludePaths;
use LaravelJsonApi\Encoder\Neomerx\Schema\SchemaFields;
use PHPUnit\Framework\TestCase;

class SchemaFieldsTest extends TestCase
{

    /**
     * @return array
     */
    public function includePathProvider(): array
    {
        return [
            [
                [],
                '',
                'author',
                false,
            ],
            [
                ['author'],
                '',
                'author',
                true,
            ],
            [
                ['author', 'comments'],
                '',
                'comments',
                true,
            ],
            [
                ['author', 'comments.user'],
                '',
                'comments',
                true,
            ],
            [
                ['author', 'comments.user'],
                'comments',
                'user',
                true,
            ],
            [
                ['author', 'comments.user.country'],
                '',
                'comments',
                true,
            ],
            [
                ['author', 'comments.user.country'],
                'comments',
                'user',
                true,
            ],
            [
                ['author', 'comments.user.country'],
                'comments.user',
                'country',
                true,
            ],
        ];
    }

    /**
     * @param array $includePaths
     * @param string $path
     * @param string $relationship
     * @param bool $expected
     * @dataProvider includePathProvider
     */
    public function testIsRelationshipRequested(
        array $includePaths,
        string $path,
        string $relationship,
        bool $expected
    ): void
    {
        $fields = new SchemaFields(IncludePaths::fromArray($includePaths), new FieldSets());

        $this->assertSame($expected, $fields->isRelationshipRequested($path, $relationship));
    }

    /**
     * @return array|array[]
     */
    public function fieldProvider(): array
    {
        return [
            [
                [],
                'posts',
                'title',
                true,
            ],
            [
                ['posts' => ['title', 'content']],
                'posts',
                'content',
                true,
            ],
            [
                ['posts' => ['title', 'content']],
                'posts',
                'author',
                false,
            ],
            [
                ['posts' => ['title', 'content']],
                'comments',
                'content',
                true,
            ],
        ];
    }

    /**
     * @param array $fieldSets
     * @param string $type
     * @param string $field
     * @param bool $expected
     * @dataProvider fieldProvider
     */
    public function testIsFieldRequested(
        array $fieldSets,
        string $type,
        string $field,
        bool $expected
    ): void
    {
        $fields = new SchemaFields(new IncludePaths(), FieldSets::fromArray($fieldSets));

        $this->assertSame($expected, $fields->isFieldRequested($type, $field));
    }
}
