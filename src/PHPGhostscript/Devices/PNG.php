<?php

namespace daandesmedt\PHPGhostscript\Devices;

class PNG implements DeviceInterface
{

    /**
     * Device output
     *
     * @var string
     */
    protected $device = 'png';
    

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
        $arguments = array();
        
        if (isset($this->backgroundColor)) {
            $arguments[] = "-dBackgroundColor={$this->backgroundColor}";
        }

        if (isset($this->downScaleFactor)) {
            $arguments[] = "-dDownScaleFactor={$this->downScaleFactor}";
        }
        
        return $arguments;
  
    }

}