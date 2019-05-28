<?php

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
