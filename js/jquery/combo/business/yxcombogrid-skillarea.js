/**
 * ���������I�������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_skillarea', {
				options : {
					hiddenId : 'id',
					nameCol : 'skillarea',
					width : 450,
					gridOptions : {
						showcheckbox : false,
						isFocusoutCheck : false,
						model : 'outsourcing_basic_skillArea',
						//����Ϣ
						colModel : [{
		 								display : 'id',
		 								name : 'id',
		 								sortable : true,
		 								hide : true
							        },{
		            					name : 'skillarea',
			          					display : '��������',
			          					width:300,
			          					sortable : true
		                          }],
						// ��������
						searchitems : [{
									display : '��������',
									name : 'skillarea'
								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);