var show_page = function(page) {
	$("#taskChangeLogGrid").yxgrid_changeLog("reload");
};
$(function() {
			$("#taskChangeLogGrid").yxgrid_changeLog({
						param : {
							logObj : 'purchasetask',
							objId : $('#objId').val()
						},
						subGridOptions : {
							param : [{
										logObj : 'purchasetask'// 固定值放一起，而且要放前面
									}, {
										paramId : 'parentId',// 传递给后台的参数名称
										colId : 'id'// 获取主表行数据的列名称
									}]
						}
					});


		});