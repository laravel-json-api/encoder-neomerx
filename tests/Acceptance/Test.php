<?php

namespace LaravelJsonApi\Encoder\Neomerx\Tests\Acceptance;

use LaravelJsonApi\Encoder\Neomerx\Tests\Post;
use LaravelJsonApi\Encoder\Neomerx\Tests\PostResource;
use LaravelJsonApi\Encoder\Neomerx\Tests\User;
use function json_encode;

class Test extends TestCase
{

    public function test(): void
    {
        $expected = <<<JSON
{
    "data": {
        "type": "posts",
        "id": "1",
        "attributes": {
            "title": "Hello World!",
            "content": "This is my first post..."
        },
        "relationships": {
            "author": {
                "data": {
                    "type": "users",
                    "id": "2"
                },
                "links": {
                    "self": "http://example.com/api/v1/posts/1/relationships/author",
                    "related": "http://example.com/api/v1/posts/1/author"
                }
            }
        },
        "links": {
            "self": "http://example.com/api/v1/posts/1"
        }
    }
}
JSON;

        $user = new User('2', 'Ella Fitzgerald');

        $resource = new PostResource(new Post(
            '1',
            'Hello World!',
            'This is my first post...',
            $user
        ));

        $document = $this->encoder
            ->createResource($resource);

        $this->assertJsonStringEqualsJsonString($expected, $document->toJson());
        $this->assertJsonStringEqualsJsonString($expected, json_encode($document));
    }

    public function testWithIncludePaths(): void
    {
        $expected = <<<JSON
{
    "data": {
        "type": "posts",
        "id": "1",
        "attributes": {
            "title": "Hello World!",
            "content": "This is my first post..."
        },
        "relationships": {
            "author": {
                "data": {
                    "type": "users",
                    "id": "2"
                },
                "links": {
                    "self": "http://example.com/api/v1/posts/1/relationships/author",
                    "related": "http://example.com/api/v1/posts/1/author"
                }
            }
        },
        "links": {
            "self": "http://example.com/api/v1/posts/1"
        }
    },
    "included": [
        {
            "type": "users",
            "id": "2",
            "attributes": {
                "name": "Ella Fitzgerald"
            },
            "links": {
                "self": "http://example.com/api/v1/users/2"
            }
        }
    ]
}
JSON;

        $user = new User('2', 'Ella Fitzgerald');

        $resource = new PostResource(new Post(
            '1',
            'Hello World!',
            'This is my first post...',
            $user
        ));

        $document = $this->encoder
            ->withIncludePaths(['author'])
            ->createResource($resource);

        $this->assertJsonStringEqualsJsonString($expected, $document->toJson());
        $this->assertJsonStringEqualsJsonString($expected, json_encode($document));
    }
}
