$(function(){
	//邮件渲染
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});
	$("#importTable").yxeditgrid({
		url : "?model=engineering_resources_taskdetail&action=confirmTaskNumListJson",
		param : {"applyId":$("#id").val()},
		tableClass : 'form_in_table',
		objName : 'resourceapply[detail]',
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '发货任务id',
			name : 'taskId',
			type : 'hidden'
		},{
			display : '发货任务单号',
			name : 'taskCode',
			width : 120
		},{
			display : '申请单明细id',
			name : 'applyDetailId',
			type : 'hidden'
		},{
			display : '设备id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '分类id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '设备分类',
			name : 'resourceTypeName',
			width : 100,
			type : 'statictext'
		}, {
			display : '设备名称',
			name : 'resourceName',
			width : 150,
			type : 'statictext'
		}, {
			display : '单位',
			name : 'unit',
			width : 50,
			type : 'statictext'
		}, {
			display : '分配数量',
			name : 'number',
			width : 50,
			isSubmit : true
		}, {
			display : '待确认数量',
			name : 'awaitNumber',
			process : function(v) {
				return "<span class='red'>"+v+"</span>";
			},
			width : 60,
			isSubmit : true
		}, {
			display : '领用日期',
			name : 'planBeginDate',
			width : 100,
			type : 'statictext'
		}, {
			display : '归还日期',
			name : 'planEndDate',
			width : 100,
			type : 'statictext'
		}, {
			display : '使用天数',
			name : 'useDays',
			width : 50,
			type : 'statictext'
		}, {
			display : '备注',
			name : 'remark',
			process : function(v) {
				return "<span class='red'>"+v+"</span>";
			},
			width : 200
		}]
	});
});
//表单验证
function checkForm() {
	if(confirm('确认提交单据吗')){
		return true;
	}else{
		return false;
	}
}