var show_page = function(page) {	   $("#extensionGrid").yxgrid("reload");};
$(function() {			$("#extensionGrid").yxgrid({				      model : 'projectmanagent_trialproject_extension',
               	title : '��������',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'trialprojectCode',
                  					display : '������Ŀ���',
                  					sortable : true
                              },{
                    					name : 'extensionDate',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'updateTime',
                  					display : '�޸�ʱ��',
                  					sortable : true
                              },{
                    					name : 'updateName',
                  					display : '�޸�������',
                  					sortable : true
                              },{
                    					name : 'updateId',
                  					display : '�޸���Id',
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '����ʱ��',
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '����������',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '������ID',
                  					sortable : true
                              },{
                    					name : 'ExaStatus',
                  					display : '����״̬',
                  					sortable : true
                              },{
                    					name : 'ExaDT',
                  					display : '��������',
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