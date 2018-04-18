<?php

namespace app\admin\model;

use think\Model;

class Admin extends Model
{
    public function setPasswordAttr($value)
	{
		return md5($value);
	}
}
