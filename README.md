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

<div style="font-size:0;position:relative;width:274px;height:12px;">
<div style="background-color:black;width:2px;height:12px;position:absolute;left:4px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:8px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:12px;position:absolute;left:12px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:12px;position:absolute;left:16px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:4px;position:absolute;left:20px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:12px;position:absolute;left:24px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:4px;position:absolute;left:28px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:4px;position:absolute;left:32px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:12px;position:absolute;left:36px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:40px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:44px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:12px;position:absolute;left:48px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:4px;position:absolute;left:52px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:4px;position:absolute;left:56px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:12px;position:absolute;left:60px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:4px;position:absolute;left:64px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:4px;position:absolute;left:68px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:12px;position:absolute;left:72px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:76px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:80px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:12px;position:absolute;left:84px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:4px;position:absolute;left:88px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:12px;position:absolute;left:92px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:12px;position:absolute;left:96px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:100px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:104px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:4px;position:absolute;left:108px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:12px;position:absolute;left:112px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:4px;position:absolute;left:116px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:120px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:12px;position:absolute;left:124px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:128px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:4px;position:absolute;left:132px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:12px;position:absolute;left:136px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:4px;position:absolute;left:140px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:12px;position:absolute;left:144px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:148px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:152px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:4px;position:absolute;left:156px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:160px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:164px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:4px;position:absolute;left:168px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:172px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:176px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:4px;position:absolute;left:180px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:184px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:188px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:4px;position:absolute;left:192px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:196px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:200px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:4px;position:absolute;left:204px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:208px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:212px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:4px;position:absolute;left:216px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:220px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:224px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:4px;position:absolute;left:228px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:232px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:236px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:4px;position:absolute;left:240px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:244px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:248px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:4px;position:absolute;left:252px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:256px;top:0px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:260px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:8px;position:absolute;left:264px;top:4px;">&nbsp;</div>
<div style="background-color:black;width:2px;height:12px;position:absolute;left:268px;top:0px;">&nbsp;</div>
</div>

#### PNG

![PNG](./sample/sample.png)

#### JPG

![JPG](./sample/sample.jpg)

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
