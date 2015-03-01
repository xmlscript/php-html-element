<?php
/**
 * Created by PhpStorm.
 * User: werner
 * Date: 28.02.15
 * Time: 13:43
 */

namespace WernerFreytag\HTML;

use InvalidArgumentException;

class TextTest extends \PHPUnit_Framework_TestCase {

    public function testConstructor()
    {
        $text = new Text("Hello");
        $this->assertSame("Hello", $text->getString());

        $text = new Text('');
        $this->assertSame('', $text->getString());

        $text = new Text("\\'");
        $this->assertSame("\\'", $text->getString());

        $text = new Text("<a tag>");
        $this->assertSame("<a tag>", $text->getString());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructorInvalidArgument()
    {
        new Text(0);
    }

    public function testRender()
    {
        $text = new Text("Hello");
        $this->assertSame("Hello", $text->render());

        $text = new Text('');
        $this->assertSame('', $text->render());

        $text = new Text("\\'");
        $this->assertSame("\\'", $text->render());

        $text = new Text("<a tag>");
        $this->assertSame("&lt;a tag&gt;", $text->render());
    }

    public function testToString()
    {
        $text = new Text("Hello");
        $this->assertSame("Hello", sprintf('%s', $text));

        $text = new Text('');
        $this->assertSame('', sprintf('%s', $text));

        $text = new Text("\\'");
        $this->assertSame("\\'", sprintf('%s', $text));

        $text = new Text("<a tag>");
        $this->assertSame("&lt;a tag&gt;", sprintf('%s', $text));
    }
}
