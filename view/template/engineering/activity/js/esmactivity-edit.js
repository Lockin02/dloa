$(document).ready(function() {
	//�����Ա��Ϣ��Ⱦ
//	initMember();
	//����Ԥ��
//	initPerson();

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"activityName" : {
			required : true
		},
		"workContent" : {
			required : true
		},
		"workRate" :{
			required : true
		},
		"workload" :{
			required : true
		}
	});
});
//��ʼ�������Ա
function initMember(){
	//�����Ա��Ⱦ
	$("#activityMembers").yxeditgrid({
		objName : 'esmactivity[esmactmember]',
		url : '?model=engineering_activity_esmactmember&action=listJson',
		tableClass : 'form_in_table',
		param : {
			'activityId' : $("#id").val()
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '��Ա����',
			name : 'memberName',
			tclass : 'txt',
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_esmmember({
					hiddenId : 'activityMembers_cmp_memberId' + rowNum,
					valueCol : 'memberId',
					nameCol : 'memberName',
					width : 550,
					gridOptions : {
						showcheckbox : false,
						param : { "projectId" : $("#projectId").val() },
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum,'coefficient').val(rowData.coefficient);
									g.getCmpByRowAndCol(rowNum,'price').val(rowData.price);
									g.getCmpByRowAndCol(rowNum,'personLevel').val(rowData.personLevel);
									g.getCmpByRowAndCol(rowNum,'personLevelId').val(rowData.personLevelId);
									g.getCmpByRowAndCol(rowNum,'roleName').val(rowData.roleName);
									g.getCmpByRowAndCol(rowNum,'roleId').val(rowData.roleId);

									setMember();
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '��Աid',
			name : 'memberId',
			type : 'hidden'
		}, {
			display : '��Ա����id',
			name : 'personLevelId',
			type : 'hidden'
		}, {
			display : '��Ա�ȼ�',
			name : 'personLevel',
			validation : {
				required : true
			},
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '��Ա��ɫ',
			name : 'roleName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '��Ա��ɫid',
			name : 'roleId',
			type : 'hidden'
		}, {
			display : '����',
			name : 'price',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '����ϵ��',
			name : 'coefficient',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			type : 'hidden'
		}, {
			display : 'ʵ�ʿ�ʼ',
			name : 'actBeginDate',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : 'ʵ�ʽ���',
			name : 'actEndDate',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '����',
			name : 'personDays',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '�����ɱ�',
			type : 'statictext',
			name : 'personCostDays',
			width : 100,
			type : 'hidden'
		}, {
			display : '�����ɱ����',
			type : 'statictext',
			name : 'personCost',
			width : 100,
			type : 'hidden'
		}, {
			display : '��Ŀid',
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
			value : $("#id").val()
		}, {
			display : '��������',
			name : 'activityName',
			type : 'hidden',
			value : $("#activityName").val()
		}],
		event : {
			'beforeRemoveRow' : function(e, rowNum,rowDate, g){
				if(rowDate){
					if(rowDate.actBeginDate != "" && rowDate.actBeginDate != '0000-00-00'){
						alert('��Ա [ ' + rowDate.memberName + ' ] �Ѿ��Ա�������д����־������ɾ���������Ա!')
						g.isRemoveAction = false;
					}
				}
			}
		}
	})
}

//��ʼ������Ԥ��
function initPerson(){
	$("#activityPersons").yxeditgrid({
		objName : 'esmactivity[esmperson]',
		url : '?model=engineering_person_esmperson&action=taskListJson',
		tableClass : 'form_in_table',
		param : {
			'activityId' : $("#id").val()
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '��Ա����id',
			name : 'personLevelId',
			type : 'hidden'
		}, {
			display : '��Ա�ȼ�',
			name : 'personLevel',
			validation : {
				required : true
			},
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_eperson({
					hiddenId : 'activityPersons_cmp_personLevelId' + rowNum,
					nameCol : 'personLevel',
					width : 600,
					gridOptions : {
						showcheckbox : false,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum,'coefficient').val(rowData.coefficient);
									$("#activityPersons_cmp_price" + rowNum).val(rowData.price);
									calPersonBatch2(rowNum);
								}
							})(rowNum)
						}
					}
				});
			},
			tclass : 'txtmiddle'
		}, {
			display : 'Ԥ�ƿ�ʼ',
			name : 'planBeginDate',
			type : 'date',
			tclass : 'txtshort',
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
					var g = $(this).data("grid");
					var planBeginDate = $(this).val();
					var planEndDate = g.getCmpByRowAndCol(rowNum,'planEndDate').val();
					if(planBeginDate != "" && planEndDate != ""){
						var days = DateDiff(planBeginDate,planEndDate) + 1 ;
						g.getCmpByRowAndCol(rowNum,'days').val(days);
						calPersonBatch2(rowNum);
					}
				}
			},
			value : $("#planBeginDate").val()
		}, {
			display : 'Ԥ�ƽ���',
			name : 'planEndDate',
			type : 'date',
			tclass : 'txtshort',
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
					var g = $(this).data("grid");
					var planEndDate = $(this).val();
					var planBeginDate = g.getCmpByRowAndCol(rowNum,'planBeginDate').val();
					if(planBeginDate != "" && planEndDate != ""){
						var days = DateDiff(planBeginDate,planEndDate) + 1 ;
						g.getCmpByRowAndCol(rowNum,'days').val(days);
						calPersonBatch2(rowNum);
					}
				}
			},
			value : $("#planEndDate").val()
		}, {
			display : '����',
			name : 'days',
			validation : {
				required : true
			},
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
					var g = $(this).data("grid");
					var planBeginDate = g.getCmpByRowAndCol(rowNum,'planBeginDate').val();
					var planEndDate = g.getCmpByRowAndCol(rowNum,'planEndDate').val();

					var s = DateDiff(planBeginDate,planEndDate) + 1;
					if($(this).val() > s){
						alert('������������!');
						$(this).val(s);
					}
					calPersonBatch2($(this).data("rowNum"));
				}
			},
			tclass : 'txtshort',
			value : $("#days").val()
		}, {
			display : '����',
			name : 'number',
			tclass : 'txtshort',
			value : 1,
			event : {
				blur : function() {
					calPersonBatch2($(this).data("rowNum"));
				}
			}
		}, {
			display : '����',
			name : 'price',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '����ϵ��',
			name : 'coefficient',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			type : 'hidden'
		}, {
			display : '��������',
			name : 'personDays',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '�����ɱ�',
			name : 'personCostDays',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '�����ɱ����',
			name : 'personCost',
			tclass : 'readOnlyTxtMiddle',
			type : 'money',
			readonly : true
		}, {
			display : '��Ŀid',
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
			value : $("#id").val()
		}, {
			display : '��������',
			name : 'activityName',
			type : 'hidden',
			value : $("#activityName").val()
		}]
	})
}