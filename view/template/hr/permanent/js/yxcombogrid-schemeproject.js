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
						model : 'hr_permanent_standard',
						//����Ϣ
						colModel : [{
		 								display : 'id',
		 								name : 'id',
		 								sortable : true,
		 								hide : true
							        },{
		            					name : 'standardCode',
		              					display : '������Ŀ����',
		              					hide : true
		                          },{
		            					name : 'standard',
		              					display : '������Ŀ',
		              					width:130,
		              					sortable : true
		                          },{
		            					name : 'standardType',
		              					display : '������Ŀ����',
		              					width:130,
		              					sortable : true
		                          },{
		                				name : 'Content',
		              					display : '��ע'
		                          }],
						// ��������
						searchitems : [{
									display : '������Ŀ',
									name : 'standard'
								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);