var show_page = function(page) {
	$("#rdprojectChangeLogGrid").yxgrid_changeLog("reload");
};
$(function() {
			$("#rdprojectChangeLogGrid").yxgrid_changeLog({
						param : {
							logObj : 'rdproject',
							objId : $('#objId').val()
						},
						subGridOptions : {
							param : [{
										logObj : 'rdproject'// 固定值放一起，而且要放前面
									}, {
										paramId : 'parentId',// 传递给后台的参数名称
										colId : 'id'// 获取主表行数据的列名称
									}]
						}
					});


		});