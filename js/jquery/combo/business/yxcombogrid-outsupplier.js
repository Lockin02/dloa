/**
 * ���������I�������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_outsupplier', {
				options : {
					hiddenId : 'id',
					nameCol : 'suppName',
					width : 500,
					gridOptions : {
						showcheckbox : false,
						isFocusoutCheck : false,
						model : 'outsourcing_supplier_basicinfo',
						//����Ϣ
						colModel : [{
		 								display : 'id',
		 								name : 'id',
		 								sortable : true,
		 								hide : true
							        },{
		            					name : 'suppCode',
			          					display : '��Ӧ�̱��',
			          					width:60,
			          					sortable : true
		                          },{
		            					name : 'suppName',
			          					display : '�����Ӧ��',
			          					width:300,
			          					sortable : true
		                          }],
						// ��������
						searchitems : [{
									display : '�����Ӧ��',
									name : 'suppName'
								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);