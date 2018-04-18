<?php

namespace app\admin\controller;

use app\admin\controller\Base;
use think\Request;
use app\admin\model\Users as UsersModel;

class Users extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
    	$keywords = $request->get('keywords');
		$model = new UsersModel();
    	if ($keywords) {
			$records = $model->where('username','like',"%{$keywords}%")->paginate(5);
		} else {
			$records = $model->paginate(5);
		}

		$this->assign(['records' => $records, 'keywords' => $keywords]);
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $record = UsersModel::get($id);
		$this->assign('record',$record);
		return $this->fetch();
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
    	//删除用户
        $users_model = new UsersModel();
		$result = $users_model->where('id','=',$id)->delete();
		if($result){
			//删除用户评论
			$comment_model = new \app\admin\model\Comment;
			$comment_model->where('user_id','=',$id)->delete();
			return $this->success('用户删除成功',url('/admin/users/index'));
		}else{
			return $this->serror('用户删除失败',url('/admin/users/index'));
		}
    }
}
