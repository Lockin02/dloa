/**
 * ������������������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_scheme', {
				options : {
					hiddenId : 'id',
					nameCol : 'schemeName',
					gridOptions : {
						showcheckbox : true,
						model : 'supplierManage_scheme_scheme',
						//����Ϣ
						colModel : [{
		 								display : 'id',
		 								name : 'id',
		 								sortable : true,
		 								hide : true
							        },{
		            					name : 'schemeTypeName',
			          					display : '��������',
			          					sortable : true
		                          },{
		            					name : 'schemeName',
		              					display : '������������',
		              					width:130,
		              					sortable : true
		                          },{
		            					name : 'schemeCode',
		              					display : '��������',
		              					sortable : true,
		              					hide:true
		                          }],
						// ��������
						searchitems : [{
									display : '������������',
									name : 'schemeName'
								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "DESC"
					}
				}
			});
})(jQuery);