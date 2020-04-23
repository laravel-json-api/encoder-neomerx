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

namespace LaravelJsonApi\Encoder\Neomerx\Document;

use LaravelJsonApi\Core\Contracts\Document\DataDocument as DataDocumentContract;
use LaravelJsonApi\Core\Contracts\Document\ResourceObject;
use LaravelJsonApi\Core\Contracts\Encoder\DocumentBuilder;
use LaravelJsonApi\Core\Contracts\Resources\Container;
use LaravelJsonApi\Core\Query\FieldSets;
use LaravelJsonApi\Core\Query\IncludePaths;
use LaravelJsonApi\Encoder\Neomerx\Encoder;
use LaravelJsonApi\Encoder\Neomerx\Mapper;
use LaravelJsonApi\Encoder\Neomerx\Schema\SchemaContainer;
use LaravelJsonApi\Encoder\Neomerx\Schema\SchemaFields;
use Neomerx\JsonApi\Factories\Factory;

class Builder implements DocumentBuilder
{

    /**
     * @var Container
     */
    private $container;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var Mapper
     */
    private $mapper;

    /**
     * @var IncludePaths
     */
    private $includePaths;

    /**
     * @var FieldSets
     */
    private $fieldSets;

    /**
     * Builder constructor.
     *
     * @param Container $container
     * @param Factory $factory
     * @param Mapper $mapper
     */
    public function __construct(Container $container, Factory $factory, Mapper $mapper)
    {
        $this->container = $container;
        $this->factory = $factory;
        $this->mapper = $mapper;
        $this->includePaths = new IncludePaths();
        $this->fieldSets = new FieldSets();
    }

    /**
     * @inheritDoc
     */
    public function withIncludePaths($includePaths): DocumentBuilder
    {
        $this->includePaths = IncludePaths::cast($includePaths);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withFieldSets($fieldSets): DocumentBuilder
    {
        $this->fieldSets = FieldSets::cast($fieldSets);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function createResource(?ResourceObject $data): DataDocumentContract
    {
        return new DataDocument(
            $this->createEncoder(),
            $this->mapper,
            $data,
            $this->includePaths,
            $this->fieldSets
        );
    }

    /**
     * @inheritDoc
     */
    public function createResources(iterable $data): DataDocumentContract
    {
        return new DataDocument(
            $this->createEncoder(),
            $this->mapper,
            $data,
            $this->includePaths,
            $this->fieldSets
        );
    }

    /**
     * Create a new encoder instance.
     *
     * @return Encoder
     */
    private function createEncoder(): Encoder
    {
        $schemas = new SchemaContainer(
            $this->container,
            $this->mapper,
            new SchemaFields($this->includePaths, $this->fieldSets)
        );

        return new Encoder($this->factory, $schemas);
    }

}
