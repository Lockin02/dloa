/**
 * ����������������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_outstock', {
				options : {
					hiddenId : 'id',
					nameCol : 'applyCode',
					gridOptions : {
					model : 'stock_outstock_outapply',
					colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'applyCode',
                  					display : '�������뵥��',
                  					sortable : true
                              },{
                    					name : 'docType',
                  					display : '��������',
                  					hide : true
                              },{
                    					name : 'relDocName',
                  					display : '������������',
                  					sortable : true
                              },{
                    					name : 'relDocId',
                  					display : '��������ID',
                  					hide : true
                              },{
                    					name : 'stockName',
                  					display : '���ϲֿ�',
                  					sortable : true
                              },{
                    					name : 'chargeName',
                  					display : '����������',
                  					sortable : true
                              },{
                    					name : 'sendName',
                  					display : '������',
                  					hide : true
                              }],
						// ��������
						searchitems : [{
									display : '�������뵥��',
									name : 'applyCode'
								}],
						// Ĭ�������ֶ���
						sortname : "applyCode",
						// Ĭ������˳��
						sortorder : "DESC"
					}
				}
			});
})(jQuery);