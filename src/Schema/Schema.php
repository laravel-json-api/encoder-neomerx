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
use LogicException;
use Neomerx\JsonApi\Contracts\Factories\FactoryInterface;
use Neomerx\JsonApi\Contracts\Schema\ContextInterface;
use Neomerx\JsonApi\Schema\BaseSchema;

/**
 * Class Schema
 *
 * @package LaravelJsonApi\Encoder\Neomerx
 * @internal
 */
class Schema extends BaseSchema
{

    /**
     * @var string
     */
    private $type;

    /**
     * Schema constructor.
     *
     * @param FactoryInterface $factory
     * @param string $type
     */
    public function __construct(FactoryInterface $factory, string $type)
    {
        if (empty($type)) {
            throw new \InvalidArgumentException('Expecting a non-empty resource type.');
        }

        parent::__construct($factory);
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
     * @param ResourceObject $resource
     * @return string|null
     */
    public function getId($resource): ?string
    {
        if ($resource instanceof ResourceObject) {
            return $resource->id();
        }

        throw new LogicException('Expecting a resource object.');
    }

    /**
     * @inheritDoc
     */
    public function getAttributes($resource, ContextInterface $context): iterable
    {
        return new Attrs($resource, $context);
    }

    /**
     * @param ResourceObject $resource
     * @param ContextInterface $context
     * @return iterable
     */
    public function getRelationships($resource, ContextInterface $context): iterable
    {
        return new Relationships($resource, $context);
    }

}
