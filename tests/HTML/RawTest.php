<?php
/**
 * Created by PhpStorm.
 * User: werner
 * Date: 28.02.15
 * Time: 13:43
 */

namespace WernerFreytag\HTML;

class RawTest extends \PHPUnit_Framework_TestCase {

    public function testConstructor()
    {
        $raw_element = new Raw("Hello");
        $this->assertSame("Hello", $raw_element->getData());

        $raw_element = new Raw('');
        $this->assertSame('', $raw_element->getData());

        $raw_element = new Raw("\\'");
        $this->assertSame("\\'", $raw_element->getData());

        $raw_element = new Raw("<a tag>");
        $this->assertSame("<a tag>", $raw_element->getData());
    }

    public function testConstructorIntArgument()
    {
		// May not fail
        new Raw(0);
    }

    public function testRender()
    {
        $raw_element = new Raw("Hello");
        $this->assertSame("Hello", $raw_element->render());

        $raw_element = new Raw('');
        $this->assertSame('', $raw_element->render());

        $raw_element = new Raw("\\'");
        $this->assertSame("\\'", $raw_element->render());

        $raw_element = new Raw("<a tag>");
        $this->assertSame("<a tag>", $raw_element->render());
    }

    public function testToString()
    {
        $raw_element = new Raw("Hello");
        $this->assertSame("Hello", sprintf('%s', $raw_element));

        $raw_element = new Raw('');
        $this->assertSame('', sprintf('%s', $raw_element));

        $raw_element = new Raw("\\'");
        $this->assertSame("\\'", sprintf('%s', $raw_element));

        $raw_element = new Raw("<a tag>");
        $this->assertSame("<a tag>", sprintf('%s', $raw_element));
    }
}
