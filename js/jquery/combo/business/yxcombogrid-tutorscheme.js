/**
 * ��ϵ�˱�����
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_tutorscheme', {
				options : {
					hiddenId : 'id',
					nameCol : 'schemeName',
					title : '��ʦ���˷���',
					gridOptions : {
						model : 'hr_tutor_tutorScheme&action=page',

						// ��
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'schemeName',
									display : '��������',
									sortable : true
								}, {
									name : 'createName',
									display : '������',
									sortable : true,
									width : 100
								}, {
									name : 'remark',
									display : '��ע',
									sortable : true,
									width : 200
								}],

						/**
						 * ��������
						 */
						searchitems : [{
									display : "��������",
									name : 'schemeName'
								}],
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);