$(function(){
	var taskInfoObj = $("#taskInfo");
	taskInfoObj.yxeditgrid({
		url : "?model=engineering_resources_taskdetail&action=listJson",
		param : {"taskId":$("#id").val()},
		tableClass : 'form_in_table',
		type : 'view',
		colModel : [{
			display : '�豸id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '����id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceTypeName'
		}, {
			display : '�豸����',
			name : 'resourceName'
		}, {
			display : '��λ',
			name : 'unit'
		}, {
			display : '��������',
			name : 'number'
		}, {
			display : '��ȷ�Ϸ�������',
			name : 'awaitNumber',
			process : function(v) {
				if(v == "")
					v = "��";
				return v;
			}
		}, {
			display : '��������',
			name : 'exeNumber'
		}, {
			display : '��������',
			name : 'backNumber'
		}, {
			display : '��������',
			name : 'planBeginDate'
		}, {
			display : '�黹����',
			name : 'planEndDate'
		}, {
			display : 'ʹ������',
			name : 'useDays',
			width : 50
		}, {
			display : '��ע',
			name : 'remark'
		}]
	});
});