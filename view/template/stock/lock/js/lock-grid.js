var show_page = function(page) {
	$("#lockGrid").yxgrid("reload");
};
$(function() {
			$("#lockGrid").yxgrid({
				      model : 'stock_lock_lock',
               	title : '�ֿ�������¼��',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'stockId',
                  					display : '�����ֿ�id',
                  					sortable : true
                              },{
                    					name : 'inventoryId',
                  					display : '�������id',
                  					sortable : true
                              },{
                    					name : 'lockNum',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'lockType',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '������',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '������id',
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'updateName',
                  					display : '�޸���',
                  					sortable : true
                              },{
                    					name : 'updateId',
                  					display : '�޸���id',
                  					sortable : true
                              },{
                    					name : 'updateTime',
                  					display : '�޸�����',
                  					sortable : true
                              }]
 		});
 });