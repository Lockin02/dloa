/**
 * ��Ȩ�������������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_exceptionapply', {
		options : {
			hiddenId : 'id',
			nameCol : 'formNo',
			gridOptions : {
				model : 'engineering_exceptionapply_exceptionapply',
				// ��
				colModel : [{
						display : 'id',
						name : 'id',
						sortable : true,
						hide : true
					}, {
						display : '���ݱ��',
						name : 'formNo',
						sortable : true,
						width : 80
					}, {
						display : '��������',
						name : 'applyTypeName',
						sortable : true,
						width : 50
					}, {
						display : '������',
						name : 'applyUserName',
						sortable : true,
						width : 80
					}, {
						display : '��������',
						name : 'applyDate',
						sortable : true,
						width : 80
					}, {
						display : '���÷�Χ',
						name : 'useRangeName',
						sortable : true,
						width : 50
					}, {
						display : '����ԭ��',
						name : 'applyReson',
						sortable : true,
						width : 200
					}, {
						display : '������Ŀ',
						name : 'projectName',
						sortable : true,
						width : 120
					}],
				/**
				 * ��������
				 */
				searchitems : [{
					display : '���뵥��',
					name : 'formNoSearch'
				},{
					display : '��������',
					name : 'applyDateSearch'
				},{
					display : '������',
					name : 'applyUserSearch'
				},{
					display : '����ԭ��',
					name : 'applyResonSearch'
				},{
					display : '������Ŀ',
					name : 'projectNameSearch'
				}],
				pageSize : 10,
				sortorder : "ASC",
				sortname : "id",
				title : '��Ȩ������'
			}
		}
	});
})(jQuery);