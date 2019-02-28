<?php
/*
*App core class
*Create URL & Loads Core Contloller
* URL FORMAT -/controler methods params
*/
class Core {
    protected $currentController ='pages';
    protected $currentMethod ='index';
    protected $params=[];

    public function __construct(){
        //print_r($this -> getUrl());
        $url = $this->getUrl();
        //look in controllers for first value
        if(file_exists('../app/controllers/'.ucwords($url[0]).'.php')){
            //if is there set as controllre
            $this ->currentController = ucwords($url[0]);
            //uset zero index
            unset($url[0]);
        }
        //require controller
        require_once '../app/controllers/'.$this->currentController.'.php';
        //instanciate controllers
        $this->currentController = new $this->currentController;
        //check for the second partof url
        if(isset($url[1])){
           if(method_exists($this->currentController,$url[1])){
               $this->currentMethod=$url[1];
               //uset 1 index
               unset($url[1]);
           } 
        }
       // echo $this->currentMethod;

       //get params
       $this->params=$url ? array_values($url) :[];
       //call a callback with array params
       call_user_func_array([$this->currentController,$this->currentMethod],$this->params);
    }

    public function getUrl(){
        if(isset($_GET['url'])){
            $url = rtrim($_GET['url'],'/');
            $url = filter_var($url,FILTER_SANITIZE_URL);
            $url = explode('/',$url);
            return $url;
        }
    }
}