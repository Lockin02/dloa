var show_page = function(page) {
	$("#renewGrid").yxsubgrid("reload");
};
$(function() {
			$("#renewGrid").yxsubgrid({
				      model : 'projectmanagent_borrow_renew',
				      param : { 'borrowId' : $("#borrowId").val(),'ExaStatusArr' : '���,���'},
				      isRightMenu : false,
				      isAddAction : false,
				      isEditAction : false,
				      isViewAction : false,
				      isDelAction : false,
				      showcheckbox : false,
               	title : 'Ա�����������赥',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'borrowId',
                  					display : 'Դ��ID',
                  					sortable : true,
                  					hide : true
                              },{
                    					name : 'raendDate',
                  					display : 'ԭ��ֹ����',
                  					sortable : true
                              },{
                    					name : 'reendDate',
                  					display : '�����ֹ����',
                  					sortable : true
                              },{
                    					name : 'renewremark',
                  					display : '����ԭ��',
                  					sortable : true,
                  					width : 200
                              },{
                    					name : 'renewdate',
                  					display : '����ʱ��',
                  					sortable : true
                              },{
                    					name : 'renewName',
                  					display : '������',
                  					sortable : true
                              },{
                    					name : 'ExaStatus',
                  					display : '����״̬',
                  					sortable : true
                              },{
                    					name : 'ExaDT',
                  					display : '��������',
                  					sortable : true
                              }],
                              // ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_borrow_renewequ&action=pageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'renewId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������

			}],
			// ��ʾ����
			colModel : [{
						name : 'productNo',
						width : 100,
						display : '��Ʒ���'
					},{
						name : 'productName',
						width : 100,
						display : '��Ʒ����'
					},{
						name : 'productModel',
						width : 100,
						display : '��Ʒ�ͺ�'
					}, {
					    name : 'number',
					    display : '��������',
						width : 80
					}, {
					    name : 'serialName',
					    display : '���к�',
						width : 400
					}]
		   }
 		});
 });