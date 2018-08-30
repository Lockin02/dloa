var show_page = function(page) {
	$("#presentChangeLogGrid").yxgrid_changeLog("reload");
};
$(function() {
			$("#presentChangeLogGrid").yxgrid_changeLog({
						param : {
							logObj : 'present',
							objId : $('#objId').val()
						},
						subGridOptions : {
							param : [{
										logObj : 'present'// 固定值放一起，而且要放前面
									}, {
										paramId : 'parentId',// 传递给后台的参数名称
										colId : 'id'// 获取主表行数据的列名称
									}]
						}
					});


		});