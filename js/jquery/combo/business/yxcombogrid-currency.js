/**
 * �ұ���
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_currency', {
				options : {
					hiddenId : 'rateId',
					nameCol : 'Currency',
					width:550,
					gridOptions : {
						model : 'system_currency_currency',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'Currency',
                  					display : '�ұ�',
                  					sortable : true
                              },{
                    					name : 'currencyCode',
                  					display : '�ұ����',
                  					sortable : true
                              },{
                    					name : 'rate',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'standard',
                  					display : '��λ��',
                  					sortable : true
                              }],

						/**
						 * ��������
						 */
						searchitems : [{
									display : '�ұ�',
									name : 'Currency'
								}],
						sortorder : "ASC",
						title : '���ҹ���'
					}
				}
			});

})(jQuery);
