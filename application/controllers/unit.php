<?php

class Unit extends CI_Controller
{

   public function index()
   {
      /*
       * This breaks normal CodeIgniter convention.
       * We need to load the views first before we can
       * execute the unit testing.
       */

      $this->load->helper('file');
      $view = read_file('./application/views/include/header.php');
      $view .= read_file('./application/views/unit.php');
      $view .= read_file('./application/views/footer.php');
      
      $this->load->library('unit_test');
      $this->test->set_template($view);

      $this->load->model('unit_model');
      $tests = $this->unit_model->retrieve_tests();

      foreach ($tests as $test)
         $this->unit->run($test['rv'], $test['ev'], $test['t'], $test['n']);

      $this->unit->report();
   }

}
