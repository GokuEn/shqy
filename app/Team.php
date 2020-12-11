<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    //定义表名 
    protected $table = 'team';
    //主键
    protected $primaryKey = 'id';
    //禁止操作时间
    public $timestamps = false;
    //定义允许插入模型
    protected $fillable = ['mission','users','code'];

    public function createTeam($mission,$users,$code){
    	$save['mission']=$mission;
    	$save['users']=$users;
    	$save['code']=$code;

    	$result = $this->create($save);
    	return $result;
    }
}
