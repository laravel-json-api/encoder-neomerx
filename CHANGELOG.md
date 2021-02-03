# Change Log

All notable changes to this project will be documented in this file. This project adheres to
[Semantic Versioning](http://semver.org/) and [this changelog format](http://keepachangelog.com/).

## Unreleased

### Fixed

- Fixed relationship data returning a generator that could only be iterated once. Generators can cause errors within
  the Neomerx parser, which seems to iterate over the relationship data more than once in some scenarios. This bug was
  fixed by upgrading `laravel-json-api/core` to `v1.0.0-alpha.3`, which now returns an iterator that handles generators
  from the `Resource\Container::resolve()` method.
- Fixed the schema's `getSelfLink()` method, which was not passing the request through to the JSON:API resource object.

## [1.0.0-alpha.2] - 2021-02-02

### Added

- [#2](https://github.com/laravel-json-api/encoder-neomerx/pull/2)
  New `withRequest` method on the encoder, allows a request to be passed into the encoding process. This is then
  provided to each `JsonApiResource` object when it is being encoded. This change was made to bring the resource into
  line with Laravel's Eloquent resource, which is passed the request when serializing models. *(Changes to classes in
  the `Schema` namespace to implement this are considered non-breaking, as they are internal classes.)*
- Resource meta can now contain conditional attributes, as the conditional iterator is used when processing meta
  returned by the `JsonApiResource` class.

### Fixed

- [#3](https://github.com/laravel-json-api/encoder-neomerx/issues/3)
  Add missing package discovery configuration to `composer.json`.

## [1.0.0-alpha.1] - 2021-01-25

Initial release.
