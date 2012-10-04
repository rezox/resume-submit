<?php

class Getcode extends CI_Controller {

   public function index()
   {
      // Must be POST
      if ($_SERVER['REQUEST_METHOD'] !== 'POST')
      {
         show_404();
         return;
      }

      if (empty($_POST['name']))
      {
         show_404();
         return;
      }

      echo sha1($_POST['name']);
   }

}
