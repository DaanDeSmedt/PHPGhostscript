<?php

namespace daandesmedt\Tests\PHPGhostscript;

use PHPUnit\Framework\TestCase;
use daandesmedt\PHPGhostscript\Ghostscript;
use daandesmedt\PHPGhostscript\Devices\DeviceTypes;
use daandesmedt\PHPGhostscript\Devices\PDF;
use daandesmedt\PHPGhostscript\Devices\PNG16M;

final class PHPGhostscriptTest extends TestCase
{

    // protected $binaryPath = 'C:\Program Files\gs\gs9.27\bin\gswin64.exe';
    protected $binaryPath = '/usr/bin/gs';

    public function testSetBinaryPath()
    {
        // use setter
        $ghostscript = new Ghostscript();
        $this->assertEquals("", $ghostscript->getBinaryPath());
        $ghostscript->setBinaryPath($this->binaryPath);
        $this->assertEquals($this->binaryPath, $ghostscript->getBinaryPath());
        // set in constructor
        $ghostscript = new Ghostscript($this->binaryPath);
        $this->assertEquals($this->binaryPath, $ghostscript->getBinaryPath());
    }

    public function testSetAntiAliasing()
    {
        $ghostscript = new Ghostscript();
        $ghostscript->setAntiAliasing(GhostSCript::ANTIALIASING_NONE);
        $this->assertEquals(GhostSCript::ANTIALIASING_NONE, $ghostscript->getTextAntiAliasing());
        $this->assertEquals(GhostSCript::ANTIALIASING_NONE, $ghostscript->getGraphicsAntiAliasing());
        $ghostscript->setGraphicsAntiAliasing(GhostSCript::ANTIALIASING_HIGH);
        $ghostscript->setTextAntiAliasing(GhostSCript::ANTIALIASING_HIGH);
        $this->assertEquals(GhostSCript::ANTIALIASING_HIGH, $ghostscript->getTextAntiAliasing());
        $this->assertEquals(GhostSCript::ANTIALIASING_HIGH, $ghostscript->getGraphicsAntiAliasing());
        $ghostscript->setAntiAliasing(9999); // unsupported value
        $this->assertEquals(GhostSCript::ANTIALIASING_HIGH, $ghostscript->getTextAntiAliasing());
        $this->assertEquals(GhostSCript::ANTIALIASING_HIGH, $ghostscript->getGraphicsAntiAliasing());
    }

    public function testSetResolution()
    {
        $ghostscript = new Ghostscript();
        $this->assertEquals(72, $ghostscript->getResolution());
        $ghostscript->setResolution(300);
        $this->assertEquals(300, $ghostscript->getResolution());
        $ghostscript->setResolution(72, 144);
        $this->assertEquals('72x144', $ghostscript->getResolution());
    }

    public function testDefaultDevice()
    {
        $ghostscript = new Ghostscript();
        $this->assertInstanceOf('daandesmedt\PHPGhostscript\Devices\JPEG', $ghostscript->getDevice());
    }

    public function testSetDevice()
    {
        $ghostscript = new Ghostscript();
        $ghostscript->setDevice(DeviceTypes::JPEG);
        $this->assertInstanceOf('daandesmedt\PHPGhostscript\Devices\JPEG', $ghostscript->getDevice());
        $ghostscript->setDevice(new PDF());
        $this->assertInstanceOf('daandesmedt\PHPGhostscript\Devices\PDF', $ghostscript->getDevice());
        $ghostscript->setDevice(new PNG16M());
        $this->assertInstanceOf('daandesmedt\PHPGhostscript\Devices\PNG16M', $ghostscript->getDevice());
    }

    public function testUseCie()
    {
        $ghostscript = new Ghostscript();
        $this->assertFalse($ghostscript->getUseCie());
        $ghostscript->setUseCie(true);
        $this->assertTrue($ghostscript->getUseCie());
        $ghostscript->setUseCie(false);
        $this->assertFalse($ghostscript->getUseCie());
    }

    public function testUnsupportedInputFile()
    {
        $ghostscript = new Ghostscript();
        $this->expectException(\InvalidArgumentException::class);
        $ghostscript->setInputFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'notsupported.txt');
    }

    public function testInexistentInputFile()
    {
        $ghostscript = new Ghostscript();
        $this->expectException(\InvalidArgumentException::class);
        $ghostscript->setInputFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'doesnotexist.ps');
    }

    public function testInputFile()
    {
        $ghostscript = new Ghostscript();
        $ghostscript->setInputFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'Helicopter.ps');
        $this->assertEquals([__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'Helicopter.ps'], $ghostscript->getInputFile());
    }

    public function testOutputFile()
    {
        $ghostscript = new Ghostscript();
        $ghostscript->setOutputFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'Helicopter.ps');
        $this->assertEquals(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'Helicopter.ps', $ghostscript->getOutputFile());
    }

    public function testSettingPages()
    {
        $ghostscript = new Ghostscript();

        $this->assertEquals(null, $ghostscript->getPageStart());
        $ghostscript->setPageStart(20);
        $this->assertEquals(20, $ghostscript->getPageStart());

        $this->assertEquals(null, $ghostscript->getPageEnd());
        $ghostscript->setPageEnd(99);
        $this->assertEquals(99, $ghostscript->getPageEnd());

        $ghostscript->setPages(44, 88);
        $this->assertEquals(44, $ghostscript->getPageStart());
        $this->assertEquals(88, $ghostscript->getPageEnd());
    }

    public function testSetBox()
    {
        $ghostscript = new Ghostscript();
        $this->assertNull($ghostscript->getBox());
        $ghostscript->setBox(Ghostscript::BOX_CROP);
        $this->assertEquals(Ghostscript::BOX_CROP, $ghostscript->getBox());
        $ghostscript->setBox(Ghostscript::BOX_ART);
        $this->assertEquals(Ghostscript::BOX_ART, $ghostscript->getBox());
        $ghostscript->setBox(Ghostscript::BOX_ART);
        $this->assertEquals(Ghostscript::BOX_ART, $ghostscript->getBox());
        $ghostscript->setBox(Ghostscript::BOX_BLEED);
        $this->assertEquals(Ghostscript::BOX_BLEED, $ghostscript->getBox());
        $ghostscript->setBox(Ghostscript::BOX_NONE);
        $this->assertNull($ghostscript->getBox());
        $ghostscript->setBox('BOX_NOT_HERE');
        $this->assertNull($ghostscript->getBox());
    }

    public function testRender()
    {
        $outputFile = __DIR__ . DIRECTORY_SEPARATOR . 'output' . DIRECTORY_SEPARATOR . 'export.jpg';
        $ghostscript = new Ghostscript();
        $ghostscript
            ->setBinaryPath($this->binaryPath)
            ->setInputFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'MultiPageHorizontal.pdf')
            ->setOutputFile($outputFile);
        $result = $ghostscript->render();
        $this->assertTrue(file_exists($outputFile));
        $this->assertEquals("image/jpeg", mime_content_type($outputFile));
        $this->assertTrue($result);
    }

    public function testMerging()
    {
        $outputFile = __DIR__ . DIRECTORY_SEPARATOR . 'output' . DIRECTORY_SEPARATOR . 'merge.pdf';
        $ghostscript = new Ghostscript();
        $ghostscript
            ->setBinaryPath($this->binaryPath)
            ->setDevice(DeviceTypes::PDF)
            ->setInputFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'lorem.pdf')
            ->setInputFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'ipsum.pdf')
            ->setOutputFile($outputFile);
        $result = $ghostscript->render();
        $this->assertTrue(file_exists($outputFile));
        $this->assertEquals("application/pdf", mime_content_type($outputFile));
        $this->assertTrue($result);
    }

    public function testMerging2()
    {
        $outputFile = __DIR__ . DIRECTORY_SEPARATOR . 'output' . DIRECTORY_SEPARATOR . 'merge2.pdf';
        $ghostscript = new Ghostscript();
        $ghostscript
            ->setBinaryPath($this->binaryPath)
            ->setDevice(DeviceTypes::PDF)
            ->setInputFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'a2.pdf')
            ->setInputFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'b1.pdf')
            ->setOutputFile($outputFile);
        $result = $ghostscript->render();
        $this->assertTrue(file_exists($outputFile));
        $this->assertEquals("application/pdf", mime_content_type($outputFile));
        $this->assertTrue($result);
    }
}
