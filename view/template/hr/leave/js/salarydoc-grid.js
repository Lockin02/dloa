var show_page = function(page) {      $("#salarydocGrid").yxsubgrid("reload");};
$(function() {      	$("#salarydocGrid").yxsubgrid({				      model : 'hr_leave_salarydoc',
               	title : '���ʽ��ӵ�',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'userNo',
                  					display : 'Ա�����',
                  					sortable : true
                              },{
                    					name : 'userAccount',
                  					display : 'Ա���˺�',
                  					sortable : true
                              },{
                    					name : 'userName',
                  					display : 'Ա������',
                  					sortable : true
                              },{
                    					name : 'deptName',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'deptId',
                  					display : '����Id',
                  					sortable : true
                              },{
                    					name : 'jobName',
                  					display : 'ְλ����',
                  					sortable : true
                              }                    ,{
                    					name : 'companyName',
                  					display : '���ƣ���˾��',
                  					sortable : true
                              },{
                    					name : 'companyId',
                  					display : '����id',
                  					sortable : true
                              },{
                    					name : 'entryDate',
                  					display : '��ְ����',
                  					sortable : true
                              },{
                    					name : 'quitDate',
                  					display : '��ְ����',
                  					sortable : true
                              },{
                    					name : 'quitReson',
                  					display : '��ְԭ��',
                  					sortable : true
                              },{
                    					name : 'quitTypeCode',
                  					display : '��ְ����',
                  					sortable : true
                              },{
                    					name : 'quitTypeName',
                  					display : '��ְ��������',
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
                              },{
                    					name : 'ExaStatus',
                  					display : '���״̬',
                  					sortable : true
                              },{
                    					name : 'ExaDT',
                  					display : '�������',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=hr_leave_salarydocitem&action=pageItemJson',
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