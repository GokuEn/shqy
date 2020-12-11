<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    //定义表名 
    protected $table = 'mission';
    //主键
    protected $primaryKey = 'id';
    //禁止操作时间
    public $timestamps = false;
    //定义允许插入模型
    protected $fillable = ['id','title','content','createtime','questlist','hasgroup','status','group'];

    public function setMissionContent($title,$content,$createTime,$group,$status){
    	$this->title=$title;
    	$this->content=$content;
    	$this->createtime=$createTime;
    	$this->groupname=$group;
    	$this->status=$status;
    	$this->save();
    }
}
