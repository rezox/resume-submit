<?php

class Unit_model extends CI_Model {

   public function __construct()
   {
      parent::__construct();
   }

   public function retrieve_tests()
   {
      $tests = array();

      $this->load->model('sendit_model');
      $tests[] = array(
         'rv' => $this->sendit_model->validate_email('tacticalazn@gmail.com')
         'ev' => true,
         't' => 'validate_email("tacticalazn@gmail.com")',
         'n' => 'Checking if email validation works.'
      );

      return $tests;
   }

}
