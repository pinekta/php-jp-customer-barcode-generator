<?php

namespace Pinekta\JPCustomerBarcode;

use Pinekta\JPCustomerBarcode\BarcodeGenerator;

/**
 * A Japan post customer barcode generator for SVG
 *
 * @access public
 * @author @pinekta <h03a081b@gmail.com>
 * @copyright @pinekta All Rights Reserved
 */
class BarcodeGeneratorSVG extends BarcodeGenerator
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

        // replace table for special characters
        $repstr = ["\0" => '', '&' => '&amp;', '<' => '&lt;', '>' => '&gt;'];

        $svg = '<?xml version="1.0" standalone="no" ?>' . "\n";
        $svg .= '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">' . "\n";
        $svg .= '<svg width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '" version="1.1" xmlns="http://www.w3.org/2000/svg">' . "\n";
        $svg .= "\t" . '<desc>' . strtr(implode('', $convertedChars), $repstr) . '</desc>' . "\n";
        $svg .= "\t" . '<g id="bars" fill="' . $this->color . '" stroke="none">' . "\n";

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
                $svg .= "\t\t" . '<rect x="' . $px . '" y="' . $this->calculateBarPositionY($bar, $height) . '" width="' . $this->widthFactor . '" height="' . $barHeight . '" />' . "\n";
                $px = $this->calculateBarPositionX($px);
            }
        }

        $svg .= "\t" . '</g>' . "\n";
        $svg .= '</svg>' . "\n";

        return $svg;
    }
}
