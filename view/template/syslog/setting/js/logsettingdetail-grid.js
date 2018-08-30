var show_page = function(page) {
	$("#logsettingdetailGrid").yxgrid("reload");
};
$(function() {
			$("#logsettingdetailGrid").yxgrid({
				      model : 'syslog_setting_logsettingdetail',
               	title : '系统日志设置明细',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'mainId',
                  					display : 'mainId',
                  					sortable : true
                              },{
                    					name : 'columnName',
                  					display : '字段名',
                  					sortable : true
                              },{
                    					name : 'columnText',
                  					display : '字段中文意思',
                  					sortable : true
                              },{
                    					name : 'columnDataType',
                  					display : '字段数据类型',
                  					sortable : true
                              }]
 		});
 });