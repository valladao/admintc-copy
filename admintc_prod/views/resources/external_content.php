<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!isset($content)) {
	$content = "";
}

switch ($content) {
	case 'login':
		$this->load->view('content/login');
		break;
	
	default:
		$this->load->view('content/login');
		break;
}
