var show_page = function(page) {	   $("#projectRentalGrid").yxgrid("reload");};
$(function() {			$("#projectRentalGrid").yxgrid({				      model : 'outsourcing_approval_projectRental',
               	title : '������������ְ���',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                                        ,{
                    					name : 'costType',
                  					display : '��������',
                  					sortable : true
                              }                    ,{
                    					name : 'parentName',
                  					display : '�ϼ�����',
                  					sortable : true
                              },{
                    					name : 'feeType',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'price',
                  					display : '�۸�',
                  					sortable : true
                              },{
                    					name : 'number',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'period',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'amount',
                  					display : '���',
                  					sortable : true
                              }                    ,{
                    					name : 'suppCode',
                  					display : '���������Ӧ��Code',
                  					sortable : true
                              },{
                    					name : 'suppName',
                  					display : '���������Ӧ��',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '��ע',
                  					sortable : true
                              },{
                    					name : 'isSelf',
                  					display : '�Ƿ񱾹�˾',
                  					sortable : true
                              },{
                    					name : 'isOtherFee',
                  					display : '�Ƿ���������',
                  					sortable : true
                              },{
                    					name : 'isManageFee',
                  					display : '�Ƿ�������',
                  					sortable : true
                              },{
                    					name : 'isProfit',
                  					display : '�Ƿ�����',
                  					sortable : true
                              },{
                    					name : 'isTax',
                  					display : '�Ƿ�˰��',
                  					sortable : true
                              },{
                    					name : 'isServerCost',
                  					display : '�Ƿ����ɱ�',
                  					sortable : true
                              },{
                    					name : 'isAllCost',
                  					display : '�Ƿ��ܳɱ�',
                  					sortable : true
                              },{
                    					name : 'isChoosed',
                  					display : '�Ƿ�ѡ��',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=outsourcing_approval_NULL&action=pageItemJson',
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