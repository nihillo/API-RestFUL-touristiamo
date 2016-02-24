<?php

/**
 * Description of DBService
 *
 * @author I.E.S Francisco AYALA
 */

namespace touristiamo\service;

use PDO as PDO;

class DBService {
    
    
    /**
     *
     * @var DBService
     */
    private static $instance;
    
    /**
     *
     * @var \PDO
     */
    private $conexionBD;
    
    private function __construct() 
    {
        try
        {
            $this->conexionBD = new \PDO('mysql:host='. APP_BD_HOST. ';dbname='. 
                APP_BD_NAME, APP_BD_USER, APP_BD_PASSWORD, 
                array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES  \'UTF8\''));
        } catch(PDOException $e)
        {
            http_response_code(500);
            exit (
                json_encode(
                    array(
                        'message'   =>  $e->getMessage()
                    )
                )
            );
        }
        
    }

    /**
     * @return DBService
     */
    public static function getInstance() 
    {
        if (!(self::$instance instanceof self))
        {
            self::$instance=new self();
        }
        return self::$instance;
    }

    /**
    * This method return the unique connection
    *
    * @return PDO
    */
    public function getConexion() 
    {
        return $this->conexionBD;
    }
}
