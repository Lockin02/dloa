var show_page = function(page) {	   $("#changeitemGrid").yxgrid("reload");};
$(function() {			$("#changeitemGrid").yxgrid({				      model : 'service_change_changeitem',
               	title : '�豸�����嵥',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                                        ,{
                    					name : 'productCode',
                  					display : '���ϱ��',
                  					sortable : true
                              },{
                    					name : 'productName',
                  					display : '��������',
                  					sortable : true,
                  					width : 250
                              },{
                    					name : 'pattern',
                  					display : '����ͺ�',
                  					sortable : true
                              },{
                    					name : 'unitName',
                  					display : '��λ',
                  					sortable : true
                              },{
                    					name : 'serilnoName',
                  					display : '���к�',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '����ԭ��',
                  					sortable : true
                              }],

		toEditConfig : {
			toEditFn : function(p) {
				action : showThickboxWin("?model=service_change_changeitem&action=toEdit&id="
							+ row.id
							+ "&skey="
							+ row['skey_'])
			}
		},
		toViewConfig : {
			toViewFn : function(p) {
				action : showThickboxWin("?model=service_change_changeitem&action=toView&id="
							+ row.id
							+ "&skey="
							+ row['skey_'])
			}
		},
		searchitems : {
					display : "�����ֶ�",
					name : 'XXX'
				}
 		});
 });