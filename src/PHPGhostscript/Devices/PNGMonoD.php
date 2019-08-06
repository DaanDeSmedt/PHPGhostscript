<?php

namespace daandesmedt\PHPGhostscript\Devices;

/**
 * black-and-white device, but the output is formed from an internal 8 bit grayscale rendering which is then error diffused and converted down to 1bpp
 */
class PNGMonoD extends PNG
{

    /**
     * Device output
     *
     * @var string
     */
    protected $device = 'pngmonod';

    /**
     * This option allows a minimum feature size to be set; 
     * if any output pixel appears on its own, or as part of a group of pixels smaller than MinFeatureSize x MinFeatureSize, it will be expanded to ensure that it does. 
     * This is useful for output devices that are high resolution, but that have trouble rendering isolated pixels. 
     * While this parameter will accept values from 0 to 4, not all are fully implemented. 0 and 1 cause no change to the output (as expected). 2 works as specified. Values of 3 and 4 are accepted for compatibility, but behave as for 2.
     *
     * @var int
     */
    protected $minFeatureSize;


    /**
     * Get the minimum feature size
     *
     * @return self
     */
    public function setMinFeatureSize(int $size) : PNGMonoD
    {
        if ($size > 4) {
            $size = 4;
        }

        if ($size < 0) {
            $size = 0;
        }

        $this->minFeatureSize = $size;
        return $this;
    }


    /**
     * Get the minimum feature size
     *
     * @return string
     */
    public function getMinFeatureSize() : int
    {
        return $this->downScaleFactor;
    }


    /**
     * Get the command arguments
     *
     * @return array
     */
    public function getArguments(): array
    {
        if(isset($this->minFeatureSize)){
            return array_merge(
                [
                    "-dMinFeatureSize={$this->minFeatureSize}"
                ],
                parent::getArguments()
            ); 
        }
        return parent::getArguments();
        
    }

}