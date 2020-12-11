<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jielong extends Model
{
    //定义表名 
    protected $table = 'jielong';
    //主键
    protected $primaryKey = 'id';
    //禁止操作时间
    public $timestamps = false;
    //定义允许插入模型
    protected $fillable = ['time1','time2','nickname1','nickname2','card'];

    public function setJielong($time1,$time2,$nickname1,$nickname2,$card){
    	$this->time1=$time1;
    	$this->time2=$time2;
    	$this->nickname1 = $nickname1;
    	$this->nickname2 = $nickname2;
    	$this->card = $card;
    	$this->save();
	}
}
