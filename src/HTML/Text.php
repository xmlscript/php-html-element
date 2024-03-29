<?php

namespace Bockmist\HTML;


class Text extends Element
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
        if ( !is_string( $string ) )
            throw new \InvalidArgumentException('String expected');

        $this->_string = $string;
    }


    public function render()
    {
        return htmlspecialchars( $this->_string, ENT_COMPAT, 'utf-8');
    }


}