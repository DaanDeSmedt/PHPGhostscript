<?php 

require __DIR__ . '/../vendor/autoload.php';

use daandesmedt\PHPGhostscript\Ghostscript;
use daandesmedt\PHPGhostscript\Devices\JPEG;
use daandesmedt\PHPGhostscript\Devices\DeviceTypes;
use daandesmedt\PHPGhostscript\Devices\JPEGGrey;
use daandesmedt\PHPGhostscript\Devices\PNG;

$device  = new JPEG();
$device->setQuality(100);

$ghostscript = new Ghostscript();
// or $ghostscript = new Ghostscript($device, __DIR__ . '/..' . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'gs927w64.exe');

$ghostscript

    // Set binary path to Ghostscript
    ->setBinaryPath('C:\Program Files\gs\gs9.27\bin\gswin64.exe')
    
    // Set device type
    ->setDevice(DeviceTypes::JPEG)
    // ->setDevice(DeviceTypes::JPEG_GREY)
    // ->setDevice(DeviceTypes::PNG4)
    // ->setDevice(DeviceTypes::PNG_256)
    // ->setDevice(DeviceTypes::PNG_16M)
    // ->setDevice(DeviceTypes::PNG_ALPHA)
    // ->setDevice(DeviceTypes::PNG_GREY)
    // ->setDevice(DeviceTypes::PNG_MONO)
    // ->setDevice(DeviceTypes::PNG_MONO_D)
    // ->setDevice($this->$device)

    ->setResolution(300)

    // Antialiasing
    ->setAntiAliasing(Ghostscript::ANTIALIASING_HIGH)
    // ->setTextAntiAliasing(Ghostscript::ANTIALIASING_HIGH)
    // ->setGraphicsAntiAliasing(Ghostscript::ANTIALIASING_HIGH)

    // set input & output file
    ->setInputFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'MultiPageHorizontal.pdf')
    ->setOutputFile(__DIR__ . DIRECTORY_SEPARATOR . 'output' . DIRECTORY_SEPARATOR . 'MultiPageHorizontal-%01d.png')

    // Set page range
    // ->setPages(1, 3)
    ->setPageStart(1)
    ->setPageEnd(1)

    // Use CIE
    ->setUseCie(true)

    // Set BOX
    // ->setBox(Ghostscript::BOX_ART)
    // ->setBox(Ghostscript::BOX_BLEED)
    // ->setBox(Ghostscript::BOX_CROP)
    // ->setBox(Ghostscript::BOX_TRIM);
    ->setBox(Ghostscript::BOX_NONE);

// set quality (JPEG / JPEG GREY)
$ghostscript->getDevice()->setQuality(100);

// set background color (PNG ALPHA)
// $ghostscript->getDevice()->setBackgroundColor('#CCCC00');
// $ghostscript->getDevice()->setBackgroundColor('#CCCC00');

// set downscale (PNG 16M / PNG ALPHA)
// $ghostscript->getDevice()->setDownScaleFactor(1);

// set min feature size (PNG MONO D)
// $ghostscript->getDevice()->setMinFeatureSize(0);

// render
if (true === $ghostscript->render()) {
    echo 'success';
} else {
    echo 'error';
}