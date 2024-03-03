<?php

use Phalcon\Mvc\Controller;
use Phalcon\Acl\Adapter\Memory;

class SecureController extends Controller
{
    public function BuildACLAction( )
    {   
        $users = Users::find();

       $aclFile = APP_PATH.'/security/acl.cache';
       if(true !== is_file($aclFile)){

           $acl = new Memory();

           $acl->addRole('admin');
           $acl->addRole('customer');
           $acl->addRole('manager');
           $acl->addRole('guest');
        foreach($users as $value){
        $acl->addComponent(
            $value->controller,
            [
                $value->action
            ]
            );
            if($value->role ="admin"){
             $acl->allow($value->role,'*','*');
            }
        
   }
   
   foreach ($users as $v){
       if($v->role != "admin"){
     
            $acl->allow($v->role,$v->controller,$v->action);
       }
   }
   

   file_put_contents(
       $aclFile,
       serialize($acl)
   );
}
     else{
      $acl= unserialize(
          file_get_contents($aclFile)
      );
      }
    }
}

?>