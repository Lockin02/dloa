var show_page = function(page) {
	$("#systeminfoGrid").yxgrid("reload");
};
$(function() {
			$("#systeminfoGrid").yxgrid({
				      model : 'stock_stockinfo_systeminfo',
               	title : '�ִ���������Ϣ����',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'salesStockId',
                  					display : '����Ĭ�ϲֿ�id',
                  					sortable : true
                              },{
                    					name : 'salesStockName',
                  					display : '����Ĭ���˲ֿ�����',
                  					sortable : true
                              },{
                    					name : 'salesStockCode',
                  					display : '����Ĭ�ϲֿ����',
                  					sortable : true
                              },{
                    					name : 'packingStockId',
                  					display : '��װ��Ĭ�ϲֿ�id',
                  					sortable : true
                              },{
                    					name : 'packingStockName',
                  					display : '��װ��Ĭ�ϲֿ�����',
                  					sortable : true
                              },{
                    					name : 'packingStockCode',
                  					display : '��װ��Ĭ�ϲֿ����',
                  					sortable : true
                              }]
 		});
 });