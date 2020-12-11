<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    //定义表名 
    protected $table = 'lesson';
    //主键
    protected $primaryKey = 'id';
    //禁止操作时间
    public $timestamps = false;
    //定义允许插入模型
    protected $fillable = ['id','date','text','block','finished'];

    public function setLessonContent($date,$text,$block,$finished){
    	$this->date=$date;
    	$this->text=$text;
    	$this->block=$block;
    	$this->finished=$finished;
    	$this->save();
	}
}
