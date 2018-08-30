$(document).ready(function() {
	//�ӱ��ʼ��
	$("#importTable").yxeditgrid({
		url : "?model=engineering_resources_elentdetail&action=listJson",
		param : {"mainId" : $("#id").val()},
		isAddAndDel : false,
		objName : "elent[item]",
		tableClass : 'form_in_table',
		colModel : [{
			display : 'id',
			name : 'id',
			isSubmit : true,
			type: 'hidden'
		}, {
			display : 'borrowItemId',
			name : 'borrowItemId',
			isSubmit : true,
			type: 'hidden'
		}, {
			display : 'resourceListId',
			name : 'resourceListId',
			isSubmit : true,
			type: 'hidden'
		}, {
			name : 'resourceTypeId',
			display : '�豸����ID',
			isSubmit : true,
			type : 'hidden'
		}, {
			display : '�豸����',
			width : 100,
			name : 'resourceTypeName',
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'resourceId',
			display : '�豸ID',
			isSubmit : true,
			type : 'hidden'
		}, {
			name : 'resourceName',
			width : 120,
			display : '�豸����',
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'coding',
			display : '������',
			width : 120,
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'dpcoding',
			display : '���ű���',
			width : 100,
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'number',
			display : '����',
			width : 60,
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'unit',
			display : '��λ',
			width : 60,
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'beginDate',
			display : 'Ԥ����������',
			width : 80,
			isSubmit : true,
			type: 'statictext'
		}, {
			name : 'endDate',
			display : 'Ԥ�ƹ黹����',
			width : 80,
			isSubmit : true,
			type: 'statictext'
		}, {
			name : 'realDate',
			display : 'ʵ��ת������',
			width : 100,
			isSubmit : true,
			type: 'date',
			validation : {
				required : true
			},
			tclass : 'Wdate'
		}, {
			name : 'remark',
			display : '��ע',
			width : 120,
			isSubmit : true,
			type : 'statictext'
		}]
	});

	/**
	 * ��֤��Ϣ(�õ��ӱ���֤ǰ��������ʹ��validate)
	 */
	validate({
		"applyUser" : {
			required : true
		}
	});
});

//ȷ��ʱ���������֤
function checkSubmit() {
	var objGrid = $("#importTable");
	//��ȡ�豸������
	var curRowNum = objGrid.yxeditgrid("getCurShowRowNum");
	//������֤
	var today = $("#today").val();
	for(var i = 0; i < curRowNum ; i++){
		var realDateObj = objGrid.yxeditgrid("getCmpByRowAndCol",i,"realDate");

		if(DateDiff(realDateObj.val(),today) < 0){
			alert("ʵ��ת�����ڲ������ڽ���");
			realDateObj.focus();
			return false;
		}
	}
}