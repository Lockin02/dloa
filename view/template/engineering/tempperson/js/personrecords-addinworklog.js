$(document).ready(function() {
	//��ʼ����Ƹ��Ա��¼
	initPersonrecords();

	validate();
})

//��Ƹ��Ա��¼
function initPersonrecords(){
	$("#importTable").yxeditgrid({
		url : '?model=engineering_tempperson_personrecords&action=listjson',
		param : {
			'worklogId' : $("#worklogId").val()
		},
		objName : 'personrecords',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '��ԱId',
			name : 'personId',
			type : 'hidden'
		}, {
			display : '��Ա����',
			name : 'personName',
			validation : {
				required : true
			},
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_tempperson({
					hiddenId : 'importTable_cmp_personId' + rowNum,
					nameCol : 'personName',
					width : 600,
					gridOptions : {
						isTitle : true,
						showcheckbox : false,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum,'idCardNo').val(rowData.idCardNo);
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '���֤��',
			name : 'idCardNo',
			readonly : true,
			tclass : 'readOnlyTxtNormal',
			type : 'hidden'
		}, {
			display : '���ʼ�����',
			name : 'money',
			type : 'money',
			validation : {
				required : true
			},
			tclass : 'txtmiddle'
		}, {
			display : '����',
			name : 'thisDate',
			readonly : true,
			tclass : 'readOnlyTxtMiddle',
			value : $("#executionDate").val()
		}, {
			display : '��������',
			name : 'workContent',
			tclass : 'txtlong'
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