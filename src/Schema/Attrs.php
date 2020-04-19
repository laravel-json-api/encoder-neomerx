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

use LaravelJsonApi\Core\Contracts\Document\ResourceObject;
use LaravelJsonApi\Core\Contracts\Document\Skippable;
use LaravelJsonApi\Core\Document\ResourceObject\ConditionalAttr;
use LaravelJsonApi\Core\Document\ResourceObject\ConditionalAttrs;
use Neomerx\JsonApi\Contracts\Schema\ContextInterface;

class Attrs implements \IteratorAggregate
{

    /**
     * @var ResourceObject
     */
    private $resource;

    /**
     * @var ContextInterface
     */
    private $context;

    /**
     * Attrs constructor.
     *
     * @param ResourceObject $resource
     * @param ContextInterface $context
     */
    public function __construct(ResourceObject $resource, ContextInterface $context)
    {
        $this->resource = $resource;
        $this->context = $context;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        foreach ($this->resource->attributes() as $key => $value) {
            if ($value instanceof Skippable && true === $value->skip()) {
                continue;
            }

            if ($value instanceof ConditionalAttrs) {
                yield from $value;
                continue;
            }

            yield $key => $value;
        }
    }

}
