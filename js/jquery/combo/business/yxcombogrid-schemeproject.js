/**
 * ����������Ŀ������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_schemeproject', {
				options : {
					hiddenId : 'id',
					nameCol : 'assesProName',
					gridOptions : {
						showcheckbox : true,
						model : 'supplierManage_scheme_schemeproject',
						//����Ϣ
						colModel : [{
		 								display : 'id',
		 								name : 'id',
		 								sortable : true,
		 								hide : true
							        },{
		            					name : 'assesProCode',
			          					display : '������Ŀ����',
			          					sortable : true
		                          },{
		            					name : 'assesProName',
		              					display : '������Ŀ����',
		              					width:130,
		              					sortable : true
		                          },{
		            					name : 'assesProProportion',
		              					display : '������ĿȨ��',
		              					sortable : true,
		              					hide:true
		                          },{
		                					name : 'formManName',
		              					display : '������',
		              					hide : true
		                          }],
						// ��������
						searchitems : [{
									display : '������Ŀ����',
									name : 'assesProName'
								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);