var show_page = function(page) {	   $("#suppVerifyGrid").yxgrid("reload");};
$(function() {			$("#suppVerifyGrid").yxgrid({				      model : 'outsourcing_workverify_suppVerify',
               	title : '�����Ӧ�̹�����ȷ�ϵ�',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'parentCode',
                  					display : 'ȷ�ϵ����',
                  					sortable : true
                              }                    ,{
                    					name : 'formCode',
                  					display : '���ݱ��',
                  					sortable : true
                              },{
                    					name : 'formDate',
                  					display : '����ʱ��',
                  					sortable : true
                              },{
                    					name : 'beginDate',
                  					display : '���ڿ�ʼ����',
                  					sortable : true
                              },{
                    					name : 'endDate',
                  					display : '���ڽ�������',
                  					sortable : true
                              }                    ,{
                    					name : 'outsourceSuppCode',
                  					display : '�����˾���',
                  					sortable : true
                              },{
                    					name : 'outsourceSupp',
                  					display : '�����˾',
                  					sortable : true
                              }                    ,{
                    					name : 'projectCode',
                  					display : '��Ŀ���',
                  					sortable : true
                              },{
                    					name : 'projecttName',
                  					display : '��Ŀ����',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '��ע',
                  					sortable : true
                              },{
                    					name : 'status',
                  					display : '״̬',
                  					sortable : true
                              },{
                    					name : 'statusName',
                  					display : '״̬����',
                  					sortable : true
                              },{
                    					name : 'closeDate',
                  					display : '�ر�����',
                  					sortable : true
                              },{
                    					name : 'closeDesc',
                  					display : '�ر�˵��',
                  					sortable : true
                              },{
                    					name : 'ExaStatus',
                  					display : '����״̬',
                  					sortable : true
                              },{
                    					name : 'ExaDT',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'approveId',
                  					display : '�����',
                  					sortable : true
                              },{
                    					name : 'approveName',
                  					display : '���������',
                  					sortable : true
                              },{
                    					name : 'approveTime',
                  					display : '���ʱ��',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '������',
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
                  					display : '������',
                  					sortable : true
                              },{
                    					name : 'updateName',
                  					display : '����������',
                  					sortable : true
                              },{
                    					name : 'updateTime',
                  					display : '����ʱ��',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=outsourcing_workverify_NULL&action=pageItemJson',
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