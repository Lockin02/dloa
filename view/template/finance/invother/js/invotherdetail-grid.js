var show_page = function(page) {	   $("#invotherdetailGrid").yxgrid("reload");};
$(function() {			$("#invotherdetailGrid").yxgrid({				      model : 'finance_invother_invotherdetail',
               	title : 'Ӧ��������Ʊ��ϸ',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'invOthId',
                  					display : '������Ʊid',
                  					sortable : true
                              },{
                    					name : 'productName',
                  					display : '��Ʊ��Ŀ',
                  					sortable : true
                              },{
                    					name : 'productNo',
                  					display : '��Ʊ��Ŀ����',
                  					sortable : true
                              },{
                    					name : 'productId',
                  					display : '��Ʊ��Ŀid',
                  					sortable : true
                              },{
                    					name : 'number',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'unit',
                  					display : '��λ',
                  					sortable : true
                              },{
                    					name : 'price',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'rate',
                  					display : '˰��',
                  					sortable : true
                              },{
                    					name : 'taxPrice',
                  					display : '��˰����',
                  					sortable : true
                              },{
                    					name : 'assessment',
                  					display : '˰��',
                  					sortable : true
                              },{
                    					name : 'amount',
                  					display : '�ܽ��(��˰)',
                  					sortable : true
                              },{
                    					name : 'allCount',
                  					display : '�ܶ�(����˰)',
                  					sortable : true
                              },{
                    					name : 'objId',
                  					display : 'Դ��id',
                  					sortable : true
                              },{
                    					name : 'objCode',
                  					display : 'Դ�����',
                  					sortable : true
                              },{
                    					name : 'objType',
                  					display : 'Դ������',
                  					sortable : true
                              }],	   	
      
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : {
					display : "�����ֶ�",
					name : 'XXX'
				}
 		});
 });