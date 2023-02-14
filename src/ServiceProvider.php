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

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use LaravelJsonApi\Contracts\Encoder\Factory as EncoderFactoryContract;
use LaravelJsonApi\Encoder\Neomerx\Factory as EncoderFactory;
use Neomerx\JsonApi\Contracts\Factories\FactoryInterface;

class ServiceProvider extends BaseServiceProvider implements DeferrableProvider
{

    /**
     * @inheritDoc
     */
    public function provides(): array
    {
        return [
            EncoderFactoryContract::class,
            FactoryInterface::class,
        ];
    }

    /**
     * Bind package services into the service container.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(EncoderFactoryContract::class, EncoderFactory::class);
        $this->app->bind(FactoryInterface::class, Factory\Factory::class);
    }
}
