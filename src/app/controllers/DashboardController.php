<?php


use Phalcon\Mvc\Controller;




class DashboardController extends Controller
{ 
    public function indexAction()
    {
         
    }
    public function managerAction(){
        $this->view->managers = Tb_users::find("userrole = 'manager'");
    }
    public function changestatusAction(){
        $bearer=$this->request->get('bearer');
        $userid = $this->request->getPost('btnChangestatus');
        $user = Tb_users::findFirstByid($userid);
        if($user->status == "pending"){
            $user->status = "approved";
        }
        else{
            $user->status = "pending";
        }
       $success =  $user->save();
       if($success){
        $this->response->redirect("../dashboard/manager?bearer=$bearer");
     }
    }
   
}