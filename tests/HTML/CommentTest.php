<?php

namespace Bockmist\HTML;

class CommentTest extends \PHPUnit_Framework_TestCase {

    public function testConstructor()
    {
        $comment_element = new Comment("Hello");
        $this->assertSame("Hello", $comment_element->getString());

        $comment_element = new Comment('');
        $this->assertSame('', $comment_element->getString());

        $comment_element = new Comment("\\'");
        $this->assertSame("\\'", $comment_element->getString());

        $comment_element = new Comment("<a tag>");
        $this->assertSame("<a tag>", $comment_element->getString());
    }

    public function testConstructorIntArgument()
    {
		// May not fail
        new Comment(0);
    }

    public function testRender()
    {
        $comment_element = new Comment("Hello");
        $this->assertSame("<!--Hello-->", $comment_element->render());

        $comment_element = new Comment('');
        $this->assertSame('<!---->', $comment_element->render());

        $comment_element = new Comment("\\'");
        $this->assertSame("<!--\\'-->", $comment_element->render());

        $comment_element = new Comment("<a tag>");
        $this->assertSame("<!--<a tag>-->", $comment_element->render());
    }

    public function testToString()
    {
        $comment_element = new Comment("Hello");
        $this->assertSame("<!--Hello-->", sprintf('%s', $comment_element));

        $comment_element = new Comment('');
        $this->assertSame('<!---->', sprintf('%s', $comment_element));

        $comment_element = new Comment("\\'");
        $this->assertSame("<!--\\'-->", sprintf('%s', $comment_element));

        $comment_element = new Comment("<a tag>");
        $this->assertSame("<!--<a tag>-->", sprintf('%s', $comment_element));
    }
}
