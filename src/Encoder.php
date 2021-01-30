<?php
/*
 * Copyright 2021 Cloud Creativity Limited
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

namespace LaravelJsonApi\Encoder\Neomerx;

use Illuminate\Http\Request;
use LaravelJsonApi\Contracts\Encoder\Encoder as EncoderContract;
use LaravelJsonApi\Contracts\Encoder\JsonApiDocument as DocumentContract;
use LaravelJsonApi\Contracts\Resources\Container;
use LaravelJsonApi\Core\Document\JsonApi;
use LaravelJsonApi\Core\Query\FieldSets;
use LaravelJsonApi\Core\Query\IncludePaths;
use LaravelJsonApi\Core\Resources\JsonApiResource;
use LaravelJsonApi\Encoder\Neomerx\Encoder\Encoder as ExtendedEncoder;
use LaravelJsonApi\Encoder\Neomerx\Schema\SchemaContainer;
use LaravelJsonApi\Encoder\Neomerx\Schema\SchemaFields;
use Neomerx\JsonApi\Contracts\Factories\FactoryInterface;

class Encoder implements EncoderContract
{

    /**
     * @var Container
     */
    private Container $resources;

    /**
     * @var FactoryInterface
     */
    private FactoryInterface $factory;

    /**
     * @var Mapper
     */
    private Mapper $mapper;

    /**
     * @var JsonApi
     */
    private JsonApi $version;

    /**
     * @var Request|null
     */
    private $request;

    /**
     * @var IncludePaths|null
     */
    private ?IncludePaths $includePaths = null;

    /**
     * @var FieldSets|null
     */
    private ?FieldSets $fieldSets = null;

    /**
     * Encoder constructor.
     *
     * @param Container $container
     * @param FactoryInterface $factory
     * @param Mapper $mapper
     * @param JsonApi $version
     */
    public function __construct(
        Container $container,
        FactoryInterface $factory,
        Mapper $mapper,
        JsonApi $version
    ) {
        $this->resources = $container;
        $this->factory = $factory;
        $this->mapper = $mapper;
        $this->version = $version;
    }

    /**
     * @inheritDoc
     */
    public function withRequest($request): self
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withIncludePaths($includePaths): self
    {
        $this->includePaths = IncludePaths::cast($includePaths);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withFieldSets($fieldSets): self
    {
        $this->fieldSets = FieldSets::cast($fieldSets);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withResource(?object $resource): DocumentContract
    {
        if (is_object($resource) && !$resource instanceof JsonApiResource) {
            $resource = $this->resources->create($resource);
        }

        return $this->createCompoundDocument($resource);
    }

    /**
     * @inheritDoc
     */
    public function withResources(iterable $resources): DocumentContract
    {
        return $this->createCompoundDocument(
            $this->resources->cursor($resources)
        );
    }

    /**
     * @inheritDoc
     */
    public function withIdentifiers(object $resource, string $fieldName, $identifiers): DocumentContract
    {
        $document = new RelationshipDocument(
            $this->createNeomerxEncoder(),
            $this->mapper,
            $resource,
            $fieldName,
            $identifiers
        );

        $document->withJsonApi($this->version);

        return $document;
    }

    /**
     * @param mixed $data
     * @return DocumentContract
     */
    private function createCompoundDocument($data): DocumentContract
    {
        $document = new CompoundDocument($this->createNeomerxEncoder(), $this->mapper, $data);
        $document->withJsonApi($this->version);

        return $document;
    }

    /**
     * Create a new encoder instance.
     *
     * @return ExtendedEncoder
     */
    private function createNeomerxEncoder(): ExtendedEncoder
    {
        $schemas = new SchemaContainer(
            $this->resources,
            $this->mapper,
            new SchemaFields($this->includePaths ?: new IncludePaths(), $this->fieldSets ?: new FieldSets()),
            $this->request,
        );

        $encoder = new ExtendedEncoder($this->factory, $schemas);

        if ($this->includePaths) {
            $encoder->withIncludedPaths($this->includePaths->toArray());
        }

        if ($this->fieldSets) {
            $encoder->withFieldSets($this->fieldSets->toArray());
        }

        return $encoder;
    }
}
