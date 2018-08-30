$(document).ready(function() {
	//�����Ա��Ϣ��Ⱦ
	initPerson();

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
		"parentName" : {
			required : true
		}
	});
});

//��ʼ����Ŀ��Ա�б���Ⱦ
function initPerson(){
	$("#activityMembers").yxeditgrid({
		objName : 'esmactivity[esmperson]',
		tableClass : 'form_in_table',
		colModel : [{
			display : '��Ա����',
			name : 'memberName',
			tclass : 'txtmiddle',
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

									//ѡ������Ա����Ա�ȼ���������Ϊ����¼
									$("#activityMembers_cmp_personLevel" + rowNum).attr("readonly",true).attr('class','readOnlyTxtShort').yxcombogrid_eperson("remove");
									$("#activityMembers_cmp_number" + rowNum).attr("readonly",true).attr('class','readOnlyTxtShort');
									calPersonBatch(rowNum);
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
//			validation : {
//				required : true
//			},
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_eperson({
					hiddenId : 'activityMembers_cmp_personLevelId' + rowNum,
					nameCol : 'personLevel',
					width : 600,
					gridOptions : {
						showcheckbox : false,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									//ѡ������Ա����Ա�ȼ���������Ϊ����¼
									$("#activityMembers_cmp_memberName" + rowNum).attr("readonly",true).attr('class','readOnlyTxtMiddle').yxcombogrid_esmmember("remove");

									g.getCmpByRowAndCol(rowNum,'coefficient').val(rowData.coefficient);
									$("#activityMembers_cmp_price" + rowNum).val(rowData.price);
									calPersonBatch(rowNum);
								}
							})(rowNum)
						}
					}
				});
			},
			tclass : 'txtshort'
		}, {
			display : '��Ա��ɫ',
			name : 'roleName',
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
			display : '��Ա��ɫ',
			name : 'roleId',
			type : 'hidden'
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
						calPersonBatch(rowNum);
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
						calPersonBatch(rowNum);
					}
				}
			},
			value : $("#planEndDate").val()
		}, {
			display : '����',
			name : 'days',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			validation : {
				required : true
			},
			value : $("#days").val()
		}, {
			display : '����',
			name : 'number',
			tclass : 'txtshort',
			value : 1,
			event : {
				blur : function() {
					calPersonBatch($(this).data("rowNum"));
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
			readonly : true,
			type : 'hidden'
		}, {
			display : '�����ɱ�',
			name : 'personCostDays',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '�����ɱ����',
			name : 'personCost',
			tclass : 'readOnlyTxtShort',
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
		}]
	})
}