# PDF Imposition Toolkit

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kduma/pdf-imposition.svg?style=flat-square)](https://packagist.org/packages/kduma/pdf-imposition)
[![Build Status](https://img.shields.io/travis/kduma/pdf-imposition/master.svg?style=flat-square)](https://travis-ci.org/kduma/pdf-imposition)
[![Quality Score](https://img.shields.io/scrutinizer/g/kduma/pdf-imposition.svg?style=flat-square)](https://scrutinizer-ci.com/g/kduma/pdf-imposition)
[![Total Downloads](https://img.shields.io/packagist/dt/kduma/pdf-imposition.svg?style=flat-square)](https://packagist.org/packages/kduma/pdf-imposition)

PDF Imposition Toolkit

## Installation

You can install the package via composer:

```bash
composer require kduma/pdf-imposition
```

## Usage

```php
use Kduma\PdfImposition\LayoutGenerators\AutoGridPageLayoutGenerator;
use Kduma\PdfImposition\LayoutGenerators\Markers\PrinterBoxCutMarkings;
use Kduma\PdfImposition\PdfImposer;
use Kduma\PdfImposition\PdfSource;
use Kduma\PdfImposition\DTO\Size;

$cardSize = Size::make(90, 50);

$layoutGenerator = new AutoGridPageLayoutGenerator($cardSize);
$layoutGenerator->center();

$layoutGenerator = new PrinterBoxCutMarkings($layoutGenerator);

$PdfImposer = new PdfImposer($layoutGenerator);

$cards = (new PdfSource)->read('input.pdf');
$PdfImposer->export($cards, 'output.pdf');
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email git@krystian.duma.sh instead of using the issue tracker.

## Credits

- [Krystian Duma](https://github.com/kduma)
- [All Contributors](../../contributors)

## License

The GNU GPLv3. Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).
