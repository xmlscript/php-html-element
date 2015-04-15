<?php
/**
 * Created by PhpStorm.
 * User: werner
 * Date: 28.02.15
 * Time: 13:40
 */

namespace WernerFreytag\HTML;

use InvalidArgumentException;

class Tag extends Element
{
    /**
     * @var string
     */
    protected $_name;

    /**
     * @var array
     */
    protected $_attributes = array();

    /**
     * @var Element[]
     */
    protected $_children = array();

    const TYPE_VOID = 'void';
    const TYPE_RAW_TEXT = 'raw_text';
    const TYPE_ESCAPABLE_RAW_TEXT = 'escapable_raw_text';
    const TYPE_NORMAL = 'normal';

    protected static $_tag_types = array(
        self::TYPE_VOID => array('area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'menuitem', 'meta', 'param', 'source', 'track', 'wbr'),
        self::TYPE_RAW_TEXT => array('script', 'style'),
        self::TYPE_ESCAPABLE_RAW_TEXT => array('textarea', 'title')
    );

    function __construct( $name, array $attributes = array(), array $children = array() )
    {
        $this->setName( $name );
    }

    /**
     * @param string $name
     * @param string|int|null $value
     */
    function __set($name, $value)
    {
        $this->setAttribute( $name, $value );
    }

    /**
     * @param string $name
     *
     * @return string|int|null
     */
    function __get($name)
    {
        return $this->getAttribute( $name );
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        if ( !is_string( $name ) )
            throw new \InvalidArgumentException('String expected');

        if ( preg_match( '#[^a-zA-Z0-9:]#', $name, $matches ) )
            throw new InvalidArgumentException( "String contains wrong character '$matches[0]'." );

        $this->_name = strtolower($name);

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->_attributes;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setAttributes( array $attributes )
    {
        $this->_attributes = array();

        foreach ( $attributes as $attribute => $value )
        {
            $this->setAttribute( $attribute, $value );
        }

        return $this;
    }

    /**
     * @param string $name
     * @param string|int|null $value
     * @return $this
     */
    public function setAttribute( $name, $value )
    {
        if ( !is_string( $name ) )
            throw new \InvalidArgumentException('String expected');

        if ( preg_match( '#[^-a-zA-Z0-9:]#', $name, $matches ) )
            throw new InvalidArgumentException( "String contains wrong character '$matches[0]'." );

        if ( !is_scalar( $value ) && !is_null($value) )
            throw new \InvalidArgumentException('String or NULL expected');

        $this->_attributes[$name] = $value;

        return $this;
    }

    /**
     * @param string $name
     * @return string|int|null
     */
    public function getAttribute( $name )
    {
        if ( !is_string( $name ) )
            throw new \InvalidArgumentException('String expected');

		return array_key_exists( $name, $this->_attributes ) ? $this->_attributes[$name] : NULL;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function addClass( $class )
    {
        if ( !is_string( $class ) )
            throw new InvalidArgumentException( 'String expected.' );

        if ( ( $current_value = $this->class ) )
        {
            $this->class .= ' ' . $class;
        }
        else {
            $this->class = $class;
        }

        return $this;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function removeClass( $class )
    {
        if ( !is_string( $class ) )
            throw new InvalidArgumentException( 'String expected.' );

        if ( ( $current_value = $this->class ) )
        {
			$classes = preg_split('#\s+#', $current_value);
            $this->class = join(' ', array_diff( $classes, array( $class ) ));
        }

        return $this;
    }

    /**
     * @param Element $child
     * @return $this
     */
    public function setChild( Element $child )
    {
        $this->setChildren( array( $child ) );
        return $this;
    }

    /**
     * @param string $str
     * @return $this
     */
    public function addText( $str )
    {
        $text = new Text( $str );
        $this->addChild( $text );
        return $this;
    }

    /**
     * @param string $str
     * @return $this
     */
    public function setText( $str )
    {
        $this->_children = array();
        return $this->addText( $str );
    }

    /**
     * @return string|NULL
     */
    public function getText()
    {
        /** @var Text $element */
        if ( ( $element = $this->getChild(0) ) instanceof Text )
        {
            return $element->getString();
        }

        return NULL;
    }

	/**
	 * @param string $str
	 * @return $this
	 */
	public function addRaw( $str )
	{
		$text = new Raw( $str );
		return $this->addChild( $text );
	}

	/**
	 * @param string $str
	 * @return $this
	 */
	public function addComment( $str )
	{
		$text = new Comment( $str );
		return $this->addChild( $text );
	}


	/**
     * @return Element[]
     */
    public function getChildren()
    {
        return $this->_children;
    }

    /**
     * @param int $index
     * @return Element|NULL
     */
    public function getChild( $index = 0 )
    {
        if ( $index < count( $this->_children ) )
            return $this->_children[$index];

        return NULL;
    }

    /**
     * @param Element[] $children
     * @return $this
     */
    public function setChildren($children)
    {
        $this->_children = array();

        foreach ( $children as $child )
            $this->addChild( $child );

        return $this;
    }

    /**
     * @param Element $child
     * @return $this
     */
    public function addChild( Element $child )
    {
        switch ( $this->getTagType() ) {
            case self::TYPE_VOID:
                throw new \BadMethodCallException("You can't add a child to this tag");

            case self::TYPE_RAW_TEXT:
            case self::TYPE_ESCAPABLE_RAW_TEXT:
                if (!$child instanceof Text)
                    throw new \BadMethodCallException("You can only add text to this tag");
        }

        $this->_children[] = $child;

        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        $result_string = '<' . $this->getName();

        foreach ( $this->_attributes as $attribute => $value ) {
            $result_string .= ' ' . $attribute;
            if ( !is_null( $value ) )
                $result_string .= '="' . str_replace(array('"', '<', '>'), array('&quot;', '&lt;', '&gt;'), $value) . '"';
        }

        $result_string .= '>';

        if ( ( $children = $this->getChildren() ) ) {
            switch ($this->getTagType()) {
                case self::TYPE_RAW_TEXT:
                    $result_string .= join("\n", array_map(function ($child) {
                        /** @var Text $child */
                        return $child->getString();
                    }, $this->getChildren()));
                    break;

                case self::TYPE_NORMAL:
                case self::TYPE_ESCAPABLE_RAW_TEXT:
                    $result_string .= join('', array_map(function ($child) {
                        /** @var Element $child */
                        return $child->render();
                    }, $this->getChildren()));
            }
        }

        if ( $this->getTagType() != self::TYPE_VOID )
            $result_string .= '</' . $this->getName() . '>';

        return $result_string;
    }


    /**
     * @return string
     */
    public function getTagType()
    {
        foreach ( self::$_tag_types as $type => $names )
        {
            if ( in_array( $this->getName(), $names ) )
                return $type;
        }

        return self::TYPE_NORMAL;
    }

}
