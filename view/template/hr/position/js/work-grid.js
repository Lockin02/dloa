var show_page = function(page) {	   $("#workGrid").yxgrid("reload");};
$(function() {			$("#workGrid").yxgrid({				      model : 'hr_position_work',
               	title : 'ְλ����ְ��',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'parentCode',
                  					display : 'ְλ�����',
                  					sortable : true
                              },{
                    					name : 'positionName',
                  					display : 'ְλ����',
                  					sortable : true
                              },{
                    					name : 'jobContents',
                  					display : 'ְ������',
                  					sortable : true
                              },{
                    					name : 'specificContents',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'jobTarget',
                  					display : 'Ŀ��',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=hr_position_NULL&action=pageItemJson',
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