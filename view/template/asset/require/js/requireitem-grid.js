var show_page = function(page) {	   $("#requireitemGrid").yxgrid("reload");};
$(function() {			$("#requireitemGrid").yxgrid({				      model : 'asset_require_requireitem',
               	title : '�ʲ�����������ϸ',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'requireId',
                  					display : '�ʲ�����ID',
                  					sortable : true
                              },{
                    					name : 'description',
                  					display : '�豸����',
                  					sortable : true
                              },{
                    					name : 'number',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'executedNum',
                  					display : '�ѷ�������',
                  					sortable : true
                              },{
                    					name : 'dateHope',
                  					display : '������������',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '��ע',
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
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=asset_require_NULL&action=pageJson',
			param : {
						paramId : 'mainId',
						colId : 'id'
					},
			colModel : {
						name : 'XXX',
						display : '�ӱ��ֶ�'
					}
		},
      
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