var show_page = function(page) {	   $("#budgetinfoGrid").yxgrid("reload");};
$(function() {			$("#budgetinfoGrid").yxgrid({				      model : 'equipment_budget_budgetinfo',
               	title : '�豸Ԥ���ӱ�',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'name',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'info',
                  					display : '������ϸ',
                  					sortable : true
                              },{
                    					name : 'price',
                  					display : '���õ���',
                  					sortable : true
                              },{
                    					name : 'num',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'money',
                  					display : '���ý��',
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