var show_page = function(page) {	   $("#planfeedbackGrid").yxgrid("reload");};
$(function() {			$("#planfeedbackGrid").yxgrid({				      model : 'produce_plan_planfeedback',
               	title : '�����ƻ����ȷ���',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'feedbackNum',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'feedbackDate',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'feedbackName',
                  					display : '������',
                  					sortable : true
                              },{
                    					name : 'feedbackId',
                  					display : '������Id',
                  					sortable : true
                              },{
                    					name : 'process',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'processName',
                  					display : '��Ŀ����',
                  					sortable : true
                              },{
                    					name : 'processTime',
                  					display : '����ʱ�䣨�룩',
                  					sortable : true
                              },{
                    					name : 'recipient',
                  					display : '������',
                  					sortable : true
                              },{
                    					name : 'recipientId',
                  					display : '������ID',
                  					sortable : true
                              },{
                    					name : 'recipientNum',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'recipientTime',
                  					display : '����ʱ��',
                  					sortable : true
                              },{
                    					name : 'finishTime',
                  					display : '���ʱ��',
                  					sortable : true
                              },{
                    					name : 'qualifiedNum',
                  					display : '�ϸ�����',
                  					sortable : true
                              },{
                    					name : 'unqualifiedNum',
                  					display : '���ϸ�����',
                  					sortable : true
                              },{
                    					name : 'productBatch',
                  					display : '�������κ�',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '��ע',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=produce_plan_NULL&action=pageItemJson',
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