var show_page = function(page) {	   $("#shipmenttypeGrid").yxgrid("reload");};
$(function() {			$("#shipmenttypeGrid").yxgrid({				      model : 'projectmanagent_shipment_shipmenttype',
               	title : '���������Զ�������',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'type',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '��ע',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_shipment_NULL&action=pageJson',
			param : {
						paramId : 'mainId',
						colId : 'id'
					},
			colModel : {
						name : 'XXX',
						display : '�ӱ��ֶ�'
					}
		},
      
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