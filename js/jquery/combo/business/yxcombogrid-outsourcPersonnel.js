/*
 * �����Ա�������
 */
(function($){
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_outsourcPersonnel', {
		options : {
			hiddenId : 'id',
			nameCol : 'userName',
			width : 500,
			gridOptions : {
				showcheckbox : false,
				isFocusoutCheck : false,
				model : 'outsourcing_supplier_personnel',
				//����Ϣ
						colModel : [{
		 								display : 'id',
		 								name : 'id',
		 								sortable : true,
		 								hide : true
							        },{
		 								display : '����',
		 								name : 'userName',
		 								sortable : true
							        },{
		            					name : 'identityCard',
			          					display : '���֤����',
			          					width:180,
			          					sortable : true
		                          	},{
		 								display : '��ϵ�绰',
		 								name : 'mobile',
		 								sortable : true
							        },{
		 								display : '����',
		 								name : 'email',
		 								sortable : true
							        }],
						// ��������
						searchitems : [{
									display : '���ݱ��',
									name : 'userName'
								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"
			}
		}
	});
})(jQuery);