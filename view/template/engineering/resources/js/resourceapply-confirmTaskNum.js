$(function(){
	//�ʼ���Ⱦ
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});
	$("#importTable").yxeditgrid({
		url : "?model=engineering_resources_taskdetail&action=confirmTaskNumListJson",
		param : {"applyId":$("#id").val()},
		tableClass : 'form_in_table',
		objName : 'resourceapply[detail]',
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '��������id',
			name : 'taskId',
			type : 'hidden'
		},{
			display : '�������񵥺�',
			name : 'taskCode',
			width : 120
		},{
			display : '���뵥��ϸid',
			name : 'applyDetailId',
			type : 'hidden'
		},{
			display : '�豸id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '����id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceTypeName',
			width : 100,
			type : 'statictext'
		}, {
			display : '�豸����',
			name : 'resourceName',
			width : 150,
			type : 'statictext'
		}, {
			display : '��λ',
			name : 'unit',
			width : 50,
			type : 'statictext'
		}, {
			display : '��������',
			name : 'number',
			width : 50,
			isSubmit : true
		}, {
			display : '��ȷ������',
			name : 'awaitNumber',
			process : function(v) {
				return "<span class='red'>"+v+"</span>";
			},
			width : 60,
			isSubmit : true
		}, {
			display : '��������',
			name : 'planBeginDate',
			width : 100,
			type : 'statictext'
		}, {
			display : '�黹����',
			name : 'planEndDate',
			width : 100,
			type : 'statictext'
		}, {
			display : 'ʹ������',
			name : 'useDays',
			width : 50,
			type : 'statictext'
		}, {
			display : '��ע',
			name : 'remark',
			process : function(v) {
				return "<span class='red'>"+v+"</span>";
			},
			width : 200
		}]
	});
});
//����֤
function checkForm() {
	if(confirm('ȷ���ύ������')){
		return true;
	}else{
		return false;
	}
}