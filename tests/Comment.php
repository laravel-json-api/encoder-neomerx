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

namespace LaravelJsonApi\Encoder\Neomerx\Tests;

class Comment
{

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $content;

    /**
     * @var User
     */
    public $user;

    /**
     * @var Post
     */
    public $post;

    /**
     * Comment constructor.
     *
     * @param string $id
     * @param string $content
     * @param User $user
     * @param Post $post
     */
    public function __construct(
        string $id,
        string $content,
        User $user,
        Post $post
    ) {
        $this->id = $id;
        $this->content = $content;
        $this->user = $user;
        $this->post = $post;
    }

    /**
     * @return string
     */
    public function getRouteKey(): string
    {
        return $this->id;
    }
}
