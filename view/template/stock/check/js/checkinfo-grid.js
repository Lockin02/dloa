var show_page = function(page) {
	$("#checkinfoGrid").yxgrid("reload");
};
$(function() {
			$("#checkinfoGrid").yxgrid({
				      model : 'stock_check_checkinfo',
               	title : '�̵������Ϣ',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'docCode',
                  					display : '���ݱ��',
                  					sortable : true
                              },{
                    					name : 'checkType',
                  					display : '�̵�����',
                  					sortable : true
                              },{
                    					name : 'stockId',
                  					display : '�ֿ�id',
                  					sortable : true
                              },{
                    					name : 'stockCode',
                  					display : '�ֿ���',
                  					sortable : true
                              },{
                    					name : 'stockName',
                  					display : '�ֿ�����',
                  					sortable : true
                              },{
                    					name : 'auditStatus',
                  					display : '�̵�״̬',
                  					sortable : true
                              },{
                    					name : 'dealUserId',
                  					display : '������id',
                  					sortable : true
                              },{
                    					name : 'dealUserName',
                  					display : '������',
                  					sortable : true
                              },{
                    					name : 'auditUserName',
                  					display : '�����',
                  					sortable : true
                              },{
                    					name : 'auditUserId',
                  					display : '�����id',
                  					sortable : true
                              },{
                    					name : 'ExaStatus',
                  					display : '����״̬',
                  					sortable : true
                              },{
                    					name : 'ExaDT',
                  					display : '����ʱ��',
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '������',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '������id',
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'updateName',
                  					display : '�޸���',
                  					sortable : true
                              },{
                    					name : 'updateId',
                  					display : '�޸���id',
                  					sortable : true
                              },{
                    					name : 'updateTime',
                  					display : '�޸�����',
                  					sortable : true
                              }]
 		});
 });