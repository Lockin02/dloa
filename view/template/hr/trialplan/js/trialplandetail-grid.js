var show_page = function(page) {	   $("#trialplandetailGrid").yxgrid("reload");};
$(function() {			$("#trialplandetailGrid").yxgrid({				      model : 'hr_trialplan_trialplandetail',
               	title : 'Ա�����üƻ���ϸ',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'taskName',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'description',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'managerName',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'managerId',
                  					display : '������������',
                  					sortable : true
                              },{
                    					name : 'weights',
                  					display : '��ռ����',
                  					sortable : true
                              },{
                    					name : 'memberName',
                  					display : '����ִ����',
                  					sortable : true
                              },{
                    					name : 'memberId',
                  					display : '����ִ����id',
                  					sortable : true
                              },{
                    					name : 'status',
                  					display : '״̬',
                  					sortable : true
                              },{
                    					name : 'score',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'scoreDesc',
                  					display : '����˵��',
                  					sortable : true
                              },{
                    					name : 'beforeId',
                  					display : 'ǰ������id',
                  					sortable : true
                              },{
                    					name : 'beforeName',
                  					display : 'ǰ����������',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=hr_trialplan_NULL&action=pageItemJson',
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