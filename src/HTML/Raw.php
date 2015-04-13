<?php
/**
 * Created by PhpStorm.
 * User: werner
 * Date: 01.04.15
 * Time: 23:41
 */

namespace WernerFreytag\HTML;


class Raw extends Element
{
    /**
     * @var string
     */
    protected $_data;

    function __construct( $string )
    {
        $this->setData( $string );
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * @param string|int $raw
     */
    public function setData( $raw )
    {
        if ( !is_scalar( $raw ) )
            throw new \InvalidArgumentException('Scalar data expected');

        $this->_data = $raw;
    }


    public function render()
    {
        return $this->_data;
    }


}