var show_page = function(page) {	   $("#bomitemGrid").yxgrid("reload");};
$(function() {			$("#bomitemGrid").yxgrid({				      model : 'produce_bom_bomitem',
               	title : 'BOM��¼��',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                                        ,{
                    					name : 'productCode',
                  					display : '���ϱ���',
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
                    					name : 'properties',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'useNum',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'useStatus',
                  					display : 'ʹ��״̬',
                  					sortable : true
                              },{
                    					name : 'planPercent',
                  					display : '�ƻ��ٷֱ�',
                  					sortable : true
                              },{
                    					name : 'lossRate',
                  					display : '�����',
                  					sortable : true
                              },{
                    					name : 'effectiveDate',
                  					display : '��Ч����',
                  					sortable : true
                              },{
                    					name : 'expirationDate',
                  					display : 'ʧЧ����',
                  					sortable : true
                              },{
                    					name : 'isAllow',
                  					display : '�Ƿ����',
                  					sortable : true
                              },{
                    					name : 'productType',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'configPro',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'isCharacter',
                  					display : '�Ƿ�������',
                  					sortable : true
                              },{
                    					name : 'isKeyObj',
                  					display : '�ؼ���',
                  					sortable : true
                              },{
                    					name : 'stockCode',
                  					display : '���ϲֿ����',
                  					sortable : true
                              },{
                    					name : 'stockName',
                  					display : '���ϲֿ�����',
                  					sortable : true
                              }                    ,{
                    					name : 'remark',
                  					display : '��ע',
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