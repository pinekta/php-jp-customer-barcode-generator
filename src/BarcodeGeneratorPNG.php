<?php

namespace Pinekta\JPCustomerBarcode;

use Pinekta\JPCustomerBarcode\BarcodeGenerator;
use Pinekta\JPCustomerBarcode\Exceptions\BarcodeException;

/**
 * A Japan post customer barcode generator for PNG
 *
 * @access public
 * @author @pinekta <h03a081b@gmail.com>
 * @copyright @pinekta All Rights Reserved
 */
class BarcodeGeneratorPNG extends BarcodeGenerator
{
    /**
     * Create barcode
     *
     * @param array $convertedChars
     * @return mixed
     *
     * @todo The color is fixed to black
     */
    protected function createBarcode(array $convertedChars)
    {
        $width = $this->calculateWidth($convertedChars);
        $height = $this->calculateHeight();
        $useImageLibrary = $this->resolveImageLibrary();
        $color = [0, 0, 0];

        if ($useImageLibrary === static::IMAGE_LIBRARY_TYPES_GD) {
            // GD library
            $imagick = false;
            $png = imagecreate($width, $height);
            $colorBackground = imagecolorallocate($png, 255, 255, 255);
            imagecolortransparent($png, $colorBackground);
            $colorForeground = imagecolorallocate($png, $color[0], $color[1], $color[2]);
        } elseif ($useImageLibrary === static::IMAGE_LIBRARY_TYPES_IMAGEMAGICK) {
            $imagick = true;
            $colorForeground = new \imagickpixel('rgb(' . $color[0] . ',' . $color[1] . ',' . $color[2] . ')');
            $png = new \Imagick();
            $png->newImage($width, $height, 'none', 'png');
            $imageMagickObject = new \imagickdraw();
            $imageMagickObject->setFillColor($colorForeground);
        }

        // print bars
        $px = 0;
        foreach ($convertedChars as $char) {
            $bars = static::CONVERT_MAP[$char];
            foreach ($bars as $bar) {
                if ($bar === static::TYPES_NONE) {
                    $px = $this->calculateBarPositionX($px);
                    continue;
                }

                $barHeight = $this->calculateBarHeight($bar);
                $py = $this->calculateBarPositionY($bar, $height);
                if ($imagick && isset($imageMagickObject)) {
                    $imageMagickObject->rectangle($px, $py, $this->widthFactor, $barHeight);
                } else {
                    imagefilledrectangle($png, $px, $py, ($px + $this->widthFactor) - 1, ($py + $barHeight) - 1, $colorForeground);
                }
                $px = $this->calculateBarPositionX($px);
            }
        }

        ob_start();
        if ($imagick && isset($imageMagickObject)) {
            $png->drawImage($imageMagickObject);
            echo $png;
        } else {
            imagepng($png);
            imagedestroy($png);
        }
        $image = ob_get_clean();

        return $image;
    }
}
