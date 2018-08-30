$(document).ready(function() {
	//��ʼ�����Կ���Ϣ
	initCardrecords();

	validate();
});

//��ʼ�����Կ���Ϣ
function initCardrecords(){
	$("#importTable").yxeditgrid({
		url : '?model=cardsys_cardrecords_cardrecords&action=listjson',
		param : {
			'worklogId' : $("#worklogId").val()
		},
		objName : 'cardrecords',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '���Կ�id',
			name : 'cardId',
			type : 'hidden'
		}, {
			display : '���Կ���',
			name : 'cardNo',
			validation : {
				required : true
			},
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_cardsinfo({
					hiddenId : 'importTable_cmp_cardId' + rowNum,
					nameCol : 'cardNo',
					height : 300,
					gridOptions : {
						isTitle : true,
						showcheckbox : false,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
								}
							})(rowNum)
						}
					}
				});
			},
			tclass : 'txt'
		}, {
			display : '����',
			name : 'useDate',
			readonly : true,
			tclass : 'readOnlyTxtMiddle',
			value : $("#executionDate").val()
		}, {
			display : '�쿨',
			name : 'openMoney',
			tclass : 'txtmiddle'
		}, {
			display : '��ֵ',
			name : 'rechargerMoney',
			validation : {
				required : true
			},
			tclass : 'txtmiddle'
		}, {
			display : 'ʹ����id',
			name : 'ownerId',
			type : 'hidden'
		}, {
			display : 'ʹ����',
			name : 'ownerName',
			type : 'hidden'
		}, {
			display : '��־Id',
			name : 'worklogId',
			type : 'hidden',
			value : $("#worklogId").val()
		}, {
			display : '��ĿId',
			name : 'projectId',
			type : 'hidden',
			value : $("#projectId").val()
		}, {
			display : '��Ŀ���',
			name : 'projectCode',
			type : 'hidden',
			value : $("#projectCode").val()
		}, {
			display : '��Ŀ����',
			name : 'projectName',
			type : 'hidden',
			value : $("#projectName").val()
		}, {
			display : '����id',
			name : 'activityId',
			type : 'hidden',
			value : $("#activityId").val()
		}, {
			display : '��������',
			name : 'activityName',
			type : 'hidden',
			value : $("#activityName").val()
		}]
	})
}