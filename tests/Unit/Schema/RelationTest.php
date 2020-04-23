<?php

namespace LaravelJsonApi\Encoder\Neomerx\Tests\Unit\Schema;

use LaravelJsonApi\Core\Contracts\Document\RelationshipObject;
use LaravelJsonApi\Core\Contracts\Document\ResourceObject;
use LaravelJsonApi\Core\Document\Link;
use LaravelJsonApi\Core\Document\Links;
use LaravelJsonApi\Core\Document\ResourceObject\Identifier;
use LaravelJsonApi\Core\Json\Hash;
use LaravelJsonApi\Core\Resources\Container;
use LaravelJsonApi\Encoder\Neomerx\Mapper;
use LaravelJsonApi\Encoder\Neomerx\Schema\Relation;
use Neomerx\JsonApi\Contracts\Schema\SchemaInterface;
use Neomerx\JsonApi\Factories\Factory;
use Neomerx\JsonApi\Schema\Identifier as SchemaIdentifier;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RelationTest extends TestCase
{

    /**
     * @var RelationshipObject|MockObject
     */
    private $relationship;

    /**
     * @var Container|MockObject
     */
    private $container;

    /**
     * @var Relation
     */
    private $relation;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->relationship = $this->createMock(RelationshipObject::class);
        $this->relation = new Relation(
            $this->container = $this->createMock(Container::class),
            new Mapper($this->factory = new Factory()),
            $this->relationship
        );
    }

    public function test(): void
    {
        $expected = [
            SchemaInterface::RELATIONSHIP_DATA => null,
            SchemaInterface::RELATIONSHIP_LINKS => [
                'self' => $this->factory->createLink(
                    false,
                    $self = 'http://localhost/api/v1/posts/1/relationships/author',
                    false
                ),
                'related' => $this->factory->createLink(
                    false,
                    $related = 'http://localhost/api/v1/posts/1/author',
                    false
                ),
                'custom' => $this->factory->createLink(
                    false,
                    $custom = 'http://localhost/foo/bar',
                    false
                ),
            ],
            SchemaInterface::RELATIONSHIP_META => $meta = new Hash(['foo' => 'bar']),
        ];

        $links = new Links(
            new Link('self', $self),
            new Link('related', $related),
            new Link('custom', $custom)
        );

        $this->relationship
            ->expects($this->once())
            ->method('showData')
            ->willReturn(true);

        $this->relationship
            ->expects($this->once())
            ->method('data')
            ->willReturn(null);

        $this->relationship
            ->expects($this->once())
            ->method('hasLinks')
            ->willReturn(true);

        $this->relationship
            ->expects($this->once())
            ->method('links')
            ->willReturn($links);

        $this->relationship
            ->expects($this->once())
            ->method('meta')
            ->willReturn($meta);

        $this->relationship
            ->expects($this->once())
            ->method('hasMeta')
            ->willReturn(true);

        $actual = $this->relation->toArray();

        $this->assertEquals($expected, $actual);
    }

    public function testDataNull(): void
    {
        $this->relationship->method('showData')->willReturn(true);
        $this->relationship->method('data')->willReturn(null);

        $this->assertEquals([
            SchemaInterface::RELATIONSHIP_DATA => null,
        ], $this->relation->toArray());
    }

    public function testDataIsResourceObject(): void
    {
        $related = $this->createMock(ResourceObject::class);

        $this->relationship->method('showData')->willReturn(true);
        $this->relationship->method('data')->willReturn($related);

        $this->assertSame([
            SchemaInterface::RELATIONSHIP_DATA => $related,
        ], $this->relation->toArray());
    }

    public function testDataIsResourceObjects(): void
    {
        $related1 = $this->createMock(ResourceObject::class);
        $related2 = $this->createMock(ResourceObject::class);

        $this->relationship->method('showData')->willReturn(true);
        $this->relationship->method('data')->willReturn($expected = [$related1, $related2]);
        $this->container->method('resolve')->with($expected)->willReturn($expected);

        $actual = $this->relation->toArray();

        $this->assertArrayHasKey(SchemaInterface::RELATIONSHIP_DATA, $actual);

        $this->assertSame([
            SchemaInterface::RELATIONSHIP_DATA => $expected,
        ], $actual);
    }

    public function testDataIsResourceIdentifier(): void
    {
        $related = new Identifier('users', '123');
        $expected = new SchemaIdentifier('123', 'users');

        $this->relationship->method('showData')->willReturn(true);
        $this->relationship->method('data')->willReturn($related);

        $this->assertEquals([
            SchemaInterface::RELATIONSHIP_DATA => $expected,
        ], $this->relation->toArray());
    }

    public function testDoesNotShowData(): void
    {
        $this->relationship->method('showData')->willReturn(false);
        $this->relationship->expects($this->never())->method('data');
        $this->relationship->method('hasLinks')->willReturn(true);

        $this->relationship->method('links')->willReturn(new Links(
            new Link('self', $self = 'https://localhost/api/v1/posts/1/author')
        ));

        $this->assertEquals([
            SchemaInterface::RELATIONSHIP_LINKS => [
                'self' => $this->factory->createLink(false, $self, false),
            ],
        ], $this->relation->toArray());
    }
}
