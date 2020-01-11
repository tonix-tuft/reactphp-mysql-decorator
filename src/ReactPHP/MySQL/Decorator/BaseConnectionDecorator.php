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

use React\MySQL\ConnectionInterface;

/**
 * A base abstract decorator to extend in order to add behaviour to a MySQL connection interface
 * of the `react/mysql` library.
 *
 * @author Anton Bagdatyev (Tonix-Tuft) <antonytuft@gmail.com>
 */
abstract class BaseConnectionDecorator implements ConnectionInterface {
    
    /**
     * @var ConnectionInterface
     */
    protected $conn;
    
    /**
     * Constructor.
     * 
     * @param ConnectionInterface $conn A connection.
     */
    public function __construct(ConnectionInterface $conn) {
        $this->conn = $conn;
    }
    
    /**
     * {@inheritdoc}
     */
    public function query($sql, array $params = array()) {
        return $this->conn->query($sql, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function queryStream($sql, $params = array()) {
        return $this->conn->queryStream($sql, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function ping() {
        return $this->conn->ping();
    }

    /**
     * {@inheritdoc}
     */
    public function quit() {
        return $this->conn->quit();
    }

    /**
     * {@inheritdoc}
     */
    public function close() {
        return $this->conn->close();
    }
    
    /**
     * {@inheritdoc}
     */
    public function on($event, callable $listener) {
        return $this->conn->on($event, $listener);
    }
    
    /**
     * {@inheritdoc}
     */
    public function once($event, callable $listener) {
        return $this->conn->once($event, $listener);
    }
    
    /**
     * {@inheritdoc}
     */
    public function removeListener($event, callable $listener) {
        return $this->conn->removeListener($event, $listener);
    }
    
    /**
     * {@inheritdoc}
     */
    public function removeAllListeners($event = null) {
        return $this->conn->removeAllListeners($event);
    }
    
    /**
     * {@inheritdoc}
     */
    public function listeners($event = null) {
        return $this->conn->listeners($event);
    }
    
    /**
     * {@inheritdoc}
     */
    public function emit($event, array $arguments = []) {
        return $this->conn->emit($event, $arguments);
    }

}
