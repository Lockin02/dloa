var show_page = function(page) {	   $("#qualityapplyitemGrid").yxgrid("reload");};
$(function() {			$("#qualityapplyitemGrid").yxgrid({				      model : 'produce_quality_qualityapplyitem',
               	title : '�ʼ����뵥�嵥',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                                                            ,{
                    					name : 'productCode',
                  					display : '���ϱ��',
                  					sortable : true
                              },{
                    					name : 'productName',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'pattern',
                  					display : '����ͺ�',
                  					sortable : true
                              },{
                    					name : 'unitName',
                  					display : '��λ',
                  					sortable : true
                              },{
                    					name : 'fittings',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'qualityNum',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'assignNum',
                  					display : '���´�����',
                  					sortable : true
                              },{
                    					name : 'standardNum',
                  					display : '�ϸ�����',
                  					sortable : true
                              },{
                    					name : 'planEndDate',
                  					display : '�������ʱ��',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '��ע',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=produce_quality_NULL&action=pageItemJson',
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