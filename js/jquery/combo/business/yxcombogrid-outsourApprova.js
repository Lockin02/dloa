/*
 * ��������������
 */
(function($){
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_outsourApprova', {
		options : {
			hiddenId : 'id',
			nameCol : 'formCode',
			width : 500,
			gridOptions : {
				showcheckbox : false,
				isFocusoutCheck : false,
				model : 'outsourcing_approval_basic',
				action : 'pullPage',
				param:{ExaStatusArr:'���'},
				//����Ϣ
						colModel : [{
		 								display : 'id',
		 								name : 'id',
		 								sortable : true,
		 								hide : true
							        },{
		            					name : 'formCode',
			          					display : '���ݱ��',
			          					width:180,
			          					sortable : true
		                          }],
						// ��������
						searchitems : [{
									display : '���ݱ��',
									name : 'formCode'
								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"
			}
		}
	});
})(jQuery);