<?php
class Users extends controller{
    public function __construct(){
        $this->usermodel = $this->model('User');
    }
    public function register(){
        //check for posts
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
             //process the form
             //sanitize POST data
             $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                //init data
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),

                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            //valdate email
            if(empty($data['email'])){
                $data['email_err'] = 'please enter email';
            }else{
                if($this->usermodel->findUserByEmail($data['email'])){
                    $data['email_err'] = 'Email is already taken';
                }
            }
            //validate name
            if(empty($data['name'])){
                $data['name_err'] = 'please enter name';
            } 
            //validate password 
            if(empty($data['password'])){
                $data['password_err'] = 'please enter password';
            } elseif (strlen($data['password']) < 6){
                $data['password_err'] = 'password must be at least 6 characters';
            }
               //valdate cornfirm password
            if(empty($data['confirm_password'])){
                $data['confirm_password_err'] = 'please confirm password';
            }else{
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'passwords do not much';
                }
            }
            //make sure errors are empty
            if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err'])  &&
            empty($data['confirm_password_err'])) {
               //validated
              //Hash  password
              $data['password'] = password_hash($data['password'],PASSWORD_DEFAULT);
              //register user
              if ($this->usermodel->register($data)) {
                  flash('register_success', 'You are registered and can now log in');
                 redirect('users/login');
              } else{
                  die ('somthing went wrong');
              }

            }else{
                //load views with errors
                $this -> view('users/register', $data);
            }

        } else {
            //init data
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' =>'' ,
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            //load view
            $this->view('users/register',$data);

        }
    }

    public function login(){
        //check for posts
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
             //process the form
               //sanitize POST data
               $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
               //init data
           $data = [      
               'email' => trim($_POST['email']),
               'password' => trim($_POST['password']),
               'email_err' => '',
               'password_err' => ''      
           ];
           //valdate email
           if(empty($data['email'])){
            $data['email_err'] = 'please enter email';
        }
        //validate password
        if(empty($data['password'])){
            $data['password_err'] = 'please enter password';
        }
        //check for user/email
        if($this->usermodel->findUserByEmail($data['email'])){
        //user found
        }else{
            //user not found
            $data['email_err'] = 'No user found';
        }

        //make sure errors are empty
        if (empty($data['email_err']) && empty($data['password_err'])) {
           //validated
           //check and set logged i user
           $loggedInUser = $this->usermodel->login($data['email'], $data['password']);
           if($loggedInUser){
             $this->createUserSession($loggedInUser);
           }else{
            $data['password_err'] = 'password incorrect';
            $this->view('users/login' ,$data);
           }

        }else{
            //load views with errors
            $this->view('users/login', $data);        
        }             
        }else {
            //init data
            $data = [              
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''               
            ];
            //load view
            $this->view('users/login',$data);
        }
    }
    public function createUserSession($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        redirect('posts');

    }
    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        session_destroy();
        redirect('users/login');
    }
}