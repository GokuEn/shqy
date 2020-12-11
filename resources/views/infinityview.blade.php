<!DOCTYPE HTML>
<html>
<head>
    <title>监视器</title>
    <script type="text/javascript" src="js/jquery-3.4.1.js"></script>
    <script src="js/echarts.min.js"></script>
    <script type="text/javascript">
        $().ready(function(){
            var myChart = echarts.init(document.getElementById('main'));
            var array = [0,0,0,0,0,0,0];
            getChange(myChart,array);
        });




        function getChange(myChart,array){
            // $.get('/infinity',{},function(data,status){
            //     $("#missionlist").append(data);
            //     getChange();
            // }).fail(function(){
            //     alert('超时');
            //     getChange();
            // });
            $.ajax({url:"/infinity",success:function(result){
                array=[array[1],array[2],array[3],array[4],array[5],array[6],result];
                option = {
                    xAxis: {
                        type: 'category',
                        data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
                    },
                    yAxis: {
                        type: 'value'
                    },
                    series: [{
                        data: array,
                        type: 'line'
                    }]
                };
                myChart.setOption(option);
                    getChange(myChart,array);
            }});
        }
    </script>
</head>
<body>
    <div id='missionlist'></div>
    <div id="main" style="width: 600px;height:400px;"></div>
<!--         <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例


        // 指定图表的配置项和数据
        


        // 使用刚指定的配置项和数据显示图表。

    </script> -->
</body>
</html>