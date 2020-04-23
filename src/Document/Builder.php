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
use LaravelJsonApi\Encoder\Neomerx\Encoder;
use LaravelJsonApi\Encoder\Neomerx\Mapper;

class Builder implements DocumentBuilder
{

    /**
     * @var Encoder
     */
    private $encoder;

    /**
     * @var Mapper
     */
    private $mapper;

    /**
     * @var iterable|null
     */
    private $includePaths;

    /**
     * @var array|null
     */
    private $fieldSets;

    /**
     * DocumentBuilder constructor.
     *
     * @param Encoder $encoder
     * @param Mapper $mapper
     */
    public function __construct(Encoder $encoder, Mapper $mapper)
    {
        $this->encoder = $encoder;
        $this->mapper = $mapper;
    }

    /**
     * @inheritDoc
     */
    public function withIncludePaths(iterable $includePaths): DocumentBuilder
    {
        $this->includePaths = $includePaths;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withFieldSets(array $fieldSets): DocumentBuilder
    {
        $this->fieldSets = $fieldSets;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function createResource(?ResourceObject $data): DataDocumentContract
    {
        return new DataDocument(
            $this->encoder,
            $this->mapper,
            $data,
            $this->includePaths ?: [],
            $this->fieldSets ?: []
        );
    }

    /**
     * @inheritDoc
     */
    public function createResources(iterable $data): DataDocumentContract
    {
        return new DataDocument(
            $this->encoder,
            $this->mapper,
            $data,
            $this->includePaths ?: [],
            $this->fieldSets ?: []
        );
    }

}
