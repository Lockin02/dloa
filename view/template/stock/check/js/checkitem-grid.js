var show_page = function(page) {
	$("#checkitemGrid").yxgrid("reload");
};
$(function() {
			$("#checkitemGrid").yxgrid({
				      model : 'stock_check_checkitem',
               	title : '�̵������嵥',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'checkId',
                  					display : '����̵�id',
                  					sortable : true
                              },{
                    					name : 'productId',
                  					display : '����id',
                  					sortable : true
                              },{
                    					name : 'productCode',
                  					display : '���ϱ��',
                  					sortable : true
                              },{
                    					name : 'productName',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'pattern',
                  					display : '����ͺ�',
                  					sortable : true
                              },{
                    					name : 'billNum',
                  					display : '�ʴ�����',
                  					sortable : true
                              },{
                    					name : 'actNum',
                  					display : 'ʵ������',
                  					sortable : true
                              },{
                    					name : 'adjustNum',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'unitName',
                  					display : '��λ',
                  					sortable : true
                              },{
                    					name : 'batchNum',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'price',
                  					display : '�۸�',
                  					sortable : true
                              },{
                    					name : 'subPrice',
                  					display : '���',
                  					sortable : true
                              },{
                    					name : 'stockId',
                  					display : '�ֿ�id',
                  					sortable : true
                              },{
                    					name : 'stockCode',
                  					display : '�ֿ����',
                  					sortable : true
                              },{
                    					name : 'stockName',
                  					display : '�ֿ�����',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '��ע',
                  					sortable : true
                              }]
 		});
 });