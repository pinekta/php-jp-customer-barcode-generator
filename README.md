# php-jp-customer-barcode-generator

<!--
![Build status](https://img.shields.io/circleci/project/github/pinekta/php-jp-customer-barcode-generator.svg)
[![Coverage Status](https://coveralls.io/repos/github/pinekta/php-jp-customer-barcode-generator/badge.svg?branch=master)](https://coveralls.io/github/pinekta/php-jp-customer-barcode-generator?branch=master)
![License](https://img.shields.io/packagist/l/pinekta/php-jp-customer-barcode-generator.svg)
![PHP Version](https://img.shields.io/badge/PHP-%3E%3D5.4-blue.svg)
![Packagist Version](https://img.shields.io/packagist/v/pinekta/php-jp-customer-barcode-generator.svg)
-->


<!--
badge
logo image
-->

This library 'php-jp-customer-barcode-generator' is an easy to use, framework independent, Japan post's customer barcode generator in PHP.

It creates Japan post's customer barcode by SVG, PNG, JPG and HTML images.

php-jp-customer-barcode-generator requires PHP >= 5.4.0.

## Installation

```
composer require pinekta/php-jp-customer-barcode-generator
```

If you want to generate PNG or JPG images, you need the GD library or Imagick installed on your system as well.

## Usage

```php
<?php

// SVG
$generator = new Pinekta\JPCustomerBarcode\BarcodeGeneratorSVG();
echo $generator->getBarcode('104-0045', '東京都中央区築地2-3-4');

// HTML
$generator = new Pinekta\JPCustomerBarcode\BarcodeGeneratorHTML();
echo $generator->getBarcode('104-0045', '東京都中央区築地2-3-4');

// PNG
$generator = new Pinekta\JPCustomerBarcode\BarcodeGeneratorPNG();
echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode('104-0045', '東京都中央区築地2-3-4')) . '">');

// JPG
$generator = new Pinekta\JPCustomerBarcode\BarcodeGeneratorJPG();
echo '<img src="data:image/jpg;base64,' . base64_encode($generator->getBarcode('104-0045', '東京都中央区築地2-3-4')) . '">');
```

### Generated customer barcode

#### SVG

![SVG](./sample/sample.svg)

#### HTML

[HTML](./sample/sample.html)

#### PNG

![PNG](./sample/sample.png)

#### JPG

![JPG](./sample/sample.jpg)

## Result about Japan post check sheet

Please check below link.  
[result](./sample/create-barcode-result.html)

<!--
## Documentation

Comming soon...
-->

## Contributing

Contributions are welcome!
This project adheres to a [Contributor Code of Conduct](./CODE_OF_CONDUCT.md). By participating in this project and its community, you are expected to uphold this code.
Please read [CONTRIBUTING](./CONTRIBUTING.md) for details.

## Copyright

The pinekta/php-jp-customer-barcode-generator is copyright © [@pinekta](https://github.com/pinekta).

## License

The pinekta/php-jp-customer-barcode-generator is licensed under the MIT License.
Please see [LICENSE](./LICENSE) for more information.
