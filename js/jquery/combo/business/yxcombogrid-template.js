/**
 * ��ϵ�˱�����
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_templatevals', {
				options : {
					hiddenId : 'id',
					// param : { 'customerId' :$('customerId').val() },
					nameCol : 'templateName',
					title : '�̵�ģ��',
					gridOptions : {
						model : 'hr_inventory_template&action=page',

						// ��
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'templateName',
									display : 'ģ������',
									sortable : true,
									width:250
								},{
									name : 'remark',
									display : '��ע',
									sortable : true,
									width:250
								}],

						/**
						 * ��������
						 */
						searchitems : [{
									display : "��������",
									name : 'attrName'
								}],
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);