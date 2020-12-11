<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alarmclock extends Model
{
    //定义表名 
    protected $table = 'alarmclock';
    //主键
    protected $primaryKey = 'id';
    //禁止操作时间
    public $timestamps = false;
    //定义允许插入模型
    protected $fillable = ['time'];

    public function setAlarmclock($time){
    	$this->time=$time;
    	$this->save();
	}
}
