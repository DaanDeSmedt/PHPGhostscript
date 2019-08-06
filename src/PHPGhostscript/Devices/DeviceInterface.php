<?php

namespace daandesmedt\PHPGhostscript\Devices;

interface DeviceInterface
{
    public function getDevice(): string;
    public function getArguments(): array;
}