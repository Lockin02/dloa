$(function() {
	if($("#isBeyondBudget").val() == "��"){
		$("#isBeyondBudgetView").css("color","red");
	}else{
		$("#isBeyondBudgetView").css("color","black");
	};

	var loanType = $("#xmflagTag").val();
	if(loanType == '1'){
	  $("#proView").show();
	  $("input[name='loan[XmFlag]']").eq(1).attr("checked","checked");
	}else{
		$("#proView").hide();
		$("input[name='loan[XmFlag]']").eq(0).attr("checked","checked");
	}

	var loanNatureVal = $("#loanNatureCode").val();
	$("#loanNature option").each(function(i,n){
		if($(n).val()==loanNatureVal)
		{
			$(n).attr("selected",true);
		}
	});
	if(loanNatureVal == 1){// �ⷿѺ������ݴ���
		$(".rendDepositInfo").show();
	}else{// �������ʵ�ʱ��,�����ⷿѺ�������
		$(".rendDepositInfo").hide();
		$("input.rendDepositInfo").val('');
	}

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
	 * ��֤��Ϣ
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

	//������Ŀ��Ⱦ
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
				statusArr: 'GCXMZT02',// �ڽ�
				attributes:'GCXMSS-02,GCXMSS-01,GCXMSS-05'//������Ŀ,������Ŀ,�з���Ŀ
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

	// ��������޸�
	$("#loanNature").change(function(){
		$("#loanNatureCode").val($(this).val());
		if($(this).val() == 1){// �ⷿѺ������ݴ���
			$(".rendDepositInfo").show();
			$("#no_writeoff").val(1);// ���Ѻ�����͵Ľ�ϵͳĬ��Ϊ���ɳ������ͣ��������͵Ľ���Ĭ�Ͽɳ������͡�
		}else{// �������ʵ�ʱ��,�����ⷿѺ�������
			$(".rendDepositInfo").hide();
			$("input.rendDepositInfo").val('');
			$("#no_writeoff").val(0);
		}
	});

	// ��������鲢�����Ƿ񳬳���ĿԤ����
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
			alert("��Ŀ���Ϊ������Ŀ��");
			$("#ProjectNo").focus();
			return false;
		}
	}
	if($("#loanNatureCode").val() == '1'){
		var rendHouseStartDate = $("#rendHouseStartDate").val();
		var rendHouseEndDate = $("#rendHouseEndDate").val();
		var projId = $("#ProjectId").val();
		if(rendHouseStartDate == ''){
			alert("�ⷿʱ����");
			return false;
		}else if(rendHouseEndDate == ''){
			alert("�ⷿʱ����");
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
					var confirmRslt = confirm("����ĿԤ�ƹ��ڷ�Χ "+responseObj.startDate+" �� "+responseObj.endDate+"���ⷿʱ�䲻������Ŀ���ڷ�Χ���Ƿ�ȷ���ύ?");
					if(!confirmRslt){
						return false;
					}
				}
			}
		}
	}

	if(act == "toApp"){
		if($("#xmflagTag").val() == 1 && $("#isBeyondBudget").val() != "��"){
			alert("������ĿԤ�㣬��֪ͨ��Ŀ����ʱ�޸���ĿԤ�㡣");
			return false;
		}
	}
	return true;
}

// �������Ƿ񳬹���ĿԤ�ƽ�start��
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
			$("#isBeyondBudgetView").text("��");
			$("#isBeyondBudget").val("��");
			$("#isBeyondBudgetView").css("color","black");
		}else{
			$("#isBeyondBudgetView").text("��");
			$("#isBeyondBudget").val("��");
			$("#isBeyondBudgetView").css("color","red");
		}
	}else{
		cleanBeyondBudgetChk();
	}
}
// �������Ƿ񳬹���ĿԤ�ƽ�end��

function toApp() {
    document.getElementById('form1').action = "index1.php?model=loan_loan_loan&action=edit&act=app";
	var result = chkForm("toApp");
	if(result){
		var loanNatureVal = $("#loanNatureCode").val();
		var uploadFileCount = $("#uploadFileList2")[0].children.length;
		if(uploadFileCount <=0 && loanNatureVal == 1){
			$("#hasFilesNum").val(0);
			if(confirm("�뾡�첹����ϸ�����޺�ͬ/Э���Ѻ����������ϵͳ���ڳ��ɸ����10�콫�˽����Ϊ�ɳ�����")){
				$('#form1').submit();
			}
		}else{
			$("#hasFilesNum").val(uploadFileCount);
			$('#form1').submit();
		}
	}
}
function toSave() {
    document.getElementById('form1').action = "index1.php?model=loan_loan_loan&action=edit";
	var result = chkForm("toSave");
	if(result){$('#form1').submit();}
}
