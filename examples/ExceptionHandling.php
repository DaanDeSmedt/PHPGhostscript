<?php 

require __DIR__ . '/../vendor/autoload.php';

use daandesmedt\PHPGhostscript\Ghostscript;
use daandesmedt\PHPGhostscript\Devices\JPEG;
use daandesmedt\PHPGhostscript\Devices\DeviceTypes;
use daandesmedt\PHPGhostscript\Devices\JPEGGrey;
use daandesmedt\PHPGhostscript\Devices\PNG;
use daandesmedt\PHPGhostscript\GhostscriptException;


$ghostscript = new Ghostscript();
try {
    $ghostscript
        ->setBinaryPath('C:\Program Files\gs\gs9.27\bin\gswin64.exe')
        ->setDevice(DeviceTypes::JPEG)
        // Force excetion - invalid file ; supports only for PDF & PS
        ->setInputFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'invalidfile.docx')
        ->setOutputFile(__DIR__ . DIRECTORY_SEPARATOR . 'output' . DIRECTORY_SEPARATOR . 'export.jpg');
    // render
    if (true === $ghostscript->render()) {
        echo 'success';
    } else {
        echo 'error';
    }
} catch (InvalidArgumentException $e) {
    var_dump($e->getMessage());
} catch (GhostscriptException $e) {
    var_dump($e->getMessage());
}