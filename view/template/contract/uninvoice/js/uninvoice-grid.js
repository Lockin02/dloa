var show_page = function(page) {	   $("#uninvoiceGrid").yxgrid("reload");};
$(function() {			$("#uninvoiceGrid").yxgrid({				      model : 'contract_uninvoice_uninvoice',
               	title : '��ͬ����Ʊ���',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'objCode',
                  					display : 'Դ�����',
                  					sortable : true
                              },{
                    					name : 'objType',
                  					display : 'Դ������',
                  					sortable : true
                              },{
                    					name : 'isRed',
                  					display : '�Ƿ����',
                  					sortable : true
                              },{
                    					name : 'money',
                  					display : '���',
                  					sortable : true
                              },{
                    					name : 'descript',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '¼����',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '¼����id',
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '¼��ʱ��',
                  					sortable : true
                              }],	   	
      
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : {
					display : "�����ֶ�",
					name : 'XXX'
				}
 		});
 });