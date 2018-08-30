var show_page = function(page) {
	$("#orderChangeLogGrid").yxgrid_changeLog("reload");
};
$(function() {
			$("#orderChangeLogGrid").yxgrid_changeLog({
						param : {
							logObj : 'rdprojectSignin',
							objId : $('#objId').val()
						},
						subGridOptions : {
							param : [{
										logObj : 'rdprojectSignin'// 固定值放一起，而且要放前面
									}, {
										paramId : 'parentId',// 传递给后台的参数名称
										colId : 'id'// 获取主表行数据的列名称
									}]
						}
					});


		});