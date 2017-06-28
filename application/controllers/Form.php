<?php

class Form extends CI_Controller {

        function __construct() { 
         parent::__construct(); 
         $this->load->library('session'); 
         $this->load->helper('form'); 
         $this->load->helper('url'); 
      } 

        public function index()
        {

         $config = Array(        
            // 'protocol' => 'sendmail',
            // 'smtp_host' => 'ssl://smtp.gmail.com',
            // 'smtp_port' => 25,
            // 'smtp_user' => 'aaronwilsonphoto@gmail.com',
            // 'smtp_pass' => '@Cages01',
            // 'smtp_timeout' => '4',
            'mailtype'  => 'html', 
            'charset'   => 'iso-8859-1'
        );
        $this->load->library('email', $config);

        $from_email = "aaronwilsonprofessional@gmail.com"; 


                $this->load->helper(array('form', 'url'));

                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('<div class="error">', '</div>');


                $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
                $this->form_validation->set_rules('emailcnf', 'Email Confirmation', 'trim|required|matches[email]');
                $this->form_validation->set_rules('optradio', 'Access Level', 'trim|required');

                $to_email = $this->input->post('email');
                $department = $this->input->post('department');
                $employee_type = $this->input->post('optradio');

        $this->email->from($from_email, 'LLPA Employee Network'); 
        $this->email->to($to_email);
        $this->email->subject('Employee Network Invitation');

        $data = array(
             'userName'=> ''
                 );

        $body = $this->load->view('emails/email_invite.php',$data,TRUE);
        $this->email->message($body);

        $this->email->send();

                if ($this->form_validation->run() == FALSE)
                {
                        $this->load->view('myform');
                }
                else
                {
                        $this->load->view('formsuccess');
                }


        }


}