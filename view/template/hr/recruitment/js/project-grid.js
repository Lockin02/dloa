var show_page = function(page) {	   $("#projectGrid").yxgrid("reload");};
$(function() {			$("#projectGrid").yxgrid({				      model : 'hr_recruitment_project',
               	title : 'ְλ�����-��Ŀ����',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'language',
                  					display : '�������',
                  					sortable : true
                              },{
                    					name : 'system',
                  					display : '����ϵͳ',
                  					sortable : true
                              },{
                    					name : 'dataBank',
                  					display : '���ݿ�',
                  					sortable : true
                              },{
                    					name : 'newSkill',
                  					display : 'Ŀǰҵ���¼���',
                  					sortable : true
                              },{
                    					name : 'beginDate',
                  					display : '��ʼʱ��',
                  					sortable : true
                              },{
                    					name : 'closeDate',
                  					display : '��ֹʱ��',
                  					sortable : true
                              },{
                    					name : 'projectName',
                  					display : '��Ŀ����',
                  					sortable : true
                              },{
                    					name : 'projectSkill',
                  					display : '��Ҫ���ú��ּ���',
                  					sortable : true
                              },{
                    					name : 'projectRole',
                  					display : '����Ŀ�еĽ�ɫ',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=hr_recruitment_NULL&action=pageItemJson',
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