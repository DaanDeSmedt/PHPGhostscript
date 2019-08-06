PHPGhostscript
===============

A PHP wrapper for [Ghostscript](https://www.ghostscript.com) ; an interpreter for PostScript™ and Portable Document Format (PDF) files.

Use `PHPGhostscript` for rendering PDF / PostScript™ files to various image types (jpeg / png) or export to PDF with page range (single page / multipage). 
Easy to use and OOP interfaced.


## Installation

Install the package through [composer](http://getcomposer.org):

```
composer require daandesmedt/phpghostscript
```

Make sure, that you include the composer [autoloader](https://getcomposer.org/doc/01-basic-usage.md#autoloading) somewhere in your codebase.


## Usage

Use `PHPGhostscript` for rendering PDF / PostScript™ files to various image types (jpeg / png) or export to PDF with page range (single page / multipage). 


## Working examples

Working examples can be found in the `examples` folder.


## Supported devices

`PHPGhostscript` output devices implement `DeviceInterface`.

`PHPGhostscript` supports common output devices:

* **JPEG file format**
  * JPEG
  * JPEG Grey
* **PNG file format**
  * PNG
  * PNG 16
  * PNG 256
  * PNG 16M
  * PNG Alpha
  * PNG Grey
  * PNG Mono
  * PNG MonoD
* **PDF file format**




## Specify a output device

Set output decive through `DeviceTypes` constant.

```php
use daandesmedt\PHPGhostscript\Devices\DeviceTypes;
use daandesmedt\PHPGhostscript\Devices\JPEG;


$ghostscript = new Ghostscript();
// JPEG
$ghostscript->setDevice(DeviceTypes::JPEG);
$ghostscript->setDevice(DeviceTypes::JPEG_GREY);
// PNG
$ghostscript->setDevice(DeviceTypes::PNG4);
$ghostscript->setDevice(DeviceTypes::PNG_256);
$ghostscript->setDevice(DeviceTypes::PNG_16M);
$ghostscript->setDevice(DeviceTypes::PNG_ALPHA);
$ghostscript->setDevice(DeviceTypes::PNG_GREY);
$ghostscript->setDevice(DeviceTypes::PNG_MONO);
$ghostscript->setDevice(DeviceTypes::PNG_MONO_D);
// PDF
$ghostscript->setDevice(DeviceTypes::PDF);
```

OR set with instanceof `DeviceInterface` 

```php
// or create device    
$device  = new JPEG();

// set device
$ghostscript->setDevice($device);
```

## Device specific parameters


| Device output type	|   Function     |
| --- | --- |
| JPEG / JPEG GREY        | `setQuality(int $quality)` |
| PNG ALPHA        | `setBackgroundColor(string $color)` |
|        | `setDownScaleFactor(int $factor)` |
| PNG MONO D        | `setMinFeatureSize(int $size)` |
| PNG 16M | `setDownScaleFactor(int $factor)` |


Example :

```php
// Create JPEG device    
$device  = new JPEG();
// set JPEG device specific quality
$device->setQuality(100);
```

## PDF (single page) / PostScript™ to Image

```php
require __DIR__ . '/../vendor/autoload.php';

use daandesmedt\PHPGhostscript\Ghostscript;
use daandesmedt\PHPGhostscript\Devices\JPEG;
use daandesmedt\PHPGhostscript\Devices\DeviceTypes;
use daandesmedt\PHPGhostscript\Devices\JPEGGrey;
use daandesmedt\PHPGhostscript\Devices\PNG;

$ghostscript = new Ghostscript();
$ghostscript
    // set Ghostscript binary path
    ->setBinaryPath('C:\Program Files\gs\gs9.27\bin\gswin64.exe')
    
    // set output device 
    ->setDevice(DeviceTypes::JPEG)
    
    // set input & output file
    ->setInputFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'SinglePageHorizontal.pdf')
    ->setOutputFile(__DIR__ . DIRECTORY_SEPARATOR . 'output' . DIRECTORY_SEPARATOR . 'SinglePageHorizontal.jpg');

// render
if (true === $ghostscript->render()) {
    echo 'success';
} else {
    echo 'error';
}
```

## Single output file per page

Export multi-page PDF files to seperate image files using the ``
Specifying a single output file works fine for printing and rasterizing figures, but sometimes you want images of each page of a multi-page document. You can tell `PHPGhostscript` to put each page of the input file in a series of similarly named files. To do this place a template `%d` in the `setOutputFile` setter (`%d` will be replaced by the matching page number).

```php
// file produce output files as : 'export-1.jpg', ... 'export-10.jpg', ... 
$ghostscript->setOutputFile(__DIR__ . DIRECTORY_SEPARATOR . 'output' . DIRECTORY_SEPARATOR . 'export-%01d.jpg');

// file produce output files as : 'export-001.jpg', ... 'export-010.jpg', ... 
$ghostscript->setOutputFile(__DIR__ . DIRECTORY_SEPARATOR . 'output' . DIRECTORY_SEPARATOR . 'export-%03d.jpg');
```


## Specify page range

```php
$ghostscript->setPages(int $startPage, int $endPage);
```

or set start and end explicitly


```php
$ghostscript->setPageStart(int $page);
$ghostscript->setPageEnd(int $page);
```


## Subsample antialiasing

These options control the use of subsample antialiasing. Their use is highly recommended for producing high quality rasterizations of the input files. 
Use `Ghostscript::ANTIALIASING_HIGH` for optimum output, user `Ghostscript::ANTIALIASING_LOW` or `Ghostscript::ANTIALIASING_NONE` for faster rendering. Antialiasing can be set separately for text and graphics content, but only for image type output devices.

```php
$ghostscript->setAntiAliasing(
    Ghostscript::ANTIALIASING_HIGH || 
    Ghostscript::ANTIALIASING_LOW ||
    Ghostscript::ANTIALIASING_NONE
);
```

or set text and graphics antialiasing explicitly

```php
$ghostscript->setTextAntiAliasing(
    Ghostscript::ANTIALIASING_HIGH ||
    Ghostscript::ANTIALIASING_LOW  ||
    Ghostscript::ANTIALIASING_NONE
);

$ghostscript->setGraphicsAntiAliasing(
    Ghostscript::ANTIALIASING_HIGH || 
    Ghostscript::ANTIALIASING_LOW  ||
    Ghostscript::ANTIALIASING_NONE
);
```


## Output resolution

This option sets the resolution of the output file in dots per inch. The default value if you don't specify this options is 72 dpi. Support for specifying horizontal and vertical resolution.

```php
$ghostscript->setResolution(int $hdpi, int $vdpi = null);
```   


## Setting CIE Color

Set UseCIEColor in the page device dictionary, remapping device-dependent color values through a Postscript defined CIE color space. Document DeviceGray, DeviceRGB and DeviceCMYK source colors will be substituted respectively by Postscript CIEA, CIEABC and CIEDEFG color spaces. Only for image type output devices.

```php
$ghostscript->setUseCie(bool $useCie);
```   


## Setting page content region

Sets the page size to one of the following : 

* **BleedBox** (``Ghostscript::BOX_BLEED``) : defines the region to which the contents of the page should be clipped when output in a production environment. This may include any extra bleed area needed to accommodate the physical limitations of cutting, folding, and trimming equipment. The actual printed page may include printing marks that fall outside the bleed box.
* **TrimBox** (``Ghostscript::BOX_TRIM``) : defines the intended dimensions of the finished page after trimming. Some files have a TrimBox that is smaller than the MediaBox and may include white space, registration or cutting marks outside the CropBox. Using this option simulates appearance of the finished printed page.
* **ArtBox** (``Ghostscript::BOX_ART``) : defines the extent of the page's meaningful content (including potential white space) as intended by the page's creator. The art box is likely to be the smallest box. It can be useful when one wants to crop the page as much as possible without losing the content.
* **CropBox** (``Ghostscript::BOX_CROP``) : Unlike the other "page boundary" boxes, CropBox does not have a defined meaning, it simply provides a rectangle to which the page contents will be clipped (cropped). By convention, it is often, but not exclusively, used to aid the positioning of content on the (usually larger, in these cases) media.
* **NONE** (``Ghostscript::BOX_NONE``)

```php
$ghostscript->setBox(
    Ghostscript::BOX_BLEED,
    Ghostscript::BOX_TRIM,
    Ghostscript::BOX_ART,
    Ghostscript::BOX_CROP,
    Ghostscript::BOX_NONE
);
```   


## Handling exceptions

`PHPGhostscript` will throw following exceptions :

*   `InvalidArgumentException` : thrown if an argument is not of the expected type.
*   `GhostscriptException` : thrown if Ghostscript was unable to transcode.


```php
$ghostscript = new Ghostscript();
try {
    $ghostscript
        ->setBinaryPath('C:\Program Files\gs\gs9.27\bin\gswin64.exe')
        ->setDevice(DeviceTypes::JPEG)
        // Force excetion - invalid file ; supports only for PDF & PS
        ->setInputFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'invalidfile.docx')
        ->setOutputFile(__DIR__ . DIRECTORY_SEPARATOR . 'output' . DIRECTORY_SEPARATOR . 'export.jpg');
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
```