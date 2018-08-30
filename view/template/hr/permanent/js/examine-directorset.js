$(document).ready(function() {
	$("#afterPositionName").yxcombogrid_jobs({
				hiddenId : 'afterPositionId',
				width : 280
	});

	$("#summaryTable").yxeditgrid({
		objName : 'examine[summaryTable]',
		isAddOneRow : true,
		url : '?model=hr_permanent_linkcontent&action=listJson',
		type : 'view',
		param : {
			parentId : $("#parentId").val(),
			ownType : 1
		},
		colModel : [{
			display : '����Ҫ�����',
			name : 'workPoint'
		}, {
			display : '����ɹ�',
			name : 'outPoint'
		}, {
			display : '���ʱ��ڵ�',
			name : 'finishTime'
		}, {
			name : 'ownType',
			type : 'hidden',
			value : 1
		}, {
			type : 'hidden',
			display : 'id',
			name : 'id'
		}]
	});

	$("#planTable").yxeditgrid({
		objName : 'examine[planTable]',
		url : '?model=hr_permanent_linkcontent&action=listJson',
		type : 'view',
		param : {
			parentId : $("#parentId").val(),
			ownType : 2
		},
		isAddOneRow : true,
		colModel : [{
			display : '����Ҫ�����',
			name : 'workPoint'
		}, {
			display : '����ɹ�������׼',
			name : 'outPoint'
		}, {
			display : '���ʱ��ڵ�',
			name : 'finishTime'
		}, {
			name : 'ownType',
			type : 'hidden',
			value : 2
		}, {
			type : 'hidden',
			display : 'id',
			name : 'id'
		}]
	});

	$("#schemeTable").yxeditgrid({
		objName : 'examine[schemeTable]',
		url : '?model=hr_permanent_detail&action=listJson',
		param : {
			parentId : $("#parentId").val()
		},
		type : 'view',
		isAddOneRow : true,
		isAddAndDel : false,
		realDel : true,
		colModel : [{
			name : 'standardId',
			type : 'hidden'
		},{
			display : '������Ŀ',
			name : 'standard',
			readonly : 'readonly',
			tclass : 'readOnlyTxt'
		}, {
			display : '����Ȩ��',
			name : 'standardProportion',
			tclass : 'readOnlyTxt'
		},{
			display : '��������',
			name : 'standardContent',
			readonly : 'readonly',
			tclass : 'readOnlyTxt',
			align:'left'
		}, {
			display : '����Ҫ��',
			name : 'standardPoint',
			tclass : 'readOnlyTxt',
			align:'left'
		},  {
			display : '����',
			name : 'selfScore',
			readonly : 'readonly',
			tclass : 'readOnlyTxt'
		}, {
			display : '��ʦ����',
			name : 'otherScore'
		}, {
			display : '�쵼����',
			name : 'leaderScore'
		},{
			display : '��ע',
			name : 'comment'
		}, {
			type : 'hidden',
			display : 'id',
			name : 'id'
		}]
	});
	validate({
		"beforeSalary" : {
			required : true
		},
		"afterSalary" : {
			required : true
		},
		"afterPositionName" : {
			required : true
		},
		"levelCode" : {
			required : true
		}
	});
})
$(function (){
	if ($(":checked").val()=='ZZLXTQZZ'){
		getRadio();
	}else{
		closeRadio();
	}
});
function getRadio(){
		$("#permanentDate").remove();
	if($("#permanentDate").length>0){
		$("#permanentDate").remove();
	}
	var input = document.createElement("input");
    input.type = "text";
	input.onclick = function() { WdatePicker(); };
	if ($("#permanentDate1").val()!=""){
		input.value=$("#permanentDate1").val();
	}
    input.id = "permanentDate";
    input.name = "examine[permanentDate]";
    document.getElementById("ZZYJ").appendChild(input);
}
function closeRadio(){
	if($("#permanentDate").length>0){
		//alert($("#computerConfiguration").length);
		$("#permanentDate").hide();
		$("#permanentDate").val($("#finishtime").val());
//		$("#permanentDate").remove();
		return;
	}
}