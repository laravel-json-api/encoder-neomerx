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

use IteratorAggregate;
use LaravelJsonApi\Core\Contracts\Document\ResourceObject;
use LaravelJsonApi\Core\Contracts\Resources\Container;
use LaravelJsonApi\Encoder\Neomerx\Mapper;
use Neomerx\JsonApi\Contracts\Schema\ContextInterface;

/**
 * Class Relationships
 *
 * @package LaravelJsonApi\Encoder\Neomerx
 * @internal
 */
final class Relationships implements IteratorAggregate
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
     * @var ResourceObject
     */
    private $resource;

    /**
     * @var ContextInterface
     */
    private $context;

    /**
     * Relationships constructor.
     *
     * @param Container $container
     * @param Mapper $mapper
     * @param ResourceObject $resource
     * @param ContextInterface $context
     */
    public function __construct(
        Container $container,
        Mapper $mapper,
        ResourceObject $resource,
        ContextInterface $context
    ) {
        $this->container = $container;
        $this->mapper = $mapper;
        $this->resource = $resource;
        $this->context = $context;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        foreach ($this->resource->relationships() as $fieldName => $relation) {
            $relation = new Relation($this->container, $this->mapper, $relation);
            yield $fieldName => $relation->toArray();
        }
    }

}
