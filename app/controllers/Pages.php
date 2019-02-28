<?php
class Pages extends controller{
public function __construct(){
 
}
public function index(){
    if(isLoggedIn()){
        redirect('posts');
    }
    $data=['title' => 'shareposts',
    'description' =>  'Simple social network built on kevinmvc php framework'
      ];
      
      $this->view('pages/index',$data);
}
public function about(){
    $data=['title' => 'About Us',
    'description' =>  'app to share posts with other users'];
    $this->view('pages/about',$data);
}
}