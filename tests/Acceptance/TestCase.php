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

namespace LaravelJsonApi\Encoder\Neomerx\Tests\Acceptance;

use LaravelJsonApi\Core\Resources\Container;
use LaravelJsonApi\Core\Resources\Factory;
use LaravelJsonApi\Encoder\Neomerx\Document\Builder;
use LaravelJsonApi\Encoder\Neomerx\Encoder;
use LaravelJsonApi\Encoder\Neomerx\Mapper;
use LaravelJsonApi\Encoder\Neomerx\Schema\SchemaContainer;
use LaravelJsonApi\Encoder\Neomerx\Tests\Post;
use LaravelJsonApi\Encoder\Neomerx\Tests\PostResource;
use Neomerx\JsonApi\Factories\Factory as NeomerxFactory;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $mapper = new Mapper($neomerxFactory = new NeomerxFactory());

        $container = new Container(new Factory([
            Post::class => PostResource::class,
        ]));

        $this->builder = new Builder(
            new Encoder($neomerxFactory, new SchemaContainer($container, $mapper)),
            $mapper
        );
    }
}
