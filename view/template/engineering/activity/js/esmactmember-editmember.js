$(document).ready(function() {
	$("#importTable").yxeditgrid({
		objName : 'esmactmember[esmactmember]',
		url : '?model=engineering_activity_esmactmember&action=listJson',
		param : {
			'activityId' : $("#activityId").val(),
			'projectId' : $("#projectId").val()
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '人员姓名',
			name : 'memberName',
			tclass : 'txt',
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_esmmember({
					hiddenId : 'importTable_cmp_memberId' + rowNum,
					valueCol : 'memberId',
					nameCol : 'memberName',
					searchName : 'memberNameSearch',
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
								}
							})(rowNum)
						}
					}
				});
			},
			validation : {
				required : true
			}
		}, {
			display : 'memberId',
			name : 'memberId',
			type : 'hidden'
		}, {
			display : '人员等级',
			name : 'personLevel',
			readonly : true,
			tclass : 'readOnlyTxtNormal'
		}, {
			display : 'personLevelId',
			name : 'personLevelId',
			type : 'hidden'
		}, {
			display : '人员角色',
			name : 'roleName',
			readonly : true,
			tclass : 'readOnlyTxtNormal',
			validation : {
				required : true
			}
		}, {
			display : 'coefficient',
			name : 'coefficient',
			type : 'hidden'
		}, {
			display : 'price',
			name : 'price',
			type : 'hidden'
		}, {
			display : 'roleId',
			name : 'roleId',
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
			value : $("#activityId").val()
		}, {
			display : '任务名称',
			name : 'activityName',
			type : 'hidden',
			value : $("#activityName").val()
		}],
		event : {
			'beforeRemoveRow' : function(e, rowNum, rowData , g){
				//如果是原来的数组，判断能否删除
				if(typeof(rowData) != 'undefined'){
					if(confirm('确认删除人员吗？')){
						$.ajax({
						    type: "POST",
						    url: "?model=engineering_worklog_esmworklog&action=checkActLogUser",
						    data: {"userId" : rowData.memberId , 'activityId' : $("#activityId").val() },
						    async: false,
						    success: function(data){
						    	if(data == "1"){
									alert(rowData.memberName + ' 在本任务中已经录入过日志，不能进行删除');
									g.isRemoveAction = false;
						    	}
							}
						});
					}
				}
			}
		}
	})

	/**
	 * 验证信息(用到从表验证前，必须先使用validate)
	 */
	validate({

	});
});
