var show_page = function(page) {
	$("#logsettingdetailGrid").yxgrid("reload");
};
$(function() {
			$("#logsettingdetailGrid").yxgrid({
				      model : 'syslog_setting_logsettingdetail',
               	title : 'ϵͳ��־������ϸ',
						//����Ϣ
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
                  					display : '�ֶ���',
                  					sortable : true
                              },{
                    					name : 'columnText',
                  					display : '�ֶ�������˼',
                  					sortable : true
                              },{
                    					name : 'columnDataType',
                  					display : '�ֶ���������',
                  					sortable : true
                              }]
 		});
 });