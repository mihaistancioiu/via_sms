<?php

namespace Core;

abstract class Controller{

    /** @var array  */
    protected $routeParams = array();

    /** @var array  */
    protected $requestParams = array();

    /** @var array  */
    protected $requestFiles = array();

    /**
     * Controller constructor.
     * @param $route_params
     */
    public function __construct($routeParams)
    {
        $this->routeParams = $routeParams;
        $this->requestParams = $_POST;
        $this->requestFiles = $_FILES;
    }


}