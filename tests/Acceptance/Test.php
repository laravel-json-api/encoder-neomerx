<?php

namespace LaravelJsonApi\Encoder\Neomerx\Tests\Acceptance;

use LaravelJsonApi\Encoder\Neomerx\Tests\Post;
use LaravelJsonApi\Encoder\Neomerx\Tests\PostResource;

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
        "links": {
            "self": "http://example.com/api/v1/posts/1"
        }
    }
}
JSON;

        $resource = new PostResource(new Post(
            '1',
            'Hello World!',
            'This is my first post...'
        ));

        $actual = $this->builder
            ->createResource($resource)
            ->toJson();

        $this->assertJsonStringEqualsJsonString($expected, $actual);
    }
}
