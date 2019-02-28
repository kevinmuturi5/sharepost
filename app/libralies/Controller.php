<?php
/*
*Base controller
*loads the models and views
*/
class controller{
    //load model
    public function model($model){
        //Require model file
        require_once '../app/models/' . $model . '.php';
        //instansiate model
        return new $model();
        
    }
    //load view 
    public function view($view, $data = []){
        //check for view file
        if(file_exists('../app/views/'. $view . '.php')){
            require_once '../app/views/'. $view . '.php';
        }else {
            //view does not exist
            die('view does not exist');
        }
    }
}