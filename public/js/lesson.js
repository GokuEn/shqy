var singleClick = null;
var week=0;
var starttime = 0;
var now=0;
var stop=true;
var nowTime=0;
var someThingIsOpen = false;

$().ready(function(){
	getNowTime();
	getAchievement();
	dragFunc("alarmclock");
	dragFunc("achievement");
	dragFunc("timelog");
	getTime();
	countDown();
	getAlarmclockList();
	showTable(week);
	$(document).on("click",".data",function(){
    	if(body_area.isContenteditable=="false"){
    		$(this).toggleClass("finished");
    	}
	});
	window.onbeforeunload = function(event){
		body_area.stopLog();
	};
	// window.onblur = function(event){
	// 	body_area.stopLog();
	// };

});



function getData(url,text,actnum,row,block,week){
	$.get(url,
			{
				actnum:actnum,
				text:text,
				row:row,
				block:block,
				week:week
			},
			function(data,status){
				$("#title").focus();
			});	
}

function showTable(week){
	$.get("lessonact",
			{
				actnum:"show_table",
				week:week
			},
			function(data,status){
				body_area.arr=JSON.parse(data).data;
				body_area.dayWeek=JSON.parse(data).date;
				body_area.isFinished=JSON.parse(data).isFinished;
			});	
	if(week==0){
		if(now.getDay()==0){
			day = 7;
		}else{
			day = now.getDay();
		}
		$(".data").css({"background-color":"white"});
		$(".data").filter(":lt("+((day-1)*15)+")").css({"background-color":"#E0E0E0"});
	}else if(week>0){
		$(".data").css({"background-color":"white"});
	}else{
		$(".data").css({"background-color":"#E0E0E0"});
	}
}

function copyTable(week){
	$.get("lessonact",
			{
				actnum:"copy",
				week:week
			},
			function(data,status){
			});	
}


function setFinished(actnum,row,block,week){
	$.get("lessonact",
			{
				actnum:actnum,
				row:row,
				block:block,
				week:week
			},
			function(data,status){
				return data;
			});	
}


function getTime(){
	body_area.date = now.format("yyyy年MM月dd日");
	body_area.time = now.format("   hh:mm:ss");
	setTimeout("getTime()",50);
}

function getNowTime(){
	now = new Date();
	nowTime = now.getTime()+28800000;
	if(now.format("hhmmss")=="235959"&&stop==false){
		body_area.stopLog();
		$("#stop_log").attr("disabled", true);
		$("#start_log").attr("disabled", true);
		setTimeout("body_area.startLog()",2000);
	}
	setTimeout("getNowTime()",10);
}

function countDown(){
	var i=0;

	var timeLimit;
	var h;
	var m;
	var s;
	var sec;
	var limit;
	for(i=0;i<body_area.alertTime.length;i++){
		limit = new Date(now.format("yyyy-MM-dd")+" "+body_area.alertTime[i])
		timeLimit = limit.getTime();
		output = new Date(timeLimit-nowTime);
		if(output.format("hhmmss")=="000000"){
			alert("到时间了！");
		}
		body_area.countTime[i] = "距"+limit.format("hh:mm")+"还有"+output.format("hh:mm:ss");
	}
	setTimeout("countDown()",50);
}


function getAlarmclockList(){
	$.get("lessonact",
			{
				actnum:"getalarmclock",
			},
			function(data,status){
				body_area.alertTime=JSON.parse(data);
			});	
}


function thisTime(){
	if(stop==true){
		return;
	}else{
		var h,m,s;
		time = new Date(nowTime-starttime-28800000);
		body_area.thistime = time.format("hh:mm:ss");
		setTimeout("thisTime()",50);
	}

}


function getAchievement(){
	var achievementArray;
	$.get("lessonact",{
		actnum:"getachievement",
		time:nowTime,
	},function(result,status){
		achievementArray = JSON.parse(result);
		if(achievementArray.length==0){
			return ;
		}
		if(achievementArray.dayTotal[(achievementArray.dayTotal.length-1)]['date']==0){
			body_area.timetoday = new Date(achievementArray.dayTotal[(achievementArray.dayTotal.length-1)]['timeTotal']-28800000).format("hh:mm:ss");
		}else{
			body_area.timetoday = "00:00:00";
		}
		body_area.timetotal = new Date(achievementArray.totalTime-28800000).format("hh:mm:ss");
		body_area.timeweek = new Date(achievementArray.weekTotal[(achievementArray.weekTotal.length-1)]['timeTotal']-28800000).format("hh:mm:ss");
		body_area.totaldays = achievementArray.dayTotal.length;
		body_area.longestsingletime = new Date(achievementArray.singlemax-28800000).format("hh:mm:ss");
		body_area.longesttimeweek = new Date(achievementArray.weekmax-28800000).format("hh:mm:ss");
		body_area.longestdaytime = new Date(achievementArray.daymax-28800000).format("hh:mm:ss");
	});
}


function getHistoryLog(actnum,logdata){
	switch(actnum){
		case "day":
			var i = 0;
			$.get("lessonact",
			{
				actnum:"gethistorylogbyday",
				week:week,
				day:logdata,
			},function(result,status){
				data = JSON.parse(result);
				body_area.logday = data.date;
				body_area.logdayTimes = data.data.length;
				body_area.daylog = new Array();
				for(i=0;i<data.data.length;i++){
					body_area.daylog[i] = new Array();
					body_area.daylog[i]['starttime'] = new Date(data.data[i]['starttime']-28800000).format("hh:mm:ss");
					body_area.daylog[i]['time'] = new Date(data.data[i]['time']-28800000).format("hh:mm:ss");
					body_area.daylog[i]['stoptime'] = new Date(data.data[i]['stoptime']-28800000).format("hh:mm:ss");
				}
			});
			break;
		default:break;
	}
}



function dragFunc(id) {
    var Drag = document.getElementById(id);
    Drag.onmousedown = function(event) {
        var ev = event || window.event;
        event.stopPropagation();
        var disX = ev.clientX - Drag.offsetLeft;
        var disY = ev.clientY - Drag.offsetTop;
        document.onmousemove = function(event) {
            var ev = event || window.event;
            Drag.style.left = ev.clientX - disX + "px";
            Drag.style.top = ev.clientY - disY + "px";
            Drag.style.cursor = "move";
        };
    };
    Drag.onmouseup = function() {
        document.onmousemove = null;
        this.style.cursor = "default";
    };
};

Date.prototype.format = function(fmt){
  var o = {
    "M+" : this.getMonth()+1,                 //月份
    "d+" : this.getDate(),                    //日
    "h+" : this.getHours(),                   //小时
    "m+" : this.getMinutes(),                 //分
    "s+" : this.getSeconds(),                 //秒
    "q+" : Math.floor((this.getMonth()+3)/3), //季度
    "S"  : this.getMilliseconds()             //毫秒
  };

  if(/(y+)/.test(fmt)){
    fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
  }
        
  for(var k in o){
    if(new RegExp("("+ k +")").test(fmt)){
      fmt = fmt.replace(
        RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));  
    }       
  }

  return fmt;
}


function getVue(elName){

	var body_area = new Vue({
		el:elName,
		data:{
			arr:[
	    			["","","","","","","","","","","","","","",""],
	    			["","","","","","","","","","","","","","",""],
	    			["","","","","","","","","","","","","","",""],
	    			["","","","","","","","","","","","","","",""],
	    			["","","","","","","","","","","","","","",""],
	    			["","","","","","","","","","","","","","",""],
	    			["","","","","","","","","","","","","","",""],
				],
			isFinished:[
	    			["","","","","","","","","","","","","","",""],
	    			["","","","","","","","","","","","","","",""],
	    			["","","","","","","","","","","","","","",""],
	    			["","","","","","","","","","","","","","",""],
	    			["","","","","","","","","","","","","","",""],
	    			["","","","","","","","","","","","","","",""],
	    			["","","","","","","","","","","","","","",""],
				],
			date:"",
			time:"",
            dayWeek:["1","2","3","4","5","6","7"],
            dayMark:["一","二","三","四","五","六","日"],
            toggle:"切换为编辑模式",
            isContenteditable:"false",
            alertTime:[],
            countTime:[],
            timetoday:"",
            timetotal:"",
            longestsingletime:"",
            longestdaytime:"",
            timeweek:"",
            longesttimeweek:"",
            thistime:"00:00:00",
            totaldays:"",
            logday:"",
            logdayTimes:0,
            daylog:[],
		},
		methods:{
            toggleEdit:function(){
                if(this.isContenteditable=="false"){
                    this.toggle = "切换为学习模式";
                    this.isContenteditable = "true";
                }else{
                    this.toggle = "切换为编辑模式";
                    this.isContenteditable = "false";                        
                }
            },
            click:function(event,row,col){
            	if(this.isContenteditable=="false"){
	    			setFinished('finished',row,col,week);            		
            	}

            },
            blur:function(event,day,block){
				clearTimeout(singleClick);
		    	var text=event.target.innerText;
		    	getData('lessonact',text,'blur',day,block,week);
            },
            prev:function(){
            	week--;
				showTable(week);
            },
            next:function(){
            	week++;
				showTable(week);
            },
            copy:function(){
				if(confirm("注意，此操作不可逆")){
					copyTable(week);
					setTimeout("showTable("+week+")",100)
				}
            },
            toggleAlarmclock(){
            	$("#alarmclock").toggle();
            },
            initAlarmclock(){
            	var check = /[0-2][0-9]:[0-6][0-9]/;
            	var alertTime = prompt("请确定闹钟时间（格式为mm:ss）","");
            	if(alertTime != null && alertTime != ""){
            		if(check.test(alertTime)){
            			this.alertTime.push(alertTime);
						$.get("lessonact",
								{
									actnum:"setalarmclock",
									time:alertTime,
								},
								function(data,status){
								});	
            		}else{
            			alert("格式有误");
            		}
            		
            	}
            },
            cancelAlarmclock(num){
				$.get("lessonact",
						{
							actnum:"deletealarmclock",
							time:this.alertTime[num],
						},
						function(data,status){
						});	
            	this.alertTime.splice(num,1);
            	this.countTime.splice(num,1);
            },
            startLog:function(){
            	if(stop==true){
					starttime = nowTime;
					$("#start_log").attr("disabled", true);
					$("#stop_log").attr("disabled", false);
					stop=false;
            	}else{
            		return;
            	}
            	thisTime();
			},
			stopLog:function(){
				if(stop==true){
					return;
				}else{
					$("#stop_log").attr("disabled", true);
					$("#start_log").attr("disabled", false);
					$.get("lessonact",
							{
								actnum:"settimelog",
								starttime:starttime,
								stoptime:nowTime,
							},function(result,status){
							});
					stop=true;
				}
				getAchievement();
			},
			showAchievement:function(){
				getAchievement();
				$("#achievement").toggle();
			},
			toggleTimeLog:function(){
				$("#timelog").toggle();
			},
			getHistoryLogByDay:function(day){
				$("#day_log").show();
				getHistoryLog("day",day);
			},
			closeHistoryLogByDay:function(){
				$("#day_log").hide();
			},
			test:function(){
                alert(week);
			}
		}
	});
	return body_area;
}

