<?php
/*
 * Copyright 2023 Cloud Creativity Limited
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

use LaravelJsonApi\Core\Resources\JsonApiResource;
use LaravelJsonApi\Encoder\Neomerx\Encoder\Encoder as ExtendedEncoder;

class RelationshipDocument extends Document
{
    /**
     * @var JsonApiResource
     */
    protected JsonApiResource $resource;

    /**
     * @var string
     */
    protected string $fieldName;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * RelationshipDocument constructor.
     *
     * @param ExtendedEncoder $encoder
     * @param Mapper $mapper
     * @param JsonApiResource $resource
     * @param string $fieldName
     * @param object|iterable|null $data
     */
    public function __construct(
        ExtendedEncoder $encoder,
        Mapper $mapper,
        JsonApiResource $resource,
        string $fieldName,
        $data
    ) {
        parent::__construct($encoder, $mapper);
        $this->resource = $resource;
        $this->fieldName = $fieldName;
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    protected function serialize(): array
    {
        return $this
            ->encoder()
            ->serializeIdentifiers($this->data);
    }

    /**
     * @inheritDoc
     */
    protected function encode(): string
    {
        return $this
            ->encoder()
            ->encodeIdentifiers($this->data);
    }
}
