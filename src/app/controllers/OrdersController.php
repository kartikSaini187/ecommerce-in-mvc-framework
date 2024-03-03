<?php


use Phalcon\Mvc\Controller;

class OrdersController extends Controller
{
    public function indexAction()
    {
        $this->view->orders = Orders::find();
    }
   

}