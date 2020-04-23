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

use LaravelJsonApi\Core\Document\Concerns\HasLinks;
use LaravelJsonApi\Core\Document\Document;
use LaravelJsonApi\Core\Json\Hash;
use LaravelJsonApi\Encoder\Neomerx\Encoder;
use LaravelJsonApi\Encoder\Neomerx\Mapper;
use LogicException;
use Throwable;

class MetaDocument extends BaseDocument
{

    use HasLinks;

    /**
     * @var Mapper
     */
    private $mapper;

    /**
     * @var Hash
     */
    private $meta;

    /**
     * MetaDocument constructor.
     *
     * @param Encoder $encoder
     * @param Mapper $mapper
     * @param mixed $meta
     */
    public function __construct(Encoder $encoder, Mapper $mapper, $meta)
    {
        parent::__construct($encoder);
        $this->mapper = $mapper;
        $this->meta = Document::meta($meta);
    }

    /**
     * @return Hash
     */
    public function meta(): Hash
    {
        return $this->meta;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        try {
            return $this
                ->encoder()
                ->serializeMeta($this->meta);
        } catch (Throwable $ex) {
            throw new LogicException('Unable to serialize meta document.', 0, $ex);
        }
    }

    /**
     * @inheritDoc
     */
    public function toJson($options = 0)
    {
        try {
            return $this
                ->encoder()
                ->withEncodeOptions($options | JSON_THROW_ON_ERROR)
                ->encodeMeta($this->meta);
        } catch (Throwable $ex) {
            throw new LogicException('Unable to encode meta document.', 0, $ex);
        }
    }

    /**
     * @return Encoder
     */
    protected function encoder(): Encoder
    {
        $encoder = parent::encoder();

        if ($this->hasLinks()) {
            $encoder->withLinks($this->mapper->allLinks($this->links()));
        }

        return $encoder;
    }

}
