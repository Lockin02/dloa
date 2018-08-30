$(document).ready(function() {
	//任务成员信息渲染
	initPerson();

	/**
	 * 验证信息
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

//初始化项目成员列表渲染
function initPerson(){
	$("#activityMembers").yxeditgrid({
		objName : 'esmactivity[esmperson]',
		tableClass : 'form_in_table',
		colModel : [{
			display : '人员姓名',
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

									//选择了人员后，人员等级和数量变为不可录
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
			display : '人员id',
			name : 'memberId',
			type : 'hidden'
		}, {
			display : '人员级别id',
			name : 'personLevelId',
			type : 'hidden'
		}, {
			display : '人员等级',
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
									//选择了人员后，人员等级和数量变为不可录
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
			display : '人员角色',
			name : 'roleName',
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
			display : '人员角色',
			name : 'roleId',
			type : 'hidden'
		}, {
			display : '预计开始',
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
			display : '预计结束',
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
			display : '天数',
			name : 'days',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			validation : {
				required : true
			},
			value : $("#days").val()
		}, {
			display : '数量',
			name : 'number',
			tclass : 'txtshort',
			value : 1,
			event : {
				blur : function() {
					calPersonBatch($(this).data("rowNum"));
				}
			}
		}, {
			display : '单价',
			name : 'price',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '计量系数',
			name : 'coefficient',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			type : 'hidden'
		}, {
			display : '人力天数',
			name : 'personDays',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			type : 'hidden'
		}, {
			display : '人力成本',
			name : 'personCostDays',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '人力成本金额',
			name : 'personCost',
			tclass : 'readOnlyTxtShort',
			type : 'money',
			readonly : true
		}, {
			display : '项目id',
			name : 'projectId',
			type : 'hidden',
			value : $("#projectId").val()
		}, {
			display : '项目编号',
			name : 'projectCode',
			type : 'hidden',
			value : $("#projectCode").val()
		}, {
			display : '项目名称',
			name : 'projectName',
			type : 'hidden',
			value : $("#projectName").val()
		}]
	})
}