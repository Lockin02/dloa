var show_page = function(page) {	   $("#replacedinfoGrid").yxgrid("reload");};
$(function() {			$("#replacedinfoGrid").yxgrid({				      model : 'engineering_resources_replacedinfo',
               	title : '�豸����-���滻�豸����-���滻����',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'deviceName',
                  					display : '�豸����',
                  					sortable : true
                              }                    ,{
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
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=engineering_resources_NULL&action=pageItemJson',
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