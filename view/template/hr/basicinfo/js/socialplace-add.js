$(document).ready(function() {

	var url = "?model=hr_basicinfo_socialplace&action=checkRepeat";
	$("#socialCity").ajaxCheck({
		url : url,
		alertText : "* �ù�����Ѵ���",
		alertTextOk : "* ����"
	});
	//��֤������
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
						alert("�����ظ��ˣ�");
						boolcheck=false;
						return;
					}
				}
				boolcheck=true;
			}
		});
		return boolcheck;
	}