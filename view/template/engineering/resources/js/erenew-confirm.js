$(document).ready(function() {
	//�ӱ���Ϣ
	$("#importTable").yxeditgrid({
		url : '?model=engineering_resources_erenewdetail&action=listJson',
		param : {
			'mainId' : $("#id").val(),
			'status' : '0'
		},
		isAdd : false,
		hideRowNum : true,
		objName : 'erenew[item]',
		tableClass : 'form_in_table',
		colModel : [{
			display : 'ȷ��',
			name : 'isChecked',
			type : 'checkbox',
			checkVal : '1',
			process : function ($input ,rowData) {
				var rowNum = $input.data("rowNum");
				$("#importTable_cmp_isChecked" + rowNum).attr('checked' ,'checked');
			}
		}, {
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
            display : 'Ԥ�ƿ�ʼ����',
            width : 80,
            isSubmit : true,
            type : 'statictext'
        }, {
            name : 'endDate',
            display : 'Ԥ�ƹ黹����',
            width : 80,
            isSubmit : true,
            type: 'statictext'
        }, {
			name : 'remark',
			display : '��ע',
			width : 120,
			isSubmit : true,
			type : 'statictext'
		}]
	});
});

//ȷ��ʱ���������֤
function checkSubmit() {
	var objGrid = $("#importTable");

	var resourceIdArr = objGrid.yxeditgrid('getCmpByCol','resourceId');
	if(resourceIdArr.length == 0){
		alert("�豸��Ϣ����Ϊ��!");
		return false;
	}
    //�Ƿ�ѡ������
    var isChecked = false;
    resourceIdArr.each(function(){
        if(objGrid.yxeditgrid("getCmpByRowAndCol",$(this).data("rowNum"),"isChecked").attr('checked')){
        	isChecked = true;
        }
    });
    if(isChecked == false){
        alert('�����ٹ�ѡһ���豸����ȷ��');
        return false;
    }
}