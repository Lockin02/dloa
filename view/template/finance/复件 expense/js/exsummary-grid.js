var show_page = function(page) {	   $("#exsummaryGrid").yxgrid("reload");};
$(function() {			$("#exsummaryGrid").yxgrid({				      model : 'finance_expense_exsummary',
               	title : '������������',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'BillNo',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'InputMan',
                  					display : '������',
                  					sortable : true
                              },{
                    					name : 'InputDate',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'CostMan',
                  					display : '������id',
                  					sortable : true
                              },{
                    					name : 'CostDepartID',
                  					display : '���ò���ID',
                  					sortable : true
                              },{
                    					name : 'Area',
                  					display : '����id',
                  					sortable : true
                              },{
                    					name : 'ProjectNo',
                  					display : '��Ŀ���',
                  					sortable : true
                              },{
                    					name : 'CostDates',
                  					display : '��������(��~��~)',
                  					sortable : true
                              },{
                    					name : 'CostMasterID',
                  					display : 'CostMasterID',
                  					sortable : true
                              },{
                    					name : 'CostBelongtoDeptIds',
                  					display : 'CostBelongtoDeptIds',
                  					sortable : true
                              },{
                    					name : 'CostClientType',
                  					display : '�ͻ�����(����)',
                  					sortable : true
                              },{
                    					name : 'CostClientArea',
                  					display : 'CostClientArea',
                  					sortable : true
                              },{
                    					name : 'CostClientName',
                  					display : '�ͻ�����',
                  					sortable : true
                              },{
                    					name : 'ServiceQuantity',
                  					display : 'ServiceQuantity',
                  					sortable : true
                              },{
                    					name : 'Status',
                  					display : 'Status',
                  					sortable : true
                              },{
                    					name : 'UpdateDT',
                  					display : 'UpdateDT',
                  					sortable : true
                              },{
                    					name : 'isProject',
                  					display : 'isProject',
                  					sortable : true
                              },{
                    					name : 'xm_sid',
                  					display : 'xm_sid',
                  					sortable : true
                              },{
                    					name : 'RecInvoiceDT',
                  					display : 'RecInvoiceDT',
                  					sortable : true
                              },{
                    					name : 'isNotReced',
                  					display : 'isNotReced',
                  					sortable : true
                              },{
                    					name : 'Amount',
                  					display : '���',
                  					sortable : true
                              },{
                    					name : 'Updator',
                  					display : 'Updator',
                  					sortable : true
                              },{
                    					name : 'PayDT',
                  					display : 'PayDT',
                  					sortable : true
                              },{
                    					name : 'IsFinRec',
                  					display : 'IsFinRec',
                  					sortable : true
                              },{
                    					name : 'FinRecDT',
                  					display : 'FinRecDT',
                  					sortable : true
                              },{
                    					name : 'SubDept',
                  					display : 'SubDept',
                  					sortable : true
                              },{
                    					name : 'ExamType',
                  					display : 'ExamType',
                  					sortable : true
                              },{
                    					name : 'CostBelongTo',
                  					display : 'CostBelongTo',
                  					sortable : true
                              },{
                    					name : 'CheckAmount',
                  					display : 'CheckAmount',
                  					sortable : true
                              },{
                    					name : 'isHandUp',
                  					display : 'isHandUp',
                  					sortable : true
                              },{
                    					name : 'HandUpDT',
                  					display : 'HandUpDT',
                  					sortable : true
                              },{
                    					name : 'Payee',
                  					display : 'Payee',
                  					sortable : true
                              },{
                    					name : 'rand_key',
                  					display : 'rand_key',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=finance_expense_NULL&action=pageItemJson',
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