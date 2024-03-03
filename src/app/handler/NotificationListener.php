<?php
namespace App\Handler;

use Phalcon\Mvc\Dispatcher;
use Phalcon\Events\Event;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;


class NotificationListener{
 
    public function beforeHandleRequest(Event $event , \Phalcon\Mvc\Application $application , Dispatcher $containerspatcher){
        
        
        $aclFile = APP_PATH.'/security/acl.cache';
        if(true === is_file($aclFile)){
            
            $acl = unserialize(
                file_get_contents($aclFile) 
            );
            
           $bearer = $application->request->get('bearer');
           if($bearer){
               try{
                   $parser = new Parser();
                   $tokenObject = $parser->parse($bearer);
                   $now = new \DateTimeImmutable();
                   $expires = $now->getTimestamp();
                   $validator = new Validator($tokenObject,100);
                   $validator->validateExpiration($expires);

                   $claims = $tokenObject->getClaims()->getPayload();
                   $role=$claims['sub'];
                   
                 }
                catch(\Exception $e){
                echo $e->getMessage();
                die;
               }
           
            $controller = $containerspatcher->getControllerName();
            $action     = $containerspatcher->getActionName();
            if(!$action){
                $action="index";
            }
            if( !$role || true !== $acl->isAllowed($role,$controller,$action)){
                echo "Access Denied ";
                die;
            }
        }else{
             //echo "token is not Provied";
            // die;
        }
            
        }
        else{
               echo "can't find any acl file";
               die; 
        }    
    }
}