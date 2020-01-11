<?php

/*
 * Copyright (c) 2020 Anton Bagdatyev (Tonix-Tuft)
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */

namespace ReactPHP\MySQL\Decorator;

use ReactPHP\MySQL\Decorator\BaseConnectionDecorator;

/**
 * A decorator which allows the binding of named parameters (i.e. ":key")
 * when using `react/mysql` using an array with associative keys:
 * 
 *     [':key' => $value]
 *
 * @author Anton Bagdatyev (Tonix-Tuft) <antonytuft@gmail.com>
 */
class BindAssocParamsConnectionDecorator extends BaseConnectionDecorator {
    
    /**
     * {@inheritdoc}
     */
    public function query($sql, array $params = array()) {
        list($query, $notAssocParams) = $this->notAssocParamsAndQuery($sql, $params);
        return parent::query($query, $notAssocParams);
    }

    /**
     * {@inheritdoc}
     */
    public function queryStream($sql, $params = array()) {
        list($query, $notAssocParams) = $this->notAssocParamsAndQuery($sql, $params);
        return parent::queryStream($query, $notAssocParams);
    }
    
    /**
     * Tests if the given array is associative.
     * 
     * @param array $array An array.
     * @return bool TRUE if the given array is associative, FALSE otherwise.
     */
    protected function isAssoc($array) {
        return array_keys($array) !== range(0, count($array) - 1);
    }
    
    /**
     * Returns the not associative parameters and the rewritten query, if necessary.
     * 
     * @param string $sql SQL statement.
     * @param array $params Parameters.
     * @return array A tuple of two elements, the first the eventually rewritten query, the second an array with the parameters to bind.
     */
    protected function notAssocParamsAndQuery($sql, $params = array()) {
        if (empty($params) || !$this->isAssoc($params)) {
            return [$sql, $params];
        }
        
        $matches = [];
        $res = preg_match_all('/
                (?P<assoc_param>[:][a-zA-Z0-9_]+)
                |
                (?:
                   (?:[^?a-zA-Z0-9_]|^|\A)
                   (?P<not_assoc_param>\?)
                   (?:[^?a-zA-Z0-9_]|$|\Z)
                )
                /mx',
                $sql,
                $matches,
                PREG_SET_ORDER|PREG_OFFSET_CAPTURE
        );
        
        if (empty($res)) {
            return [$sql, $params];
        }
        else {
            $indexedParams = [];
            foreach ($params as $k => $v) {
                if (is_int($k)) {
                    $indexedParams[$k] = $v;
                }
            }
            
            $finalParams = [];
            $assocParamRewritesMap = [];
            $indexedIndex = 0;
            foreach ($matches as $match) {
                if (!empty($match['assoc_param'][0])) {
                    // Associative key.
                    $param = $match['assoc_param'][0];
                    $offset = $match['assoc_param'][1];
                    $assocParamRewritesMap[$offset] = [
                        $offset,
                        strlen($param)
                    ];
                    if (array_key_exists($param, $params)) {
                        $finalParams[] = $params[$param];
                    }
                }
                else {
                    // Not associative index.
                    if (array_key_exists($indexedIndex, $indexedParams)) {
                        $finalParams[] = $indexedParams[$indexedIndex];
                    }
                    $indexedIndex++;
                }
            }
            
            krsort($assocParamRewritesMap, SORT_NUMERIC);
            foreach ($assocParamRewritesMap as list($assocParamRewriteStartOffset, $assocParamRewriteLen)) {
                $sql = substr_replace(
                    $sql,
                    '?',
                    $assocParamRewriteStartOffset,
                    $assocParamRewriteLen
                );
            }
            
            return [$sql, $finalParams];
        }
    }
    
}
