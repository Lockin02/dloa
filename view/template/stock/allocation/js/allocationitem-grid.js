var show_page = function(page) {
	$("#allocationitemGrid").yxgrid("reload");
};
$(function() {
			$("#allocationitemGrid").yxgrid({
				      model : 'stock_allocation_allocationitem',
               	title : '�����������嵥',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'mainId',
                  					display : '������id',
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
                    					name : 'cost',
                  					display : '��λ�ɱ�',
                  					sortable : true
                              },{
                    					name : 'subCost',
                  					display : '�ɱ�',
                  					sortable : true
                              },{
                    					name : 'allocatNum',
                  					display : '��������',
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
                    					name : 'exportStockId',
                  					display : '�����ֿ�id',
                  					sortable : true
                              },{
                    					name : 'exportStockCode',
                  					display : '�����ֿ����',
                  					sortable : true
                              },{
                    					name : 'exportStockName',
                  					display : '�����ֿ�����',
                  					sortable : true
                              },{
                    					name : 'importStockId',
                  					display : '����ֿ�id',
                  					sortable : true
                              },{
                    					name : 'importStockCode',
                  					display : '����ֿ����',
                  					sortable : true
                              },{
                    					name : 'importStockName',
                  					display : '����ֿ�����',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '��ע',
                  					sortable : true
                              }]
 		});
 });