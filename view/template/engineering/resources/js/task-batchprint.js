//初始化单据
$(function(){
	//打印信息赋值
	$("#headNum").html($("#allNum").html());
	var idStr = $("#ids").val();
	var idArr = idStr.split(",");
	for(var i=0;i<idArr.length;i++){
		var taskInfoObj = $("#taskInfo"+idArr[i]);
		taskInfoObj.yxeditgrid({
			url : "?model=engineering_resources_taskdetail&action=listJson",
			param : {"taskId":idArr[i]},
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
				display : '发货数量',
				name : 'exeNumber'
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
		});;
	}
});