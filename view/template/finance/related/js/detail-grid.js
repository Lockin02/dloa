var show_page = function(page) {
	$("#detailGrid").yxgrid("reload");
};
$(function() {
			$("#detailGrid").yxgrid({
				      model : 'finance_related_detail',
               	title : '��������¼��',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'relatedId',
                  					display : '��������id(�������)',
                  					sortable : true
                              },{
                    					name : 'productId',
                  					display : '��Ʒid',
                  					sortable : true
                              },{
                    					name : 'productCode',
                  					display : '��Ʒ����',
                  					sortable : true
                              },{
                    					name : 'productModel',
                  					display : '����ͺ�',
                  					sortable : true
                              },{
                    					name : 'number',
                  					display : '���ι�������',
                  					sortable : true
                              },{
                    					name : 'amount',
                  					display : '���ι������',
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
                              },{
                    					name : 'formDate',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'supplierId',
                  					display : '��Ӧ��Id',
                  					sortable : true
                              },{
                    					name : 'supplierName',
                  					display : '��Ӧ������',
                  					sortable : true
                              },{
                    					name : 'purType',
                  					display : '�ɹ���ʽ',
                  					sortable : true
                              },{
                    					name : 'property',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'unit',
                  					display : '������λ',
                  					sortable : true
                              },{
                    					name : 'isAcount',
                  					display : '�Ƿ��Ѻ���',
                  					sortable : true
                              },{
                    					name : 'hookObjCode',
                  					display : '����������',
                  					sortable : true
                              },{
                    					name : 'hookObj',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'hookId',
                  					display : '������Ŀid',
                  					sortable : true
                              },{
                    					name : 'hookDate',
                  					display : '����',
                  					sortable : true
                              }]
 		});
 });