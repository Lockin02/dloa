var show_page = function(page) {	   $("#signcompanyGrid").yxgrid("reload");};
$(function() {			$("#signcompanyGrid").yxgrid({				      model : 'contract_signcompany_signcompany',
               	title : 'ǩԼ��˾',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'signCompanyName',
                  					display : 'ǩԼ��˾����',
                  					sortable : true
                              },{
                    					name : 'proName',
                  					display : '��˾ʡ��',
                  					sortable : true
                              },{
                    					name : 'proCode',
                  					display : '��˾ʡ�ݱ���',
                  					sortable : true
                              },{
                    					name : 'linkman',
                  					display : '��ϵ��',
                  					sortable : true
                              },{
                    					name : 'phone',
                  					display : '��ϵ�绰',
                  					sortable : true
                              },{
                    					name : 'address',
                  					display : '��ϵ��ַ',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '������Id',
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
                  					display : '�޸���Id',
                  					sortable : true
                              },{
                    					name : 'updateName',
                  					display : '�޸�������',
                  					sortable : true
                              },{
                    					name : 'updateTime',
                  					display : '�޸�ʱ��',
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