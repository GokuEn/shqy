<!DOCTYPE HTML>
<html>
<head>
    <title>备忘录</title>
    <script type="text/javascript" src="js/jquery-3.4.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script type="text/javascript" src="js/lesson.js"></script>
    <link rel="stylesheet" type="text/css" href="css/lesson.css"/>
</head>
<body>
	@verbatim
    <div id="body_area">
        <div id="alarmclock">
            <button id="newalarmclock" @click="initAlarmclock()">新建闹钟</button><button id="hidealarmclock" @click="toggleAlarmclock()">关闭窗口</button><p v-for="(item,index) in countTime" class="alarmclock">{{ item }}<button class="cancel" @click="cancelAlarmclock(index)">取消闹钟</button></p>
        </div>
        <div id="achievement">
            <p>单次最长学习时间：{{ longestsingletime }}</p><p>单日最长学习时间：{{ longestdaytime }}</p><p>单周最长学习时间：{{ longesttimeweek }}</p><p>总学习天数：{{ totaldays }}</p>
        </div>
        <div id="day_log">
            <p>在{{ logday }}共学习了{{ logdayTimes }}次</p>
            <table id="day_log_table">
                <tr>
                    <td>开始时间</td><td>持续时间</td><td>结束时间</td>
                </tr>
                <tr v-for="item in daylog">
                    <td>{{ item['starttime'] }}</td><td>{{ item['time'] }}</td><td>{{ item['stoptime'] }}</td>
                </tr>
            </table>
        </div>



    	<div id="title">
    		当前时间：{{date}}{{time}}<button @click="prev()">上一周</button><button @click="next()">下一周</button><button @click="copy()">获取上周数据</button><button @click="toggleEdit()">{{ toggle }}</button><button @click="toggleAlarmclock()">显示闹钟</button><button @click="showAchievement()">成就</button><button @click="toggleTimeLog()">计时器</button>


            <div id="timelog"><button id="start_log" @click="startLog()">开始计时</button><button id="stop_log" @click="stopLog()" disabled="true">结束计时</button><p id="thistime">{{ thistime }}</p><p>今日已学习时间：{{ timetoday }}</p><p>本周学习时间：{{ timeweek }}</p><p>总学习时间：{{ timetotal }}</p></div>
    		<div id="table">
    			<table id="lession">
    				<tr @click="test"><th>日期</th><th>星期</th><th colspan="15"></th></tr>
    				<tr v-for="(item,index) in arr">
    					<td class="td_default" @mouseenter="getHistoryLogByDay(index)" @mouseleave="closeHistoryLogByDay()">{{dayWeek[index]}}</td><td class="td_default" >{{dayMark[index]}}</td><td class="td_default data" v-for="(data,ind) in item" :contenteditable=isContenteditable @blur="blur(...arguments,index,ind)" @click="click(...arguments,index,ind)" :class={finished:isFinished[index][ind]}>{{ data }}</td>
    				</tr>
    			</table>
    		</div>
    	</div>
    </div>
	@endverbatim
    <script type="text/javascript">
        var body_area=getVue("#body_area");
    </script>
</body>
</html>