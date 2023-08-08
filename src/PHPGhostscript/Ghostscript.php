<?php


/**
 * 
 * Use PHPGhostscript for rendering PDF / PostScript™ files to various image types (jpeg / png) or export to PDF with page range (single page / multipage).  
 * 
 * @author  Daan De Smedt <daan.de.smedt@hotmail.com>
 * @license  MIT
 *
 */

namespace daandesmedt\PHPGhostscript;

use mikehaertl\shellcommand\Command;
use daandesmedt\PHPGhostscript\Devices\DeviceInterface;
use daandesmedt\PHPGhostscript\Devices\DeviceTypes;
use daandesmedt\PHPGhostscript\Devices\JPEG;

class Ghostscript
{

    /**
     * ANTIALIASING constants
     */
    const ANTIALIASING_NONE = 1;
    const ANTIALIASING_LOW = 2;
    const ANTIALIASING_HIGH = 4;

    /**
     * BOX constants
     */
    const BOX_BLEED = 'bleed';
    const BOX_TRIM = 'trim';
    const BOX_ART = 'art';
    const BOX_CROP = 'crop';
    const BOX_NONE = 'none';


    /**
     * Output device.
     *
     * @var DeviceInterface
     */
    protected $device;


    /**
     * Path to the Ghostscript binary
     * 
     * @var string
     */
    private $binaryPath;

    /**
     * Resolution dpi
     *
     * @var int $resolution
     */
    protected $resolution = 72;

    /**
     * Text subsample antialiasing
     * These options control the use of subsample antialiasing. Their use is highly recommended for producing high quality rasterizations. The subsampling box size n should be 4 for optimum output, but smaller values can be used for faster rendering. Antialiasing is enabled separately for text and graphics content.
     * 
     * @var int
     */

    private $textAntiAliasing = self::ANTIALIASING_NONE;

    /**
     * Graphic subsample antialiasing
     * These options control the use of subsample antialiasing. Their use is highly recommended for producing high quality rasterizations. The subsampling box size n should be 4 for optimum output, but smaller values can be used for faster rendering. Antialiasing is enabled separately for text and graphics content.
     * 
     * @var int
     */
    private $graphicsAntiAliasing = self::ANTIALIASING_NONE;

    /**
     * Supported MIME-types (PDF / PS)
     *
     * @var array
     */
    private static $supportedMimeTypes = [
        'application/pdf',
        'application/postscript'
    ];

    /**
     * Current files
     * 
     * @var string[]
     */
    private $files = [];

    /**
     * Output file
     * 
     * @var string
     */
    private $outputFile;

    /**
     * Page start
     *
     * @var ?int
     */
    private $pageStart = null;

    /**
     * Page end
     * 
     * @var ?int
     */
    private $pageEnd = null;

    /**
     * Box mode used for rendering
     *
     * @var string|null $_useBox
     */
    private $box = null;

    /**
     * Use CIE or not
     *
     * @var boolean
     */
    private $useCie = false;


    /**
     *  Ghostscript constructor
     * 
     * @param string $binaryPath        Path to the Chrome binary
     * 
     */
    public function __construct(string $binaryPath = null)
    {
        if (isset($binaryPath)) {
            $this->setBinaryPath($binaryPath);
        }

        $this->setDevice(new JPEG);
    }


    /**
     * Set path to the Ghostscript binary
     * 
     * @param string $binaryPath
     * @return self
     */
    public function setBinaryPath(string $binaryPath) : Ghostscript
    {
        $this->binaryPath = $binaryPath;
        return $this;
    }

    /**
     * Get path to the Ghostscript binary
     * 
     * @return string $binaryPath
     */
    public function getBinaryPath() : string
    {
        return $this->binaryPath ?? "";
    }


    /**
     * Set text antialiasing
     * 
     * @param int $level
     * @return self
     */
    public function setTextAntiAliasing(int $level) : Ghostscript
    {
        if ($level === self::ANTIALIASING_NONE || $level === self::ANTIALIASING_LOW || $level === self::ANTIALIASING_HIGH) {
            $this->textAntiAliasing = $level;
        }
        return $this;
    }

    /**
     * Get text antialiasing
     * 
     * @return int
     */
    public function getTextAntiAliasing() : int
    {
        return $this->textAntiAliasing;
    }


    /**
     * Set graphic antialiasing
     * 
     * @param int $level
     * @return self
     */
    public function setGraphicsAntiAliasing(int $level) : Ghostscript
    {
        if ($level === self::ANTIALIASING_NONE || $level === self::ANTIALIASING_LOW || $level === self::ANTIALIASING_HIGH) {
            $this->graphicsAntiAliasing = $level;
        }
        return $this;
    }

    /**
     * Get graphic antialiasing
     * 
     * @return int
     */
    public function getGraphicsAntiAliasing() : int
    {
        return $this->graphicsAntiAliasing;
    }


    /**
     * Set the antialiasing level for both text and graphics.
     *
     * @param int $level
     */
    public function setAntiAliasing(int $level) : Ghostscript
    {
        $this->setTextAntiAliasing($level);
        $this->setGraphicsAntiAliasing($level);

        return $this;
    }


    /**
     * Set the output resolution.
     *
     * @param int $hdpi
     * @param int $vdpi
     * @return self
     */
    public function setResolution(int $hdpi, int $vdpi = null) : Ghostscript
    {
        if (null !== $vdpi) {
            $this->resolution = $hdpi . 'x' . $vdpi;
        } else {
            $this->resolution = $hdpi;
        }
        return $this;
    }


    /**
     * Get output resolution.
     *
     * @return int
     */
    public function getResolution()
    {
        return $this->resolution;
    }


    /**
     * Set the device
     *
     * @param DeviceInterface|string $device
     * @return self
     */
    public function setDevice($device) : Ghostscript
    {
        if (!$device instanceof DeviceInterface) {
            $classname = 'daandesmedt\\PHPGhostscript\\Devices\\' . ucfirst(strtolower($device));
            $device = new $classname();
        }
        $this->device = $device;

        return $this;
    }


    /**
     * Get device.
     *
     * @return DeviceInterface
     */
    public function getDevice() : DeviceInterface
    {
        return $this->device;
    }


    /**
     * Set UseCIEColor in the page device dictionary, remapping device-dependent color values through a Postscript defined CIE color space. 
     *
     * @param boolean $useCIE
     *
     * @return self
     */
    public function setUseCie(bool $useCie) : Ghostscript
    {
        $this->useCie = $useCie;
        return $this;
    }

    /**
     * Shall we use the CIE map for color-conversions?
     *
     * @return boolean
     */
    public function getUseCie() : bool
    {
        return $this->useCie;
    }


    /**
     * Add file to file list
     * 
     * @param string $file
     * 
     * @throws InvalidArgumentException when file is not supported (mime) or does not exist
     * @return self
     */
    public function setInputFile($file) : Ghostscript
    {
        if (!file_exists($file)) {
            throw new \InvalidArgumentException('The provided file does not exist');
        } else {
            if (!in_array(mime_content_type($file), self::$supportedMimeTypes)) {
                throw new \InvalidArgumentException('The provided file is not supported');
            }
        }

        $this->files[] = $file;
        return $this;
    }


    /**
     * Get inputfiles
     *
     * @return array
     */
    public function getInputFiles() : array
    {
        return $this->files;
    }

    /**
     * Clear inputfiles
     *
     * @return self
     */
    public function clearInputFiles() : Ghostscript
    {
        $this->files = [];
        return $this;
    }

    /**
     * Set output file
     * 
     * @param string $file
     * 
     * @return self
     */
    public function setOutputFile($file) : Ghostscript
    {
        $this->outputFile = $file;
        return $this;
    }


    /**
     * Get outputfile
     *
     * @return string
     */
    public function getOutputFile() : string
    {
        return $this->outputFile;
    }


    /**
     * Set the rendering page start
     *
     * @param int $page
     *
     * @return self
     */
    public function setPageStart(?int $page): Ghostscript
    {
        $this->pageStart = $page;
        return $this;
    }


    /**
     *  Get the rendering page start
     *
     * @return int $pageStart
     */
    public function getPageStart(): ?int
    {
        return $this->pageStart;
    }


    /**
     *  Set the rendering page end
     *
     * @param int $page
     *
     * @return self
     */
    public function setPageEnd(?int $page): Ghostscript
    {
        $this->pageEnd = $page;
        return $this;
    }


    /**
     *  Get the rendering page end
     *
     * @return int $pageEnd
     */
    public function getPageEnd(): ?int
    {
        return $this->pageEnd;
    }


    /**
     *  Set the rendering page end
     *
     * @param int $startPage
     * @param int $endPage
     *
     * @return self
     */
    public function setPages(int $startPage, int $endPage) : Ghostscript
    {
        $this->setPageStart($startPage);
        $this->setPageEnd($endPage);
        return $this;
    }


    /**
     * Set the render box mode
     * Rather than selecting a PageSize given by the PDF MediaBox use :
     *  - BleedBox
     *  - TrimBox
     *  - ArtBox
     *  - CropBox
     *
     * @param string $box
     * @return self
     */
    public function setBox(string $box)
    {
        switch (trim(strtolower($box))) {
            case self::BOX_CROP:
            case self::BOX_ART:
            case self::BOX_TRIM:
            case self::BOX_BLEED:
                $this->box = $box;
                break;
            case self::BOX_NONE:
            default:
                $this->box = null;
                break;
        }
        return $this;
    }


    /**
     *  Get the render box mode
     *
     * @return string|null
     */
    public function getBox() : ? string
    {
        return $this->box;
    }

    /**
     * Is vector device (private for argument match according device context)
     * 
     * @return boolean
     */
    private function isVectorDevice() : bool
    {
        return "pdfwrite" === $this->device->getDevice();
    }


    /**
     * Render 
     * 
     * @throws GhostscriptException when transcode was unable to complete with success
     * @return boolean
     */
    public function render()
    {
        $command = new Command($this->getBinaryPath());
        $command->addArg('-sDEVICE=' . $this->device->getDevice());
        $command->addArg('-dNOPAUSE');
        $command->addArg('-dQUIET');
        $command->addArg('-dBATCH');
        $command->addArg('-dSAFER');
        $command->addArg('-dNOPLATFONTS');
        $command->addArg('-sOutputFile=' . $this->outputFile);

        if ($this->pageStart !== null) {
            $command->addArg('-dFirstPage=' . $this->pageStart);
        }

        if ($this->pageEnd !== null) {
            $command->addArg('-dLastPage=' . $this->pageEnd);
        }

        if (!$this->isVectorDevice()) {
            $command->addArg('-dTextAlphaBits=' . $this->getTextAntiAliasing());
            $command->addArg('-dGraphicsAlphaBits=' . $this->getGraphicsAntiAliasing());
        }

        $command->addArg('-r' . $this->getResolution());

        if ($this->getUseCie() && !$this->isVectorDevice()) {
            $command->addArg('-dUseCIEColor');
        }

        if (null !== $this->getBox()) {
            $command->addArg('-dUse' . ucfirst($this->getBox()) . 'Box');
        }

        foreach ($this->device->getArguments() as $arg) {
            $command->addArg($arg);
        }

        foreach ($this->files as $file) {
            $command->addArg(' ' . $file);
        }

        if ($command->execute() && $command->getExitCode() == 0) {
            return true;
        } else {
            throw new GhostscriptException('Ghostscript was unable to transcode');
        }
    }
}
