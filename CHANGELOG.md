# Change Log

All notable changes to this project will be documented in this file. This project adheres to
[Semantic Versioning](http://semver.org/) and [this changelog format](http://keepachangelog.com/).

## [1.0.0-beta.1] - 2021-03-30

### Changed

- Updated the encoder to implement changes made to the encoder interface. This removes the `withIdentifiers()` method
  and replaces it with the `withToOne()` and `withToMany()` methods.

### Fixed

- When encoding relationships, do not yield a relationship value that is empty (no data, links or meta).

## [1.0.0-alpha.4] - 2021-02-27

### Changed

- This package now relies on `laravel-json-api/neomerx-json-api`, a fork of `neomerx/json-api`. This was required
  because of a [bug affecting empty to-many include paths.](https://github.com/laravel-json-api/laravel/issues/11)
  Although a PR was sent to the Neomerx repository, there has been no activity in that repository for almost a year. The
  fork will be maintained for use in Laravel JSON:API, but we will switch back to using the Neomerx package if it is
  maintained in the future.

### Fixed

- Conditional field values are now correctly handled when iterating over a resource's relationships.

## [1.0.0-alpha.3] - 2021-02-09

### Added

- Encoder now supports conditional fields being used in the JSON:API resource relationships.

### Fixed

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
