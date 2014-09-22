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

/**
 * The annotation reader interface.
 * 
 * @category  PHP
 * @package   RawPHP/RawAnnotations
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
interface IAnnotationReader
{
    /**
     * Initialises the reader.
     * 
     * @param array $config configuration array
     * 
     * @action ON_READER_INIT_ACTION
     */
    public function init( $config = array( ) );
    
    /**
     * Returns a list of class annotations.
     * 
     * @filter ON_GET_CLASS_ANNOTATIONS_FILTER
     * 
     * @return array list of annotations
     */
    public function getClassAnnotations( );
    
    /**
     * Returns the annotations for a property.
     * 
     * @param string $propertyName property name
     * 
     * @filter ON_GET_PROPERTY_ANNOTATIONS_FILTER
     * 
     * @return array the property annotations
     */
    public function getPropertyAnnotations( $propertyName );
    
    /**
     * Returns a lsit of annotations for a method.
     * 
     * @param string $methodName method name
     * 
     * @filter ON_GET_METHOD_ANNOTATIONS_FILTER
     * 
     * @return array list of annotations
     */
    public function getMethodAnnotations( $methodName );
}