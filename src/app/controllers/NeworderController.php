<?php

use Phalcon\Mvc\Controller;
use Phalcon\Events\Manager as EventsManager;


class NeworderController extends Controller
{
    public function indexAction()
    {
     $id = $this->request->getPost('neworder');
     $this->view->products = Products::find($id);
    }
    public function placenewAction(){
        $orders= new Orders();
        $escape = new MyEscaper();
        $bearer=$this->request->get('bearer');
        $eventsManager = new EventsManager();
        $component = new \App\Handler\Aware();
        $component->setEventsManager($eventsManager);
        
           $zipcode = $this->request->getPost('zipcode');
           if(!$zipcode){
               $zipcode =0;
           }
           $quantity = $this->request->getPost('quantity');
           if(!$quantity){
               $quantity=0;
           }

           
        $orders->assign([
         'cname' => $this->request->getPost('cname'),
         'caddr' => $this->request->getPost('caddr'),
         'zipcode' => $zipcode,
         'product' => $this->request->getPost('product'),
         'quantity' => $quantity,
        ]);
         
        $success = $orders->save();
         if($success){
            $eventsManager->attach(
                'order',
                 new \App\Handler\Listener()
                 );
     
               $component->process2();
               $this->response->redirect("../index?bearer=$bearer");
         }
       
    }
   

}