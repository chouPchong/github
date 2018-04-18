<?php
use think\Route;

//后台
//管理员登录
Route::get('admin/login','admin/Login/login');
//管理员登录校验
Route::post('admin/check','admin/Login/check');
//管理员退出
Route::get('admin/logout','admin/Login/logout');
//管理员管理
Route::get('admin/admin/index','admin/Admin/index');
//管理员添加
Route::get('admin/admin/add','admin/Admin/add');
//执行管理员添加
Route::post('admin/admin/save','admin/Admin/save');
//管理员编辑
Route::get('admin/admin/edit/:id','admin/Admin/edit');
//执行管理员编辑
Route::post('admin/admin/update/:id','admin/Admin/update');
//管理员删除
Route::get('admin/admin/delete/:id','admin/Admin/delete');
//管理员控制页
Route::get('admin/index','admin/Index/index');

//新闻类别添加
Route::get('admin/category/create','admin/Category/create');
//执行新闻类别添加
Route::post('admin/category/save','admin/Category/save');
//新闻类别列表
Route::get('admin/category/index','admin/Category/index');
//查看新闻类别
Route::get('admin/category/read/:id','admin/Category/read');
//编辑新闻类别
Route::get('admin/category/edit/:id','admin/Category/edit');
//执行编辑
Route::put('admin/category/update/:id','admin/Category/update');
//删除新闻类别
Route::get('admin/category/delete/:id','admin/Category/delete');

//添加合作伙伴
Route::get('admin/partner/create','admin/Partner/create');
//执行合作伙伴的添加
Route::post('admin/partner/save','admin/Partner/save');
//合作伙伴列表
Route::get('admin/partner/index','admin/Partner/index');
//查看合作伙伴
Route::get('admin/partner/read/:id','admin/Partner/read');
//编辑合作伙伴
Route::get('admin/partner/edit/:id','admin/Partner/edit');
//执行编辑
Route::put('admin/partner/update/:id','admin/Partner/update');
//删除合作伙伴
Route::get('admin/partner/delete/:id','admin/Partner/delete');

//新闻添加
Route::get('admin/news/create','admin/News/create');
//执行新闻的添加
Route::post('admin/news/save','admin/News/save');
//新闻列表
Route::get('admin/news/index','admin/News/index');
//查看新闻
Route::get('admin/news/read/:id','admin/News/read');
//编辑新闻
Route::get('admin/news/edit/:id','admin/News/edit');
//执行编辑
Route::put('admin/news/update/:id','admin/News/update');
//删除新闻到回收站
Route::get('admin/news/move/:id','admin/News/move');
//新闻回收站
Route::get('admin/news/recycle','admin/News/recycle');
//新闻回收站还原
Route::get('admin/news/restore/:id','admin/News/restore');
//彻底删除新闻
Route::get('admin/news/delete/:id','admin/News/delete');
//发表新闻
Route::put('admin/news/checked','admin/News/checked');
//取消发表新闻
Route::put('admin/news/unchecked','admin/News/unchecked');

//用户管理
Route::get('admin/users/index','admin/users/index');
//用户删除
Route::get('admin/users/delete/:id','admin/users/delete');
//用户详情
Route::get('admin/users/read/:id','admin/users/read');

//评论管理
Route::get('admin/comment/index','admin/comment/index');
//评论删除
Route::get('admin/comment/delete/:id','admin/comment/delete');
//评论详情
Route::get('admin/comment/read/:id','admin/comment/read');



//前台
//首页
Route::get('/','index/Index/index');
//读取单条新闻
Route::get('index/index/read/:id','index/Index/read');
//读取分类新闻
Route::get('index/index/catenews/:id','index/Index/catenews');
//搜索新闻
Route::get('index/index/search','index/Index/search');

//用户登录
Route::get('index/users/login','index/Login/login');
//执行用户登录
Route::post('index/users/login','index/Login/dologin');
//执行用户退出
Route::get('index/users/logout','index/Login/logout');
//用户注册
Route::get('index/users/register','index/Login/register');
//执行用户注册
Route::post('index/users/register','index/Login/doregister');
//用户首页
Route::get('index/users/index/:id','index/Users/index');
//用户修改
Route::get('index/users/edit/:id','index/Users/edit');
//执行用户修改
Route::post('index/users/update/:id','index/Users/update');


//保存用户评论
Route::post('index/comment/save','index/Comment/save');
//新闻评论列表
Route::get('index/comment/read/:id','index/Comment/read');