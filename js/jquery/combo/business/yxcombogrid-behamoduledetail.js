/**
 * �����ͻ�������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_behamoduledetail', {
		options : {
			hiddenId : 'id',
			nameCol : 'detailName',
			gridOptions : {
				showcheckbox : false,
				model : 'hr_baseinfo_behamoduledetail',
				// ����Ϣ
				colModel : [{
						display : '��Ϊģ��id',
						name : 'moduleId',
						hide : true
					}, {
						display : '��Ϊģ��',
						name : 'moduleName',
						width : 120
					}, {
						display : 'id',
						name : 'id',
						hide : true
					}, {
						display : '��ΪҪ��',
						name : 'detailName',
						width : 120
					}, {
						display : '��ע',
						name : 'remark',
						width : 200
					}
				],
				// ��������
				searchitems : [{
						display : '��ΪҪ��',
						name : 'detailNameSearch'
					},{
						display : '��Ϊģ��',
						name : 'moduleNameSearch'
					}
				],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);