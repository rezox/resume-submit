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
         echo "You're missing something.";
         return;
      }

      // decoding data
      $resume = $_FILES['myresume']['tmp_name'];
      $doc = json_decode($_POST['mydoc'], TRUE);

      $temp_path = "/tmp/resume-".mt_rand().".pdf";
      
      // renaming the file
      if (move_uploaded_file($resume, $temp_path) === FALSE)
      {
         echo "Whoops, try again!";
         return;
      }
      
      $resume = $temp_path;

      // checking the doc as JSON
      if (empty($doc))
      {
         echo "Not a proper JSON document.";
         return;
      }

      // checking for fields
      if (empty($doc['full_name'])
         || empty($doc['email_address'])
         || empty($doc['phone_number'])
         || empty($doc['q1'])
         || empty($doc['q2']))
      {
         echo "Missing fields in JSON document. Exepecting full_name, email_address, phone_number, q1, q2.";
         return;
      }

      // validating email
      $this->load->helper('email');
      if (!valid_email($doc['email_address']))
      {
         echo "Email does not seem to be valid.";
         return;
      }

      //making email doc
      $this->load->library('email');

      $this->email->from($doc['email_address'], $doc['full_name']);
      //$this->email->to('steve@noisenewyork.com');
      $this->email->to('jobs@noisenewyork.com');
      $this->email->subject('Software Engineer - Priority');

      $message = "The applicant completed the application process.\n\n";
      $message .= "Q. Write a snippet of code which shows a function ana variable within the same object using Javascript.\nA. ";
      $message .= $doc['q1'];
      $message .= "\n\nQ. Briefly explain what a MVC (Model-View-Controller) is and why it's not smart to run a a function call in a view.\nA. ";
      $message .= $doc['q2'];
      $message .= "\n\n";
      $message .= "Full Name:";
      $message .= $doc['full_name'];
      $message .= "\nEmail Address:";
      $message .= $doc['email_address'];
      $message .= "\nPhone Number:";
      $message .= $doc['phone_number'];

      $this->email->message($message);
      $this->email->attach($resume);

      if (!$this->email->send())
      {
         echo "Oops! Please try again!";
         return;
      }

      // Sending confirmation email.
      $this->email->clear();

      $this->email->from('jobs@noisenewyork.com', 'The Noise Team');
      $this->email->to($doc['email_address']);
      $this->email->subject('Application Received');

      $message = "We're just letting you know that we've received your application and because you've got to the end, your application has been given priority. If our team deems you as a good match, we will get in contact with you.\n\n -- The Noise Team @ New York";

      $this->email->message($message);


      if (!$this->email->send())
      {
         echo "Oops! Please try again!";
         return;
      }

      echo "Success!";
      return;
   }

}
