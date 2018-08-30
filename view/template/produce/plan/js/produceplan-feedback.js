$(document).ready(function() {
	if ($("#projectName").val() != '') {
		$("#department0").hide();
	}

	var feedbackObj = $("#feedbackInfo");
	feedbackObj.yxeditgrid({
		objName : 'produceplan[feedback]',
		url : '?model=produce_plan_planprocess&action=listJson',
		param : {
			planId : $("#id").val()
		},
		realDel : true,
		isAdd : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '��  ��',
			name : 'process',
			width : '8%',
			type : 'statictext',
			align : 'left'
		},{
			display : '�������غ�̨��',
			name : 'process',
			type : 'hidden'
		},{
			display : '��Ŀ����',
			name : 'processName',
			width : '13%',
			type : 'statictext',
			align : 'left'
		},{
			display : '��Ŀ�������غ�̨��',
			name : 'processName',
			type : 'hidden'
		},{
			display : '����ʱ�䣨�룩',
			name : 'processTime',
			width : '8%',
			type : 'statictext'
		},{
			display : '����ʱ�����غ�̨��',
			name : 'processTime',
			type : 'hidden'
		},{
			display : '��������',
			name : 'recipientNum',
			width : '5%',
			type : 'statictext'
		},{
			display : '�����������غ�̨��',
			name : 'recipientNum',
			type : 'hidden'
		},{
			display : '������',
			name : 'recipient',
			width : '10%',
			type : 'statictext',
			align : 'left'
		},{
			display : '���������غ�̨��',
			name : 'recipient',
			type : 'hidden'
		},{
			display : '������ID',
			name : 'recipientId',
			type : 'hidden'
		},{
			display : '����ʱ��',
			name : 'recipientTime',
			width : '8%',
			type : 'date',
			readonly : true,
			validation : {
				required : true
			}
		},{
			display : '���ʱ��',
			name : 'finishTime',
			width : '8%',
			type : 'date',
			readonly : true,
			validation : {
				required : true
			},
			process : function($input) {
				$input.val('');
			}
		},{
			display : '�ϸ�����',
			name : 'qualifiedNum',
			width : '8%',
			validation : {
				required : true,
				custom : ['onlyNumber']
			},
			process : function($input) {
				$input.val('');
			}
		},{
			display : '���ϸ�����',
			name : 'unqualifiedNum',
			width : '8%',
			validation : {
				required : true,
				custom : ['onlyNumber']
			},
			process : function($input) {
				$input.val('');
			}
		},{
			display : '�������κ�',
			name : 'productBatch',
			width : '10%',
			process : function($input) {
				$input.val('');
			}
		},{
			display : '��ע',
			name : 'remark',
			type : 'textarea',
			width : '20%'
		}]
	});

	validate({
		"id" : {
			required : true
		}
	});
});