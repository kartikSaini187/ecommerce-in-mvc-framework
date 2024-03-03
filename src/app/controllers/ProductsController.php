<?php


use Phalcon\Mvc\Controller;

class ProductsController extends Controller
{
    public function indexAction()

    {
       
      $this->view->products = Products::find();
    }
   

}