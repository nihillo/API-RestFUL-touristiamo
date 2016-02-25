<?php

/**
 * Description of View
 *
 * @author I.E.S Francisco Ayala
 */

namespace touristiamo;
/**
 * This class is used to create dinamic variables. In other MVC this has 
 * a method called render wich load a template and show the result. In
 * this case we will show text in json format.
 */
class View 
{
   /**
    * This method render this object in json format.
    */
    public function render()
    {
        echo json_encode($this);
    }
}
