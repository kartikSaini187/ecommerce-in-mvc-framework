<?php


use Phalcon\Mvc\Controller;

class SettingsController extends Controller
{
    public function indexAction()
    {
      $this->view->settings = Settings::find();
    }
    
   

}