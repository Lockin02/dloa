var show_page = function(page) {	   $("#linkmanGrid").yxgrid("reload");};
$(function() {			$("#linkmanGrid").yxgrid({				      model : 'outsourcing_supplier_linkman',
               	title : '��Ӧ����ϵ��',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'suppCode',
                  					display : '��Ӧ�̱��',
                  					sortable : true
                              }                    ,{
                    					name : 'name',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'jobName',
                  					display : 'ְ��',
                  					sortable : true
                              },{
                    					name : 'mobile',
                  					display : '�绰',
                  					sortable : true
                              },{
                    					name : 'mobile2',
                  					display : '��ϵ�绰2',
                  					sortable : true
                              },{
                    					name : 'zipCode',
                  					display : '�ʱ�',
                  					sortable : true
                              },{
                    					name : 'address',
                  					display : '��ַ',
                  					sortable : true
                              },{
                    					name : 'fax',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'email',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'defaultContact',
                  					display : 'Ĭ����ϵ��',
                  					sortable : true
                              },{
                    					name : 'plane',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'remarks',
                  					display : '��ע',
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '����ʱ��',
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '����������',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '������Id',
                  					sortable : true
                              },{
                    					name : 'updateTime',
                  					display : '�޸�ʱ��',
                  					sortable : true
                              },{
                    					name : 'updateName',
                  					display : '�޸�������',
                  					sortable : true
                              },{
                    					name : 'updateId',
                  					display : '�޸���Id',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=outsourcing_supplier_NULL&action=pageItemJson',
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