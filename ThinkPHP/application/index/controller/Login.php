<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\Users;

class Login extends Controller
{
	/**
     * 显示用户登录页面
	 * 
     */
	public function login()
    {
        return $this->fetch();
    }
	
	/**
     * 执行用户登录
     *
     */
	public function dologin(Request $request)
    {
    	$data = $request->post();
        $model = new Users;
		$result = $model->where('username','=',$data['username'])->where('password','=',md5($data['password']))->find();
        if($result){
        	$user = [
				'userid' => $result['id'],
				'username' => $result['username'],
				'nickname' => $result['nickname']
			];
        	session('user',$user);

			return $this->success('登录成功',url('index/index/index'));
		} else{    						
			return $this->error('登录失败,请重试',url('index/users/login'));
		}					
    }
	
	/**
     * 执行用户退出
     *
     */
	public function logout()
    {
		session('user',null);
		return $this->redirect(url('/'));
    }
	
	/**
     * 显示用户注册页面
	 * 
     */
	public function register()
    {
        return $this->fetch();
    }
	
	/**
     * 执行用户注册
     *
     */
	public function doregister(Request $request)
    {
    	$data = $request->post();
		if(!array_key_exists('protocol', $data)){
			return $this->error('注册必须同意相关政策',url('index/users/register'));
		}
		if($data['captcha'] == null){
			return $this->error('必须输入验证码',url('index/users/register'));
		}
		$captcha = $data['captcha'];
		if(!captcha_check($captcha)){
			return $this->error('验证码错误,请重新输入',url('index/users/register'));
		}
		if($data['password'] !== $data['repassword']){
			return $this->error('密码两次输入不一致,请重新输入',url('index/users/register'));
		}
		
        $model = new Users;
		$model->username = $data['username'];  
		$model->password = md5($data['password']);       						
	 	$model->nickname = $data['nickname']; 
		$model->addtime = time();
		$result = $model->save();
		if($result){
			return $this->success('注册成功',url('index/users/login'));
		} else{    						
			return $this->error('注册失败,请重试',url('index/users/register'));
		}
	}


}
