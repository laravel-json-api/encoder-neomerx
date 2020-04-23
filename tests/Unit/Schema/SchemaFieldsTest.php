<?php

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
