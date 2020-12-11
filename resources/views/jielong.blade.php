<!DOCTYPE HTML>
<html>
<head>
    <title>备忘录</title>
    <script type="text/javascript" src="js/jquery-3.4.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script type="text/javascript" src="js/jielong.js"></script>
    <script type="text/javascript" src="js/jquery-confirm.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/jielong.css"/>
    <link rel="stylesheet" type="text/css" href="css/jquery-confirm.min.css"/>
</head>
<body>
	@verbatim
    <div id="body_area">
<!--         <input type="text" name="times" id="times" value="输入测试次数">
        <button @click="starttest()">开始测试</button>
        <p>调用了{{testdata}}次</p>
        <p>{{ data }}</p> -->
        <p id='test'> 测试字体</p>
    </div>
	@endverbatim
    <script type="text/javascript">
        var body_area=getVue("#body_area");
    </script>
</body>
</html>