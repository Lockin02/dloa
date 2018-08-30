$(document).ready(function() {
	$("#developPositionName").mouseover(function(){
		$.validationEngine.buildPrompt(this,"您可填写其主要技能，如研发可填写开发语言、网优可填写从事网络、销售可填写客户关系区域、其他职位可填职位名称",null);
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
				//职位选择
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
		if($("#uploadfileList").html() =="" || $("#uploadfileList").html() =="暂无任何附件"){
			alert('请上传附件！');
			return false;
		}
		return true;
	}
function calculate(){
	var money = $("#bonus").val();
	$("#firstGrantBonus").val(money/2);
	$("#secondGrantBonus").val($("#bonus").val() - money/2);
}