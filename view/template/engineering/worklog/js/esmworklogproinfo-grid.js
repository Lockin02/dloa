var show_page = function(page) {
	$(".esmworklogproinfoGrid").yxgrid("reload");
};
$(function() {
			$(".esmworklogproinfoGrid").yxgrid({
				      model : 'engineering_worklog_esmworklogproinfo',
               	title : '��־������Ŀ',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'workLogId',
                  					display : '������־id',
                  					sortable : true
                              },{
                    					name : 'proName',
                  					display : '��Ŀ����',
                  					sortable : true
                              },{
                    					name : 'proId',
                  					display : '��ĿID',
                  					sortable : true
                              },{
                    					name : 'proCode',
                  					display : '��Ŀ����',
                  					sortable : true
                              }]
 		});
 });