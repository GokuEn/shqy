<!DOCTYPE HTML>
<html>
<head>
    <title>完整demo</title>
    <script type="text/javascript" src="js/jquery-3.4.1.js"></script>
    <script type="text/javascript">
        $().ready(function(){
            $.get("/getmissionname",{

            },function(data,status){
                $('#missionname').html(data);
            });

            $(document).on("click",".setquestcard",function(){
                var type=$(this).attr('id');
                var id=$("#missionname").val();
                $.get("/createquest",{
                    type:type,
                    id:id
                },function(data,status){
                    $("#setquest").html(data);
                });
            });
            $(document).on("click",".addselection",function(){
                var id=$(this).attr('id');
                var qid=$(this).attr('qid');
                $.get("/addselection",{
                    id:id,
                    qid:qid
                },function(data,status){
                    $("#setquest").html(data);
                });
            });
            $(document).on("blur",".selectioncontent",function(){
                var id=$(this).attr('mid');
                var qid=$(this).attr('qid');
                var aid=$(this).attr('aid');
                var data=$(this).val();
                $.get("/changequestcontent",{
                    id:id,
                    qid:qid,
                    aid:aid,
                    data:data,
                    type:"selectioncontent"
                },function(data,status){

                });
            });
            $(document).on("click",".istrue",function(){
                var id=$(this).attr('mid');
                var qid=$(this).attr('qid');
                var aid=$(this).attr('aid');
                if($(this).prop("checked")){
                    var data=1;
                }else{
                    var data=0;
                }
                $.get("/changequestcontent",{
                    id:id,
                    qid:qid,
                    aid:aid,
                    data:data,
                    type:"istrue"
                },function(data,status){

                });
            });
            $(document).on("blur",".questtitle",function(){
                var id=$(this).attr('mid');
                var qid=$(this).attr('qid');
                var data=$(this).val();
                $.get("/changequestcontent",{
                    id:id,
                    qid:qid,
                    data:data,
                    type:"questcontent"
                },function(data,status){

                });
            });            




            $(document).on("change","#missionname",function(){
                var id=$("#missionname").val();
                $.get("/getmissioncontent",{
                    id:id
                },function(data,status){
                    $('#content').html(data);
                });
                $.get("/showquest",{
                    id:id
                },function(data,status){
                    $("#setquest").html(data);
                });
            });

        });
    </script>
</head>
<body>
    this is mission.
    <select id='missionname' name='missionname'></select><br>
    <button class='setquestcard' id="1">创建选择任务卡</button>
    <button class='setquestcard' id="2">创建答题任务卡</button>
    <button class='setquestcard' id="3">创建上传任务卡</button>
    <div id='setquest'></div>
    <div id='content'></div>
</body>
</html>