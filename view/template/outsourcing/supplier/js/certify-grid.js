var show_page = function(page) {	   $("#certifyGrid").yxgrid("reload");};
$(function() {			$("#certifyGrid").yxgrid({				      model : 'outsourcing_supplier_certify',
               	title : '��Ӧ������֤��',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'suppName',
                  					display : '�����Ӧ��',
                  					sortable : true
                              },{
                    					name : 'suppCode',
                  					display : '��Ӧ�̱��',
                  					sortable : true
                              },{
                    					name : 'typeName',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'typeCode',
                  					display : '����Code',
                  					sortable : true
                              },{
                    					name : 'certifyName',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'certifyLevel',
                  					display : '���ʵȼ�',
                  					sortable : true
                              },{
                    					name : 'certifyCode',
                  					display : '����֤����',
                  					sortable : true
                              },{
                    					name : 'beginDate',
                  					display : '������Ч��(��ʼ��)',
                  					sortable : true
                              },{
                    					name : 'endDate',
                  					display : '������Ч��(��ֹ��)',
                  					sortable : true
                              },{
                    					name : 'certifyCompany',
                  					display : '���ʷ��ŵ�λ',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '��ע',
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