$(document).ready(function() {
	//任务成员信息渲染
//	initMember();
	//人力预算
//	initPerson();

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
		"workRate" :{
			required : true
		},
		"workload" :{
			required : true
		}
	});
});
//初始化任务成员
function initMember(){
	//任务成员渲染
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
			display : '人员姓名',
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
			validation : {
				required : true
			},
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '人员角色',
			name : 'roleName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '人员角色id',
			name : 'roleId',
			type : 'hidden'
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
			display : '实际开始',
			name : 'actBeginDate',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '实际结束',
			name : 'actEndDate',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '天数',
			name : 'personDays',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '人力成本',
			type : 'statictext',
			name : 'personCostDays',
			width : 100,
			type : 'hidden'
		}, {
			display : '人力成本金额',
			type : 'statictext',
			name : 'personCost',
			width : 100,
			type : 'hidden'
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
		}, {
			display : '任务id',
			name : 'activityId',
			type : 'hidden',
			value : $("#id").val()
		}, {
			display : '任务名称',
			name : 'activityName',
			type : 'hidden',
			value : $("#activityName").val()
		}],
		event : {
			'beforeRemoveRow' : function(e, rowNum,rowDate, g){
				if(rowDate){
					if(rowDate.actBeginDate != "" && rowDate.actBeginDate != '0000-00-00'){
						alert('成员 [ ' + rowDate.memberName + ' ] 已经对本任务填写了日志，不能删除该任务成员!')
						g.isRemoveAction = false;
					}
				}
			}
		}
	})
}

//初始化人力预算
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
			display : '人员级别id',
			name : 'personLevelId',
			type : 'hidden'
		}, {
			display : '人员等级',
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
						calPersonBatch2(rowNum);
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
						calPersonBatch2(rowNum);
					}
				}
			},
			value : $("#planEndDate").val()
		}, {
			display : '天数',
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
						alert('所输天数过大!');
						$(this).val(s);
					}
					calPersonBatch2($(this).data("rowNum"));
				}
			},
			tclass : 'txtshort',
			value : $("#days").val()
		}, {
			display : '数量',
			name : 'number',
			tclass : 'txtshort',
			value : 1,
			event : {
				blur : function() {
					calPersonBatch2($(this).data("rowNum"));
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
			readonly : true
		}, {
			display : '人力成本',
			name : 'personCostDays',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '人力成本金额',
			name : 'personCost',
			tclass : 'readOnlyTxtMiddle',
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
		}, {
			display : '任务id',
			name : 'activityId',
			type : 'hidden',
			value : $("#id").val()
		}, {
			display : '任务名称',
			name : 'activityName',
			type : 'hidden',
			value : $("#activityName").val()
		}]
	})
}