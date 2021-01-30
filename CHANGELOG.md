# Change Log

All notable changes to this project will be documented in this file. This project adheres to
[Semantic Versioning](http://semver.org/) and [this changelog format](http://keepachangelog.com/).

## Unreleased

### Added
- New `withRequest` method on the encoder, allows a request to be passed into the encoding process.
This is then provided to each `JsonApiResource` object when it is being encoded. This change
was made to bring the resource into line with Laravel's Eloquent resource, which is passed the
request when serializing models. *(Changes to classes in the `Schema` namespace to implement this
are considered non-breaking, as they are internal classes.)*

## [1.0.0-alpha.1] - 2021-01-25

Initial release.
