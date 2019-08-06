<?php

namespace daandesmedt\PHPGhostscript\Devices\Traits;


trait DownScaleFactor
{

    /**
     * Downscale factor.
     *
     * @var int
     */
    protected $downScaleFactor = 1;


    /**
     * Set the downscale factor
     *
     * @param int $factor
     */
    public function setDownScaleFactor(int $factor)
    {
        if ($factor > 8) {
            $factor = 8;
        }

        if ($factor < 1) {
            $factor = 1;
        }

        $this->downScaleFactor = $factor;
    }


    /**
     * Get the downscale factor
     *
     * @return int
     */
    public function getDownScaleFactor() : int
    {
        return $this->downScaleFactor;
    }

}