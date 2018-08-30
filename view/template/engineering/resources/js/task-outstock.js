$(function(){
    $("#taskInfo").yxeditgrid({
		url : "?model=engineering_resources_taskdetail&action=listJson",
        objName : 'task[item]',
		param : {
			'taskId' : $("#id").val(),
			'waitNumGt' : 0
		},
		isAdd : false,
		tableClass : 'form_in_table',
		colModel : [{
            display : 'id',
            name : 'id',
            type : 'hidden'
        }, {
            display : 'applyDetailId',
            name : 'applyDetailId',
            type : 'hidden'
        }, {
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
            width : 80,
            type : 'statictext',
            isSubmit : true
		}, {
			display : '�豸����',
			name : 'resourceName',
            type : 'statictext',
            isSubmit : true
		}, {
			display : '��λ',
			name : 'unit',
            width : 50,
            type : 'statictext',
            isSubmit : true
		}, {
			display : '��������',
			name : 'number',
            width : 70,
            type : 'statictext',
            isSubmit : true
		}, {
			display : '��������',
			name : 'exeNumber',
            width : 70,
            type : 'statictext',
            isSubmit : true
		}, {
			display : '��������',
			name : 'planBeginDate',
            width : 70,
            type : 'statictext',
            isSubmit : true
		}, {
			display : '�黹����',
			name : 'planEndDate',
            width : 70,
            type : 'statictext',
            isSubmit : true
		}, {
			display : 'ʹ������',
			name : 'useDays',
			width : 50,
            type : 'statictext',
            isSubmit : true
		}, {
			display : '��ע',
			name : 'remark',
            width : 120,
            type : 'statictext',
            isSubmit : true
		}, {
            display : '��ѯ�ֶ�',
            name : 'searchKey',
            type : 'select',
            width : 80,
            options : [{
                'name' : '���ű���',
                'value' : 'dpcoding'
            },{
                'name' : '������',
                'value' : 'coding'
            },{
                'name' : '���',
                'value' : 'id'
            }]
        }, {
            display : '��ѯ����',
            name : 'searchText',
            type : 'textarea',
            cols : 40,
            rows : 5
        }]
	});
});
//�ύʱ��֤
function checkForm(){
	//���������Ŀ���룬��֤��Ŀ�Ƿ��ѹر�
	var projectId = $("#projectId").val();
	if(projectId != "" && projectId != "0" && $("#taskInfo").yxeditgrid('getCurShowRowNum') > 0){
		var isClose = false;
		$.ajax({
			type: "POST",
			url: "?model=engineering_project_esmproject&action=isClose",
			data: {id: projectId},
			async: false,
			success: function(data) {
				if (data == "1") {
					isClose = true;
				}
			}
		});
		if(isClose){
			alert("��Ŀ�ѹرգ����ܽ����豸���⡣����ϵ�豸����Ա�Ƿ�Ҫ���г��ز�����");
			return false;
		}
	}
}