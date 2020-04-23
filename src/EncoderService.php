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

namespace LaravelJsonApi\Encoder\Neomerx;

use LaravelJsonApi\Core\Contracts\Encoder\DocumentBuilder;
use LaravelJsonApi\Core\Contracts\Encoder\EncoderService as EncoderServiceContract;
use LaravelJsonApi\Core\Contracts\Resources\Container;
use LaravelJsonApi\Encoder\Neomerx\Document\Builder;
use Neomerx\JsonApi\Factories\Factory;

class EncoderService implements EncoderServiceContract
{

    /**
     * @var Factory
     */
    private $factory;

    /**
     * EncoderService constructor.
     *
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @inheritDoc
     */
    public function encoder(Container $container): DocumentBuilder
    {
        return new Builder(
            $container,
            $this->factory,
            new Mapper($this->factory)
        );
    }
}
