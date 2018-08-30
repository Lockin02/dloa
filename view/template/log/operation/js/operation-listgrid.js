// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#operantionGrid").yxgrid("reload");
};



$(function() {
	var objTable = $('#objTable').val();
	var objId = $('#objId').val();
	$("#operantionGrid").yxgrid({
		model : 'log_operation_operation',
		action : 'pageJson&objTable='+objTable + '&objId=' + objId,
		title : '操作记录',
		isToolBar : false,
		showcheckbox : false,
		isRightMenu : false,
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '操作人',
					name : 'operateManName',
					sortable : true
				}, {
					display : '操作时间',
					name : 'operateTime',
					sortable : true,
					width : '150'
				}, {
					display : '操作类型',
					name : 'operateType',
					sortable : true,
					width : '250'
				}, {
					display : '操作记录',
					name : 'operateLog',
					sortable : true,
					width : '400',
					process:function(v){
						if(v == 'NULL'){
							return '';
						}else{
							return v;
						}
					}
				}]
	});

});