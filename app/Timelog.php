<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timelog extends Model
{
    //定义表名 
    protected $table = 'timelog';
    //主键
    protected $primaryKey = 'id';
    //禁止操作时间
    public $timestamps = false;
    //定义允许插入模型
    protected $fillable = ['id','starttime','time','stoptime'];

    public function setTimelog($starttime,$time,$stoptime){
    	$this->starttime=$starttime;
    	$this->time=$time;
    	$this->stoptime=$stoptime;
    	$this->save();
	}
}
