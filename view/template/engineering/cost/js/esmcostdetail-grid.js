var show_page = function(page) {	   $("#esmcostdetailGrid").yxgrid("reload");};
$(function() {			$("#esmcostdetailGrid").yxgrid({				      model : 'engineering_cost_esmcostdetail',
               	title : '������ϸ',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'projectCode',
                  					display : '��Ŀ���',
                  					sortable : true
                              },{
                    					name : 'projectName',
                  					display : '��Ŀ����',
                  					sortable : true
                              }                    ,{
                    					name : 'activityName',
                  					display : '��������',
                  					sortable : true
                              }                    ,{
                    					name : 'costType',
                  					display : '��������',
                  					sortable : true
                              }                    ,{
                    					name : 'costMoney',
                  					display : '���ý��',
                  					sortable : true
                              },{
                    					name : 'days',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'status',
                  					display : '״̬',
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
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=engineering_cost_NULL&action=pageItemJson',
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