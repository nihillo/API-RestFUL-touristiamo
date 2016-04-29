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
     * @param string $args method errorInfo from PDO::Statement
     * @param \ArrayIterator $args method errorInfo from PDO::Statement
     */
    public function __construct($args)
    {
        if (is_string($args))
        {
            parent::__construct($args);
        } else 
        {
            parent::__construct($args[2]);
            $this->code = $args[0];
        }
        
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
