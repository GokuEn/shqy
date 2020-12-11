<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Service\DataService as DataService;

use Session;
class ViewController extends Controller
{
	protected $DataService;
    //
    function __construct (DataService $DataService){
    	$this->DataService= $DataService;
    }




    public function index(Request $request){
    	$code = $this -> DataService -> testInput($request->get('code','default'));
    	return view('index',compact('code'));
    }

    //列表页，用于查看有什么营，进入营的主界面

    public function missionList(Request $request){
    	return view('missionlist');
    }


    //每一个营里面的内容，有当日的课程和任务卡

    public function mission(Request $request){
    	$id=$this -> DataService -> testInput($request->get('id'));
    	return view('mission',compact('id'));
    }

    public function setQuest(){
    	return view('setquest');
    }

    public function infinityView(){
        return view('infinityview');
    }

    public function lesson(){
        return view('lesson');
    }
    public function jielong(){
        return view('jielong');
    }
    public function practice(){
        return view('practice');
    }
}