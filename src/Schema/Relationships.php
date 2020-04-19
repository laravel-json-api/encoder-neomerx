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
     * @param Mapper $mapper
     * @param ResourceObject $resource
     * @param ContextInterface $context
     */
    public function __construct(Mapper $mapper, ResourceObject $resource, ContextInterface $context)
    {
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
            $relation = new Relation($this->mapper, $relation);
            yield $fieldName => $relation->toArray();
        }
    }

}
