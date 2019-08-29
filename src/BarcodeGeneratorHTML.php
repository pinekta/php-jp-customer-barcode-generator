<?php

namespace Pinekta\JPCustomerBarcode;

use Pinekta\JPCustomerBarcode\BarcodeGenerator;

/**
 * A Japan post customer barcode generator for HTML
 *
 * @access public
 * @author @pinekta <h03a081b@gmail.com>
 * @copyright @pinekta All Rights Reserved
 */
class BarcodeGeneratorHTML extends BarcodeGenerator
{
    /**
     * Create barcode
     *
     * @param array $convertedChars
     * @return string
     */
    protected function createBarcode(array $convertedChars)
    {
        $width = $this->calculateWidth($convertedChars);
        $height = $this->calculateHeight();

        $html = '<div style="font-size:0;position:relative;width:' . $width . 'px;height:' . $height . 'px;">' . "\n";

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
                $html .= '<div style="background-color:' . $this->color . ';width:' . $this->widthFactor . 'px;height:' . $barHeight . 'px;position:absolute;left:' . $px . 'px;top:' . $this->calculateBarPositionY($bar, $height) . 'px;">&nbsp;</div>' . "\n";
                $px = $this->calculateBarPositionX($px);
            }
        }

        $html .= '</div>' . "\n";

        return $html;
    }
}
