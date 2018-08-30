var show_page = function(page) {
	$(".esmworklogproinfoGrid").yxgrid("reload");
};
$(function() {
			$(".esmworklogproinfoGrid").yxgrid({
				      model : 'engineering_worklog_esmworklogproinfo',
               	title : '日志参与项目',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'workLogId',
                  					display : '工作日志id',
                  					sortable : true
                              },{
                    					name : 'proName',
                  					display : '项目名称',
                  					sortable : true
                              },{
                    					name : 'proId',
                  					display : '项目ID',
                  					sortable : true
                              },{
                    					name : 'proCode',
                  					display : '项目编码',
                  					sortable : true
                              }]
 		});
 });