<?php

namespace touristiamo\exception;

/**
 * Description of BDException
 *
 * @author I.E.S Franciso Ayala
 */
class BDException extends \Exception 
{ 
        
    /**
     * 
     * @param \ArrayIterator $args method errorInfo from PDO::Statement
     */
    public function __construct($args)
    {
        parent::__construct($args[2]);
        $this->code = $args[0];
    }

    /**
     * 
     * @return string
     */
    public function getBdMessage()
    {
        if ($this->getMessage())
        {
            return 'BD Error ('. $this->getCode(). '): '. $this->getMessage();
        }
        return 'BD Error ('. $this->getCode(). '): Search the PDO code on internet, please.';
    }
}
