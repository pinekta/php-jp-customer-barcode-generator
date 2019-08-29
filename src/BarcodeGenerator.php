<?php

namespace Pinekta\JPCustomerBarcode;

use Pinekta\JPCustomerBarcode\Exceptions\InvalidPostCodeException;
use Pinekta\JPCustomerBarcode\Exceptions\InvalidAddressException;

/**
 * An abstract class of Japan post customer barcode generator
 *
 * @access public
 * @author @pinekta <h03a081b@gmail.com>
 * @copyright @pinekta All Rights Reserved
 *
 * @todo Vertical output
 */
abstract class BarcodeGenerator
{
    /** @var int bar type is none */
    const TYPES_NONE = 0;
    /** @var int bar type is long */
    const TYPES_LONG = 1;
    /** @var int bar type is semilong (top) */
    const TYPES_SEMILONG_TOP = 2;
    /** @var int bar type is semilong (bottom) */
    const TYPES_SEMILONG_BOTTOM = 3;
    /** @var int bar type is timing */
    const TYPES_TIMING = 4;

    /**
     * The conversion map of bar height rate
     *
     * @var array
     */
    const HEIGHT_RATE_MAP = [
        self::TYPES_NONE => 0,
        self::TYPES_LONG => 6,
        self::TYPES_SEMILONG_TOP => 4,
        self::TYPES_SEMILONG_BOTTOM => 4,
        self::TYPES_TIMING => 2,
    ];

    /**
     * The conversion map of code character and bar
     *
     * @var array
     */
    const CONVERT_MAP = [
        '1' => [self::TYPES_LONG, self::TYPES_LONG, self::TYPES_TIMING],
        '2' => [self::TYPES_LONG, self::TYPES_SEMILONG_BOTTOM, self::TYPES_SEMILONG_TOP],
        '3' => [self::TYPES_SEMILONG_BOTTOM, self::TYPES_LONG, self::TYPES_SEMILONG_TOP],
        '4' => [self::TYPES_LONG, self::TYPES_SEMILONG_TOP, self::TYPES_SEMILONG_BOTTOM],
        '5' => [self::TYPES_LONG, self::TYPES_TIMING, self::TYPES_LONG],
        '6' => [self::TYPES_SEMILONG_BOTTOM, self::TYPES_SEMILONG_TOP, self::TYPES_LONG],
        '7' => [self::TYPES_SEMILONG_TOP, self::TYPES_LONG, self::TYPES_SEMILONG_BOTTOM],
        '8' => [self::TYPES_SEMILONG_TOP, self::TYPES_SEMILONG_BOTTOM, self::TYPES_LONG],
        '9' => [self::TYPES_TIMING, self::TYPES_LONG, self::TYPES_LONG],
        '0' => [self::TYPES_LONG, self::TYPES_TIMING, self::TYPES_TIMING],
        '-' => [self::TYPES_TIMING, self::TYPES_LONG, self::TYPES_TIMING],
        'CC1' => [self::TYPES_SEMILONG_BOTTOM, self::TYPES_SEMILONG_TOP, self::TYPES_TIMING],
        'CC2' => [self::TYPES_SEMILONG_BOTTOM, self::TYPES_TIMING, self::TYPES_SEMILONG_TOP],
        'CC3' => [self::TYPES_SEMILONG_TOP, self::TYPES_SEMILONG_BOTTOM, self::TYPES_TIMING],
        'CC4' => [self::TYPES_TIMING, self::TYPES_SEMILONG_BOTTOM, self::TYPES_SEMILONG_TOP],
        'CC5' => [self::TYPES_SEMILONG_TOP, self::TYPES_TIMING, self::TYPES_SEMILONG_BOTTOM],
        'CC6' => [self::TYPES_TIMING, self::TYPES_SEMILONG_TOP, self::TYPES_SEMILONG_BOTTOM],
        'CC7' => [self::TYPES_TIMING, self::TYPES_TIMING, self::TYPES_LONG],
        'CC8' => [self::TYPES_LONG, self::TYPES_LONG, self::TYPES_LONG],
        '(' => [self::TYPES_NONE, self::TYPES_LONG, self::TYPES_SEMILONG_BOTTOM],
        ')' => [self::TYPES_SEMILONG_BOTTOM, self::TYPES_LONG, self::TYPES_NONE],
    ];

    /**
     * The conversion map of code character and alphabet
     *
     * @var array
     */
    const ALPHABET_CONVERT_MAP = [
        'A' => ['CC1', '0'],
        'B' => ['CC1', '1'],
        'C' => ['CC1', '2'],
        'D' => ['CC1', '3'],
        'E' => ['CC1', '4'],
        'F' => ['CC1', '5'],
        'G' => ['CC1', '6'],
        'H' => ['CC1', '7'],
        'I' => ['CC1', '8'],
        'J' => ['CC1', '9'],
        'K' => ['CC2', '0'],
        'L' => ['CC2', '1'],
        'M' => ['CC2', '2'],
        'N' => ['CC2', '3'],
        'O' => ['CC2', '4'],
        'P' => ['CC2', '5'],
        'Q' => ['CC2', '6'],
        'R' => ['CC2', '7'],
        'S' => ['CC2', '8'],
        'T' => ['CC2', '9'],
        'U' => ['CC3', '0'],
        'V' => ['CC3', '1'],
        'W' => ['CC3', '2'],
        'X' => ['CC3', '3'],
        'Y' => ['CC3', '4'],
        'Z' => ['CC3', '5'],
    ];

    /**
     * The conversion map of code character and alphabet
     *
     * @var array
     */
    const CHECK_DIGIT_CONVERT_MAP = [
        '1' => 1,
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
        '6' => 6,
        '7' => 7,
        '8' => 8,
        '9' => 9,
        '0' => 0,
        '-' => 10,
        'CC1' => 11,
        'CC2' => 12,
        'CC3' => 13,
        'CC4' => 14,
        'CC5' => 15,
        'CC6' => 16,
        'CC7' => 17,
        'CC8' => 18,
    ];

    /**
     * Width factor
     * 2 or 3 recommended
     *
     * @var int
     */
    protected $widthFactor;

    /**
     * Barcode color
     * black and dark blue recommended
     *
     * @var string
     */
    protected $color;

    /**
     * constructor
     *
     * @param int $widthFactor
     * @param string $color
     */
    public function __construct($widthFactor = 2, $color = 'black')
    {
        $this->widthFactor = $widthFactor;
        $this->color = $color;
    }

    /**
     * Return barcode
     *
     * @param string $postCode
     * @param string $address
     * @return string
     */
    public function getBarcode($postCode, $address)
    {
        $chars = $this->extractCodeChars($postCode, $address);
        $convertedChars = $this->convertCodeChars($chars);

        return $this->createBarcode($convertedChars);
    }

    /**
     * Create barcode
     *
     * @param array $convertedChars
     * @return string
     */
    abstract protected function createBarcode(array $convertedChars);

    /**
     * Extract characters of code from postCode and address.
     *
     * @param string $postCode
     * @param string $address
     * @return array
     * @throws InvalidPostCodeException
     * @throws InvalidAddressException
     */
    protected function extractCodeChars($postCode, $address)
    {
        if (empty($postCode)) {
            throw new InvalidPostCodeException("Post code is empty.");
        }
        if (!preg_match('/^[0-9]{3}(-)*[0-9]{4}$/u', $postCode)) {
            throw new InvalidPostCodeException("Invalid post code [{$postCode}]. Ex. 000-0000 or 0000000");
        }

        if (empty($address)) {
            throw new InvalidPostCodeException("Address is empty.");
        }

        $postCode = trim(str_replace('-', '', $postCode));

        $extractedStr = trim(mb_strtoupper($address));
        // Remove [&],[/],[・],[.]
        $extractedStr = preg_replace('/[&\/.・]/u', '', $extractedStr);
        // Convert from full-width numeric to half-width numeric
        $extractedStr = mb_convert_kana($extractedStr, 'n', 'UTF-8');

        // Replace consecutive characters with hyphens
        $extractedStr = preg_replace('/[A-Z]{2,}/u', '-', $extractedStr);

        // Convert kanji numerics before a specific character group to numbers
        $extractedStr = preg_replace_callback('/([一二三四五六七八九十]+)(丁目|丁|番地|番|号|地割|線|の|ノ)/u', function ($matches) {
            $value = trim($matches[0]);

            // case 十 (10)
            $value = preg_replace('/^十$/u', '10', $value);

            // case 十 end (x0)
            $value = preg_replace('/([一二三四五六七八九]+)十$/u', '$1０', $value);
            $value = str_replace('０', '0', $value);

            // case 十 not end (xx)
            $value = preg_replace('/([一二三四五六七八九]+)十/u', '$1', $value);

            // case nothing before 十 (1x)
            $value = preg_replace('/十([一二三四五六七八九]+)/u', '1$1', $value);

            $value = str_replace('一', '1', $value);
            $value = str_replace('二', '2', $value);
            $value = str_replace('三', '3', $value);
            $value = str_replace('四', '4', $value);
            $value = str_replace('五', '5', $value);
            $value = str_replace('六', '6', $value);
            $value = str_replace('七', '7', $value);
            $value = str_replace('八', '8', $value);
            $value = str_replace('九', '9', $value);
            return $value;
        }, $extractedStr);

        // convert hyphen if not numeric and hyphen and not-continuous alphabet
        $extractedStr = preg_replace('/[^0-9A-Z\-]/u', '-', $extractedStr);
        // F following the number is not extracted if it is the last character in the string
        $extractedStr = preg_replace('/([0-9]+)F$/u', '$1', $extractedStr);
        // F following the number is replaced with a hyphen
        $extractedStr = preg_replace('/([0-9]+)F/u', '$1-', $extractedStr);

        // Combine consecutive hyphens into one
        $extractedStr = preg_replace('/[\-]+/u', '-', $extractedStr);
        // Remove hyphens before and after the alphabet
        $extractedStr = preg_replace('/([\-]+)([A-Z]+)/u', '$2', $extractedStr);
        $extractedStr = preg_replace('/([A-Z]+)([\-]+)/u', '$1', $extractedStr);
        // Remove the trailing hyphen (because the string after the extracted string is not used)
        $extractedStr = preg_replace('/[\-]+$/u', '', $extractedStr);
        // Remove leading hyphen
        $extractedStr = preg_replace('/^[\-]{1}/u', '', $extractedStr);

        return str_split($postCode . $extractedStr);
    }

    /**
     * Convert from alphabet to control codes
     * Premise not including control character code
     *
     * @param array $chars
     * @return array
     */
    private function convertAlphabetToControlCode(array $chars)
    {
        if (count($chars) === 0) {
            return null;
        }

        $retChars = [];
        foreach ($chars as $char) {
            $char = mb_strtoupper($char);
            if (preg_match('/[A-Z]/u', $char)) {
                $convertedChars = self::ALPHABET_CONVERT_MAP[$char];
                foreach ($convertedChars as $convertedChar) {
                    $retChars[] = $convertedChar;
                }
            } else {
                $retChars[] = $char;
            }
        }

        return $retChars;
    }

    /**
     * Fill up to the specified number of digits with control code "CC4"
     * Or, if it exceeds the specified number of digits, it will be truncated
     *
     * @param array $chars
     * @param int $maxLength
     * @return array
     */
    private function paddingControlCodes(array $chars, $maxLength = 20)
    {
        $length = count($chars);
        if ($length < $maxLength) {
            for ($i = $length; $i < $maxLength; $i++) {
                $chars[] = 'CC4';
            }
        } elseif ($length > $maxLength) {
            $newChars = [];
            for ($i = 0; $i < $maxLength; $i++) {
                $newChars[] = $chars[$i];
            }
            $chars = $newChars;
        }

        return $chars;
    }

    /**
     * Add check digit
     *
     * @param array $chars
     * @return array
     */
    private function addCheckDigit(array $chars)
    {
        $sum = 0;
        foreach ($chars as $char) {
            $sum += self::CHECK_DIGIT_CONVERT_MAP[$char];
        }

        if ($sum % 19 === 0) {
            // case divisible
            $checkDigit = 0;
        } else {
            // (A multiple of 19 greater than the sum) - sum
            $checkDigit = (int)((floor($sum / 19) + 1) * 19) - $sum;
        }

        $chars[] = array_search($checkDigit, self::CHECK_DIGIT_CONVERT_MAP, true);
        return $chars;
    }

    /**
     * Add start code and stop code
     *
     * @param array $chars
     * @return array
     */
    private function addStartAndStop(array $chars)
    {
        array_unshift($chars, '(');
        array_push($chars, ')');

        return $chars;
    }

    /**
     * Convert code characters
     *
     * @param array $chars
     * @return array
     * @see https://www.post.japanpost.jp/zipcode/zipmanual/p19.html
     */
    protected function convertCodeChars(array $chars)
    {
        if (count($chars) === 0) {
            return null;
        }

        $retChars = $this->convertAlphabetToControlCode($chars);
        $retChars = $this->paddingControlCodes($retChars);
        $retChars = $this->addCheckDigit($retChars);
        $retChars = $this->addStartAndStop($retChars);

        return $retChars;
    }

    /**
     * Calculate width
     *
     * @param array $chars
     * @return int
     */
    protected function calculateWidth(array $chars)
    {
        $barCount = 0;
        foreach ($chars as $char) {
            $barCount += count(static::CONVERT_MAP[$char]);
        }
        // Decrease -1 because no trailing space
        $barSpaceCount = $barCount - 1;
        $width = ($barCount + $barSpaceCount) * $this->widthFactor;

        return $width;
    }

    /**
     * Calculate height
     *
     * @return int
     */
    protected function calculateHeight()
    {
        return $this->widthFactor * static::HEIGHT_RATE_MAP[static::TYPES_LONG];
    }

    /**
     * Calculate bar height
     *
     * @param int $bar
     * @return int
     */
    protected function calculateBarHeight($bar)
    {
        return $this->widthFactor * static::HEIGHT_RATE_MAP[$bar];
    }

    /**
     * Calculate x position of bar
     *
     * @param int $px
     * @return int
     */
    protected function calculateBarPositionX($px)
    {
        // doubled due to the width between bar
        $px += $this->widthFactor * 2;
        return $px;
    }

    /**
     * Calculate y position of bar
     *
     * @param int $bar
     * @param int|float $height
     * @return int|float
     */
    protected function calculateBarPositionY($bar, $height)
    {
        return ($bar === static::TYPES_SEMILONG_BOTTOM || $bar === static::TYPES_TIMING) ? $height / 3 : 0;
    }
}
