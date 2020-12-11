<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\DataService as DataService;
use DB;
use Session;
use App\User;
use App\Mission;
use App\Team;
use App\Lesson;
use App\Alarmclock;
use App\Timelog;
class ServiceController extends Controller
{
	protected $DataService;
    //
    function __construct (DataService $DataService){
    	$this->DataService= $DataService;
    }

    //增加用户（测试数据）
    public function addUser (Request $request){
    	for($i=0;$i<$request->get('num');$i++){
    		$model = new User;
    		$model -> addUser(rand(10000000,99999999));
    	}
    }

    //检查登录状态
    public function checkCode(Request $request){
        $data=$this->DataService->testInput(Session::get('id'));
        if($data=="default"||$data==""){
            echo "default";
        }else{
            $array =  User::where('id','=',$data)->get(['id','code','nickname'])->toArray();
            if(sizeof($array)>0){
                if(!empty($array[0]['nickname'])){
                    echo $array[0]['nickname'];                    
                }else{
                    echo "newuser";
                }

            }else{
                echo "denied";
            }

        }
    }


    //注册
    public function changeNickname(Request $request){
        if($request->method()=="POST"){
            $nickname=$this->DataService->testInput($request->post('nickname'));
            $id=$this->DataService->testInput(Session::get('id'));
            if(empty($nickname)){
                echo "未知错误";
            }else{
                User::where('id','=',$id)->update([
                    'nickname' => $nickname,
                    'registertime' => $this->DataService->getMillisecond()
                ]);
            }
        }
    }


    //登录
    public function login(Request $request){
        if($request->method()=="POST"){
            $data=$this->DataService->testInput($request->post('code'));
            if($data=="default"||$data==""){
                echo "default";
            }else{
                $array =  User::where('code','=',$data)->get(['id','code','nickname'])->toArray();
                if(sizeof($array)>0){
                    Session::put("id",$array[0]['id']);
                    User::where('id','=',$array[0]['id'])->update([
                        'lastlogintime' => $this->DataService->getMillisecond()
                    ]);
                    Session::save();
                    return redirect('/missionlist');                  
                }else{
                    echo "denied";
                }

            }
        }
    }


    //设置任务内容
    public function setMissionContent(Request $request){
        if($request->method()=="POST"){
            $data=$request->post('data');
            $title = $request->post('title');
            $group = $request->post('group');
            $status = $request->post('status');
            $model = new Mission;
            $model->setMissionContent($title,$data,$this->DataService->getMillisecond(),$group,$status);
        }
    }


    //获取任务内容
    public function getMissionContent(Request $request){
        // if($request->method()=="POST"){
            $data=$this->DataService->testInput($request->post('id'));
            $array = Mission::where("id","=",$data)->get(['content'])->toArray();
            if(sizeof($array)>0){
                echo $array[0]['content'];
            }
        // }
    }


    //在设置任务中获取任务标题列表
    public function getMissionName(){
        $array = Mission::get()->toArray();
        for($i=0;$i<sizeof($array);$i++){
            // echo "<option values={{$array[$i]['id']}}>{{$array[$i]['title']}}</option>";
            // echo "<option value=".$i.">aaa</option>";
            echo "<option value=".($i+1).">".$array[$i]['title']."</option>";
        }
    }


    //设置问题
    public function createQuest(Request $request){
        $id=$request->get('id');
        $array = Mission::where('id','=',$id)->get(['quest'])->toArray();
        $quest = json_decode($array[0]['quest'],true);
        $type=$request->get('type');
        switch ($type) {
            case 1:
                # code...
                if(empty($quest)){
                    $quest['0']['content']='在这里输入问题';
                    $quest['0']['type']='1';
                    // $quest[(sizeof('0')]['answer']='';
                }else{
                    $quest[sizeof($quest)]['content']='在这里输入问题';
                    $quest[(sizeof($quest)-1)]['type']='1';
                    // $quest[(sizeof($quest)-1)]['answer']='';
                }
                Mission::where("id","=",$id)->update([
                    'quest' => json_encode($quest,JSON_UNESCAPED_UNICODE),
                ]);
                // dd($quest);

                echo $this -> DataService -> showSetQuest($id);
                break;
            case 2:
                if(empty($quest)){
                    $quest['0']['content']='在这里输入问题';
                    $quest['0']['type']='2';
                    $quest['0']['answer']='请输入答案';
                }else{
                    $quest[sizeof($quest)]['content']='在这里输入问题';
                    $quest[(sizeof($quest)-1)]['type']='2';
                    $quest[(sizeof($quest)-1)]['answer']='请输入答案';
                }
                Mission::where("id","=",$id)->update([
                    'quest' => json_encode($quest,JSON_UNESCAPED_UNICODE),
                ]);
                // dd($quest);

                echo $this -> DataService -> showSetQuest($id);


                break;

            case 3:
                if(empty($quest)){
                    $quest['0']['content']='在这里输入问题';
                    $quest['0']['type']='3';
                    $quest['0']['answer']='';
                }else{
                    $quest[sizeof($quest)]['content']='在这里输入问题';
                    $quest[(sizeof($quest)-1)]['type']='3';
                    $quest[(sizeof($quest)-1)]['answer']='';
                }
                Mission::where("id","=",$id)->update([
                    'quest' => json_encode($quest,JSON_UNESCAPED_UNICODE),
                ]);
                // dd($quest);

                echo $this -> DataService -> showSetQuest($id);


                break;
            default:
                # code...
                break;
        }
    }

    //增加选项
    public function addSelection(Request $request){
        $id=$request->get('id');
        $qid=$request->get('qid');
        $array = Mission::where('id','=',$id)->get(['quest'])->toArray();
        $quest = json_decode($array[0]['quest'],true);
        if(empty($quest[$qid]['answer'])){
            $quest[$qid]['answer']['0']['content']='在这里输入选项';
            $quest[$qid]['answer']['0']['istrue']='0';
        }else{
            $quest[$qid]['answer'][sizeof($quest[$qid]['answer'])]['content']='在这里输入选项';
            $quest[$qid]['answer'][sizeof($quest[$qid]['answer'])-1]['istrue']=0;           
        }
        Mission::where("id","=",$id)->update([
            'quest' => json_encode($quest,JSON_UNESCAPED_UNICODE),
        ]);
        echo $this -> DataService -> showSetQuest($id);
    }



    //修改内容
    public function changeQuestContent(Request $request){
        $id=$request->get('id');
        $qid=$request->get('qid');
        $type=$request->get('type');
        $data=$request->get('data');
        $array = Mission::where('id','=',$id)->get(['quest'])->toArray();
        $quest = json_decode($array[0]['quest'],true);
        switch ($type) {
            case 'selectioncontent':
                $aid=$request->get('aid');
                $quest[$qid]['answer'][$aid]['content']=$data;
                # code...
                break;

            case 'istrue':
                $aid=$request->get('aid');
                $quest[$qid]['answer'][$aid]['istrue']=$data;
                break;

            case 'questcontent':
                $quest[$qid]['content']=$data;



                break;            
            default:
                # code...
                break;
        }
        Mission::where("id","=",$id)->update([
            'quest' => json_encode($quest,JSON_UNESCAPED_UNICODE),
        ]);
    }



    //显示问题
    public function showQuest(Request $request){
        $id=$request->get('id');
        echo $this -> DataService -> showSetQuest($id);
    }



    //获取问题卡
    public function getQuestCard(Request $request){
        $id = $this->DataService->testInput($request->post('id'));
        $array = Mission::where('id','=',$id)->get(['quest','status'])->toArray();
        //判断日期
        if($array[0]['status']==0){
            $callback = "
            <div id='join'>加入队伍</div>
            <div id='create'>成为队长</div>
            ";
        }else{
            $quest = json_decode($array[0]['quest'],true);
            $callback = "";
            if(!empty($quest)){
                for($i=0;$i<sizeof($quest);$i++){
                    $callback =  $callback."<div class='quest'>";
                    switch ($quest[$i]['type']) {
                        case 1:
                            $callback =  $callback."<p class='questtitle' type='text' mid='".$id."' qid='".$i."'>".$quest[$i]['content']."</p><br>";
                            if(!empty($quest[$i]['answer'])){
                                for($j=0;$j<sizeof($quest[$i]['answer']);$j++){
                                    $callback = $callback.$j.":"."<p class='selectioncontent' type='text' mid='".$id."' qid='".$i."' aid='".$j."'>".$quest[$i]['answer'][$j]['content']."</p>";
                                    if($quest[$i]['answer'][$j]['istrue']==1){
                                        $callback = $callback."<input class='istrue' type='checkbox' mid='".$id."' qid='".$i."' aid='".$j."' checked='checked'></input><br>";
                                    }else{
                                        $callback = $callback."<input class='istrue' type='checkbox' mid='".$id."' qid='".$i."' aid='".$j."'></input><br>";
                                    }
                                    
                                }
                            }
                            # code...
                            break;
                        case 2:
                            $callback =  $callback."<p class='questtitle' type='text' mid='".$id."' qid='".$i."' >".$quest[$i]['content']."</p><br>";
                            $callback = $callback."这是一道简答题，答案将为文字<br>";

                            break;


                        case 3:
                            $callback =  $callback."<p class='questtitle' type='text' mid='".$id."' qid='".$i."' >".$quest[$i]['content']."</p><br>";
                            $callback = $callback."这是一道简答题，答案将为链接<br>";

                            break;
                        default:
                            # code...
                            break;
                    }
                    $callback =  $callback."</div>";
                }           
            }            
        }


        echo $callback;

          
    }



    public function getMissionList(){
        $array = Mission::get('groupname')->groupBy('groupname')->toArray();
        // dd($array);
        $callback = "";
        // for($i=0;$i<sizeof($array);$i++){
        //     $callback = $callback.$array[$i]['groupname'];
        //     $callback = $callback."<br>";
        // }
        foreach ($array as $key => $value) {
            # code...
            $callback = $callback.$key."<br>";
        }
        echo $callback;
    }




    public function createTeam(Request $request){
        $uid = Session::get('id');
        $id = $this->DataService->testInput($request->get('id'));
        $array = Mission::where('id','=',$id)->get(['groupname'])->toArray();
        $groupname=$array[0]['groupname'];
        $user = User::where('id','=',$uid)->get(['inteam'])->toArray();
        $inteam = json_decode($user[0]['inteam'],true);
        if(!empty($inteam)){
            for($i=0;$i<sizeof($inteam);$i++){
                if($inteam[$i]['groupname']==$groupname){
                    echo "isin";
                    return;
                }
            }
            $model = new Team;
            $users['0']['uid']=$uid;
            $users['0']['score']=0;
            $code = $this -> DataService -> checkUnique(rand(1000,9999));
            $result = $model -> createTeam($groupname,json_encode($users,JSON_UNESCAPED_UNICODE),$code);
            $resultId = $result -> id;
            $inteam[sizeof($inteam)]['groupname']=$groupname;
            $inteam[(sizeof($inteam)-1)]['teamid']=$resultId;
            $inteam[(sizeof($inteam)-1)]['position']=0;
            User::where('id','=',$uid)->update([
                    'inteam' => json_encode($inteam,JSON_UNESCAPED_UNICODE)
                ]);
            echo $resultId;
        }else{
            $model = new Team;
            $users['0']['uid']=$uid;
            $users['0']['score']=0;
            $code = $this -> DataService -> checkUnique(rand(1000,9999));
            $result = $model -> createTeam($groupname,json_encode($users,JSON_UNESCAPED_UNICODE),$code);
            $resultId = $result -> id;
            $inteam['0']['groupname']=$groupname;
            $inteam['0']['teamid']=$resultId;
            $inteam['0']['position']=0;
            User::where('id','=',$uid)->update([
                    'inteam' => json_encode($inteam,JSON_UNESCAPED_UNICODE)
                ]);
            echo $resultId;
        }
    }



    public function joinTeam(Request $request){
        $uid = Session::get('id');
        $id = $this->DataService->testInput($request->get('id'));
        $code = $this->DataService->testInput($request->get('code'));
        $array = Mission::where('id','=',$id)->get(['groupname'])->toArray();
        $groupname=$array[0]['groupname'];
        $user = User::where('id','=',$uid)->get(['inteam'])->toArray();
        $inteam = json_decode($user[0]['inteam'],true);
        $team = Team::where('code','=',$code)->get(['id','mission','users'])->toArray();
        if(sizeof($team)==0){
            echo "no such team.";
            return;
        }
        if($team[0]['mission']!=$groupname){
            echo "wrong team code";
            return;
        }
        $tid = $team[0]['id'];
        $users = json_decode($team[0]['users'],true);
        if(!empty($inteam)){
            for($i=0;$i<sizeof($inteam);$i++){
                if($inteam[$i]['groupname']==$groupname){
                    echo "isin";
                    return;
                }
            }
            $users[sizeof($users)]['uid']=$uid;
            $users[(sizeof($users)-1)]['score']=0;
            $inteam[sizeof($inteam)]['groupname']=$groupname;
            $inteam[(sizeof($inteam)-1)]['teamid']=$tid;
            $inteam[(sizeof($inteam)-1)]['position']=(sizeof($users)-1);
            Team::where('id','=',$tid)->update([
                    'users' => json_encode($users,JSON_UNESCAPED_UNICODE)
                ]);
            User::where('id','=',$uid)->update([
                    'inteam' => json_encode($inteam,JSON_UNESCAPED_UNICODE)
                ]);
            echo "success";
        }else{
            $users[sizeof($users)]['uid']=$uid;
            $users[(sizeof($users)-1)]['score']=0;
            Team::where('id','=',$tid)->update([
                    'users' => json_encode($users,JSON_UNESCAPED_UNICODE)
                ]);
            $inteam['0']['groupname']=$groupname;
            $inteam['0']['teamid']=$tid;
            $inteam['0']['position']=(sizeof($users)-1);
            Team::where('id','=',$tid)->update([
                    'users' => json_encode($users,JSON_UNESCAPED_UNICODE)
                ]);
            User::where('id','=',$uid)->update([
                    'inteam' => json_encode($inteam,JSON_UNESCAPED_UNICODE)
                ]);
            echo "success";
        }
    }



    public function infinity(){
        $num = sizeof(User::get()->toArray());
        $i=0;
        while(true){
            if(sizeof(User::get()->toArray())>$num){
                echo rand(4000,4500);
                break;
            }else{
                User::where('id','=',88)->update([
                        'registertime' => $i
                    ]);
                $i++;
                if($i==100){
                    echo rand(1000,1500);
                    break;
                }
                usleep(100000);
            }
        }
    }

    public function lessonAct(Request $request){
        $actnum = $request->get('actnum');
        $week = $request->get('week');
        switch ($actnum) {
            case 'gethistorylogbyday':
                $day = $request->get('day');
                $monday = $this->DataService->getMonday($week);
                $mesc = $this->DataService->getDateToMesc(date_format($monday,"Y-m-d H:i:s").".000")+($day*86400000);
                $data['data'] = Timelog::where('starttime','>',$mesc)->where('starttime','<',($mesc+86400000))->orderBy('starttime','asc')->get()->toArray();
                $data['date'] = date_format(date_add($monday,date_interval_create_from_date_string($day." days")),"Y-m-d");
                echo json_encode($data,JSON_UNESCAPED_UNICODE);
                break;
            case 'getachievement':
                $time = $request->get('time');
                $startTime = ((int)($time/86400000))*86400000;
                $nextdayTime = $startTime+86400000;
                $monday = $this->DataService->getMonday(0);
                $mondayDate = date_format($monday,"Y-m-d H:i:s").".000";
                $mondaymesc = $this->DataService->getDateToMesc($mondayDate);
                $data = Timelog::orderBy('starttime','asc')->get()->toArray();
                if(empty($data)){
                    echo "[]";
                    return ;
                }
                // dd($data);
                $historyTime=$data[0]['starttime'];
                $historyStartTime = ((int)($historyTime/86400000))*86400000;
                $historyWeek=(int)((((int)($data[0]['starttime']/86400000))*86400000-$startTime)/604800000);
                $historyMonday=$this->DataService->getMonday($historyWeek);
                $historyMondayDate = date_format($historyMonday,"Y-m-d H:i:s").".000";
                $historyMondayMesc = $this->DataService->getDateToMesc($historyMondayDate);
                $weekTimeSet = $historyMondayMesc;
                $weekSet = $historyWeek;
                $array['weekTotal'][0]['week']=$historyWeek;
                $array['weekTotal'][0]['timeTotal']=0;
                $array['weekTotal'][0]['times']=0;
                $daySet = (((int)($data[0]['starttime']/86400000))*86400000-$startTime)/86400000;
                $dayTimeSet = $historyStartTime;
                $array['dayTotal'][0]['date']=$daySet;
                $array['dayTotal'][0]['timeTotal']=0;
                $array['dayTotal'][0]['times']=0;
                $array['weekmax']=0;
                $array['daymax']=0;
                $array['singlemax']=0;
                $array['totalTime']=0;
                $j=0;
                $k=0;
                for($i=0;$i<sizeof($data);$i++){
                    $array['totalTime']+=$data[$i]['time'];
                    if($data[$i]['time']>$array['singlemax']){
                        $array['singlemax']=$data[$i]['time'];
                    }
                    //-------------------------------------------------周部分----------------------------------------------
                    if(empty($array['weekTotal'][$j]['times'])){
                        $array['weekTotal'][$j]['times']=0;
                    }
                    if($data[$i]['starttime']>$weekTimeSet&&$data[$i]['starttime']<($weekTimeSet+604800000)){
                        $array['weekTotal'][$j]['week'] = $weekSet;
                        $array['weekTotal'][$j]['timeTotal']=$array['weekTotal'][$j]['timeTotal']+$data[$i]['time'];
                        $array['weekTotal'][$j]['times']++;
                    }else{
                        while($weekTimeSet<$data[$i]['starttime']-604800000){
                            $weekSet++;
                            $weekTimeSet = $weekTimeSet+604800000;
                        }
                        $j++;
                        $array['weekTotal'][$j]['times']=1;
                        $array['weekTotal'][$j]['week'] = $weekSet;
                        $array['weekTotal'][$j]['timeTotal']=$data[$i]['time'];
                    }
                    if($array['weekTotal'][$j]['timeTotal']>$array['weekmax']){
                        $array['weekmax']=$array['weekTotal'][$j]['timeTotal'];
                    }


                    //-------------------------------------------------日部分----------------------------------------------
                    if(empty($array['dayTotal'][$k]['times'])){
                        $array['dayTotal'][$k]['times']=0;
                    }
                    if($data[$i]['starttime']>$dayTimeSet&&$data[$i]['starttime']<($dayTimeSet+86400000)){
                        $array['dayTotal'][$k]['date'] = $daySet;
                        $array['dayTotal'][$k]['timeTotal']=$array['dayTotal'][$k]['timeTotal']+$data[$i]['time'];
                        $array['dayTotal'][$k]['times']++;
                    }else{
                        while($dayTimeSet<$data[$i]['starttime']-86400000){
                            $daySet++;
                            $dayTimeSet = $dayTimeSet+86400000;
                        }
                        $k++;
                        $array['dayTotal'][$k]['times']=1;
                        $array['dayTotal'][$k]['date'] = $daySet;
                        $array['dayTotal'][$k]['timeTotal']=$data[$i]['time'];
                    }
                    if($array['dayTotal'][$k]['timeTotal']>$array['daymax']){
                        $array['daymax']=$array['dayTotal'][$k]['timeTotal'];
                    }

                }
                //  dd($array);
                // echo $historyStartTime."<br>";
                // echo $historyMondayMesc."<br>";
                // echo $historyWeek."<br>";
                // echo $historyTime."<br>";
                // echo $historyMondayDate."<br>";
                // echo $this->DataService->getMsecToMescdate($historyTime)."<br>";
                // echo $i."<br>";
                // echo sizeof($data)."<br>";
                // echo $time."<br>";
                // echo $this->DataService->getMsecToMescdate($time)."<br>";
                // echo $startTime."<br>";
                // echo $this->DataService->getMsecToMescdate($startTime)."<br>";
                // echo $nextdayTime."<br>";
                // echo $this->DataService->getMsecToMescdate($nextdayTime)."<br>";
                // echo $mondayDate."<br>";
                // echo $mondaymesc;
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                break;
            case 'settimelog':
                $starttime = $request->get('starttime');
                $stoptime = $request->get('stoptime');
                $model = new Timelog;
                $model->setTimelog($starttime,$stoptime-$starttime,$stoptime);
                break;
            case 'show_table':
                $monday = $this->DataService->getMonday($week);
                $arr["data"]=                            [
                                ["","","","","","","","","","","","","","",""],
                                ["","","","","","","","","","","","","","",""],
                                ["","","","","","","","","","","","","","",""],
                                ["","","","","","","","","","","","","","",""],
                                ["","","","","","","","","","","","","","",""],
                                ["","","","","","","","","","","","","","",""],
                                ["","","","","","","","","","","","","","",""],
                            ];
                $arr["isFinished"]=[
                                        [false,false,false,false,false,false,false,false,false,false,false,false,false,false,false],
                                        [false,false,false,false,false,false,false,false,false,false,false,false,false,false,false],
                                        [false,false,false,false,false,false,false,false,false,false,false,false,false,false,false],
                                        [false,false,false,false,false,false,false,false,false,false,false,false,false,false,false],
                                        [false,false,false,false,false,false,false,false,false,false,false,false,false,false,false],
                                        [false,false,false,false,false,false,false,false,false,false,false,false,false,false,false],
                                        [false,false,false,false,false,false,false,false,false,false,false,false,false,false,false],
                                    ];
                $date = date_format($monday,"Y-m-d"); 
                for($i=0;$i<7;$i++){
                    // echo $date;
                    // for($j=0;$j<15;$j++){
                    //     $arr[$i][$j]
                    // }
                    $data = Lesson::where('date','=',$date)->orderBy('block','asc')->get(['text','block','finished'])->toArray();
                    for($j=0;$j<sizeof($data);$j++){
                        $block = $data[$j]['block'];
                        $arr["data"][$i][$block]=$data[$j]['text'];
                        if($data[$j]['finished']=="true"){
                            $arr["isFinished"][$i][$block]=true;                         
                        }else{
                            $arr["isFinished"][$i][$block]=false; 
                        }

                    }
                    $arr['date'][$i]=date_format($monday,"m-d");
                    $date = date_format(date_add($monday,date_interval_create_from_date_string("1 days")),"Y-m-d");
                }
                echo json_encode($arr,JSON_UNESCAPED_UNICODE);
                break;
            case 'setalarmclock':
                $time = $request->get('time');
                $model = new Alarmclock;
                $model->setAlarmclock($time);
                
                break;
                
            case 'deletealarmclock':
                $time = $request->get('time');
                Alarmclock::where('time','=',$time)->delete();
                break;


            case 'getalarmclock':
                $arr = [];
                $data = Alarmclock::get()->toArray();
                $i=0;
                for($i=0;$i<sizeof($data);$i++){
                    $arr[$i]=$data[$i]['time'];
                }
                echo json_encode($arr,JSON_UNESCAPED_UNICODE);
                break;

            case 'blur':
                $monday = $this->DataService->getMonday($week);
                $row = $request->get('row');
                $block = $request->get('block');
                $text = $request->get('text');
                $date = date_format(date_add($monday,date_interval_create_from_date_string($row." days")),"Y-m-d");
                // $data = Lesson::where('date','=',$date)->where('block','=',$block)->get(['id','date','text'])->toArray();
                $result = Lesson::where('date','=',$date)->where('block','=',$block)->update([
                            'text' => $text
                        ]);
                if($result==0){
                    $data = Lesson::where('date','=',$date)->where('block','=',$block)->get(['id','date','text'])->toArray();
                    if(sizeof($data)==0){
                        $model = new Lesson;
                        $model->setLessonContent($date,$text,$block,"false");                    
                    }

                }else{

                }

                break;

            case 'finished':
                $monday = $this->DataService->getMonday($week);
                $row = $request->get('row');
                $block = $request->get('block');
                $text = $request->get('text');
                $date = date_format(date_add($monday,date_interval_create_from_date_string($row." days")),"Y-m-d");
                $data = Lesson::where('date','=',$date)->where('block','=',$block)->get(['finished'])->toArray();
                if(sizeof($data)==0){
                    $model = new Lesson;
                    $model->setLessonContent($date,"",$block,"true");       
                    echo "true";
                }else if($data[0]['finished']=="true"){
                    $result = Lesson::where('date','=',$date)->where('block','=',$block)->update([
                                'finished' => "false"
                            ]);
                    echo "false";
                }else{
                    $result = Lesson::where('date','=',$date)->where('block','=',$block)->update([
                                'finished' => "true"
                            ]);     
                    echo "true";               
                }
                break;

            case "copy":
                $monday = $this->DataService->getMonday($week);
                $date = date_format($monday,"Y-m-d"); 
                for($i=0;$i<7;$i++){
                    $date = date_format(date_sub($monday,date_interval_create_from_date_string("7 days")),"Y-m-d");
                    $data = Lesson::where('date','=',$date)->get(['text','block'])->toArray();
                    $date = date_format(date_add($monday,date_interval_create_from_date_string("7 days")),"Y-m-d");
                    Lesson::where('date','=',$date)->update([
                                    'text' => ""
                                ]);
                    for($j=0;$j<sizeof($data);$j++){
                        $block = $data[$j]['block'];
                        $text=$data[$j]['text'];
                        $result = Lesson::where('date','=',$date)->where('block','=',$block)->update([
                                    'text' => $text
                                ]);
                        if($result==0){
                            $model = new Lesson;
                            $model->setLessonContent($date,$text,$block,"false");                    

                        }else{

                        }
                    }
                    $date = date_format(date_add($monday,date_interval_create_from_date_string("1 days")),"Y-m-d");
                }

                break;

            default:
                # code...
                break;
        }
    }


    public function daBoss(Request $request){
        switch ($request->get('actnum')) {
            case 'asp':
                $asp = $request->get('asp');
                $asp = $asp*0.75*0.75;
                echo "<table>";
                for($i=1;$asp>0.52;$i++){
                    echo "<tr><td>".$i."</td><td>".$asp."</td></tr>";
                    $asp = $asp*0.99-0.001;
                }
                echo "<tr><td>".$i."</td><td>".$asp."</td></tr>";
                echo "</table>";
                break;

            case 'skm':
                $skm = $request->get('skm');
                $skm = floor($skm*0.5);
                echo "<table>";
                for($i=1;$i<200;$i++){
                    echo "<tr><td>".$i."</td><td>".$skm."</td></tr>";
                    $skm = (integer)$skm*1.15;
                    // $skm = $skm*1.15;
                    // if(!is_int($skm)){
                    //     $skm = (int)floor($skm);                        
                    // }

                }
                echo "<tr><td>".$i."</td><td>".$skm."</td></tr>";
                echo "</table>";
                break;
            
            default:
                # code...
                break;
        }



    }



    public function testAct(){
        $client = new http\Client;
        $request = new http\Client\Request;
        $request->setRequestUrl('https://api.opendota.com/api/players/166564190');
        $request->setRequestMethod('GET');
        $request->setOptions(array());
        $request->setHeaders(array(
          'Cookie' => '__cfduid=d038e9ae3b72f5d73cc9e3201da21bdba1605172734'
        ));
        $client->enqueue($request)->send();
        $response = $client->getResponse();
        echo $response->getBody();
    }


    public function doPractice(Request $request){
        switch($request->post('act')){
            case "initOrigin":
                $i=0;
                $j=mt_rand(30,180);
                for($i=0;$i<$j;$i++){
                    $array[$i]=mt_rand(0,10000000);
                }
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                break;
            case "quicksort":
                $array=json_decode($request->post('array'),true);
                $result = $this->DataService->quickSort($array);
                echo json_encode($result,JSON_UNESCAPED_UNICODE);
                break;
            case "bubblesort":
                $array=json_decode($request->post('array'),true);
                $result = $this->DataService->bubbleSort($array);
                echo json_encode($result,JSON_UNESCAPED_UNICODE);
                break;
            case "search":
                $array=json_decode($request->post('array'),true);
                $num=$request->post('num');
                echo $this->DataService->binarySearch($array,$num);
            default:break;
        }
    }
}
