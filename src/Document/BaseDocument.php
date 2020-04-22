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

use LaravelJsonApi\Core\Contracts\Serializable;
use LaravelJsonApi\Core\Document\Concerns\HasJsonApi;
use LaravelJsonApi\Encoder\Neomerx\Encoder;
use LogicException;
use Throwable;
use function json_decode;

abstract class BaseDocument implements Serializable
{

    use HasJsonApi;

    /**
     * @var Encoder
     */
    private $encoder;

    /**
     * BaseDocument constructor.
     *
     * @param Encoder $encoder
     */
    public function __construct(Encoder $encoder)
    {
        $this->encoder = $encoder;
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
        } catch (Throwable $ex) {
            throw new LogicException('Unable to convert document to an array.', 0, $ex);
        }
    }

    /**
     * @return Encoder
     */
    protected function encoder(): Encoder
    {
        $encoder = $this->encoder->reset();

        if ($this->doesntHaveJsonApi()) {
            return $encoder;
        }

        if ($version = $this->jsonApi()->version()) {
            $encoder->withJsonApiVersion($version);
        }

        if ($this->jsonApi()->hasMeta()) {
            $encoder->withJsonApiMeta($this->jsonApi()->meta());
        }

        return $encoder;
    }
}
