<?php
/**
 * Created by PhpStorm.
 * User: werner
 * Date: 01.03.15
 * Time: 12:15
 */

namespace WernerFreytag\HTML;


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
        if ( !is_string( $string ) )
            throw new \InvalidArgumentException('String expected');

        $this->_string = $string;
    }


    public function render()
    {
        return '<!--' . $this->_string . '-->';
    }
}