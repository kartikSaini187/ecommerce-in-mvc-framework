<?php


use Phalcon\Mvc\Controller;




class CheckoutController extends Controller
{ 
    public function indexAction()
    {    
        $userid = $this->session->get('userid');
        $this->view->carts= Carts::find("userid = '$userid'");
    }
    public function placeorderAction(){
    $userid = $this->session->get('userid');
    $username = $this->session->get('username');
     $cart = Carts::find("userid = '$userid'");
     $order = new Orders();
     foreach($cart as $carts){
     $order->assign([
         'userid'=>$userid,
         'cname'=>$username,
         'caddr'=>"anywhere",
         'zipcode'=>"230032",
         'product'=>$carts->product_name,
         'quantity'=>$carts->quantity,


     ]);
     $carts->delete();
    }
     $success= $order->save();
     if($success){
         
        $this->response->redirect('index');
     }
    }
   
}
