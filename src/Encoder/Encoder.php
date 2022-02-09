<?php
/*
 * Copyright 2022 Cloud Creativity Limited
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

namespace LaravelJsonApi\Encoder\Neomerx\Encoder;

use Neomerx\JsonApi\Encoder\Encoder as BaseEncoder;

class Encoder extends BaseEncoder
{

    /**
     * Serialize data to an array.
     *
     * @param $data
     * @return array
     */
    public function serializeData($data): array
    {
        return $this->encodeDataToArray($data);
    }

    /**
     * Serialize resource identifiers to an array.
     *
     * @param $data
     * @return array
     */
    public function serializeIdentifiers($data): array
    {
        return $this->encodeIdentifiersToArray($data);
    }

    /**
     * Serialize a meta document to an array.
     *
     * @param $meta
     * @return array
     */
    public function serializeMeta($meta): array
    {
        return $this->encodeMetaToArray($meta);
    }
}
