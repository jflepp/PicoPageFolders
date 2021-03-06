<?php

use PHPUnit\Framework\TestCase;
use PicoPageFolders\Managers\SpecialPageManager;

class SpecialPageManagerTest extends TestCase {

    public function test_isIndex_Index_ReturnsTrue() {
        $testee = new SpecialPageManager(array(), $this);

        $result = $testee->isIndex('index/de');

        $this->assertTrue($result);
    }

    public function test_isIndex_NonSpecialPage_ReturnsFalse() {
        $testee = new SpecialPageManager(array(), $this);

        $result = $testee->isIndex('test/de');

        $this->assertFalse($result);
    }

    public function test_is404_404_ReturnsTrue() {
        $testee = new SpecialPageManager(array(), $this);

        $result = $testee->is404('404/de');

        $this->assertTrue($result);
    }

    public function test_is404_NonSpecialPage_ReturnsFalse() {
        $testee = new SpecialPageManager(array(), $this);

        $result = $testee->is404('test/de');

        $this->assertFalse($result);
    }

    public function test_load404_ContentConfigSetInConstructor_SetsContent() {
        $fs = $this->createMock(\PicoPageFolders\Wrappers\FileSystemWrapper::class);
        $fs->method('exists')
           ->willReturn(true)
           ->with('C:\testpage/404/de.html');
        $fs->method('readFile')
           ->willReturn('sorry')
           ->with('C:\testpage/404/de.html');
        $testee = new SpecialPageManager(array('content_dir' => 'C:\testpage', 'content_ext' => '.html'), $fs);

        $testee->load404($result, 'de');

        $this->assertSame('sorry', $result);
    }

    public function test_load404_FileNotExists_DoesNothing() {
        $fs = $this->createMock(\PicoPageFolders\Wrappers\FileSystemWrapper::class);
        $fs->method('exists')
           ->willReturn(false)
           ->with('C:\testpage/404/de.html');
        $result = 'untouched';
        $testee = new SpecialPageManager(array('content_dir' => 'C:\testpage', 'content_ext' => '.html'), $fs);

        $testee->load404($result, 'de');

        $this->assertSame('untouched', $result);
    }
    
}