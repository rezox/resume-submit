<?php

class Sendit_model extends CI_Model {

   public function __construct()
   {
      parent::__construct();
   }

   public function validate_email($email)
   {
      $this->load->helper('email');
      return valid_email($email);
   }

   public function move_file($path)
   {
      $temp_path = "/tmp/resume-".mt_rand().".pdf";
      
      // renaming the file
      if (move_uploaded_file($path, $temp_path) === FALSE)
         return false;

      return $temp_path;
   }

   public function decode_doc($doc)
   {
      $doc = json_decode($doc, true);
      if (empty($doc))
         return false;

      return $doc;
   }

   public function validate_doc($doc)
   {
      if (empty($doc['full_name'])
         || empty($doc['email_address'])
         || empty($doc['phone_number'])
         || empty($doc['q1'])
         || empty($doc['q2']))
         return false;

      return true;
   }

   public function send_confirmation_email($email)
   {
      $this->load->library('email');

      // clearing, just in case.
      $this->email->clear();

      $this->email->from('jobs@noisenewyork.com', 'The Noise Team');
      $this->email->to($email);
      $this->email->subject('Application Received');

      $message = "We're just letting you know that we've received your application and because you've got to the end, your application has been given priority. If our team deems you as a good match, we will get in contact with you.\n\n -- The Noise Team @ New York";

      $this->email->message($message);

      if (!$this->email->send())
         return false;

      return true;
   }

   public function send_noise_email($doc, $resume)
   {
      //making email doc
      $this->load->library('email');
      $this->email->clear(); // just incase;

      // making message headers
      $this->email->from($doc['email_address'], $doc['full_name']);
      $this->email->to('steve@noisenewyork.com');
      //$this->email->to('jobs@noisenewyork.com');
      $this->email->subject('Software Engineer - Priority');

      // making message body
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

      // attaching hte message and resume.
      $this->email->message($message);
      $this->email->attach($resume);

      // finally sending mail
      if (!$this->email->send())
         return false;

      return true;
   }

}
