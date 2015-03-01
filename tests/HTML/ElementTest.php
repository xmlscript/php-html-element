<?php
/**
 * Created by PhpStorm.
 * User: werner
 * Date: 28.02.15
 * Time: 17:38
 */

namespace WernerFreytag\HTML;

class ElementTest extends \PHPUnit_Framework_TestCase {

    public function testParseString()
    {
        $result = Element::createFromString('<html><body><h1 style="font-size:12px">Hä?<!--comment--></h1>Test<br></body></html>');
        $this->assertSame('<html><body><h1 style="font-size:12px">Hä?<!--comment--></h1>Test<br></body></html>', $result->render() );

        // Convert to lowercase
        $result = Element::createFromString('<HTML><BoDy><h1 style="font-size:12px">Hä?<!--comment--></h1>Test<br></body></html>');
        $this->assertSame('<html><body><h1 style="font-size:12px">Hä?<!--comment--></h1>Test<br></body></html>', $result->render() );

        // Embed into <p> when multiple elements on top level
        $result = Element::createFromString('Hello<b>User');
        $this->assertSame('<p>Hello<b>User</b></p>', $result->render() );

        // Single only opening tag
        $tag = Element::createFromString('<a href="bla" data-test>');
        $this->assertSame('<a href="bla" data-test></a>', $tag->render() );

        // Single tag
        $tag = Element::createFromString('<a href="bla" data-test></a>');
        $this->assertSame('<a href="bla" data-test></a>', $tag->render() );

        // Text only
        $text = Element::createFromString('Hello User');
        $this->assertSame('Hello User', $text->render() );
    }
}
