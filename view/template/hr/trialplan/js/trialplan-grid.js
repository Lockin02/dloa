var show_page = function(page) {	   $("#trialplanGrid").yxgrid("reload");};
$(function() {			$("#trialplanGrid").yxgrid({				      model : 'hr_trialplan_trialplan',
               	title : 'Ա��������ѵ�ƻ�',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'planName',
                  					display : '�ƻ�����',
                  					sortable : true
                              },{
                    					name : 'description',
                  					display : '������Ϣ',
                  					sortable : true
                              },{
                    					name : 'memberName',
                  					display : '�ƻ�ִ����',
                  					sortable : true
                              },{
                    					name : 'memberId',
                  					display : '�ƻ�ִ����id',
                  					sortable : true
                              },{
                    					name : 'status',
                  					display : '״̬',
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '������',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '������ID',
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '����ʱ��',
                  					sortable : true
                              },{
                    					name : 'updateName',
                  					display : '�޸���',
                  					sortable : true
                              },{
                    					name : 'updateId',
                  					display : '�޸���ID',
                  					sortable : true
                              },{
                    					name : 'updateTime',
                  					display : '�޸�ʱ��',
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