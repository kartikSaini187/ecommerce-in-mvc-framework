<?php


use Phalcon\Mvc\Controller;




class AddtocartController extends Controller
{   
     

    public function indexAction()
    {    
         $userid = $this->session->get('userid');
         $this->view->carts= Carts::find("userid = '$userid'");
    }
    public function atocartAction(){
        $bearer=$this->request->get('bearer');
        $userid=$this->session->get('userid');
          $id = $this->request->getPost('addtocart');
          $carts = new Carts();
          $product =  Products::findFirstByproductId($id);
          $cart = Carts::findFirst(['conditions' => "productId = '$id' AND userid = '$userid'"]);
          
          if($cart){
            
              $cart->quantity = $cart->quantity+1;
              $cart->price = $cart->quantity*$cart->price;
             $success= $cart->save();
             if($success){
                $this->response->redirect("../index?bearer=$bearer");
            }

          }
          else{
            
            $carts->assign([
                'productId'=>$product->productId,
                'userid'=>$userid,
                'product_name'=>$product->product_name,
                'quantity'=>1,
                'price'=>$product->price,

            ]);
            $success = $carts->save();
            if($success){
                $this->response->redirect("../index?bearer=$bearer");
            }
          }
          
    }
    public function removeAction(){
        $bearer=$this->request->get('bearer');
         $cartid = $this->request->getPost('btnRemove');
         $cart = Carts::findFirstByid($cartid);
         $success = $cart->delete();
         if($success){
            $this->response->redirect("../addtocart?bearer=$bearer");
         }
    }
    public function changequantityAction(){
        $bearer=$this->request->get('bearer');
        $cartid = $this->request->getPost('cartid');
        $quantity = $this->request->getPost('quantity');
         $cart = Carts::findFirstByid($cartid);
         $cart->quantity = $quantity;
         $success = $cart->save();
         if($success){
            $this->response->redirect("../addtocart?bearer=$bearer");
         }

    }
   
}