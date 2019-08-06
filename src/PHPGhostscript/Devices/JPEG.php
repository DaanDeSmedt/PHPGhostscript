<?php

namespace daandesmedt\PHPGhostscript\Devices;

class JPEG implements DeviceInterface
{

    /**
     * Device output
     *
     * @var string
     */
    protected $device = 'jpeg';
    

    /**
     * The JPEG quality
     * Set the quality level N according to the widely used IJG quality scale, which balances the extent of compression against the fidelity of the image when reconstituted. 
     * Lower values drop more information from the image to achieve higher compression, and therefore have lower quality when reconstituted.
     *
     * @var int|null
     */
    protected $quality = 100;


    /**
     * Get the name of the Ghostscript device
     *
     * @return string
     */
    public function getDevice(): string
    {
        return $this->device;
    }


    /**
     * Set the JPEG quality
     *
     * @param int $quality
     * @return self
     */
    public function setQuality(int $quality)
    {
        if ($quality > 100) {
            $quality = 100;
        }

        if ($quality < 0) {
            $quality = 0;
        }

        $this->quality = $quality;
        return $this;
    }


    /**
     * Get the command arguments
     *
     * @return array
     */
    public function getArguments(): array
    {
        return [            
            "-dJPEGQ={$this->quality}",
        ];
    }

}