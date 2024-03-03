<?php
namespace App\Handler;

use Orders;
use Phalcon\Events\Event;
use Products;
use Settings;
use Tb_users;

class Listener{

    public function productsave(){
         $settings = Settings::findFirstByid(1);
         $products = Products::findFirst(['order' => 'productId DESC']);
         if($products->price == 0){
            $products->price = $settings->default_price;
         }
         if ($products->stock == 0){
             $products->stock = $settings->default_stock;
         }
         if ($products->category == 0){
            $products->category = $settings->default_category;
        } 
         $products->save();  
    }
    public function ordersave(){
        $settings = Settings::findFirstByid(1);
        $order = Orders::findFirst(['order'=>'order_id DESC']);
        if($order->zipcode == 0){
            $order->zipcode = $settings->default_zip;
        }
        if($order->quantity == 0){
            $order->quantity = $settings->default_user_order_quantity;
        }
        $order->save();
    }
    public function usersave(){
        $user = Tb_users::findFirst(['order'=>'id DESC']);
        if($user->status == "approved"){
            
        if($user->userrole == "manager"){
            $user->status = "pending";
        }
        else{
            $user->status = "approved";
        }
          
        }
        $user->save();
    }

}