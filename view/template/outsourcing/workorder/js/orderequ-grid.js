var show_page = function(page) {	   $("#orderequGrid").yxgrid("reload");};
$(function() {			$("#orderequGrid").yxgrid({				      model : 'outsourcing_workorder_orderequ',
               	title : '�����ӱ�',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'personName',
                  					display : 'ʩ����Ա',
                  					sortable : true
                              },{
                    					name : 'personId',
                  					display : 'ʩ����ԱID',
                  					sortable : true
                              },{
                    					name : 'IdCard',
                  					display : '���֤����',
                  					sortable : true
                              },{
                    					name : 'email',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'exceptStart',
                  					display : '��ĿԤ�ƿ�ʼʱ��',
                  					sortable : true
                              },{
                    					name : 'exceptEnd',
                  					display : '��ĿԤ�ƽ���ʱ��',
                  					sortable : true
                              },{
                    					name : 'price',
                  					display : '���ۣ�Ԫ��',
                  					sortable : true
                              },{
                    					name : 'payWay',
                  					display : '���㷽ʽ',
                  					sortable : true
                              },{
                    					name : 'payExplain',
                  					display : '����˵��',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '��ע',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=outsourcing_workorder_NULL&action=pageItemJson',
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