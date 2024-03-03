<?php
namespace App\Handler;
use Phalcon\Di\Injectable;
use Phalcon\Events\EventsAwareInterface;

use Phalcon\Events\ManagerInterface;


class Aware extends Injectable {
    protected $eventsManager;
    
    public function getEventsManager()
    {
        return $this->eventsManager;
    }

    public function setEventsManager(ManagerInterface $eventsManager)
    {
        return $this->eventsManager = $eventsManager;
    }


    public function process1()
    {
       return  $this->eventsManager->fire('product:productsave', $this);
       

    }
    public function process2()
    {
       return  $this->eventsManager->fire('order:ordersave', $this);
       

    }
    public function process3()
    {
       return  $this->eventsManager->fire('user:usersave', $this);
       

    }
  
}