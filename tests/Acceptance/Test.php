<?php

namespace LaravelJsonApi\Encoder\Neomerx\Tests\Acceptance;

use LaravelJsonApi\Encoder\Neomerx\Tests\Comment;
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
                "links": {
                    "self": "http://example.com/api/v1/posts/1/relationships/author",
                    "related": "http://example.com/api/v1/posts/1/author"
                }
            },
            "comments": {
                "links": {
                    "self": "http://example.com/api/v1/posts/1/relationships/comments",
                    "related": "http://example.com/api/v1/posts/1/comments"
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
            },
            "comments": {
                "data": [
                    {
                        "type": "comments",
                        "id": "123"
                    }
                ],
                "links": {
                    "self": "http://example.com/api/v1/posts/1/relationships/comments",
                    "related": "http://example.com/api/v1/posts/1/comments"
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
        },
        {
            "type": "comments",
            "id": "123",
            "attributes": {
                "content": "This is a great first post!"
            },
            "relationships": {
                "post": {
                    "data": {
                        "type": "posts",
                        "id": "1"
                    },
                    "links": {
                        "self": "http://example.com/api/v1/comments/123/relationships/post",
                        "related": "http://example.com/api/v1/comments/123/post"
                    }
                },
                "user": {
                    "data": {
                        "type": "users",
                        "id": "3"
                    },
                    "links": {
                        "self": "http://example.com/api/v1/comments/123/relationships/user",
                        "related": "http://example.com/api/v1/comments/123/user"
                    }
                }
            },
            "links": {
                "self": "http://example.com/api/v1/comments/123"
            }
        },
        {
            "type": "users",
            "id": "3",
            "attributes": {
                "name": "Artie Shaw"
            },
            "links": {
                "self": "http://example.com/api/v1/users/3"
            }
        }
    ]
}
JSON;

        $ella = new User('2', 'Ella Fitzgerald');
        $artie = new User('3', 'Artie Shaw');

        $resource = new PostResource($post = new Post(
            '1',
            'Hello World!',
            'This is my first post...',
            $ella
        ));

        $post->withComments(new Comment(
            '123',
            'This is a great first post!',
            $artie,
            $post
        ));

        $document = $this->encoder
            ->withIncludePaths(['author', 'comments.user'])
            ->createResource($resource);

        $this->assertJsonStringEqualsJsonString($expected, $document->toJson());
        $this->assertJsonStringEqualsJsonString($expected, json_encode($document));
    }

    public function testWithFieldSets(): void
    {
        $expected = <<<JSON
{
    "data": {
        "type": "posts",
        "id": "1",
        "attributes": {
            "title": "Hello World!"
        },
        "relationships": {
            "author": {
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
            ->withFieldSets(['posts' => ['title', 'author']])
            ->createResource($resource);

        $this->assertJsonStringEqualsJsonString($expected, $document->toJson());
        $this->assertJsonStringEqualsJsonString($expected, json_encode($document));
    }
}
