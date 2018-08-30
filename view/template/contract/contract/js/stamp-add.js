$(document).ready(function(){
    //客户类型
    customerTypeArr = getData('GZLB');
    addDataToSelect(customerTypeArr, 'categoryId');

	// 验证信息
	validate({
		"fileName" : {
			required : true
		},
		"signCompanyName" : {
			required : true
		},
		"stampExecution" : {
			required : true
		},
		"stampType" : {
			required : true
		},
		"contractType" : {
			required : true
		}
	});

	//盖章类型渲染
	$("#stampType").yxcombogrid_stampconfig({
		hiddenId : 'stampType',
		height : 250,
		gridOptions : {
			isTitle : true,
			showcheckbox : true
		}
	});
	//根据合同归属公司改变提交按钮，只有贝讯的合同需要走审批流
	if($("#businessBelong").val() == "bx"){
		$("#businessBelong").next("input").val("提交审批");
	}
});

function checkForm(){
	if($("#uploadfileList").html() =="" || $("#uploadfileList").html() =="暂无任何附件"){
		alert('申请盖章前需要上传合同附件！');
		return false;
	}
	//提交前检查是否已存在盖章并且未审核完的
	var	msg = $.ajax({
		url:'?model=contract_stamp_stamp&action=checkStamp',
		data:'contractId=' + $("#contractId").val() + '&contractType=HTGZYD-04',
		dataType:'html',
		type:'post',
		async:false
	}).responseText;
	if(msg == 1){
		alert('此合同已申请盖章，不能重复申请，可到我的盖章申请查看。');
		return false;
	}
}

//新增 - 提交审批或保存
function audit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=contract_stamp_stampapply&action=add&act=audit";
	}
}