//��ʼ������
$(function(){
	//��ӡ��Ϣ��ֵ
	$("#headNum").html($("#allNum").html());
	var idStr = $("#ids").val();
	var idArr = idStr.split(",");
	for(var i=0;i<idArr.length;i++){
		var taskInfoObj = $("#taskInfo"+idArr[i]);
		taskInfoObj.yxeditgrid({
			url : "?model=engineering_resources_taskdetail&action=listJson",
			param : {"taskId":idArr[i]},
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
				display : '��������',
				name : 'exeNumber'
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
		});;
	}
});