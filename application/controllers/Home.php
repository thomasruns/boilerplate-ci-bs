<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
		$view_data = [
			'var' => 'some data'
		];
		$this->load->view('header', $view_data);
		$this->load->view('home', $view_data);
		$this->load->view('footer');
	}
}
