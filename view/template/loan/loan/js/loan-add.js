$(function() {

	$(".jkSlt").removeAttr("checked");
	$.each($(".jkSlt"),function(i,item){
		if(i == 0){
			$(this).attr("checked",true);
			$("#xmflagTag").val(0);
			$("#ProjectNo").attr("title",'');
			$("#ProjectNo").val('');
			$("#ProjectId").val('');
		}
	});

	$("input[name='loan[XmFlag]']").bind("click",function(){
		$("#xmflagTag").val($(this).val());
		if($(this).val() == '1'){
			$("#proView").show();
		}else{
			$("#proView").hide();
			$("#ProjectNo").val("");
			$("#XmSid").val("");
			cleanBeyondBudgetChk();
		}
	});

	/**
	 * 验证信息
	 */
	validate({
		"Amount_v" : {
			required : true
		},
		// "BankNo" : {
		// 	required : true
		// },
		// "BankAddress" : {
		// 	required : true
		// },
		"PrepaymentDate" : {
			required : true
		},
		"Reason" : {
			required : true
		}
	});

	//工程项目渲染
	$("#ProjectNo").yxcombogrid_esmproject({
		isDown: true,
		hiddenId: 'projectId',
		nameCol: 'ProjectNo',
		height: 250,
		isFocusoutCheck: false,
		openPageOptions : false,
		gridOptions: {
			isTitle: true,
			showcheckbox: false,
			autoload : false,
			param: {
				statusArr: 'GCXMZT02',// 在建
				attributes:'GCXMSS-02,GCXMSS-01,GCXMSS-05'//服务项目,试用项目,研发项目
			},
			event: {
				row_dblclick: function(e, row, data) {
					$("#ProjectNo").val(data.projectCode);
					$("#ProjectId").val(data.id);
					chkBeyondBudget(data.id,'');
				}
			}
		},
		event: {
			clear: function() {
				$("#ProjectNo").val('');
				$("#ProjectId").val('');
				cleanBeyondBudgetChk();
			}
		}
	});

	// 借款性质修改
	$("#loanNature").change(function(){
		$("#loanNatureCode").val($(this).val());
		if($(this).val() == 1){// 租房押金表单内容带出
			$(".rendDepositInfo").show();
			$("#no_writeoff").val(1);// 租金押金类型的借款单系统默认为不可冲销类型，其他类型的借款都是默认可冲销类型。
		}else{// 其他性质的时候,隐藏租房押金表单内容
			$(".rendDepositInfo").hide();
			$("input.rendDepositInfo").val('');
			$("#no_writeoff").val(0);
		}
	});
	var loanNatureVal = $("#loanNatureCode").val();
	if(loanNatureVal == 1){// 租房押金表单内容带出
		$(".rendDepositInfo").show();
		$("#no_writeoff").val(1);// 租金押金类型的借款单系统默认为不可冲销类型，其他类型的借款都是默认可冲销类型。
	}else{// 其他性质的时候,隐藏租房押金表单内容
		$(".rendDepositInfo").hide();
		$("input.rendDepositInfo").val('');
		$("#no_writeoff").val(0);
	}

	// 输入金额后检查并更新是否超出项目预算结果
	$("#Amount_v").blur(function(){
		var loanType = $("#xmflagTag").val();
		if(loanType == 1){
			chkBeyondBudget('',$("#Amount").val());
		}
	});
});

function chkForm(act){
	var loanType = $("#xmflagTag").val();
	if(loanType == 1){
		if($("#ProjectNo").val() == ""){
			alert("项目编号为必填项目。");
			$("#ProjectNo").focus();
			return false;
		}
	}
	if($("#loanNatureCode").val() == '1'){
		var rendHouseStartDate = $("#rendHouseStartDate").val();
		var rendHouseEndDate = $("#rendHouseEndDate").val();
		var projId = $("#ProjectId").val();
		if(rendHouseStartDate == ''){
			alert("租房时间必填。");
			return false;
		}else if(rendHouseEndDate == ''){
			alert("租房时间必填。");
			return false;
		}else if(loanType == 1){
			var responseText = $.ajax({
				url:'index1.php?model=loan_loan_loan&action=ajaxChkRendDateRange',
				data : {"projId" : projId,"rendHouseStartDate" : rendHouseStartDate,"rendHouseEndDate" : rendHouseEndDate},
				type : "POST",
				async : false
			}).responseText;
			var responseObj = eval("("+responseText+")");
			if(responseObj.result != 'ok'){
				if(responseObj.startDate != '' || responseObj.endDate != ''){
					var conResult = confirm("该项目预计工期范围 "+responseObj.startDate+" 至 "+responseObj.endDate+"，租房时间不符合项目工期范围，是否确认提交?");
					if(!conResult){
						return false;
					}
					// $("input.rendDepositInfo").val('');
				}
			}
		}
	}

	if(act == "toApp"){
		if($("#xmflagTag").val() == 1 && $("#isBeyondBudget").val() != "否"){
			alert("借款超过项目预算，请通知项目经理及时修改项目预算。");
			return false;
		}
	}
	return true;
}

// 申请金额是否超过项目预计金额（start）
function cleanBeyondBudgetChk() {
	$("#isBeyondBudgetView").text("");
	$("#isBeyondBudget").val("");
}
function chkBeyondBudget(id,amount) {
	var projId = (id == undefined || id == '')? $("#ProjectId").val() : id;
	var amountVal = (amount == undefined || amount == '')? $("#Amount").val() : amount;
	if(projId != '' && amountVal != ''){
		var responseText = $.ajax({
			url:'index1.php?model=loan_loan_loan&action=ajaxChkBeyondBudget',
			data : {"projId" : projId,"amountVal" : amountVal},
			type : "POST",
			async : false
		}).responseText;
		var responseObj = eval("("+responseText+")");
		if(responseObj.isBeyondBudget != 1){
			$("#isBeyondBudgetView").text("否");
			$("#isBeyondBudget").val("否");
			$("#isBeyondBudgetView").css("color","black");
		}else{
			$("#isBeyondBudgetView").text("是");
			$("#isBeyondBudget").val("是");
			$("#isBeyondBudgetView").css("color","red");
		}
	}else{
		cleanBeyondBudgetChk();
	}
}
// 申请金额是否超过项目预计金额（end）

function toApp() {
    document.getElementById('form1').action = "index1.php?model=loan_loan_loan&action=add&act=app";
	var result = chkForm("toApp");
	if(result){
		var loanNatureVal = $("#loanNatureCode").val();
		var uploadFileCount = $("#uploadFileList2")[0].children.length;
		if(uploadFileCount <=0 && loanNatureVal == 1){
			$("#hasFilesNum").val(0);
			if(confirm("请尽快补充详细的租赁合同/协议跟押金条，否则系统将在出纳付款后10天将此借款变更为可冲销借款！")){
				$('#form1').submit();
			}
		}else{
			$("#hasFilesNum").val(uploadFileCount);
			$('#form1').submit();
		}
	}
}
function toSave() {
    document.getElementById('form1').action = "index1.php?model=loan_loan_loan&action=add";
	var result = chkForm("toSave");
	if(result){$('#form1').submit();}
}
