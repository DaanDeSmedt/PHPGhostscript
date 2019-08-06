<?php

namespace daandesmedt\PHPGhostscript\Devices;

class PDF implements DeviceInterface
{

    /**
     * Device output
     *
     * @var string
     */
    protected $device = 'pdfwrite';


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
     * Get the command arguments
     *
     * @return array
     */
    public function getArguments(): array
    {
        return [];
    }

}