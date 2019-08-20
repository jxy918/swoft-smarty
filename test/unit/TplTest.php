<?php

namespace SwoftTest\Smarty\Test\Cases;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Swoft\Smarty\Tpl;
use function dirname;

/**
 * Class TplTest
 */
class TplTest extends TestCase
{
    public function testRenderer(): void
    {
        $config = [
            'layout'    => 'layout.php',
            'viewsPath' => dirname(__DIR__) . '/fixture/',
        ];

        $r = new Renderer($config);

        $this->assertSame('php', $r->getSuffix());
        $this->assertSame($config['layout'], $r->getLayout());
        $this->assertSame($config['viewsPath'], $r->getViewsPath());

        // setters
        $r->setSuffix('html');
        $this->assertSame('html', $r->getSuffix());

        // set attrs
        $r->setAttributes([
            'a' => 1,
            'b' => true,
        ]);

        $this->assertTrue($r->getAttribute('b'));
        $this->assertSame(1, $r->getAttribute('a'));
        $this->assertIsArray($r->getAttributes());
        $this->assertArrayHasKey('a', $r->getAttributes());
    }

    public function testRender(): void
    {
        $config = [
            'layout'    => 'layout.php',
            'viewsPath' => dirname(__DIR__) . '/fixture',
        ];

        $r = new Renderer($config);
        $this->assertEquals('ABC', $r->renderBody('B'));

        $this->expectException(RuntimeException::class);
        $r->renderPartial('not-exist');
    }
}
