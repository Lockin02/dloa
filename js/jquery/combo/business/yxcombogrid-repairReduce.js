/**
 * ά�����뵥����combogrid
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_reduce', {
				options : {
					hiddenId : 'id',
					nameCol : 'docCode',
					gridOptions : {
						model : 'service_repair_repairapply',

						// ��
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : 'ά�����뵥���',
									name : 'docCode',
									sortable : true,
									width : 150
								}, {
									display : '�ͻ�����',
									name : 'customerName',
									sortable : true,

									width : 150
								}, {
									display : '�ͻ���ַ',
									name : 'adress',
									sortable : true,
									width : 150
								}, {
									display : '����������',
									name : 'applyUserName',
									sortable : true,
									width : 150
								}, {
									display : 'ά�޷���',
									name : 'subCost',
									sortable : true,
									width : 150
								}],

						/**
						 * ��������
						 */
						searchitems : [{
									display : 'ά�����뵥���',
									name : 'docCode'
								}],
						pageSize : 10,
						sortorder : "desc",
						title : '���пͻ���ϵ��'
					}
				}
			});
})(jQuery);