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

namespace LaravelJsonApi\Encoder\Neomerx\Parser;

use LaravelJsonApi\Core\Json\Hash;
use LaravelJsonApi\Core\Json\Json;
use Neomerx\JsonApi\Parser\IdentifierAndResource as BaseIdentifierAndResource;

class IdentifierAndResource extends BaseIdentifierAndResource
{

    /**
     * Cached meta.
     *
     * @var Hash|null
     */
    private ?Hash $meta = null;

    /**
     * Cached identifier meta.
     *
     * @var Hash|null
     */
    private ?Hash $identifierMeta = null;

    /**
     * @inheritDoc
     */
    public function hasIdentifierMeta(): bool
    {
        $this->cacheIdentifierMeta();

        return $this->identifierMeta->isNotEmpty();
    }

    /**
     * @inheritDoc
     */
    public function getIdentifierMeta()
    {
        $this->cacheIdentifierMeta();

        return $this->identifierMeta->jsonSerialize();
    }

    /**
     * @inheritDoc
     */
    public function hasResourceMeta(): bool
    {
        $this->cacheMeta();

        return $this->meta->isNotEmpty();
    }

    /**
     * @inheritDoc
     */
    public function getResourceMeta()
    {
        $this->cacheMeta();

        return $this->meta->jsonSerialize();
    }

    /**
     * Cache resource meta.
     *
     * @return void
     */
    private function cacheMeta(): void
    {
        if (null === $this->meta) {
            $this->meta = Json::hash(parent::getResourceMeta());
        }
    }

    /**
     * Cache resource identifier meta.
     *
     * @return void
     */
    private function cacheIdentifierMeta(): void
    {
        if (null === $this->identifierMeta) {
            $this->identifierMeta = Json::hash(parent::getIdentifierMeta());
        }
    }

}
