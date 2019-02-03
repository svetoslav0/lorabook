<?php
/**
 * Created by PhpStorm.
 * User: Svetoslav
 * Date: 26-Jan-19
 * Time: 2:18 PM
 */

class Users extends CI_Controller
{
    function index(){
        if ($this->is_logged()){
            redirect('feed');
        } else{
            $data['main_view'] = 'not_logged/login/login_form';
            $this->load->view('not_logged/include/template', $data);
        }
    }

    function is_logged(){
        if($this->session->userdata('is_logged')){
            return true;
        }
        return false;
    }

    function login(){
        $this->load->model('users_model');

        if (!$this->users_model->user_exists() || !$this->users_model->check_login()){
            $this->session->set_flashdata('errmsg', 'Wrong username or password.');
            redirect('users', 'refresh');
        } else{
            if ($this->users_model->_user_is_active()) {
                $username = $this->input->post('username');
                $fname = $this->users_model->get_full_name($username)->fname;
                $lname = $this->users_model->get_full_name($username)->lname;
                $user_data = [
                    'username' => $this->input->post('username'),
                    'fname' => $fname,
                    'lname' => $lname,
                    'is_logged' => true
                ];
                $this->session->set_userdata($user_data);
                $this->index();
            } else {
                $data['main_view'] = 'not_logged/login/account_not_active';
                $this->load->view('not_logged/include/template', $data);
            }
        }
    }

    function logout(){
        $this->session->sess_destroy();
        redirect('users');
    }

    function register(){
        $data['main_view'] = 'not_logged/register/register_form';
        $this->load->view('not_logged/include/template', $data);
    }

    function validate_register(){
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_error_delimiters('<p class="validation_err">', '</p>');

        $val->set_rules('username', 'Username', 'trim|required|alpha_numeric|min_length[5]');
        $val->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $val->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');
        $val->set_rules('fname', 'First Name', 'trim|required|min_length[3]');
        $val->set_rules('lname', 'Last Name', 'trim|required|min_length[3]');
        $val->set_rules('email', 'Email', 'trim|required|valid_email');

        if ($val->run()){
            $this->load->model('users_model');
            if ($this->users_model->_username_exists() == FALSE){
                if ($this->users_model->_email_exists() == FALSE){
                    $activation_code = $this->_generate_activation_code(32);
                    if ($this->users_model->_insert_user($activation_code)){
                        if ($this->_send_email($activation_code)){
                            $data['main_view'] = 'not_logged/register/register_done';
                            $this->load->view('not_logged/include/template', $data);
                        } else {
                            $this->session->set_flashdata('reg_err', 'Some error with sending the email has appeared. Please contact the system administrator.');
                        }
                    } else {
                        $this->session->set_flashdata('reg_err', 'Some error with the database appeared. Please contact the system administrator.');
                        redirect('users/register', 'refresh');
                    }
                } else {
                    // check if email exists
                    $this->session->set_flashdata('reg_err', 'An account with this Email has already been registered.');
                    redirect('users/register', 'refresh');
                }
            } else {
                // check if username already exists
                $this->session->set_flashdata('reg_err', 'An account with this Username has already been registered.');
                redirect('users/register', 'refresh');
            }
        } else {
            $this->register();
        }
    }

    function activate(){
        $this->load->helper('url');
        $username = $this->uri->segment(3);
        $activation_code = $this->uri->segment(4);

        $this->load->model('users_model');
        if ($this->users_model->validate_activation($username, $activation_code)){
            $this->users_model->activate_user($username);
            $data = [
                'main_view' => 'not_logged/register/account_activated'
            ];
            $this->load->view('not_logged/include/template', $data);
        } else {
            echo 'Wrong code dude...';
            // TODO: load->view("What is this code??")
        }
    }

    function _generate_activation_code($len){
        $pool = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = '';
        for($i = 0; $i < $len; $i++){
            $str .= substr($pool, mt_rand(0, strlen($pool) - 1), 1);
        }
        return $str;
    }

    function _send_email($code){
        $username = $this->input->post('username');
        $email_to = $this->input->post('email');

        $config = [
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.abv.bg',
            'smtp_port' => 465,
            'smtp_user' => '',
            'smtp_pass' => ''
        ];

        $message = "Hello, $username!" . PHP_EOL;
        $message .= 'Your registration is almost completed.' . PHP_EOL;
        $message .= 'Click on the link bellow to activate your account:' . PHP_EOL;
        $message .= site_url('users/activate') . '/' . $username . '/' . $code;

        $this->load->library('email');

        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->set_crlf("\r\n");

        $this->email->from($config['smtp_user'], 'LoraBook`s System Administrator');
        $this->email->to($email_to);
        $this->email->subject('Activate you LoraBook account');
        $this->email->message($message);

        if($this->email->send()){
            return TRUE;
        }
        //show_error($this->email->print_debugger());
        return FALSE;
    }

}