var show_page = function(page) {	   $("#schemelistGrid").yxgrid("reload");};
$(function() {			$("#schemelistGrid").yxgrid({				      model : 'hr_permanent_schemelist',
               	title : 'Ա�����ÿ�����Ŀϸ��',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'schemeCode',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'schemeName',
                  					display : '��������',
                  					sortable : true
                              }                    ,{
                    					name : 'standardCode',
                  					display : '������Ŀ����',
                  					sortable : true
                              },{
                    					name : 'standard',
                  					display : '������Ŀ����',
                  					sortable : true
                              },{
                    					name : 'standardProportion',
                  					display : '������ĿȨ��',
                  					sortable : true
                              },{
                    					name : 'standardContent',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'standardPoint',
                  					display : '����Ҫ��',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=hr_permanent_NULL&action=pageItemJson',
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