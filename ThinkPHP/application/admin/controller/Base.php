<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;

abstract class Base extends Controller
{
	protected function _initialize(){
		if(!session('admin.id') && !session('admin.name')){
       		return $this->error('管理员尚未登录',url('/admin/login'));
       	}
		$this->_init();
	}
	
	protected function _init(){
		
	}
}
