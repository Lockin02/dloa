var show_page = function(page) {
	$("#vehiclesuppequGrid").yxgrid("reload");
};
$(function() {
	$("#vehiclesuppequGrid").yxgrid({
		model : 'outsourcing_outsourcessupp_vehiclesuppequ',
       	title : '������Ӧ��-������Դ��Ϣ',
			//����Ϣ
			colModel : [{
 					display : 'id',
 						name : 'id',
 					sortable : true,
 						hide : true
			  },{
            			name : 'area',
  					display : '����',
  					sortable : true
              },{
    					name : 'areaId',
  					display : '����id',
  					sortable : true
              },{
    					name : 'rentPrice',
  					display : '�⳵�ѵ���',
  					sortable : true,
  					process : function (v) {
  						return moneyFormat2(v);
  					}
              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=outsourcing_outsourcessupp_NULL&action=pageItemJson',
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