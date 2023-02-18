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

use LaravelJsonApi\Contracts\Encoder\JsonApiDocument;
use LaravelJsonApi\Core\Document\JsonApi;
use LaravelJsonApi\Core\Document\Links;
use LaravelJsonApi\Core\Json\Hash;
use LaravelJsonApi\Encoder\Neomerx\Encoder\Encoder as ExtendedEncoder;

abstract class Document implements JsonApiDocument
{
    /**
     * @var ExtendedEncoder
     */
    private ExtendedEncoder $encoder;

    /**
     * @var Mapper
     */
    private Mapper $mapper;

    /**
     * @var JsonApi|null
     */
    private ?JsonApi $jsonApi;

    /**
     * @var Links|null
     */
    private ?Links $links = null;

    /**
     * @var Hash|null
     */
    private ?Hash $meta = null;

    /**
     * JsonApiDocument constructor.
     *
     * @param ExtendedEncoder $encoder
     * @param Mapper $mapper
     */
    public function __construct(ExtendedEncoder $encoder, Mapper $mapper)
    {
        $this->encoder = $encoder;
        $this->mapper = $mapper;
    }

    /**
     * @return array
     */
    abstract protected function serialize(): array;

    /**
     * @return string
     */
    abstract protected function encode(): string;

    /**
     * @inheritDoc
     */
    public function withJsonApi($jsonApi): self
    {
        if ($value = JsonApi::nullable($jsonApi)) {
            $this->jsonApi = $value;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withoutJsonApi(): JsonApiDocument
    {
        $this->jsonApi = null;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withLinks($links): self
    {
        $this->links = Links::cast($links);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withoutLinks(): JsonApiDocument
    {
        $this->links = null;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withMeta($meta): self
    {
        $this->meta = Hash::cast($meta);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withoutMeta(): JsonApiDocument
    {
        $this->meta = null;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return $this->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        try {
            return json_decode($this->toJson(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable $ex) {
            throw new \LogicException(
                'Unable to convert document to an array. See previous exception for cause of failure.',
                0,
                $ex,
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        try {
            $this->prepareEncoder();

            return $this->serialize();
        } catch (\Throwable $ex) {
            throw new \LogicException(
                'Unable to serialize compound document. See previous exception for cause of failure.',
                0,
                $ex,
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function toJson($options = 0)
    {
        try {
            $this->prepareEncoder();

            $this->encoder->withEncodeOptions($options | JSON_THROW_ON_ERROR);

            return $this->encode();
        } catch (\Throwable $ex) {
            throw new \LogicException(
                'Unable to encode compound document. See previous exception for cause of failure.',
                0,
                $ex,
            );
        }
    }

    /**
     * Reset the encoder.
     *
     * @return void
     */
    private function prepareEncoder(): void
    {
        if ($this->jsonApi && $version = $this->jsonApi->version()) {
            $this->encoder->withJsonApiVersion($version);
        }

        if ($this->jsonApi && $this->jsonApi->hasMeta()) {
            $this->encoder->withJsonApiMeta($this->jsonApi->meta());
        }

        if ($this->meta && $this->meta->isNotEmpty()) {
            $this->encoder->withMeta($this->meta);
        }

        if ($this->links && $this->links->isNotEmpty()) {
            $this->encoder->withLinks($this->mapper->allLinks($this->links));
        }
    }

    /**
     * @return ExtendedEncoder
     */
    protected function encoder(): ExtendedEncoder
    {
        return $this->encoder;
    }

    /**
     * @return Mapper
     */
    protected function mapper(): Mapper
    {
        return $this->mapper;
    }
}
