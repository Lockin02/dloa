var show_page = function(page) {	   $("#salarytplateitemGrid").yxgrid("reload");};
$(function() {			$("#salarytplateitemGrid").yxgrid({				      model : 'hr_leave_salarytplateitem',
               	title : '�����嵥ģ���嵥',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'salaryContent',
                  					display : '������Ŀ',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '��ע˵��',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=hr_leave_NULL&action=pageItemJson',
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