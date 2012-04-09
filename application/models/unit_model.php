<?php

class Unit_model extends CI_Model {

   public function __construct()
   {
      parent::__construct();
   }

   public function count_failed_tests($tests)
   {
      $count = 0;

      foreach ($tests as $test)
         if ($test['Result'] == 'Failed') $count++;

      return $count;
   }

   public function retrieve_tests()
   {
      $tests = array();

      $this->load->model('sendit_model');

      $tests[] = array(
         'rv' => $this->sendit_model->validate_email('tacticalazn@gmail.com'),
         'ev' => true,
         't' => 'validate_email("tacticalazn@gmail.com")',
         'n' => 'Checking if email validation works.'
      );

      $tests[] = array(
         'rv' => $this->sendit_model->validate_email('tacticalazn'),
         'ev' => false,
         't' => 'sendit_model->validate_email()',
         'n' => 'Throwing in a bad email, should return false.'
      );

      $tests[] = array(
         'rv' => $this->sendit_model->decode_doc('{"value1":"value2"}'),
         'ev' => 'is_array',
         't' => 'sendit_model->decode_doc()',
         'n' => 'A simple test to make sure JSON decoding works on the server'
      );

      $tests[] = array(
         'rv' => $this->sendit_model->decode_doc('["value1":"test"]'),
         'ev' => false,
         't' => 'sendit_model->decode_doc()',
         'n' => 'sent in ["value":"test"] which should return false'
      );

      $tests[] = array(
         'rv' => $this->sendit_model->validate_doc(
            array(
               'email_address' => 'tacticalazn@gmail.com',
               'full_name' => 'Steven Lu',
               'phone_number' => '9089031218',
               'q1' => 'Answer to question',
               'q2' => 'Answer to another question'
            )),
         'ev' => true,
         't' => 'sendit_model->validate_doc()',
         'n' => 'sent in a good data array, should return true.'
      );

      $tests[] = array(
         'rv' => $this->sendit_model->validate_doc(
            array(
               'email_address' => 'tacticalazn@gmail.com',
               'full_name' => 'Steven Lu',
               'phone_number' => '9089031218',
               'q1' => 'Answer to question',
            )),
         'ev' => false,
         't' => 'sendit_model->validate_doc()',
         'n' => 'bad data array, missing fields, should return false'
      );

      $tests[] = array(
         'rv' => $this->sendit_model->validate_doc(
            array(
               'email_address' => 'tacticalazn@gmail.com',
               'full_name' => 'Steven Lu',
               'phone_number' => '9089031218',
               'q1' => 'Answer to question',
               'q2' => ''
            )),
         'ev' => false,
         't' => 'sendit_model->validate_doc()',
         'n' => 'should also return false, has all fields, but some are empty.'
      );

      $tests[] = array(
         'rv' => $this->sendit_model->send_confirmation_email("noreply@noisenewyork.com"),
         'ev' => true,
         't' => 'sendit_model->send_confirmation_email()',
         'n' => 'this makes sure email is working on the server itself.'
      );

      return $tests;
   }

}
