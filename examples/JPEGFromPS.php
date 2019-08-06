<?php 

require __DIR__ . '/../vendor/autoload.php';

use daandesmedt\PHPGhostscript\Ghostscript;
use daandesmedt\PHPGhostscript\Devices\DeviceTypes;


$ghostscript = new Ghostscript();
$ghostscript
    ->setBinaryPath('C:\Program Files\gs\gs9.27\bin\gswin64.exe')    
    ->setDevice(DeviceTypes::JPEG)
    ->setResolution(300)
    ->setAntiAliasing(Ghostscript::ANTIALIASING_HIGH)
    ->setInputFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'Helicopter.ps')
    ->setOutputFile(__DIR__ . DIRECTORY_SEPARATOR . 'output' . DIRECTORY_SEPARATOR . 'Helicopter.jpg') 
    ->setUseCie(true)
    ->setBox(Ghostscript::BOX_NONE);

// set quality
$ghostscript->getDevice()->setQuality(100);

// render
if (true === $ghostscript->render()) {
    echo 'success';
} else {
    echo 'error';
}


