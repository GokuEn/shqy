<!DOCTYPE HTML>
<html>
<head>
    <title>练习</title>
    <script type="text/javascript" src="js/jquery-3.4.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script type="text/javascript" src="js/practice.js"></script>
    <link rel="stylesheet" type="text/css" href="css/practice.css"/>
</head>
<body>
	@verbatim
    <div id="body_area">
        <div id="origin_array">
            <p v-for="item in originArray">{{ item }},</p>
        </div>
        <div id="sorted_array">
            <button @click="quicksort">快排</button><button @click="bubblesort">冒泡</button>
            <p v-for="(item,index) in sortedArray">{{ index }}:{{ item }},</p>
        </div>
        <div id="founded_array">
            <input type="number" id="search_value"></div>
            <button @click="search">查找</button>
            <p id="founded_num">是第{{ foundedNum }}个元素</p>
        </div>
    </div>
	@endverbatim
    <script type="text/javascript">
        var token = '{{csrf_token()}}';
        var body_area=getVue("#body_area");
    </script>
</body>
</html>