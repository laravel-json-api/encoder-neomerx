<?php
/**
 * Copyright 2020 Cloud Creativity Limited
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

declare(strict_types=1);

namespace LaravelJsonApi\Encoder\Neomerx\Schema;

use InvalidArgumentException;
use LaravelJsonApi\Core\Contracts\Document\ResourceObject;
use LaravelJsonApi\Core\Contracts\Resources\Container;
use LaravelJsonApi\Encoder\Neomerx\Mapper;
use LogicException;
use Neomerx\JsonApi\Contracts\Schema\ContextInterface;
use Neomerx\JsonApi\Contracts\Schema\LinkInterface;
use Neomerx\JsonApi\Contracts\Schema\SchemaInterface;
use function assert;
use function sprintf;

/**
 * Class Schema
 *
 * @package LaravelJsonApi\Encoder\Neomerx
 * @internal
 */
final class Schema implements SchemaInterface
{

    /**
     * @var Container
     */
    private $container;

    /**
     * @var Mapper
     */
    private $mapper;

    /**
     * @var string
     */
    private $type;

    /**
     * Schema constructor.
     *
     * @param Container $container
     * @param Mapper $mapper
     * @param string $type
     */
    public function __construct(Container $container, Mapper $mapper, string $type)
    {
        if (empty($type)) {
            throw new InvalidArgumentException('Expecting a non-empty resource type.');
        }

        $this->container = $container;
        $this->mapper = $mapper;
        $this->type = $type;
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function getId($resource): ?string
    {
        assert($resource instanceof ResourceObject, 'Expecting a resource object.');

        return $resource->id();
    }

    /**
     * @inheritDoc
     */
    public function getAttributes($resource, ContextInterface $context): iterable
    {
        return new Attrs($resource, $context);
    }

    /**
     * @inheritDoc
     */
    public function getRelationships($resource, ContextInterface $context): iterable
    {
        return new Relationships(
            $this->container,
            $this->mapper,
            $resource,
            $context
        );
    }

    /**
     * @inheritDoc
     */
    public function getSelfLink($resource): LinkInterface
    {
        assert($resource instanceof ResourceObject, 'Expecting a resource object.');

        if ($link = $resource->links()->get('self')) {
            return $this->mapper->link($link);
        }

        throw new LogicException(sprintf(
            'Resource object %s does not have a self link.',
            $resource->type()
        ));
    }

    /**
     * @inheritDoc
     */
    public function getLinks($resource): iterable
    {
        assert($resource instanceof ResourceObject, 'Expecting a resource object.');

        return $this->mapper->links($resource->links());
    }

    /**
     * @inheritDoc
     */
    public function getRelationshipSelfLink($resource, string $name): LinkInterface
    {
        assert($resource instanceof ResourceObject, 'Expecting a resource object.');

        if ($link = $resource->relation($name)->links()->get('self')) {
            return $this->mapper->link($link);
        }

        throw new LogicException(sprintf(
            'Relation %s on resource object %s does not have a self link.',
            $name,
            $resource->type()
        ));
    }

    /**
     * @inheritDoc
     */
    public function getRelationshipRelatedLink($resource, string $name): LinkInterface
    {
        assert($resource instanceof ResourceObject, 'Expecting a resource object.');

        if ($link = $resource->relation($name)->links()->get('related')) {
            return $this->mapper->link($link);
        }

        throw new LogicException(sprintf(
            'Relation %s on resource object %s does not have a related link.',
            $name,
            $resource->type()
        ));
    }

    /**
     * @inheritDoc
     */
    public function hasIdentifierMeta($resource): bool
    {
        assert($resource instanceof ResourceObject, 'Expecting a resource object.');

        return $resource->identifier()->hasMeta();
    }

    /**
     * @inheritDoc
     */
    public function getIdentifierMeta($resource)
    {
        assert($resource instanceof ResourceObject, 'Expecting a resource object.');

        return $resource->identifier()->meta();
    }

    /**
     * @inheritDoc
     */
    public function hasResourceMeta($resource): bool
    {
        assert($resource instanceof ResourceObject, 'Expecting a resource object.');

        return $resource->hasMeta();
    }

    /**
     * @inheritDoc
     */
    public function getResourceMeta($resource)
    {
        assert($resource instanceof ResourceObject, 'Expecting a resource object.');

        return $resource->meta();
    }

    /**
     * @inheritDoc
     */
    public function isAddSelfLinkInRelationshipByDefault(string $relationshipName): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function isAddRelatedLinkInRelationshipByDefault(string $relationshipName): bool
    {
        return false;
    }

}
