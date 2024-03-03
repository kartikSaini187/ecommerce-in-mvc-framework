<?php


use Phalcon\Mvc\Controller;

use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;
use Phalcon\Events\Manager as EventsManager;


class LoginController extends Controller
{ 
    public function indexAction()
    {
        
        
    }
    public function signupAction()
    {
    }
    public function authAction()
    {
       $signer  = new Hmac();
        
        $email = $this->request->getPost('email');
        $pass = $this->request->getPost('pass');
        $user =  Tb_users::findFirst(['conditions' => "email = '$email' AND password = '$pass'"]);
       
        if ($user) {
            if($user->status == "pending"){
                $this->view->message = "You Are On Pending State";
            }
            else{
            $this->session->set("userrole",$user->userrole);
            $this->session->set('username',$user->name);
            $this->session->set('userid',$user->id);
            if($this->request->getPost('remember')){
                $this->cookies->set(
                    'remember-me',
                    'some value',
                     time() + 15 * 86400
                    );
                 $this->cookies->send();
                }

            $builder = new Builder($signer);

            $now        = new DateTimeImmutable();

            $issued     = $now->getTimestamp();
            $notBefore  = $now->modify('-1 minute')->getTimestamp();
            $expires    = $now->modify('+1 day')->getTimestamp();
            $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';

            // Setup
            $builder
                ->setAudience('localhost:8080')  // aud
                ->setContentType('application/json')        // cty - header
                ->setExpirationTime($expires)               // exp 
                ->setId('abcd123456789')                    // JTI id 
                ->setIssuedAt($issued)                      // iat 
                ->setIssuer('https://phalcon.io')           // iss 
                ->setNotBefore($notBefore)                  // nbf
                ->setSubject($user->userrole)   // sub
                ->setPassphrase($passphrase)                // password 
            ;

            $tokenObject = $builder->getToken();
            $token = $tokenObject->getToken();
            $this->logger->excludeAdapters(['signup'])->notice('Login By :-'.$user->name);

            $this->response->redirect("index?bearer=$token");
            }
        }
        else{
            
            $this->logger->excludeAdapters(['signup'])->error($email.':-Wrong Credentials');
            return   $this->view->message = "Check Your Credentials";
         
        }
    }
    public function registerAction()
    {    
       
        $signer  = new Hmac();
        $user = new Tb_users();
        $escape = new MyEscaper();
        $email = $this->request->getPost('email');
        $eventsManager = new EventsManager();
        $component = new \App\Handler\Aware();
        $component->setEventsManager($eventsManager);
        $data = array(
            "name" => $escape->sanitize($this->request->getPost('name')),
            "email" => $escape->sanitize($this->request->getPost('email')),
            "userrole" => $escape->sanitize($this->request->getPost('role')),
            "password" => $escape->sanitize($this->request->getPost('password')),
            "status"=> "approved",
        );
         $user->assign(  $data ,[
            'name' ,
            'email',
            'userrole',
            'password',
             'status',

           ]);

        $success = $user->save();
        if ($success) {

            $builder = new Builder($signer);

            $now        = new DateTimeImmutable();

            $issued     = $now->getTimestamp();
            $notBefore  = $now->modify('-1 minute')->getTimestamp();
            $expires    = $now->modify('+1 day')->getTimestamp();
            $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';

            // Setup
            $builder
                ->setAudience('localhost:8080')  // aud
                ->setContentType('application/json')        // cty - header
                ->setExpirationTime($expires)               // exp 
                ->setId('abcd123456789')                    // JTI id 
                ->setIssuedAt($issued)                      // iat 
                ->setIssuer('https://phalcon.io')           // iss 
                ->setNotBefore($notBefore)                  // nbf
                ->setSubject($user->userrole)   // sub
                ->setPassphrase($passphrase)                // password 
            ;

            $tokenObject = $builder->getToken();
            $token=$tokenObject->getToken();
            $this->session->set('username',$user->name);
            $eventsManager->attach(
                'user',
                 new \App\Handler\Listener()
                 );
     
               $component->process3();
            $this->logger->excludeAdapters(['login'])->notice('Created a new Account with Email:-'.$email);

            $this->response->redirect("index?bearer=$token");

            
        }
        else {
            $this->view->message = "Sorry, the following problems were generated:<br>"
                . implode('<br>', $user->getMessages());
            return $this->logger->excludeAdapters(['login'])->error(implode('<br>', $user->getMessages()));
    }
    }
    public function logoutAction(){
        $this->session->destroy();
        $rememberMeCookie = $this->cookies->get('remember-me');
        $rememberMeCookie->delete();
        $this->response->redirect('../login');
    }
}