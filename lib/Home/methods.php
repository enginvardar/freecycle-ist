<?php

namespace Home;

use Config\DB;
use Config\Mail;

class methods {
	public function home($app)
	{
		$db = new DB();
		$pages = new Pages($db);
		$pageData = $pages->listAll();
		return array('template'=>'default.html','pages'=>$pageData);
	}


	public function contact($app,$method)
	{
		$mailer = new Mail();
	}
}