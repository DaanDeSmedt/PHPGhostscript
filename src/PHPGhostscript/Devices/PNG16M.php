<?php

namespace daandesmedt\PHPGhostscript\Devices;

use daandesmedt\PHPGhostscript\Devices\Traits\DownScaleFactor;


class PNG16M extends PNG
{

    use DownScaleFactor;

    /**
     * Device output
     *
     * @var string
     */
    protected $device = 'png16m';

}