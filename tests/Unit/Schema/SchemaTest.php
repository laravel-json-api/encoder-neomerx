<?php

namespace LaravelJsonApi\Encoder\Neomerx\Tests\Unit\Schema;

use LaravelJsonApi\Core\Contracts\Document\RelationshipObject;
use LaravelJsonApi\Core\Contracts\Document\ResourceObject;
use LaravelJsonApi\Core\Resources\Container;
use LaravelJsonApi\Encoder\Neomerx\Mapper;
use LaravelJsonApi\Encoder\Neomerx\Schema\Attrs;
use LaravelJsonApi\Encoder\Neomerx\Schema\Relationships;
use LaravelJsonApi\Encoder\Neomerx\Schema\Schema;
use Neomerx\JsonApi\Contracts\Schema\ContextInterface;
use Neomerx\JsonApi\Contracts\Schema\SchemaInterface;
use Neomerx\JsonApi\Factories\Factory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SchemaTest extends TestCase
{

    /**
     * @var ResourceObject|MockObject
     */
    private $resourceObject;

    /**
     * @var Container|MockObject
     */
    private $container;

    /**
     * @var Schema
     */
    private $schema;

    /**
     * @var ContextInterface|MockObject
     */
    private $context;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->resourceObject = $this->createMock(ResourceObject::class);
        $this->container = $this->createMock(Container::class);
        $this->schema = new Schema($this->container, new Mapper(new Factory()), 'posts');
        $this->context = $this->createMock(ContextInterface::class);
    }

    public function testType(): void
    {
        $this->assertSame('posts', $this->schema->getType());
    }

    public function testId(): void
    {
        $this->resourceObject
            ->expects($this->once())
            ->method('id')
            ->willReturn('123');

        $this->assertSame('123', $this->schema->getId($this->resourceObject));
    }

    public function testAttributes(): void
    {
        $this->resourceObject
            ->expects($this->once())
            ->method('attributes')
            ->willReturn($attrs = ['foo' => 'bar']);

        $actual = $this->schema->getAttributes($this->resourceObject, $this->context);

        $this->assertInstanceOf(Attrs::class, $actual);
        $this->assertSame($attrs, iterator_to_array($actual));
    }

    public function testRelationships(): void
    {
        $relation = $this->createMock(RelationshipObject::class);
        $relation->method('showData')->willReturn(true);
        $relation->method('data')->willReturn(null);

        $this->resourceObject
            ->expects($this->once())
            ->method('relationships')
            ->willReturn(['foo' => $relation]);

        $actual = $this->schema->getRelationships($this->resourceObject, $this->context);

        $this->assertInstanceOf(Relationships::class, $actual);
        $this->assertSame([
            'foo' => [
                SchemaInterface::RELATIONSHIP_DATA => null
            ],
        ], iterator_to_array($actual));
    }
}
