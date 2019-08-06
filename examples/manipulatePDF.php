<?php 

require __DIR__ . '/../vendor/autoload.php';

use daandesmedt\PHPGhostscript\Ghostscript;
use daandesmedt\PHPGhostscript\Devices\DeviceTypes;


$ghostscript = new Ghostscript();
$ghostscript
    ->setBinaryPath('C:\Program Files\gs\gs9.27\bin\gswin64.exe')

    ->setDevice(DeviceTypes::PDF)
    ->setResolution(300)

    ->setOutputFile(__DIR__ . DIRECTORY_SEPARATOR . 'output' . DIRECTORY_SEPARATOR . 'pdf_%2d.pdf')
    ->setInputFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'MultiPageHorizontal.pdf')

    ->setPageStart(2)
    ->setPageEnd(3)

    // ->setBox(Ghostscript::BOX_ART)
    // ->setBox(Ghostscript::BOX_BLEED);
    // ->setBox(Ghostscript::BOX_CROP)
    // ->setBox(Ghostscript::BOX_TRIM);
    ->setBox(Ghostscript::BOX_NONE);

// render
if (true === $ghostscript->render()) {
    echo 'success';
} else {
    echo 'error';
}