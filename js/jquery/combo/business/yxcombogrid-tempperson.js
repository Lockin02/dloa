/**
 * ���Կ�����combogrid
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_tempperson', {
		options : {
			hiddenId : 'id',
			nameCol : 'personName',
			gridOptions : {
				model : 'engineering_tempperson_tempperson&action=listJson1',
				// ��
				colModel : [{
						display : 'id',
						name : 'id',
						sortable : true,
						hide : true
					}, {
						display : '����',
						name : 'personName',
						sortable : true,
						width : 80
					},{
			            name : 'province',
			            display : '����(ʡ)',
			            sortable : true
			        }, {
						name : 'city',
						display : '����(��)',
						sortable : true
					}, {
						name : 'phone',
						display : '�ֻ�',
						sortable : true
					}
				],
				/**
				 * ��������
				 */
				searchitems : [{
					display : "����",
					name : 'personNameSearch'
				},{
					display : "���֤",
					name : 'idCardNoSearch'
				},{
					display : "�ֻ���",
					name : 'phoneSearch'
				}],
				pageSize : 10,
				sortorder : "ASC",
				title : '��Ƹ��Ա��Ϣ'
			}
		}
	});
})(jQuery);