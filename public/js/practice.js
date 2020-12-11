$().ready(function(){
	initOrigin();
	


});

function initOrigin(){
	$.post("/dopractice",
		{
			act:"initOrigin",
			_token:token,
		},
		function(data,status){
			body_area.originArray=JSON.parse(data);
		});
}


function getVue(elName){

	var body_area = new Vue({
		el:elName,
		data:{
			originArray:[],
			sortedArray:[],
			foundedNum:0,
		},
		methods:{
			quicksort:function(){
				$.post("dopractice",{
					act:"quicksort",
					_token:token,
					array:JSON.stringify(body_area.originArray),
				},function(data,status){
					body_area.sortedArray=JSON.parse(data);
				});
				$("#founded_array").show();
			},
			bubblesort:function(){
				$.post("dopractice",{
					act:"bubblesort",
					_token:token,
					array:JSON.stringify(body_area.originArray),
				},function(data,status){
					body_area.sortedArray=JSON.parse(data);
				});
				$("#founded_array").show();
			},
			search:function(){
				var num=$("#search_value").val();
				$.post("dopractice",{
					act:"search",
					_token:token,
					num:num,
					array:JSON.stringify(body_area.sortedArray),
				},function(data,status){
					body_area.foundedNum=data;
				});
				$("#founded_num").show();
			},
		}
	});
	return body_area;
}

