var show_page = function(page) {
	$("#logsettingGrid").yxgrid("reload");
};
$(function() {
			$("#logsettingGrid").yxgrid({
						model : 'syslog_setting_logsetting',
						title : '系统日志设置',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'tableName',
									display : '表名',
									sortable : true,
									width : 200
								}, {
									name : 'businessName',
									display : '业务名称',
									sortable : true,
									width : 200
								}, {
									name : 'pkName',
									display : '业务主键字段名',
									sortable : true
								}, {
									name : 'createName',
									display : '创建人',
									sortable : true
								}, {
									name : 'createTime',
									display : '创建日期',
									sortable : true
								}, {
									name : 'updateName',
									display : '修改人',
									sortable : true
								}, {
									name : 'updateTime',
									display : '修改日期',
									sortable : true
								}],
						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [{
									display : "表名",
									name : 'tableName'
								}, {
									display : "业务名称",
									name : 'businessName'
								}]
					});
		});