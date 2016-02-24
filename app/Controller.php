<?php
/**
 * Description of Controller
 *
 * @author Cristóbal Cobos Budia <soporte@crisoftweb.es>
 */

namespace touristiamo;


abstract class Controller {
    
    protected $view;

    public function __construct() {
        $this->view = new \touristiamo\View();
    }
    
}