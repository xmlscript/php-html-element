<?php

namespace Bockmist\HTML;

use InvalidArgumentException;
use DOMDocument, DOMNode, DOMElement, DOMText;

abstract class Element
{
	/*
	 * @return string
	 */
	public function __toString()
	{
		return $this->render();
	}

	/*
	 * @return string
	 */
	abstract public function render();

	/**
	 * @param string $html
	 *
	 * @return Element
	 */
	public static function createFromString( $html )
	{
		if ( !is_string( $html ) )
			throw new InvalidArgumentException( "Argument not a string." );

		// Use own method to parse simple strings
		if ( ($element = self::_createElementFromString( $html )) )
			return $element;

		$node = new DOMDocument();
		$node->loadHTML( '<?xml encoding="UTF-8">' . $html );

		if ( !preg_match( '#<html\b#i', $html ) )
		{
			$node_list = $node->getElementsByTagName( 'body' );
			foreach ( $node_list as $node )
			{
				break;
			}
		}

		if ( !preg_match( '#<body\b#i', $html ) )
		{
			$node = $node->firstChild;
		}

		return self::createFromDOMNode( $node );
	}

	/**
	 * @param $node
	 *
	 * @return Element
	 */
	public static function createFromDOMNode( DOMNode $node )
	{
		/**
		 * @var DOMElement $item
		 */
		switch ( $node->nodeType )
		{
			case XML_HTML_DOCUMENT_NODE:

				/** @var DOMDocument $document */
				$document = $node;

				$body_node = NULL;
				foreach ( $document->getElementsByTagName( 'html' ) as $body_node )
				{
					break;
				}

				if ( $body_node == NULL )
					throw new \LogicException( 'No html element found' );

				return self::createFromDOMNode( $body_node );

			case XML_ELEMENT_NODE:

				/** @var \DOMElement $element */
				$element = $node;

				$tag = new Tag( $element->nodeName );

				/**
				 * @var string   $attribute
				 * @var \DOMAttr $attr
				 */
				foreach ( $element->attributes as $attribute => $attr )
					$tag->setAttribute( $attribute, $attr->value );

				foreach ( $element->childNodes as $child_node )
				{
					if ( ($child = self::createFromDOMNode( $child_node )) )
						$tag->addChild( $child );
				}

				return $tag;

			case XML_TEXT_NODE:
				/** @var DOMText $text */
				$text = $node;

				return new Text( $text->wholeText );

			case XML_COMMENT_NODE:
				/** @var \DOMComment $comment */
				$comment = $node;

				return new Comment( $comment->data );
		}

		throw new InvalidArgumentException( "Non supported type '{$node->nodeType}'" );
	}

	const PARSE_KEY = 'key';
	const PARSE_VALUE = 'value';

	/**
	 * Parse a string and create an element from it
	 *
	 * @param string $string
	 *
	 * @return Element
	 */
	protected static function _createElementFromString( $string )
	{
		if ( !preg_match( '#<#', $string, $matches ) )
			return new Text( $string );

		if ( !preg_match( '#^<([a-zA-Z0-9]+)(?:\s+([^>]+))?>(?:</([^>]+)>)?$#s', trim( $string ), $matches ) )
			return NULL;

		if ( !empty($matches[3]) && strtolower( $matches[3] ) != strtolower( $matches[1] ) )
			throw new InvalidArgumentException( 'Closing tag not equal to opening tag' );

		$tag = new Tag( $matches[1] );

		$split_parts = preg_split( '/([[:space:]\\"\'=])/m', trim( $matches[2] ), -1, PREG_SPLIT_DELIM_CAPTURE );

		$result       = array();
		$result_index = 0;
		$field_type   = self::PARSE_KEY; // toggle between key and value
		for ( $split_index = 0; $split_index < count( $split_parts ); ++$split_index )
		{

			if ( !isset($result[$result_index][$field_type]) )
				$result[$result_index][$field_type] = '';

			$value = $split_parts[$split_index];

			if ( $value == '=' )
			{

				$field_type = self::PARSE_VALUE;

				$split_index++;
				while ( $split_index < count( $split_parts ) && trim( $split_parts[$split_index] ) === '' )
					$split_index++;
				$split_index--;
			}

			// Value is in quotation marks
			elseif ( in_array( $value, array('\'', '"') ) )
			{
				$field_type     = self::PARSE_VALUE;
				$quotation_mark = $value;

				// append string until quotation mark is closed
				for ( $split_index++; $split_index < count( $split_parts ); ++$split_index )
				{
					$value = $split_parts[$split_index];

					// chr(92) = backslash
					if ( strlen( $value ) > 0 && $value[strlen( $value ) - 1] == chr(92) && $split_parts[$split_index + 1] == $quotation_mark )
					{
						$result[$result_index][$field_type] .= substr( $value, 0, -1 );
						$split_index++;
						$result[$result_index][$field_type] .= $split_parts[$split_index];
					}
					elseif ( $value == $quotation_mark )
					{
						break;
					}
					else
					{
						$result[$result_index][$field_type] .= $split_parts[$split_index];
					}
				}

				$result_index++;
				$field_type = self::PARSE_KEY;
			}

			// Value is not in quotation marks
			elseif ( trim( $value ) )
			{
				$result[$result_index][$field_type] = $value;

				$field_type = $field_type == self::PARSE_KEY ? self::PARSE_VALUE : self::PARSE_KEY;
				if ( $field_type == self::PARSE_KEY )
					$result_index++;
			}
		}

		foreach ( $result as $fields )
		{
			if ( !trim( $fields[self::PARSE_KEY] ) || trim( $fields[self::PARSE_KEY] ) == '/' )
				continue;

			$value = array_key_exists( self::PARSE_VALUE, $fields ) ? $fields[self::PARSE_VALUE] : NULL;

			$tag->setAttribute( $fields[self::PARSE_KEY], $value );
		}

		return $tag;
	}
}

