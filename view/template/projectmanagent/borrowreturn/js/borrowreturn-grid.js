var show_page = function(page) {	   $("#borrowreturnGrid").yxgrid("reload");};
$(function() {			$("#borrowreturnGrid").yxgrid({				      model : 'projectmanagent_borrowreturn_borrowreturn',
               	title : '�����ù黹����',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'borrowId',
                  					display : '���õ�ID',
                  					sortable : true
                              },{
                    					name : 'borrowCode',
                  					display : '���õ����',
                  					sortable : true
                              },{
                    					name : 'borrowLimit',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '��ע',
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
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_borrowreturn_NULL&action=pageItemJson',
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