var show_page = function(page) {
	$("#stockinitemGrid").yxgrid("reload");
};
$(function() {
			$("#stockinitemGrid").yxgrid({
				      model : 'stock_instock_stockinitem',
               	title : '��ⵥ�����嵥',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'mainId',
                  					display : '��ⵥid',
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
                    					name : 'inStockId',
                  					display : '���ϲֿ�id',
                  					sortable : true
                              },{
                    					name : 'inStockCode',
                  					display : '���ϲֿ����',
                  					sortable : true
                              },{
                    					name : 'inStockName',
                  					display : '���ϲֿ�����',
                  					sortable : true
                              },{
                    					name : 'contractId',
                  					display : '��ͬid',
                  					sortable : true
                              },{
                    					name : 'contractCode',
                  					display : '��ͬ���',
                  					sortable : true
                              },{
                    					name : 'contractName',
                  					display : '��ͬ����',
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
                    					name : 'storageNum',
                  					display : 'Ӧ������',
                  					sortable : true
                              },{
                    					name : 'actNum',
                  					display : 'ʵ������',
                  					sortable : true
                              },{
                    					name : 'shelfLife',
                  					display : '������',
                  					sortable : true
                              },{
                    					name : 'prodDate',
                  					display : '�������ɹ�������',
                  					sortable : true
                              },{
                    					name : 'validDate',
                  					display : '��Ч����',
                  					sortable : true
                              },{
                    					name : 'hookNumber',
                  					display : '�ѹ�������',
                  					sortable : true
                              },{
                    					name : 'hookAmount',
                  					display : '�ѹ������',
                  					sortable : true
                              },{
                    					name : 'unHookNumber',
                  					display : 'δ��������',
                  					sortable : true
                              },{
                    					name : 'unHookAmount',
                  					display : 'δ�������',
                  					sortable : true
                              }]
 		});
 });