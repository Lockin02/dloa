var show_page = function(page) {	   $("#esmroleGrid").yxgrid("reload");};
$(function() {			$("#esmroleGrid").yxgrid({				      model : 'engineering_role_esmrole',
               	title : '��Ŀ��ɫ(oa_esm_project_role)',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'roleName',
                  					display : '��ɫ����',
                  					sortable : true
                              }                    ,{
                    					name : 'projectCode',
                  					display : '��Ŀ���',
                  					sortable : true
                              },{
                    					name : 'projectName',
                  					display : '��Ŀ����',
                  					sortable : true
                              },{
                    					name : 'jobDescription',
                  					display : '����˵��',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '������Id',
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '����������',
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '����ʱ��',
                  					sortable : true
                              },{
                    					name : 'updateId',
                  					display : '�޸���Id',
                  					sortable : true
                              },{
                    					name : 'updateName',
                  					display : '�޸�������',
                  					sortable : true
                              },{
                    					name : 'updateTime',
                  					display : '�޸�ʱ��',
                  					sortable : true
                              }                    ,{
                    					name : 'parentName',
                  					display : '�ϼ�����',
                  					sortable : true
                              }                                        ,{
                    					name : 'isLeaf',
                  					display : '�Ƿ�Ҷ�ӽڵ�',
                  					sortable : true
                              },{
                    					name : 'activityName',
                  					display : '�����',
                  					sortable : true
                              },{
                    					name : 'activityId',
                  					display : '�id',
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