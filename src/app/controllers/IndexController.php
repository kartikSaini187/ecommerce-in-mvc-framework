<?php


use Phalcon\Mvc\Controller;




class IndexController extends Controller
{ 
    public function indexAction()
    {    
        
         if(!$this->session->has('userid') && !$this->cookies->has('remember-me')) {

            $this->response->redirect("login");
        }
        
         $this->view->products= Products::find();
         if($this->session->has('userid')){
            $userid = $this->session->get('userid');
          $this->view->carts= Carts::find("userid = $userid");
         }
         

    }
   
}
