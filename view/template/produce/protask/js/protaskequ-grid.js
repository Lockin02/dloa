var show_page = function(page) {
	$("#protaskequGrid").yxgrid("reload");
};
$(function() {
			$("#protaskequGrid").yxgrid({
				      model : 'produce_protask_protaskequ',
               	title : '���������嵥',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'mainId',
                  					display : '����id',
                  					sortable : true
                              },{
                    					name : 'mainCode',
                  					display : '������',
                  					sortable : true
                              },{
                    					name : 'relDocCode',
                  					display : 'ҵ����',
                  					sortable : true
                              },{
                    					name : 'relDocName',
                  					display : 'ҵ������',
                  					sortable : true
                              },{
                    					name : 'productId',
                  					display : '��Ʒid',
                  					sortable : true
                              },{
                    					name : 'productNo',
                  					display : '��Ʒ���',
                  					sortable : true
                              },{
                    					name : 'productName',
                  					display : '��Ʒ����',
                  					sortable : true
                              },{
                    					name : 'productModel',
                  					display : '��Ʒ����',
                  					sortable : true
                              },{
                    					name : 'number',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'unitName',
                  					display : '��λ',
                  					sortable : true
                              },{
                    					name : 'aidUnit',
                  					display : '������λ',
                  					sortable : true
                              },{
                    					name : 'converRate',
                  					display : '������',
                  					sortable : true
                              },{
                    					name : 'referDate',
                  					display : '�ƻ���������',
                  					sortable : true
                              },{
                    					name : 'license',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '��ע',
                  					sortable : true
                              }]
 		});
 });