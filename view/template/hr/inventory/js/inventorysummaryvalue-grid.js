var show_page = function(page) {	   $("#inventorysummaryvalueGrid").yxgrid("reload");};
$(function() {			$("#inventorysummaryvalueGrid").yxgrid({				      model : 'hr_inventory_inventorysummaryvalue',
               	title : '�̵��ܽ�ֵ',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'question',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'answer',
                  					display : '��',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=hr_inventory_NULL&action=pageItemJson',
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