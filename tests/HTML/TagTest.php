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

        $img->src = "http://www.pecora.de/";
        $this->assertSame('<a href="TEST"><img src="http://www.pecora.de/"></a>', $tag->render());

        $tag->addText('Click me');
        $this->assertSame('<a href="TEST"><img src="http://www.pecora.de/">Click me</a>', $tag->render());

        $tag->addText('<some text>');
        $this->assertSame('<a href="TEST"><img src="http://www.pecora.de/">Click me&lt;some text&gt;</a>', $tag->render());
    }
}
