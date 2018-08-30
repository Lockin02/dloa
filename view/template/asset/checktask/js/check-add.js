$(function() {

	/**
	 * 验证信息
	 */
	validate({
		"taskNo" : {
			required : true

		},
		"beginDate" : {
			custom : ['date']
		},
		"endDate" : {
			custom : ['date']

		},
		"dept" : {
			required : true

		},
		"man" : {
			required : true

		}

	});

	//选择人员带出部门信息
	$("#man").yxselect_user({
		hiddenId : 'manId'
//		isGetDept : [true, "deptId", "dept"]

	});

	//选择盘点任务编号
	$("#taskNo").yxcombogrid_checktask({
		hiddenId : 'taskId',
		width : 600,
		isShowButton : false,
		gridOptions : {
			isShowButton : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#dept").val(data.deptName);
					$("#deptId").val(data.deptId);
				}
			}
		}
	});

});
//验证开始时间结束时间先后
function Check() {

	var start = $("#beginDate").val();
	var end = $("#endDate").val();

	if (start != '' && end != '') {
		start = start.split('-');
		end = end.split('-');
		var start1 = new Date(start[0], start[1] - 1, start[2]);
		var end1 = new Date(end[0], end[1] - 1, end[2]);

		if (start1 > end1) {
			alert("结束时间不能在开始时间之前！");
			return false;
		}
	}
}