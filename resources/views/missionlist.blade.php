<!DOCTYPE HTML>
<html>
<head>
    <title>完整demo</title>
    <script type="text/javascript" src="js/jquery-3.4.1.js"></script>
    <script type="text/javascript">
        $().ready(function(){
            $.get('/checkcode',
                {
                },
                function(data,status){
                    if(data=='denied'){
                        alert("错误号码");
                    }else if(data=='default'){
                        alert('未输入号码');
                    }else if(data=='newuser'){
                        newUser();
                    }else{
                        alert('欢迎回来'+data);
                    }
                });

            $.get('/getmissionlist',{},function(data,status){
                $("#missionlist").html(data);
            });

            getChange();
            // while(true){
                // $.get('/infinity',{},function(data,status){
                //     $("#missionlist").append(data);
                // });
            // }
        });



        function newUser(code){
            var nickname = prompt("您是新用户，请输入一个昵称","");
            if(nickname){
                $.post('/changenickname',
                    {
                        nickname:nickname,
                        _token:'{{csrf_token()}}'
                    },
                    function(data,status){
                        
                    });
            }else{
                newUser();
            }
        }


        function getChange(){
            // $.get('/infinity',{},function(data,status){
            //     $("#missionlist").append(data);
            //     getChange();
            // }).fail(function(){
            //     alert('超时');
            //     getChange();
            // });
            $.ajax({url:"/infinity",success:function(result){
                $("#missionlist").append(result);
                getChange();
            }});
        }
    </script>
</head>
<body>
    this is missionlist.
    <div id='missionlist'></div>
</body>
</html>