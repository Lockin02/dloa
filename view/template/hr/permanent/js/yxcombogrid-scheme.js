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
						model : 'hr_permanent_scheme',
						//����Ϣ
						colModel : [{
		 								display : 'id',
		 								name : 'id',
		 								sortable : true,
		 								hide : true
							        },{
		            					name : 'schemeTypeName',
			          					display : '���˶���',
			          					sortable : true
		                          },{
		            					name : 'schemeName',
		              					display : '���˷�������',
		              					width:130,
		              					sortable : true
		                          }],
						// ��������
						searchitems : [{
									display : '��������',
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