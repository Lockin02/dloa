var show_page = function(page) {	   $("#abilityGrid").yxgrid("reload");};
$(function() {			$("#abilityGrid").yxgrid({				      model : 'hr_position_ability',
               	title : 'ְλ����Ҫ��',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'parentCode',
                  					display : 'ְλ�����',
                  					sortable : true
                              },{
                    					name : 'positionName',
                  					display : 'ְλ����',
                  					sortable : true
                              },{
                    					name : 'featureItem',
                  					display : '������',
                  					sortable : true
                              },{
                    					name : 'contents',
                  					display : '��������',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=hr_position_NULL&action=pageItemJson',
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