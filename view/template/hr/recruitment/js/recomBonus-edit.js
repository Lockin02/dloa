$(document).ready(function() {
	$("#developPositionName").mouseover(function(){
		$.validationEngine.buildPrompt(this,"������д����Ҫ���ܣ����з�����д�������ԡ����ſ���д�������硢���ۿ���д�ͻ���ϵ��������ְλ����ְλ����",null);
	});
//	$("#positionName").yxcombogrid_position({
//		hiddenId : 'positionId',
//		isShowButton : false,
//		width:350
//	});
	$("#job").change(function(){
		$("#jobName").val($(this).find('option:selected').text());
	});
   validate({
		"bonus" : {
			required : true,
			custom : ['percentageNum']
		},
		"bonusProprotion" : {
			required : true,
			custom : ['percentageNum']
		},
		"firstGrantBonus" : {
			required : true
		},
		"secondGrantBonus" : {
			required : true
		},
		"positionName" : {
			required : true
		},
		"deptName" : {
			required : true
		}
	});
   $("#deptName").yxselect_dept({
		hiddenId : 'deptId',
		event : {
			selectReturn : function(e,row){
				$("#positionName").val("");
				$("#positionId").val("");
				$("#positionName").yxcombogrid_position("remove");
				//ְλѡ��
				$("#positionName").yxcombogrid_position({
					hiddenId : 'positionId',
					width:350,
					gridOptions : {
						param:{deptId:row.dept.id}
					}
				});
				$("#positionName").yxcombogrid_position("show");
			}
		}
});
})
  function checkForm(){
		if($("#uploadfileList").html() =="" || $("#uploadfileList").html() =="�����κθ���"){
			alert('���ϴ�������');
			return false;
		}
		return true;
	}
function calculate(){
	var money = $("#bonus").val();
	$("#firstGrantBonus").val(money/2);
	$("#secondGrantBonus").val($("#bonus").val() - money/2);
}