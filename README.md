# Laravel Modules

[![Latest Version on Packagist](https://img.shields.io/packagist/v/PerkDotCom/laravel-modules.svg?style=flat-square)](https://packagist.org/packages/PerkDotCom/laravel-modules)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/PerkDotCom/laravel-modules/master.svg?style=flat-square)](https://travis-ci.org/PerkDotCom/laravel-modules)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/PerkDotCom/laravel-modules.svg?style=flat-square)](https://scrutinizer-ci.com/g/PerkDotCom/laravel-modules/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/PerkDotCom/laravel-modules.svg?style=flat-square)](https://scrutinizer-ci.com/g/PerkDotCom/laravel-modules)
[![Total Downloads](https://img.shields.io/packagist/dt/PerkDotCom/laravel-modules.svg?style=flat-square)](https://packagist.org/packages/PerkDotCom/laravel-modules)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what
PSRs you support to avoid any confusion with users and contributors.

## Install

Via Composer

``` bash
$ composer require PerkDotCom/laravel-modules
```

## Usage

Add the service provider to the `providers` array in `config/app.php`
``` php
PerkDotCom\Modules\ModuleServiceProvider::class
```

You can now run the following command to create new modules
```bash
php artisan make:module {name}
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email developers@perk.com instead of using the issue tracker.

## Credits

- [Perk.com](https://github.com/PerkDotCom)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.