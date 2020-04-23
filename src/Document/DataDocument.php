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
use LaravelJsonApi\Core\Document\Concerns\HasLinks;
use LaravelJsonApi\Core\Document\Concerns\HasMeta;
use LaravelJsonApi\Core\Query\FieldSets;
use LaravelJsonApi\Core\Query\IncludePaths;
use LaravelJsonApi\Encoder\Neomerx\Encoder;
use LaravelJsonApi\Encoder\Neomerx\Mapper;
use LogicException;
use Throwable;

class DataDocument extends BaseDocument implements DataDocumentContract
{

    use HasLinks;
    use HasMeta;

    /**
     * @var Mapper
     */
    private $mapper;

    /**
     * @var iterable|mixed|null
     */
    private $data;

    /**
     * @var IncludePaths
     */
    private $includePaths;

    /**
     * @var FieldSets
     */
    private $fieldSets;

    /**
     * CompoundDocument constructor.
     *
     * @param Encoder $encoder
     * @param Mapper $mapper
     * @param mixed|iterable|null $data
     *      the data to encode.
     * @param IncludePaths|null $includePaths
     *      the include paths used to load the data.
     * @param FieldSets|null $fieldSets
     *      the sparse field-sets used to load the data.
     */
    public function __construct(
        Encoder $encoder,
        Mapper $mapper,
        $data,
        ?IncludePaths $includePaths,
        ?FieldSets $fieldSets
    ) {
        parent::__construct($encoder);
        $this->mapper = $mapper;
        $this->data = $data;
        $this->includePaths = $includePaths ?: new IncludePaths();
        $this->fieldSets = $fieldSets ?: new FieldSets();
    }

    /**
     * @return iterable|mixed|null
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        try {
            return $this
                ->encoder()
                ->serializeData($this->data);
        } catch (Throwable $ex) {
            throw new LogicException('Unable to serialize compound document.', 0, $ex);
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
                ->encodeData($this->data);
        } catch (Throwable $ex) {
            throw new LogicException('Unable to encode compound document.', 0, $ex);
        }
    }

    /**
     * @return Encoder
     */
    protected function encoder(): Encoder
    {
        $encoder = parent::encoder();

        if ($this->hasMeta()) {
            $encoder->withMeta($this->meta());
        }

        if ($this->hasLinks()) {
            $encoder->withLinks($this->mapper->allLinks($this->links()));
        }

        return $encoder
            ->withIncludedPaths($this->includePaths->toArray())
            ->withFieldSets($this->fieldSets->toArray());
    }

}
