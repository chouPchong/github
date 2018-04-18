<?php

namespace app\admin\controller;

use think\Request;
use think\Controller;

class Login extends Controller
{

	/*
 	 * 管理员登录
 	 */
	public function login()
	{
		return $this->fetch();
	}

	/*
	 * 登录检测
	 */
	public function check(Request $Request)
	{
		$data = $Request->post();
		$item = \app\admin\model\Admin::where('username','=',$data['username'])->where('password','=',md5($data['password']))->find();
		if($item){
			$info= [
				'id' => $item->id,
				'name' => $item->username,
				'level' => $item->level
			];
			session('admin',$info);
			return $this->success('登陆成功',url('admin/index/index'));
		}else{
			return $this->error('用户名或密码错误,请重新登录',url('admin/login/login'));
		}

	}

	/*
	 * 管理员登出
	 */
	public function logout()
	{
		session('admin',null);
		return redirect(url('admin/login/login'));
	}
}