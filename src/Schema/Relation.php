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

use LaravelJsonApi\Core\Contracts\Document\RelationshipObject;
use LaravelJsonApi\Core\Contracts\Document\ResourceIdentifierObject;
use LaravelJsonApi\Core\Contracts\Document\ResourceObject;
use LaravelJsonApi\Core\Contracts\Resources\Container;
use LaravelJsonApi\Encoder\Neomerx\Mapper;
use Neomerx\JsonApi\Contracts\Schema\IdentifierInterface;
use Neomerx\JsonApi\Contracts\Schema\SchemaInterface;
use function is_null;

/**
 * Class Relation
 *
 * @package LaravelJsonApi\Encoder\Neomerx
 * @internal
 */
final class Relation
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
     * @var RelationshipObject
     */
    private $relation;

    /**
     * Relation constructor.
     *
     * @param Container $container
     * @param Mapper $mapper
     * @param RelationshipObject $object
     */
    public function __construct(Container $container, Mapper $mapper, RelationshipObject $object)
    {
        $this->container = $container;
        $this->mapper = $mapper;
        $this->relation = $object;
    }

    /**
     * @return ResourceObject|IdentifierInterface|iterable|null
     */
    public function data()
    {
        $data = $this->relation->data();

        if ($data instanceof ResourceObject || is_null($data)) {
            return $data;
        }

        if ($data instanceof ResourceIdentifierObject) {
            return $this->mapper->identifier($data);
        }

        return $this->container->resolve($data);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $relation = [];

        if ($this->relation->showData()) {
            $relation[SchemaInterface::RELATIONSHIP_DATA] = $this->data();
        }

        if ($this->relation->hasLinks()) {
            $relation[SchemaInterface::RELATIONSHIP_LINKS] = $this->mapper->allLinks(
                $this->relation->links()
            );
        }

        if ($this->relation->hasMeta()) {
            $relation[SchemaInterface::RELATIONSHIP_META] = $this->relation->meta();
        }

        return $relation;
    }

}
