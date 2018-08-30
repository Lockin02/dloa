$(document).ready(function() {
	$("#importTable").yxeditgrid({
		url : '?model=engineering_resources_resourceapplydet&action=listJson',
//		title : '�豸������ϸ',
		param : {
			'mainId' : $("#id").val(),
			'status' : '2'
		},
		objName : 'resourceapply[detail]',
		tableClass : 'form_in_table',
		isAdd : false,
		async : false,
		colModel : [{
            display : 'id',
            name : 'id',
            type : 'hidden'
        },{
			name : 'status',
			display : '״̬',
			process : function(v){
				if(v == 0){
					return '��ȷ��';
				}else if(v == 1){
					return '��ȷ��';
				}else if(v == 2){
					return '���ش�ȷ��';
				}
			},
			width : 60,
			type : 'statictext'
		}, {
			name : 'resourceTypeName',
			display : '�豸����',
			process : function(v,row){
				if(row.resourceId == "0"){
					return "<span class='red'>-- ���豸 --</span>";
				}else{
					return v;
				}
			},
			width : 80,
			type : 'statictext'
		}, {
			name : 'resourceName',
			display : '�豸����',
			width : 80,
			type : 'statictext'
		}, {
			name : 'number',
			display : '����',
			width : 40,
			type : 'statictext',
			isSubmit : true
		}, {
			name : 'exeNumber',
			display : '���´�����',
			width : 40,
			type : 'statictext',
			isSubmit : true
		}, {
			name : 'backNumber',
			display : '��������',
			width : 40,
			type : 'statictext',
			isSubmit : true
		}, {
			name : 'unit',
			display : '��λ',
			width : 40,
			type : 'statictext'
		}, {
			name : 'planBeginDate',
			display : '��������',
			width : 70,
			type : 'statictext'
		}, {
			name : 'planEndDate',
			display : '�黹����',
			width : 70,
			type : 'statictext'
		}, {
			name : 'useDays',
			display : 'ʹ������',
			width : 40,
			type : 'statictext'
		}, {
			name : 'price',
			display : '���豸�۾�',
			align : 'right',
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 60,
			type : 'statictext'
		}, {
			name : 'amount',
			display : '�豸�ɱ�',
			align : 'right',
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80,
			type : 'statictext'
		}, {
			name : 'remark',
			display : '��ע',
			type : 'statictext'
		}, {
			name : 'feeBack',
			display : 'Ԥ�Ƴﱸ��������',
			width : 80,
			type : 'statictext'
		}]
	});
});