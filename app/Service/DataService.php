<?php 
// 声明好命名空间
namespace App\Service;


use Illuminate\Http\Request;
use App\Service\DataService as DataService;
use DB;
use Session;
use App\User;
use App\Mission;
use App\Team;
class DataService{
	function getMillisecond() {
	    list($t1, $t2) = explode(' ', microtime());
	    return (int)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
	}


	function getSign($data,$salt){
	    $sign = md5($data.$salt);
	    return $sign;
	}

	function testInput($data) {
	    $data = trim($data);
	    $data = stripslashes($data);
	    $data = htmlspecialchars($data);
	    return $data;
	}



	function getMsecToMescdate($msectime)
	{
	    $msectime = $msectime * 0.001;
	    if(strstr($msectime,'.')){
	        sprintf("%01.3f",$msectime);
	        list($usec, $sec) = explode(".",$msectime);
	        $sec = str_pad($sec,3,"0",STR_PAD_RIGHT);
	    }else{
	        $usec = $msectime;
	        $sec = "000";
	    }
	    $date = date("Y-m-d H:i:s.x",$usec);
	    return $mescdate = str_replace('x', $sec, $date);
	}



	function getDateToMesc($mescdate)
	{
	    list($usec, $sec) = explode(".", $mescdate);
	    $date = strtotime($usec);
	    $return_data = str_pad($date.$sec,13,"0",STR_PAD_RIGHT);
	    return $msectime = $return_data;
	}

	function get_real_ip()

	{
	    
	    $ip=FALSE;
	    
	    //客户端IP 或 NONE
	    
	    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
	        
	        $ip = $_SERVER["HTTP_CLIENT_IP"];
	        
	    }
	    
	    //多重代理服务器下的客户端真实IP地址（可能伪造）,如果没有使用代理，此字段为空
	    
	    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	        
	        $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
	        
	        if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
	        
	        for ($i = 0; $i < count($ips); $i++) {
	            
	            if (!eregi ("^(10│172.16│192.168).", $ips[$i])) {
	                
	                $ip = $ips[$i];
	                
	                break;
	                
	            }
	            
	        }
	        
	    }
	    
	    //客户端IP 或 (最后一个)代理服务器 IP
	    
	    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
	    
	}


	public function showSetQuest($id){
        // $id=$request->get('id');
        $array = Mission::where('id','=',$id)->get(['quest'])->toArray();
        $quest = json_decode($array[0]['quest'],true);
        $callback = "";
        if(!empty($quest)){
	        for($i=0;$i<sizeof($quest);$i++){
	        	switch ($quest[$i]['type']) {
		        	case 1:
		        		$callback =  $callback."<input class='questtitle' type='text' mid='".$id."' qid='".$i."' value='".$quest[$i]['content']."'><br>";
		        		if(!empty($quest[$i]['answer'])){
		        			for($j=0;$j<sizeof($quest[$i]['answer']);$j++){
			    				$callback = $callback.$j.":"."<input class='selectioncontent' type='text' mid='".$id."' qid='".$i."' aid='".$j."' value='".$quest[$i]['answer'][$j]['content']."'></input>";
			    				if($quest[$i]['answer'][$j]['istrue']==1){
			    					$callback = $callback."<input class='istrue' type='checkbox' mid='".$id."' qid='".$i."' aid='".$j."' checked='checked'></input><br>";
			    				}else{
			    					$callback = $callback."<input class='istrue' type='checkbox' mid='".$id."' qid='".$i."' aid='".$j."'></input><br>";
			    				}
		        				
		        			}
		        		}
		        		$callback = $callback."<button class='addselection' id='".$id."' qid='".$i."'>增加选项</button></br>";
		        		# code...
		        		break;
		        	case 2:
		        		$callback =  $callback."<input class='questtitle' type='text' mid='".$id."' qid='".$i."' value='".$quest[$i]['content']."'><br>";
		        		$callback = $callback."这是一道简答题，答案将为文字<br>";

		        		break;


		        	case 3:
		        		$callback =  $callback."<input class='questtitle' type='text' mid='".$id."' qid='".$i."' value='".$quest[$i]['content']."'><br>";
		        		$callback = $callback."这是一道简答题，答案将为链接<br>";

		        		break;
		        	default:
		        		# code...
		        		break;
	        	}
	        }        	
        }

        return $callback;

	}


	public function checkUnique($code){
		$result = Team::where("code","=",$code)->get()->toArray();
		if(!empty($result)){
			return $this->checkUnique(rand(1000,9999));
		}else{
			return $code;
		}
	}

	public function getMonday($week){
	    $dateNow = getdate();
	    $dayWeek = $dateNow['wday'];
	    if($dayWeek==0){
	        $dayWeek=7;
	    }
	    $date = date_create($dateNow['year']."-".$dateNow['mon']."-".$dateNow['mday']);
	    $sub = $dayWeek-1-($week*7);
	    $date = date_sub($date,date_interval_create_from_date_string($sub." days"));
	    return $date;
	}

	public function quickSort($array){
		$j = sizeof($array);
		if($j<=1){
			return $array;
		}
		$key = $array[0];
		$left=array();
		$right=array();
		for($i = 1;$i < $j;$i++){
			if($array[$i]<$key){
				$left[]=$array[$i];
			}else{
				$right[]=$array[$i];
			}
		}
		$right=$this->quickSort($right);
		$left=$this->quickSort($left);
		return array_merge($left,array($key),$right);
	}

	public function bubbleSort($array){
		$j=sizeof($array);
		for($j;$j>1;$j--){
			for($i=1;$i<$j;$i++){
				if($array[$i]<$array[$i-1]){
					$array[$i]=$array[$i]+$array[$i-1];
					$array[$i-1]=$array[$i]-$array[$i-1];
					$array[$i]=$array[$i]-$array[$i-1];
				}
			}
		}
		return $array;
	}

	public function binarySearch($array,$num){
		$j=(int)(sizeof($array)/2);
		if($array[$j]==$num){
			return $j;
		}else if($array[$j]<$num){
			$newArray=array();
			for($i=$j;$i<sizeof($array);$i++){
				$newArray[]=$array[$i];
			}
			return $j+$this->binarySearch($newArray,$num);
		}else if($array[$j]>$num){
			$newArray=array();
			for($i=0;$i<$j;$i++){
				$newArray[]=$array[$i];
			}
			return $this->binarySearch($newArray,$num);
		}
	}
//j1=50,j2=25,j3=12,j4=6,j5=3,j6=1
}
?>