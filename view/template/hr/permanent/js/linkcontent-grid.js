var show_page = function(page) {	   $("#linkcontentGrid").yxgrid("reload");};
$(function() {			$("#linkcontentGrid").yxgrid({				      model : 'hr_permanent_linkcontent',
               	title : 'Ա��ת�����˹������',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'parentCode',
                  					display : 'Դ�����',
                  					sortable : true
                              },{
                    					name : 'workPoint',
                  					display : '����Ҫ��',
                  					sortable : true
                              },{
                    					name : 'outPoint',
                  					display : '����ɹ�',
                  					sortable : true
                              },{
                    					name : 'finishTime',
                  					display : '���ʱ��ڵ�',
                  					sortable : true
                              },{
                    					name : 'ownType',
                  					display : '��������',
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