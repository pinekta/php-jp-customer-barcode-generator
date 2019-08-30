<?php

namespace Pinekta\JPCustomerBarcode;

use Pinekta\JPCustomerBarcode\BarcodeGenerator;
use Pinekta\JPCustomerBarcode\Exceptions\BarcodeException;

/**
 * A Japan post customer barcode generator for JPG
 *
 * @access public
 * @author @pinekta <h03a081b@gmail.com>
 * @copyright @pinekta All Rights Reserved
 */
class BarcodeGeneratorJPG extends BarcodeGenerator
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
        $color = [0, 0, 0];

        if (function_exists('imagecreate')) {
            // GD library
            $imagick = false;
            $jpg = imagecreate($width, $height);
            $colorBackground = imagecolorallocate($jpg, 255, 255, 255);
            imagecolortransparent($jpg, $colorBackground);
            $colorForeground = imagecolorallocate($jpg, $color[0], $color[1], $color[2]);
        } elseif (extension_loaded('imagick')) {
            $imagick = true;
            $colorForeground = new \imagickpixel('rgb(' . $color[0] . ',' . $color[1] . ',' . $color[2] . ')');
            $jpg = new \Imagick();
            $jpg->newImage($width, $height, 'white', 'jpg');
            $imageMagickObject = new \imagickdraw();
            $imageMagickObject->setFillColor($colorForeground);
        } else {
            throw new BarcodeException('Neither gd-lib or imagick are installed!');
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
                    imagefilledrectangle($jpg, $px, $py, ($px + $this->widthFactor) - 1, ($py + $barHeight) - 1, $colorForeground);
                }
                $px = $this->calculateBarPositionX($px);
            }
        }

        ob_start();
        if ($imagick && isset($imageMagickObject)) {
            $jpg->drawImage($imageMagickObject);
            echo $jpg;
        } else {
            imagejpeg($jpg);
            imagedestroy($jpg);
        }
        $image = ob_get_clean();

        return $image;
    }
}
