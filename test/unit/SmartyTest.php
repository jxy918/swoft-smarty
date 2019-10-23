<?php

namespace SwoftTest\Smarty;

use PHPUnit\Framework\TestCase;
use Swoft\Smarty\Smarty;
use function dirname;

/**
 * Class SmartyTest
 */
class SmartyTest extends TestCase
{
    public function testSmarty(): void
    {

        $r = new Smarty();
        $r->initView();

        $this->assertSame(true, $r->getDebugging());
        $r->setDebugging(false);
        $this->assertSame(false, $r->getDebugging());

        $this->assertSame(true, $r->getCaching());
        $r->setCaching(false);
        $this->assertSame(false, $r->getCaching());

        $this->assertSame(120, $r->getCacheLifetime());
        $r->setCacheLifetime(100);
        $this->assertSame(100, $r->getCacheLifetime());

        $this->assertSame('<!--{', $r->getLeftDelimiter());
        $r->setLeftDelimiter('{{');
        $this->assertSame('{{', $r->getLeftDelimiter());

        $this->assertSame('}-->', $r->getRightDelimiter());
        $r->setRightDelimiter('}}');
        $this->assertSame('}}', $r->getRightDelimiter());

        $this->assertSame('', $r->getTemplateDir());
        $r->setTemplateDir(dirname(dirname(__DIR__)).'/resource/template');
        $this->assertSame(dirname(dirname(__DIR__)).'/resource/template/', $r->getTemplateDir());

        $this->assertSame('', $r->getCompileDir());
        $r->setCompileDir(dirname(dirname(__DIR__)).'/resource/template_c');
        $this->assertSame(dirname(dirname(__DIR__)).'/resource/template_c/', $r->getCompileDir());

        $this->assertSame('', $r->getCacheDir());
        $r->setCacheDir(dirname(dirname(__DIR__)).'/runtime/cache');
        $this->assertSame(dirname(dirname(__DIR__)).'/runtime/cache/', $r->getCacheDir());
    }
}
