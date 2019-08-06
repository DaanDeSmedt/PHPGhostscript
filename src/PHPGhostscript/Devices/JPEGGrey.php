<?php

namespace daandesmedt\PHPGhostscript\Devices;

class JPEGGrey extends JPEG
{

    /**
     * Device ouput
     *
     * @var string
     */
    protected $device = 'jpeggray';


    /**
     * Get the name of the Ghostscript device
     *
     * @return string
     */
    public function getDevice() : string
    {
        return $this->device;
    }

}