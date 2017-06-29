<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Community Auth - Examples Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2017, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Form extends MY_Controller {

        function __construct() { 
         parent::__construct(); 
         $this->load->library('session'); 
         $this->load->helper('form'); 
         $this->load->helper('url'); 
      } 

        public function index()
    {
        if ( $this->verify_min_level(9) )
        {
        // Get Elements from Form
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
            $to_email = $this->input->post('email');
            $department = $this->input->post('department');
            $auth_level = $this->input->post('auth_level');

        // Customize this array for your user
        $user_data = [
            'username'   => $to_email,
            'passwd'     => 'Temporary1',
            'email'      => $to_email,
            'auth_level' => $auth_level,
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'department' => $department,
        ];


        echo $this->load->view('security/page_header', '', TRUE);
        

        // Load resources
        $this->load->helper('auth');
        $this->load->model('security/security_model');
        $this->load->model('security/validation_callables');
        $this->load->library('form_validation');

        $this->form_validation->set_data( $user_data );
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $validation_rules = [
            [
                'field' => 'username',
                'label' => 'username',
                'rules' => 'max_length[35]|is_unique[' . db_table('user_table') . '.username]',
                'errors' => [
                    'is_unique' => 'Username already in use.'
                ]
            ],

            [
                'field' => 'first_name',
                'label' => 'first_name',
                'rules' => 'max_length[35]',
                'errors' => [
                    'max_length' => 'Name must be under 35 characters long.'
                ]
            ],
            [
                'field' => 'passwd',
                'label' => 'passwd',
                'rules' => [
                    'trim',
                    'required',
                    [ 
                        '_check_password_strength', 
                        [ $this->validation_callables, '_check_password_strength' ] 
                    ]
                ],
                'errors' => [
                    'required' => 'The password field is required.'
                ]
            ],
            [
                'field'  => 'email',
                'label'  => 'email',
                'rules'  => 'trim|required|valid_email|is_unique[' . db_table('user_table') . '.email]',
                'errors' => [
                    'is_unique' => 'Email address already in use.'
                ]
            ],
            [
                'field' => 'auth_level',
                'label' => 'auth_level',
                'rules' => 'required|integer|in_list[1,6,9]'
            ]
        ];

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
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[' . db_table('user_table') . '.email]');

        $to_email = $this->input->post('email');
        $department = $this->input->post('department');
        $employee_type = $this->input->post('optradio');

        $this->email->from($from_email, 'LLPA Employee Network'); 
        $this->email->to($to_email);
        $this->email->subject('Employee Network Invitation');

        // Get Elements from Form
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
            $to_email = $this->input->post('email');
            $department = $this->input->post('department');
            $auth_level = $this->input->post('auth_level');

        // Customize this array for your user
        $user_data = [
            'username'   => $to_email,
            'passwd'     => 'Aw907074',
            'email'      => $to_email,
            'auth_level' => $auth_level,
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'department' => $department,
            'auth_level' => $auth_level,
        ];

        $body = $this->load->view('emails/email_invite.php',$user_data,TRUE);
        $this->email->message($body);

        $this->email->send();



        if( $this->form_validation->run() == FALSE )
        {
            echo $this->load->view('security/page_header', '', TRUE);
            echo $this->load->view('security/myform', '', TRUE);
            echo $this->load->view('security/page_footer', '', TRUE);
        }
        else
        {
            $user_data['passwd']     = $this->authentication->hash_passwd($user_data['passwd']);
            $user_data['user_id']    = $this->security_model->get_unused_id();
            $user_data['created_at'] = date('Y-m-d H:i:s');

            // If username is not used, it must be entered into the record as NULL
            if( empty( $user_data['username'] ) )
            {
                $user_data['username'] = NULL;
            }

            $this->db->set($user_data)
                ->insert(db_table('user_table'));

            if( $this->db->affected_rows() == 1 ) {
                echo '<div class="position"><div class ="container"><h1>Congratulations</h1>' . '<p>User ' . $user_data['username'] . ' was created.</p></div></div>';
                echo $this->load->view('security/page_header', '', TRUE);
                echo $this->load->view('security/formsuccess', '', TRUE);
                echo $this->load->view('security/page_footer', '', TRUE);
            }
        }

        echo $this->load->view('security/page_footer', '', TRUE);
        } /*End Admin Check - verify_min_level(9)*/

        elseif( $this->verify_min_level(6) )
            {
                echo $this->load->view('security/page_header', '', TRUE);
                echo $this->load->view('security/employee', '', TRUE);
                echo $this->load->view('security/page_footer', '', TRUE);
            }
        else
            {       
                redirect( site_url( LOGIN_PAGE . '?logout=0', $redirect_protocol ) );
            }


    }

}