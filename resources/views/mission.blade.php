<!DOCTYPE HTML>
<html>
<head>
    <title>完整demo</title>
    <script type="text/javascript" src="js/jquery-3.4.1.js"></script>
    <script type="text/javascript">
        $().ready(function(){
            $.post("/getmissioncontent",{
                id:{{ $id }},
                _token:'{{csrf_token()}}'
            },function(data,status){
                $('#content').html(data);
                alert(data);
            });
            $.post("/getquestcard",{
                id:{{ $id }},
                _token:'{{csrf_token()}}'
            },function(data,status){
                $('#questcard').html(data);
                alert(data);
            });            
            $(document).on("click","#join",function(){
                var code=prompt("请输入对应的队伍号码","");
                $.get("/jointeam",{
                    id:{{$id}},
                    code:code
                },function(data,status){
                alert(data);
                });
            });   
            $(document).on("click","#create",function(){
                $.get("/createteam",{
                    id:{{$id}}
                },function(data,status){
                alert(data);
                });
            });            
        });
    </script>
</head>
<body>
    this is mission.
    <div id='content'></div>
    <div id='questcard'></div>
</body>
</html>