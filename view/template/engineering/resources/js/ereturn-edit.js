$(document).ready(function() {
	// 人员渲染
	$("#applyUser").yxselect_user({
		hiddenId : 'applyUserId',
		isGetDept : [ true, "deptId", "deptName" ],
		formCode : 'resourceapply'
	});

	// 实例化邮寄公司
	$("#expressName").yxcombogrid_logistics({
		hiddenId : 'expressId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
				}
			}
		}
	});

	$("#importTable").yxeditgrid({
		url : '?model=engineering_resources_ereturndetail&action=listJson',
		param : {
			'mainId' : $("#id").val()
		},
		tableClass : 'form_in_table',
		type : 'view',
		colModel : [ {
			display : '设备类型',
			name : 'resourceTypeName',
			isSubmit : true
		}, {
			name : 'resourceName',
			display : '设备名称',
			isSubmit : true
		}, {
			name : 'coding',
			display : '机身码',
			isSubmit : true
		}, {
			name : 'dpcoding',
			display : '部门编码',
			isSubmit : true
		}, {
			name : 'number',
			display : '数量',
			isSubmit : true
		}, {
			name : 'unit',
			display : '单位',
			isSubmit : true
		}, {
			name : 'resourceId',
			display : '设备ID',
			isSubmit : true,
			type : 'hidden'
		}, {
			name : 'resourceTypeId',
			display : '设备类型ID',
			isSubmit : true,
			type : 'hidden'
		}]
	});
	/**
	 * 验证信息(用到从表验证前，必须先使用validate)
	 */
	validate({
		"applyUser" : {
			required : true
		},
		"applyDate" : {
			required : true
		},
		"areaId" : {
			required : true
		}
	});
});

// 提交确认改变隐藏值
function setStatus(thisType) {
	$("#status").val(thisType);
}