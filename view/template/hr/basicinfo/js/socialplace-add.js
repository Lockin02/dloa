$(document).ready(function() {

	var url = "?model=hr_basicinfo_socialplace&action=checkRepeat";
	$("#socialCity").ajaxCheck({
		url : url,
		alertText : "* 该购买地已存在",
		alertTextOk : "* 可用"
	});
	//验证必填项
	validate({
//		"socialCity" : {
//			required : true
//		}
	});

  })

var boolcheck=false;
	function checkOnly(){
		$.ajax({
			url: "?model=hr_basicinfo_socialplace&action=listJson",
			success: function(data){
				data=eval("("+data+")");
				var city=$("#socialCity").val();
				for(var i=0;i<data.length;i++){
					if(city==data[i].socialCity){
						alert("城市重复了！");
						boolcheck=false;
						return;
					}
				}
				boolcheck=true;
			}
		});
		return boolcheck;
	}