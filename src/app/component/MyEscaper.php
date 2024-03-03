<?php

use Phalcon\Escaper;

class MyEscaper extends Escaper
{
   public function sanitize($value){

       return $this->escapeHtml($value);
   }
}