var show_page = function(page) {	   $("#requirebackGrid").yxgrid("reload");};
$(function() {			$("#requirebackGrid").yxgrid({				      model : 'asset_require_requireback',
               	title : 'oa_asset_requireback',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'requireId',
                  					display : 'requireId',
                  					sortable : true
                              },{
                    					name : 'backReason',
                  					display : 'backReason',
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