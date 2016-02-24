<?php

/**
 * Description of Model
 *
 * @author Cristóbal Cobos Budia <soporte@crisoftweb.es>
 */

namespace touristiamo;
use touristiamo\service\DBService as DBService;

abstract class Model {

    /**
     *
     * @var \PDO Conexión con la BD
     */
    protected $connection;
    
    /*
     * This method get the connection instance.
     */
    public function __construct() {
        $this->connection = DBService::getInstance()->getConexion();
    }
    
    /*
     * Close the connection.
     */
    public function __destruct() {
        $this->connection = null;
    }

    
}
