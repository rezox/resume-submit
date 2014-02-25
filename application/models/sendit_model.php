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
         || empty($doc['phone_number']))
         return false;

      return true;
   }

   public function send_confirmation_email($email)
   {
      $this->load->library('email');

      // clearing, just in case.
      $this->email->clear();

      $this->email->from('noreply@urbancompass.com', 'The Urban Compass Team');
      $this->email->to($email);
      $this->email->subject('Application Received!');

      $message = "We're just letting you know that we've received your application and because you've got to the end, your application has been given priority. If our team deems you as a good match, we will get in contact with you.\n\n -- The Urban Compass Team";

      $this->email->message($message);

      if (!$this->email->send())
         return false;

      return true;
   }

   public function send_email($doc, $resume)
   {
      //making email doc
      $this->load->library('email');
      $this->email->clear(); // just incase;

      // making message headers
      $this->email->from($doc['email_address'], $doc['full_name']);
      $this->email->to('steve@urbancompass.com');
      $this->email->to('theinternship@urbancompass.com');
      $this->email->subject('Software Engineer Application Received - ' . $doc['full_name']);

      // making message body
      $message = "This individual successfully completeled the challenge.\n\n";
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
