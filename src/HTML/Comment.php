<?php

namespace Bockmist\HTML;


class Comment extends Element
{
    /**
     * @var string
     */
    protected $_string;

    function __construct( $string )
    {
        $this->setString( $string );
    }

    /**
     * @return string
     */
    public function getString()
    {
        return $this->_string;
    }

    /**
     * @param string $string
     */
    public function setString($string)
    {
		if ( !is_scalar( $string ) )
			throw new \InvalidArgumentException('Scalar data expected');

        $this->_string = $string;
    }


    public function render()
    {
        return '<!--' . $this->_string . '-->';
    }
}