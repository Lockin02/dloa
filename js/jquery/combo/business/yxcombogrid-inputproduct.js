/**
 * ������������������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_inputproduct', {
				options : {
					hiddenId : 'id',
					nameCol : 'inputProductName',
					gridOptions : {
						showcheckbox : true,
						model : 'asset_purchase_apply_applyItem',
						//����Ϣ
						colModel : [{
		 								display : 'id',
		 								name : 'id',
		 								sortable : true,
		 								hide : true
							        },{
		            					name : 'productCategoryName',
			          					display : '�������',
			          					sortable : true
		                          },{
		            					name : 'inputProductName',
		              					display : '�ɹ���������',
		              					width:130,
		              					sortable : true
		                          }],
						// ��������
						searchitems : [{
									display : '�ɹ���������',
									name : 'inputProductName'
								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "DESC"
					}
				}
			});
})(jQuery);