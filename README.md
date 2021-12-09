# Use Percy.io in your Laravel testsuite

## What It Does

This package allows you to easily use Percy in your existing Laravel testsuite

## Installation

### First make sure you have the Percy CLI installed

```bash
npm install --save-dev @percy/cli
```

or

```bash
yarn add --dev @percy/cli
```

### Install this package

```bash
composer require --dev letspaak/laravel-percy
```

### Add the `PERCY_TOKEN` to your .env file

```bash
PERCY_TOKEN=aaabbbcccdddeeefff
```

## Usage

By default we use Laravel's Dusk package to capture our webpages that we send to Percy.

```php
// Visit the homepage and send the snapshot to Percy
$browser->visit('/')->snapshot('Homepage')
```

After your tests are created, run them as follows:

```bash
npx percy exec -- php artisan dusk
```

[comment]: <> (### Testing)

[comment]: <> (``` bash)

[comment]: <> (composer test)

[comment]: <> (```)

[comment]: <> (### Changelog)

[comment]: <> (Please see [CHANGELOG]&#40;CHANGELOG.md&#41; for more information what has changed recently.)

[comment]: <> (## Contributing)

[comment]: <> (Please see [CONTRIBUTING]&#40;CONTRIBUTING.md&#41; for details.)

[comment]: <> (### Security)

[comment]: <> (If you discover any security-related issues, please email [freek@spatie.be]&#40;mailto:freek@spatie.be&#41; instead of using the)

[comment]: <> (issue tracker.)

## Credits

- [Samir Nijenhuis](https://github.com/samirnijenhuis)

This package is heavily based on [Percy's Java implementation](https://github.com/percy/percy-selenium-java)

## Alternatives

- [Signature Tech Studio's Laravel visual testing](https://github.com/stechstudio/laravel-visual-testing)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
