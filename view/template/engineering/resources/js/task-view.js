$(function(){
	var taskInfoObj = $("#taskInfo");
	taskInfoObj.yxeditgrid({
		url : "?model=engineering_resources_taskdetail&action=listJson",
		param : {"taskId":$("#id").val()},
		tableClass : 'form_in_table',
		type : 'view',
		colModel : [{
			display : '设备id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '分类id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '设备分类',
			name : 'resourceTypeName'
		}, {
			display : '设备名称',
			name : 'resourceName'
		}, {
			display : '单位',
			name : 'unit'
		}, {
			display : '分配数量',
			name : 'number'
		}, {
			display : '待确认分配数量',
			name : 'awaitNumber',
			process : function(v) {
				if(v == "")
					v = "无";
				return v;
			}
		}, {
			display : '发货数量',
			name : 'exeNumber'
		}, {
			display : '撤回数量',
			name : 'backNumber'
		}, {
			display : '领用日期',
			name : 'planBeginDate'
		}, {
			display : '归还日期',
			name : 'planEndDate'
		}, {
			display : '使用天数',
			name : 'useDays',
			width : 50
		}, {
			display : '备注',
			name : 'remark'
		}]
	});
});