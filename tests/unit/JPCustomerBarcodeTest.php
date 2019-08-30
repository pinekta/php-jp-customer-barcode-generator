<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Pinekta\JPCustomerBarcode\BarcodeGeneratorHTML;
use Pinekta\JPCustomerBarcode\BarcodeGeneratorJPG;
use Pinekta\JPCustomerBarcode\BarcodeGeneratorPNG;
use Pinekta\JPCustomerBarcode\BarcodeGeneratorSVG;

/**
 * A test for Japan post customer barcode generator
 *
 * @access public
 * @author @pinekta <h03a081b@gmail.com>
 * @copyright @pinekta All Rights Reserved
 */
class JPCustomerBarcodeTest extends TestCase
{
    /**
     * @test
     * @group extract
     * @dataProvider isRightConvertCodeCharsProvider
     *
     * @param string $postCode
     * @param string $address
     * @param string $expected
     */
    public function isRightConvertCodeChars($postCode, $address, $expected)
    {
        $class = new \ReflectionClass(BarcodeGeneratorHTML::class);
        $methodExtractCodeChars = $class->getMethod('extractCodeChars');
        $methodExtractCodeChars->setAccessible(true);
        $methodConvertCodeChars = $class->getMethod('convertCodeChars');
        $methodConvertCodeChars->setAccessible(true);

        $chars = $methodExtractCodeChars->invokeArgs(new BarcodeGeneratorHTML(), [$postCode, $address]);
        $actual = $methodConvertCodeChars->invokeArgs(new BarcodeGeneratorHTML(), [$chars]);
        $this->assertEquals($expected, implode('', $actual));
    }

    /**
     * @test
     * @group create
     * @dataProvider isRightCreateBarcodeProvider
     *
     * @param array $convertedChars
     * @param string $case
     */
    public function isRightCreateBarcode(array $convertedChars, $case)
    {
        $testClasses = [
            'svg' => BarcodeGeneratorSVG::class,
            'html' => BarcodeGeneratorHTML::class,
            'png' => BarcodeGeneratorPNG::class,
            'jpg' => BarcodeGeneratorJPG::class,
        ];

        $resultHtml = './tests/files/generated/create-barcode-result.html';

        foreach ($testClasses as $ext => $testClass) {
            $class = new \ReflectionClass($testClass);
            $method = $class->getMethod('createBarcode');
            $method->setAccessible(true);

            $generated = $method->invokeArgs(new $testClass(), [$convertedChars]);
            if ($ext === 'png' || $ext === 'jpg') {
                $generated = '<img src="data:image/' . $ext . ';base64,' . base64_encode($generated) . '">';
            }
            $generated = "<h1>{$ext} {$case}</h1>" . $generated;
            file_put_contents($resultHtml, $generated, FILE_APPEND);

            $this->assertNotNull($generated);
        }
    }

    /**
     * @test
     * @group svg
     */
    public function svgBarcodeGeneratorCanGenerate()
    {
        $generator = new BarcodeGeneratorSVG();
        $generated = $generator->getBarcode('104-0045', '東京都中央区築地2-3-4');
        file_put_contents('./tests/files/generated/test.svg', $generated);

        $this->assertEquals('xml', substr($generated, 2, 3));
    }

    /**
     * @test
     * @group html
     */
    public function htmlBarcodeGeneratorCanGenerate()
    {
        $generator = new BarcodeGeneratorHTML();
        $generated = $generator->getBarcode('104-0045', '東京都中央区築地2-3-4');
        file_put_contents('./tests/files/generated/test.html', $generated);

        $this->assertEquals('div', substr($generated, 1, 3));
    }

    /**
     * @test
     * @group png
     */
    public function pngBarcodeGeneratorCanGenerate()
    {
        $generator = new BarcodeGeneratorPNG();
        $generated = $generator->getBarcode('104-0045', '東京都中央区築地2-3-4');
        file_put_contents('./tests/files/generated/test.png', $generated);

        $this->assertNotNull($generated);
    }

    /**
     * @test
     * @group jpg
     */
    public function jpgBarcodeGeneratorCanGenerate()
    {
        $generator = new BarcodeGeneratorJPG();
        $generated = $generator->getBarcode('104-0045', '東京都中央区築地2-3-4');
        file_put_contents('./tests/files/generated/test.jpg', $generated);

        $this->assertNotNull($generated);
    }

    /**
     * @test
     * @group error
     * @expectedException Pinekta\JPCustomerBarcode\Exceptions\InvalidPostCodeException
     */
    public function errorIfEmptyPostCode()
    {
        $generator = new BarcodeGeneratorSVG();
        $generated = $generator->getBarcode('', '東京都中央区築地2-3-4');
    }

    /**
     * @test
     * @group error
     * @expectedException Pinekta\JPCustomerBarcode\Exceptions\InvalidPostCodeException
     */
    public function errorIfInvalidPostCode()
    {
        $generator = new BarcodeGeneratorSVG();
        $generated = $generator->getBarcode('abc-1234', '東京都中央区築地2-3-4');
    }

    /**
     * @test
     * @group error
     * @expectedException Pinekta\JPCustomerBarcode\Exceptions\InvalidAddressException
     */
    public function errorIfEmptyAddress()
    {
        $generator = new BarcodeGeneratorSVG();
        $generated = $generator->getBarcode('104-0045', '');
    }

    /**
     * @test
     * @group error
     */
    public function errorIfEmptyCodeChars()
    {
        $class = new \ReflectionClass(BarcodeGeneratorSVG::class);
        $method = $class->getMethod('convertCodeChars');
        $method->setAccessible(true);
        $this->assertNull($method->invokeArgs(new BarcodeGeneratorSVG(), [[]]));

        $method = $class->getMethod('convertAlphabetToControlCode');
        $method->setAccessible(true);
        $this->assertNull($method->invokeArgs(new BarcodeGeneratorSVG(), [[]]));
    }

    /**
     * Data provider for isRightConvertCodeChars method
     * Data type is [$postCode, $address, $expected]
     *
     * @return array
     * @see https://www.post.japanpost.jp/zipcode/zipmanual/p25.html
     */
    public function isRightConvertCodeCharsProvider()
    {
        return [
            'case1' => [
                '263-0023',
                '千葉市稲毛区緑町3丁目30-8　郵便ビル403号',
                '(26300233-30-8-403CC4CC4CC45)',
            ],
            'case2' => [
                '014-0113',
                '秋田県大仙市堀見内　南田茂木　添60-1',
                '(014011360-1CC4CC4CC4CC4CC4CC4CC4CC4CC4CC8)',
            ],
            'case3' => [
                '110-0016',
                '東京都台東区台東5-6-3　ABCビル10F',
                '(11000165-6-3-10CC4CC4CC4CC4CC49)',
            ],
            'case4' => [
                '060-0906',
                '北海道札幌市東区北六条東4丁目　郵便センター6号館',
                '(06009064-6CC4CC4CC4CC4CC4CC4CC4CC4CC4CC49)',
            ],
            'case5' => [
                '065-0006',
                '北海道札幌市東区北六条東8丁目　郵便センター10号館',
                '(06500068-10CC4CC4CC4CC4CC4CC4CC4CC4CC49)',
            ],
            'case6' => [
                '407-0033',
                '山梨県韮崎市龍岡町下條南割　韮崎400',
                '(4070033400CC4CC4CC4CC4CC4CC4CC4CC4CC4CC4-)',
            ],
            'case7' => [
                '273-0102',
                '千葉県鎌ケ谷市右京塚　東3丁目-20-5　郵便・A&bコーポB604号',
                '(27301023-20-5CC11604CC4CC40)',
            ],
            'case8' => [
                '198-0036',
                '東京都青梅市河辺町十一丁目六番地一号　郵便タワー601',
                '(198003611-6-1-601CC4CC4CC4CC8)',
            ],
            'case9' => [
                '027-0203',
                '岩手県宮古市大字津軽石第二十一地割大淵川480',
                '(027020321-480CC4CC4CC4CC4CC4CC4CC4CC5)',
            ],
            'case10' => [
                '590-0016',
                '大阪府堺市堺区中田出井町四丁六番十九号',
                '(59000164-6-19CC4CC4CC4CC4CC4CC4CC4CC2)',
            ],
            'case11' => [
                '080-0831',
                '北海道帯広市稲田町南七線　西28',
                '(08008317-28CC4CC4CC4CC4CC4CC4CC4CC4CC4CC7)',
            ],
            'case12' => [
                '317-0055',
                '茨城県日立市宮田町6丁目7-14　ABCビル2F',
                '(31700556-7-14-2CC4CC4CC4CC4CC4CC1)',
            ],
            'case13' => [
                '650-0046',
                '神戸市中央区港島中町9丁目7-6　郵便シティA棟1F1号',
                '(65000469-7-6CC101-1CC4CC4CC45)',
            ],
            'case14' => [
                '623-0011',
                '京都府綾部市青野町綾部6-7　LプラザB106',
                '(62300116-7CC21CC11106CC4CC4CC44)',
            ],
            'case15' => [
                '064-0804',
                '札幌市中央区南四条西29丁目1524-23　第2郵便ハウス501',
                '(064080429-1524-23-2-3)',
            ],
            'case16' => [
                '910-0067',
                '福井県福井市新田塚3丁目80-25　J1ビル2-B',
                '(91000673-80-25CC191-2CC19)',
            ],
        ];
    }

    /**
     * データの形式は[$convertedChars, $case]
     * Data provider for isRightCreateBarcode method
     * Data type is [$convertedChars, $case]
     *
     * @see https://www.post.japanpost.jp/zipcode/zipmanual/p25.html
     */
    public function isRightCreateBarcodeProvider()
    {
        return [
            'case1' => [
                ['(','2','6','3','0','0','2','3','3','-','3','0','-','8','-','4','0','3','CC4','CC4','CC4','5',')'],
                'case1',
            ],
            'case2' => [
                ['(','0','1','4','0','1','1','3','6','0','-','1','CC4','CC4','CC4','CC4','CC4','CC4','CC4','CC4','CC4','CC8',')'],
                'case2',
            ],
            'case3' => [
                ['(','1','1','0','0','0','1','6','5','-','6','-','3','-','1','0','CC4','CC4','CC4','CC4','CC4','9',')'],
                'case3',
            ],
            'case4' => [
                ['(','0','6','0','0','9','0','6','4','-','6','CC4','CC4','CC4','CC4','CC4','CC4','CC4','CC4','CC4','CC4','9',')'],
                'case4',
            ],
            'case5' => [
                ['(','0','6','5','0','0','0','6','8','-','1','0','CC4','CC4','CC4','CC4','CC4','CC4','CC4','CC4','CC4','9',')'],
                'case5',
            ],
            'case6' => [
                ['(','4','0','7','0','0','3','3','4','0','0','CC4','CC4','CC4','CC4','CC4','CC4','CC4','CC4','CC4','CC4','-',')'],
                'case6',
            ],
            'case7' => [
                ['(','2','7','3','0','1','0','2','3','-','2','0','-','5','CC1','1','6','0','4','CC4','CC4','0',')'],
                'case7',
            ],
            'case8' => [
                ['(','1','9','8','0','0','3','6','1','1','-','6','-','1','-','6','0','1','CC4','CC4','CC4','CC8',')'],
                'case8',
            ],
            'case9' => [
                ['(','0','2','7','0','2','0','3','2','1','-','4','8','0','CC4','CC4','CC4','CC4','CC4','CC4','CC4','CC5',')'],
                'case9',
            ],
            'case10' => [
                ['(','5','9','0','0','0','1','6','4','-','6','-','1','9','CC4','CC4','CC4','CC4','CC4','CC4','CC4','CC2',')'],
                'case10',
            ],
            'case11' => [
                ['(','0','8','0','0','8','3','1','7','-','2','8','CC4','CC4','CC4','CC4','CC4','CC4','CC4','CC4','CC4','CC7',')'],
                'case11',
            ],
            'case12' => [
                ['(','3','1','7','0','0','5','5','6','-','7','-','1','4','-','2','CC4','CC4','CC4','CC4','CC4','CC1',')'],
                'case12',
            ],
            'case13' => [
                ['(','6','5','0','0','0','4','6','9','-','7','-','6','CC1','0','1','-','1','CC4','CC4','CC4','5',')'],
                'case13',
            ],
            'case14' => [
                ['(','6','2','3','0','0','1','1','6','-','7','CC2','1','CC1','1','1','0','6','CC4','CC4','CC4','4',')'],
                'case14',
            ],
            'case15' => [
                ['(','0','6','4','0','8','0','4','2','9','-','1','5','2','4','-','2','3','-','2','-','3',')'],
                'case15',
            ],
            'case16' => [
                ['(','9','1','0','0','0','6','7','3','-','8','0','-','2','5','CC1','9','1','-','2','CC1','9',')'],
                'case16',
            ],
        ];
    }
}
