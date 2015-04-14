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
		$this->assertInstanceOf('\WernerFreytag\HTML\Tag', $tag);
        $this->assertSame('<a href="bla" data-test></a>', $tag->render() );

		// ' in <">-encapusulated string and visa versa
		$tag = Element::createFromString('<a val1="\'" val2=\'"\'></a>');
		$this->assertInstanceOf('\WernerFreytag\HTML\Tag', $tag);
		$this->assertSame('<a val1="\'" val2="&quot;"></a>', $tag->render() );

		// Escaped quotation - replaced with entity
		$tag = Element::createFromString('<a href="bla\"" data-test></a>');
		$this->assertInstanceOf('\WernerFreytag\HTML\Tag', $tag);
		$this->assertSame('<a href="bla&quot;" data-test></a>', $tag->render() );

		// Text only
        $text = Element::createFromString('Hello User');
		$this->assertInstanceOf('\WernerFreytag\HTML\Text', $text);
        $this->assertSame('Hello User', $text->render() );

        // Parse and modify
        $html = "<div><h1>Hello world</h1>How do you do?</div>";

		/** @var Tag $div */
        $div = Element::createFromString($html);

		$this->assertInstanceOf('\WernerFreytag\HTML\Tag', $div);

        $div->getChild(0)->setAttribute('style', 'font-weight:25px')->setText('Hello there!');
        $this->assertSame('<div><h1 style="font-weight:25px">Hello there!</h1>How do you do?</div>', $div->render());
    }

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidDOMNode()
	{
		$dom_element = new \DOMEntityReference('fail');
		Element::createFromDOMNode( $dom_element );
	}
}
