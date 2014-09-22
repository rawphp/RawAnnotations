<?php

/**
 * This file is part of RawPHP - a PHP Framework.
 * 
 * Copyright (c) 2014 RawPHP.org
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * 
 * PHP version 5.3
 * 
 * @category  PHP
 * @package   RawPHP/RawAnnotations
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawAnnotations;

use RawPHP\RawBase\Component;
use RawPHP\RawAnnotations\Annotation;
use RawPHP\RawAnnotations\IAnnotationReader;
use ReflectionClass;

/**
 * The annotation reader service.
 * 
 * @category  PHP
 * @package   RawPHP/RawAnnotations
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class AnnotationReader extends Component implements IAnnotationReader
{
    /**
     * @var object
     */
    public $object;
    
    /**
     *
     * @var ReflectionClass
     */
    protected $reflector;
    
    /**
     * Initialises the reader.
     * 
     * @param array $config configuration array
     * 
     * @action ON_READER_INIT_ACTION
     */
    public function init( $config = array( ) )
    {
        if ( isset( $config[ 'object' ] ) )
        {
            $this->object = $config[ 'object' ];
        }
        elseif ( isset( $config[ 'class' ] ) )
        {
            $this->object = $config[ 'class' ];
            $this->reflector = new ReflectionClass( $this->object );
        }
        
        $this->doAction( self::ON_READER_INIT_ACTION );
    }
    
    /**
     * Returns a list of class annotations.
     * 
     * @filter ON_GET_CLASS_ANNOTATIONS_FILTER
     * 
     * @return array list of annotations
     */
    public function getClassAnnotations( )
    {
        return $this->filter( 
                self::ON_GET_CLASS_ANNOTATIONS_FILTER, 
                $this->_parseDoc( $this->reflector->getDocComment( ) )
        );
    }
    
    /**
     * Returns the annotations for a property.
     * 
     * @param string $propertyName property name
     * 
     * @filter ON_GET_PROPERTY_ANNOTATIONS_FILTER
     * 
     * @return array the property annotations
     */
    public function getPropertyAnnotations( $propertyName )
    {
        $props = $this->reflector->getProperties( );
        
        $annotations = array( );
        
        foreach( $props as $property )
        {
            $name = $property->getName( );
            
            if ( $propertyName !== $name )
            {
                continue;
            }
            
            $annotations = $this->_parseDoc( $property->getDocComment( ) );
            
            break;
        }
        
        return $this->filter( self::ON_GET_PROPERTY_ANNOTATIONS_FILTER, $annotations );
    }
    
    /**
     * Returns a lsit of annotations for a method.
     * 
     * @param string $methodName method name
     * 
     * @filter ON_GET_METHOD_ANNOTATIONS_FILTER
     * 
     * @return array list of annotations
     */
    public function getMethodAnnotations( $methodName )
    {
        $methods = $this->reflector->getMethods( );
        
        $annotations = array( );
        
        foreach ( $methods as $method )
        {
            $name = $method->getName( );
            
            if ( $methodName !== $name )
            {
                continue;
            }
            
            $annotations = $this->_parseDoc( $method->getDocComment( ) );
        }
        
        return $this->filter( self::ON_GET_METHOD_ANNOTATIONS_FILTER, $annotations );
    }
    
    /**
     * Parses a docComment string and returns a list of annotations.
     * 
     * @param string $docComment the doc comemnt
     * 
     * @return array list of annotations
     */
    private function _parseDoc( $docComment )
    {
        $annotations = array( );
        
        $lines = explode( PHP_EOL, trim( $docComment ) );
        
        foreach( $lines as $line )
        {
            $line = trim( $line );
            
            if ( FALSE !== strstr( $line, '* @' ) )
            {
                $line = str_replace( '*', '', $line );
                
                $index = strpos( trim( $liine ), ' ' ) + 1;
                
                $str1 = substr( line, 0, $index );
                $str1 = str_replace( '@', '', $str1 );
                $str2 = substr( $line, $index, strlen( $line ) - $index );
                
                $annotation = new Annotation( );
                $annotation->init( array( 'name' => trim( $str1 ), 'value' => trim( $str2 ) ) );
                
                $annotations[] = $annotation;
            }
        }
        
        return $annotations;
    }
    
    const ON_READER_INIT_ACTION                 = 'on_reader_init_action';
    
    const ON_GET_CLASS_ANNOTATIONS_FILTER       = 'on_get_class_annotations_filter';
    const ON_GET_PROPERTY_ANNOTATIONS_FILTER    = 'on_get_property_annotations_filter';
    const ON_GET_METHOD_ANNOTATIONS_FILTER      = 'on_get_method_annotations_filter';
}