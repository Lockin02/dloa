var show_page = function(page) {	   $("#procompositebaseitemGrid").yxgrid("reload");};
$(function() {			$("#procompositebaseitemGrid").yxgrid({				      model : 'stock_extra_procompositebaseitem',
               	title : '��Ʒ���Ͽ��ɹ������ۺϱ��嵥',
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
                    					name : 'goodsId',
                  					display : '��Ʒid',
                  					sortable : true
                              },{
                    					name : 'goodsName',
                  					display : '��Ʒ����',
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
                    					name : 'purchDays',
                  					display : '�ɹ�ʱ�䣨�죩',
                  					sortable : true
                              },{
                    					name : 'forecastSaleNum',
                  					display : '����Ԥ������',
                  					sortable : true
                              },{
                    					name : 'planPurchNum',
                  					display : '�ƻ��ɹ�����',
                  					sortable : true
                              },{
                    					name : 'availableNum',
                  					display : '���ÿ��',
                  					sortable : true
                              },{
                    					name : 'isProduce',
                  					display : '�Ƿ�ͣ��',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '��ע',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=stock_extra_NULL&action=pageItemJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'XXX',
						display : '�ӱ��ֶ�'
					}]
		},
      
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "�����ֶ�",
					name : 'XXX'
				}]
 		});
 });