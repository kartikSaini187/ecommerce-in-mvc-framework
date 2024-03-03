<?php


use Phalcon\Mvc\Controller;



class ComponentController extends Controller
{
    public function indexAction( )
    {
        $this->view->components = Users::find();
       
    }
    public function newroleAction(){
      
      $user = new Users();
      $user->assign([
        'role'=>$this->request->getPost('user'),
        'controller'=>'index',
        'action'=>'index'
      ]);
      $success= $user->save();
      if($success){
         $this->response->redirect('./component');
      }
      
    }
    public function changecomponentAction(){
      $action = $this->request->getPost('action');
      $controller = $this->request->getPost('controller');
      $cid= $this->request->getPost('btnedit');
       $users= Users::findFirstByid($cid);
       $users->controller = $controller;
       $users->action=$action;
      $success=  $users->save();
      if($success){
        $this->response->redirect('./component');
     }
    }
}

?>