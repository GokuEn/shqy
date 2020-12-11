<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //定义表名 
    protected $table = 'user';
    //主键
    protected $primaryKey = 'id';
    //禁止操作时间
    public $timestamps = false;
    //定义允许插入模型
    protected $fillable = ['id','nickname','registertime','lastlogintime','avatar','inteam'];


    public function addUser ($num){
		$this->code = $num;
		$this -> save();
	}
}
