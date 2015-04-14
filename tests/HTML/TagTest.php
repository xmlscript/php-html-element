<?php
/**
 * Created by PhpStorm.
 * User: werner
 * Date: 28.02.15
 * Time: 13:44
 */

namespace WernerFreytag\HTML;


class TagTest extends \PHPUnit_Framework_TestCase {

    public function testTagType()
    {
        $fixtures = array(
            Tag::TYPE_VOID => array('area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'menuitem', 'meta', 'param', 'source', 'track', 'wbr'),
            Tag::TYPE_RAW_TEXT => array('script', 'style'),
            Tag::TYPE_ESCAPABLE_RAW_TEXT => array('textarea', 'title')
        );

        foreach ( $fixtures as $type => $names )
        {
            foreach ( $names as $name )
            {
                $tag = new Tag($name);
                $this->assertSame( $type, $tag->getTagType(), "Tag name '$name'." );
            }
        }

        $tag = new Tag('a');
        $this->assertSame("<a></a>", $tag->render());

        $tag = new Tag('br');
        $this->assertSame("<br>", $tag->render());

        $tag = new Tag('script');
        $tag->addText("if ( a < b ) { alert('c'); }");
        $this->assertSame("<script>if ( a < b ) { alert('c'); }</script>", $tag->render());
    }

    public function testCreate()
    {
        $tag = new Tag('a');

        $this->assertSame("a", $tag->getName());
        $this->assertSame("<a></a>", $tag->render());

        $tag->href = "TEST";
        $this->assertSame('<a href="TEST"></a>', $tag->render());

        $img = new Tag('img');
        $this->assertSame('<img>', $img->render());

        $tag->addChild($img);
        $this->assertSame('<a href="TEST"><img></a>', $tag->render());

        $img->src = "http://github.com/";
        $this->assertSame('<a href="TEST"><img src="http://github.com/"></a>', $tag->render());

        $tag->addText('Click me');
        $this->assertSame('<a href="TEST"><img src="http://github.com/">Click me</a>', $tag->render());

		$tag->addText('<some text>');
		$this->assertSame('<a href="TEST"><img src="http://github.com/">Click me&lt;some text&gt;</a>', $tag->render());

		// getText - text at wrong index
		$this->assertSame(NULL, $tag->getText());

		// getText - text at index 0
		$tag->setText('<some text>');
		$this->assertSame('&lt;some text&gt;', $tag->getText());
    }

	public function testAttributes()
	{
		$tag = new Tag('a');
		$this->assertNull( $tag->getAttribute('href') );
		$this->assertNull( $tag->href );

		$tag->href = "#";
		$this->assertSame('#', $tag->href);
		$this->assertSame('#', $tag->getAttribute('href'));
		$this->assertSame(array('href' => '#'), $tag->getAttributes() );

		$tag->setAttribute('href', 'javascript:void(0)');
		$this->assertSame('javascript:void(0)', $tag->href);
		$this->assertSame('javascript:void(0)', $tag->getAttribute('href'));
		$this->assertSame(array('href' => 'javascript:void(0)'), $tag->getAttributes() );

		$tag->setAttributes(array('href' => ''));
		$this->assertSame('', $tag->href);
		$this->assertSame('', $tag->getAttribute('href'));
		$this->assertSame(array('href' => ''), $tag->getAttributes() );

		$tag->addClass( 'link' );
		$this->assertSame('link', $tag->class );

		$tag->addClass( 'link-external' );
		$this->assertSame('link link-external', $tag->class );

		$tag->removeClass( 'link' );
		$this->assertSame('link-external', $tag->class );
	}

	public function testHierarchy()
	{
		$tag = new Tag('a');
		$child1 = new Text('');
		$child2 = new Text('any');

		$this->assertNull( $tag->getChild(0) );

		$tag->addChild( $child1 );

		$this->assertSame( $child1, $tag->getChild(0) );
		$this->assertSame( $child1, $tag->getChild() );
		$this->assertNull( $tag->getChild(1) );

		$tag->addChild( $child2 );

		$this->assertSame( $child1, $tag->getChild(0) );
		$this->assertSame( $child1, $tag->getChild() );
		$this->assertSame( $child2, $tag->getChild(1) );
		$this->assertNull( $tag->getChild(2) );

		$tag->setChild( $child2 );
		$this->assertSame( $child2, $tag->getChild(0) );
		$this->assertSame( $child2, $tag->getChild() );
		$this->assertNull( $tag->getChild(1) );

		$tag->setChildren( array($child2, $child1) );
		$this->assertSame( $child2, $tag->getChild(0) );
		$this->assertSame( $child2, $tag->getChild() );
		$this->assertSame( $child1, $tag->getChild(1) );
		$this->assertNull( $tag->getChild(2) );
	}
}
