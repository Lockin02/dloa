var show_page = function(page) {	   $("#persronGrid").yxgrid("reload");};
$(function() {			$("#persronGrid").yxgrid({				      model : 'outsourcing_account_persron',
               	title : '���������Ա����',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'personLevel',
                  					display : '��Ա����',
                  					sortable : true
                              },{
                    					name : 'personLevelName',
                  					display : '��Ա��������',
                  					sortable : true
                              },{
                    					name : 'pesonName',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'userAccount',
                  					display : '�����˺�',
                  					sortable : true
                              },{
                    					name : 'userNo',
                  					display : 'Ա�����',
                  					sortable : true
                              },{
                    					name : 'suppName',
                  					display : '���������Ӧ��',
                  					sortable : true
                              }                    ,{
                    					name : 'beginDate',
                  					display : '���޿�ʼ����',
                  					sortable : true
                              },{
                    					name : 'endDate',
                  					display : '���޽�������',
                  					sortable : true
                              }                    ,{
                    					name : 'inBudgetPrice',
                  					display : '�����������ɱ�����',
                  					sortable : true
                              },{
                    					name : 'selfPrice',
                  					display : '�����������ɱ�',
                  					sortable : true
                              },{
                    					name : 'outBudgetPrice',
                  					display : '�����������',
                  					sortable : true
                              },{
                    					name : 'rentalPrice',
                  					display : '����۸�',
                  					sortable : true
                              },{
                    					name : 'trafficMoney',
                  					display : '��ͨ��',
                  					sortable : true
                              },{
                    					name : 'otherMoney',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'customerDeduct',
                  					display : '�ͻ��ۿ�',
                  					sortable : true
                              },{
                    					name : 'examinDuduct',
                  					display : '���˿ۿ�',
                  					sortable : true
                              },{
                    					name : 'skillsRequired',
                  					display : '��������Ҫ��',
                  					sortable : true
                              },{
                    					name : 'interviewResults',
                  					display : '�������Խ��',
                  					sortable : true
                              },{
                    					name : 'interviewName',
                  					display : '������Ա',
                  					sortable : true
                              },{
                    					name : 'interviewId',
                  					display : '������Աid',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '��ע',
                  					sortable : true
                              },{
                    					name : 'changeTips',
                  					display : '�����־',
                  					sortable : true
                              },{
                    					name : 'isTemp',
                  					display : '�Ƿ���ʱ����',
                  					sortable : true
                              }                    ,{
                    					name : 'isDel',
                  					display : '��ɾ����־λ',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=outsourcing_account_NULL&action=pageItemJson',
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