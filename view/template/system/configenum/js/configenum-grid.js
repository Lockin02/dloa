var show_page = function(page) {	   $("#configenumGrid").yxgrid("reload");};
$(function() {			$("#configenumGrid").yxgrid({				      model : 'system_configenum_configenum',
               	title : 'ϵͳ��������ö��',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'configEnum1',
                  					display : '����ö��1',
                  					sortable : true
                              },{
                    					name : 'configEnum2',
                  					display : '����ö��2',
                  					sortable : true
                              },{
                    					name : 'configEnum3',
                  					display : '����ö��3',
                  					sortable : true
                              },{
                    					name : 'configEnum4',
                  					display : '����ö��4',
                  					sortable : true
                              },{
                    					name : 'configEnum5',
                  					display : '����ö��5',
                  					sortable : true
                              },{
                    					name : 'configEnum6',
                  					display : '����ö��6',
                  					sortable : true
                              },{
                    					name : 'configEnum7',
                  					display : '����ö��7',
                  					sortable : true
                              },{
                    					name : 'configEnum8',
                  					display : '����ö��8',
                  					sortable : true
                              },{
                    					name : 'configEnum9',
                  					display : '����ö��9',
                  					sortable : true
                              },{
                    					name : 'configEnum10',
                  					display : '����ö��10',
                  					sortable : true
                              },{
                    					name : 'configEnumName',
                  					display : '����ö������',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=system_configenum_NULL&action=pageItemJson',
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