<?php
/**
 * Created by PhpStorm.
 * User: Svetoslav
 * Date: 26-Jan-19
 * Time: 3:19 PM
 */

class Users_model extends CI_Model
{
    function get_full_name($username){
        $this->db->select('fname, lname');
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        return $query->row();
    }

    function user_exists(){
        $this->db->select('username');
        $this->db->where('username', $this->input->post('username'));
        $query = $this->db->get('users');

        if ($query->num_rows() == 1){
            return true;
        }
        return false;
    }

    function check_login(){
        $username = $this->input->post('username');
        $password = md5($this->input->post('password'));

        $this->db->where('username', $username);
        $this->db->where('password', $password);
        $query = $this->db->get('users');

        if ($query->num_rows() == 1){
            return true;
        }
        return false;
    }

    function _user_is_active(){
        $username = $this->input->post('username');
        $email = $this->input->post('email');

        $this->db->where('username', $username);
        $this->db->where('active', 1);
        $query = $this->db->get('users');

        if ($query->num_rows() == 1){
            return TRUE;
        }
        return FALSE;
    }

    function _username_exists(){
        $username = $this->input->post('username');

        $this->db->where('username', $username);
        $query = $this->db->get('users');

        if ($query->num_rows() == 1){
            return TRUE;
        }
        return FALSE;
    }

    function _email_exists(){
        $email = $this->input->post('email');

        $this->db->where('email', $email);
        $query = $this->db->get('users');

        if ($query->num_rows() == 1){
            return TRUE;
        }
        return FALSE;
    }

    function _insert_user($activation_code){
        $data = [
            'username' => $this->input->post('username'),
            'password' => md5($this->input->post('password')),
            'fname' => $this->input->post('fname'),
            'lname' => $this->input->post('lname'),
            'email' => $this->input->post('email'),
            'active' => 0,
            'type' => 1,
            'activation_code' => $activation_code
        ];

        $this->db->insert('users', $data);
        if ($this->db->affected_rows() == 1){
            return TRUE;
        }
        return FALSE;
    }

    function validate_activation($username, $activation_code){
        $this->db->where('username', $username);
        $this->db->where('activation_code', $activation_code);
        $query = $this->db->get('users');

        if ($query->num_rows() == 1){
            return TRUE;
        }
        return FALSE;
    }

    function activate_user($username){
        $data = [
            'active' => 1,
            'activation_code' => ''
        ];

        $this->db->where('username', $username);
        $this->db->update('users', $data);
    }
}