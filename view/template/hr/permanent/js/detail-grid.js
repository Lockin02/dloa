var show_page = function(page) {	   $("#detailGrid").yxgrid("reload");};
$(function() {			$("#detailGrid").yxgrid({				      model : 'hr_permanent_detail',
               	title : 'Ա��ת��������ϸ��',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'parentCode',
                  					display : '������Code',
                  					sortable : true
                              }                    ,{
                    					name : 'standardType',
                  					display : '������Ŀ����',
                  					sortable : true
                              },{
                    					name : 'selfScore',
                  					display : '������ֵ',
                  					sortable : true
                              },{
                    					name : 'otherScore',
                  					display : '������ֵ',
                  					sortable : true
                              },{
                    					name : 'standardContent',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'standardPoint',
                  					display : '����Ҫ��',
                  					sortable : true
                              },{
                    					name : 'comment',
                  					display : '����˵��',
                  					sortable : true
                              },{
                    					name : 'status',
                  					display : '״̬',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=hr_permanent_NULL&action=pageItemJson',
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