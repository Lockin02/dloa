var show_page = function(page) {	   $("#personGrid").yxgrid("reload");};
$(function() {			$("#personGrid").yxgrid({				      model : 'outsourcing_outsourcing_person',
               	title : '��Ա�����ϸ',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'riskCode',
                  					display : '����/ְλ����',
                  					sortable : true
                              },{
                    					name : 'peopleCount',
                  					display : '�����Ա����',
                  					sortable : true
                              },{
                    					name : 'startTime',
                  					display : 'Ԥ��ʹ�ÿ�ʼʱ��',
                  					sortable : true
                              },{
                    					name : 'endTime',
                  					display : 'Ԥ��ʹ�ý���ʱ��',
                  					sortable : true
                              },{
                    					name : 'skill',
                  					display : '��Ա��������',
                  					sortable : true
                              },{
                    					name : 'inBudget',
                  					display : '�����ɱ����ڲ�Ԥ�㣩',
                  					sortable : true
                              },{
                    					name : 'outBudget',
                  					display : '������',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=outsourcing_outsourcing_NULL&action=pageItemJson',
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