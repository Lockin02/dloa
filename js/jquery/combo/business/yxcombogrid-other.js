/**
 * �������������������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_other', {
		options : {
			hiddenId : 'id',
			nameCol : 'orderCode',
			gridOptions : {
				showcheckbox : false,
				model : 'contract_other_other',
				action : 'pageJson',
				pageSize : 10,
				// ����Ϣ
				colModel : [{
						display : 'id',
						name : 'id',
						sortable : true,
						hide : true
					}, {
						name : 'fundType',
						display : '��������',
						sortable : true,
						datacode : 'KXXZ'
					}, {
						name : 'orderCode',
						display : '������ͬ��',
						sortable : true,
						width : 120
					}, {
						name : 'orderTempCode',
						display : '��ʱ��ͬ��',
						sortable : true,
						hide : true,
						width : 110
					}, {
						name : 'objCode',
						display : 'ҵ����',
						sortable : true,
						hide : true
					}, {
						name : 'orderName',
						display : '��ͬ����',
						sortable : true,
						width : 120
					}, {
						name : 'signCompanyName',
						display : 'ǩԼ��˾',
						sortable : true
					}, {
						name : 'proName',
						display : '��˾ʡ��',
						sortable : true
					}, {
						name : 'address',
						display : '��ϵ��ַ',
						sortable : true,
						hide : true
					}, {
						name : 'phone',
						display : '��ϵ�绰',
						sortable : true
					}, {
						name : 'linkman',
						display : '��ϵ��',
						sortable : true
					}, {
						name : 'signDate',
						display : 'ǩԼ����',
						sortable : true
					}, {
						name : 'orderMoney',
						display : '��ͬ�ܽ��',
						sortable : true,
						process : function(v) {
							return moneyFormat2(v);
						}
					}, {
						name : 'principalName',
						display : '��ͬ������',
						sortable : true
					}, {
						name : 'deptName',
						display : '��������',
						sortable : true,
						hide : true
					}, {
						name : 'fundCondition',
						display : '������������',
						sortable : true,
						hide : true
					}, {
						name : 'description',
						display : '��ͬ��������',
						sortable : true,
						hide : true
					}, {
						name : 'ExaStatus',
						display : '����״̬',
						sortable : true
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