$().ready(function(){
});

function getdata(times){
	var i=0;
	for(i=0;i<times;i++){
		$.get("https://api.opendota.com/api/players/166564190/matches/?hero_id=59&win=1",
		{
		},
		function(data,status){
			body_area.testdata++;
			body_area.data=body_area.data+JSON.stringify(data);
		});	
	}
}

function getVue(elName){

	var body_area = new Vue({
		el:elName,
		data:{
			testdata:0,
			data:"",
		},
		methods:{
			starttest:function(){
				var times=$("#times").val();
				getdata(times);
			},
		}
	});
	return body_area;
}

