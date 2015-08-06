<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	public function index()
	{
		$this->display('stadium/index');
	}
	
	
	public function logout()
	{
		
		$this->session->unset_userdata('admin_info');
		
		redirect(site_url('member/admin_login'));
	}
}