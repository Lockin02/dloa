/**
 * ��ϵ�˱�����
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_attrvals', {
				options : {
					hiddenId : 'id',
					// param : { 'customerId' :$('customerId').val() },
					nameCol : 'attrName',
					title : '�̵������',
					// openPageOptions : {
					// url :
					// '?model=customer_linkman_linkman&action=selectLinkman'
					// },
					gridOptions : {
						model : 'hr_inventory_attr&action=page',

						// ��
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'attrName',
									display : '��������',
									sortable : true,
									width:200
								}, {
									name : 'attrType',
									display : '��������',
									process : function(v,row) {
										return (v == 0 ? "�ı���" : "������");
									},
									sortable : true
								}, {
									name : 'remark',
									display : '��ע',
									sortable : true
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