<?php
/*
 * Copyright 2022 Cloud Creativity Limited
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

use Illuminate\Http\Request;
use IteratorAggregate;
use LaravelJsonApi\Contracts\Resources\Container;
use LaravelJsonApi\Contracts\Resources\JsonApiRelation;
use LaravelJsonApi\Core\Resources\ConditionalField;
use LaravelJsonApi\Core\Resources\ConditionalList;
use LaravelJsonApi\Core\Resources\JsonApiResource;
use LaravelJsonApi\Encoder\Neomerx\Mapper;
use Neomerx\JsonApi\Contracts\Schema\ContextInterface;
use Traversable;
use UnexpectedValueException;

/**
 * Class Relationships
 *
 * @internal
 */
final class Relationships implements IteratorAggregate
{

    /**
     * @var Container
     */
    private Container $container;

    /**
     * @var Mapper
     */
    private Mapper $mapper;

    /**
     * @var JsonApiResource
     */
    private JsonApiResource $resource;

    /**
     * @var SchemaFields
     */
    private SchemaFields $fields;

    /**
     * @var ContextInterface
     */
    private ContextInterface $context;

    /**
     * @var Request|null
     */
    private $request;

    /**
     * Relationships constructor.
     *
     * @param Container $container
     * @param Mapper $mapper
     * @param JsonApiResource $resource
     * @param SchemaFields $fields
     * @param ContextInterface $context
     * @param Request|null $request
     */
    public function __construct(
        Container $container,
        Mapper $mapper,
        JsonApiResource $resource,
        SchemaFields $fields,
        ContextInterface $context,
        $request
    ) {
        $this->container = $container;
        $this->mapper = $mapper;
        $this->resource = $resource;
        $this->fields = $fields;
        $this->context = $context;
        $this->request = $request;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        foreach ($this->iterator() as $relation) {
            if (!$relation instanceof JsonApiRelation) {
                throw new UnexpectedValueException('Unexpected resource relationship value.');
            }

            $fieldName = $relation->fieldName();

            if ($this->fields->isFieldRequested($this->resource->type(), $fieldName)) {
                $value = $this->convert($relation)->toArray();

                /**
                 * The value could be empty, in which case we should not yield it otherwise
                 * the Neomerx encoder ends up encoding the relationship as an empty array.
                 * An example of when this might be legitimately empty is when the relationship
                 * is meta-only, but the meta value is controlled by the client (for example our
                 * countable implementation) and the client hasn't requested it.
                 */
                if (!empty($value)) {
                    yield $fieldName => $value;
                }
            }
        }
    }

    /**
     * Convert a JSON:API relation to an schema relation.
     *
     * @param JsonApiRelation $relation
     * @return Relation
     */
    private function convert(JsonApiRelation $relation): Relation
    {
        return new Relation(
            $this->container,
            $this->mapper,
            $relation,
            $this->fields,
            $this->context,
        );
    }

    /**
     * @return iterable
     */
    private function iterator(): iterable
    {
        foreach (new ConditionalList($this->resource->relationships($this->request)) as $value) {
            if ($value instanceof ConditionalField) {
                yield $value->get();
                continue;
            }

            yield $value;
        }
    }

}
