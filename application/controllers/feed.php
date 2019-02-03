<?php
/**
 * Created by PhpStorm.
 * User: Svetoslav
 * Date: 26-Jan-19
 * Time: 11:24 PM
 */

class Feed extends CI_Controller
{
    function index(){
        if (!$this->session->userdata('is_logged')){
            redirect('users');
        }

        $data['main_view'] = 'is_logged/feed/feed';
        $this->load->view('is_logged/include/template', $data);
    }
}