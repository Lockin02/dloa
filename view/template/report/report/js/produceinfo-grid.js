var show_page = function(page) {	   $("#produceinfoGrid").yxgrid("reload");};
$(function() {			$("#produceinfoGrid").yxgrid({				      model : 'report_report_produceinfo',
               	title : '������������ϸ',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                                                                                                    ,{
                    					name : 'proType',
                  					display : '������',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=report_report_NULL&action=pageItemJson',
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