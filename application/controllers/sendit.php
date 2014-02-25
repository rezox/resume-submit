<?php

class Sendit extends CI_Controller {

   public function index()
   {
      // Must be POST
      if ($_SERVER['REQUEST_METHOD'] !== 'POST')
      {
         show_404();
         return;
      }

      // If any of these are empty, its bad.
      if (empty($_POST['mydoc']) || empty($_FILES['myresume']))
      {
         echo "I need JSON object posted with the key 'mydoc' and a file posted with the key 'myresume'";
         return;
      }

      $this->load->model('sendit_model');

      // renaming the file
      if (!$resume = $this->sendit_model->move_file($_FILES['myresume']['tmp_name']))
      {
         echo "Whoops, filesystem error. Please try again!";
         return;
      }

      // checking the doc as JSON
      if (!$doc = $this->sendit_model->decode_doc($_POST['mydoc']))
      {
         echo "Not a proper JSON document.";
         return;
      }

      // checking for fields
      if (!$this->sendit_model->validate_doc($doc))
      {
         echo "Missing fields in JSON document. Exepected fields: full_name email_address phone_number";
         return;
      }

      // validating email
      if (!$this->sendit_model->validate_email($doc['email_address']))
      {
         echo "Email does not seem to be valid.";
         return;
      }

      if (!$this->sendit_model->send_email($doc, $resume)
         || !$this->sendit_model->send_confirmation_email($doc['email_address']))
      {
         echo "Oops! Couldn't send mail. Please try again!";
         return;
      }

      echo "Success!";
      return;
   }

}
