<?php

namespace app\index\controller;

use think\Controller;
use app\index\model\Category;

class Base extends Controller
{
	/**
	 * 显示前台页面公共数据
	 */
	protected function _initialize()
	{
		$CateModel = new Category();
		$naviCate = $CateModel->getCategoryByPid('0');
		$this->assign('naviCate', $naviCate);

		$this->_init();
	}

	/**
	 * 子类初始化数据
	 */
	protected function _init()
	{

	}
}