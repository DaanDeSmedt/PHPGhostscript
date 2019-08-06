<?php

namespace daandesmedt\PHPGhostscript\Devices;

use daandesmedt\PHPGhostscript\Devices\Traits\BackgroundColor;
use daandesmedt\PHPGhostscript\Devices\Traits\DownScaleFactor;



/**
 * The pngalpha device is 32-bit RGBA color with transparency indicating pixel coverage. 
 * The background is transparent unless it has been explicitly filled. 
 * PDF 1.4 transparent files do not give a transparent background with this device. 
 * Text and graphics anti-aliasing are enabled by default.
 */
class PNGAlpha extends PNG
{

    use DownScaleFactor;

    /**
     * Device output
     *
     * @var string
     */
    protected $device = 'pngalpha';


    /**
     * Backgroundcolor
     *
     * @var string
     */
    protected $backgroundColor;


    /**
     * Set the PNG background color
     *
     * @throws InvalidArgumentException invalid HEX value
     * @param int $quality
     * @return self
     */
    public function setBackgroundColor(string $color)
    {
        $color = ltrim($color, '#');
        if (!preg_match('/^[a-fA-F0-9]{6}$/', $color)) {
            throw new \InvalidArgumentException('Invalid HEX color value, expected format of #......');
        }
        
        $this->backgroundColor = '16#' . $color;
        return $this;
    }


    /**
     * Get the background color
     *
     * @return string
     */
    public function getBackgroundColor() : string
    {
        return $this->downScaleFactor;
    }

}