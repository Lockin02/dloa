function toSupport(){

	var userName = $.trim($("#userName").val());
	var jobName = $.trim($("#jobName").val());

	var workBeginYear = $.trim($("#workBeginYear").val());

	var workBeginMonth = $.trim($("#workBeginMonth").val());
	var userNo = $.trim($("#userNo").val());
	var deptName = $.trim($("#deptName").val());
	//���б�����ȡ
	var listGrid= parent.$("#applyGrid").data('yxgrid');
	//����ֵ�Լ������б����
	setVal(listGrid,'userNameS',userName);
	setVal(listGrid,'jobName',jobName);
	setVal(listGrid,'workBeginYear',workBeginYear);
	setVal(listGrid,'workBeginMonth',workBeginMonth);
	setVal(listGrid,'userNoS',userNo);
	setVal(listGrid,'deptName',deptName);

	//ˢ���б�
	listGrid.reload();
	closeFun();
}

function setVal(obj,thisKey,thisVal){
//	parent.$("#" + thisKey).val(thisVal);
	return obj.options.extParam[thisKey] = thisVal;
}

//$(function(){
//	//����
////	$("#deptSearch").yxselect_dept({
////		hiddenId : 'feeDeptId'
////	});
//	//��������
//	$("#deptNameSSearch").yxselect_dept({
//		hiddenId : 'deptIdS'
//	});
//	//��������
//	$("#deptNameTSearch").yxselect_dept({
//		hiddenId : 'deptIdT'
//	});
//	//ֱ������
//	$("#deptNameSearch").yxselect_dept({
//		hiddenId : 'deptId'
//	});
//		//ְλѡ��
//		$("#jobNameSearch").yxcombogrid_position({
//			hiddenId : 'jobId',
//			width:350
//		});
//
//	$("#regionId").bind('change', function() {
//		$("#regionNameSearch").val($("#regionId").find("option:selected").text());
//	});
//	$("#companyName").bind('change', function() {
//		$("#companyNameSearch").val($("#companyName").find("option:selected").text());
//	});
//
//
//});


//���
function toClear(){
	$(".toClear").val('');
}