/**
 * �����û����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_borrow', {
				options : {
					hiddenId : 'id',
					nameCol : 'Code',
					gridOptions : {
						showcheckbox : false,
						model : 'projectmanagent_borrow_borrow',
						action : 'pageJson',
						param : {
							"ExaStatus" : "���",
							"limits" : "�ͻ�"
						},
						pageSize : 10,
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'chanceId',
									display : '�̻�Id',
									sortable : true,
									hide : true
								}, {
									name : 'Code',
									display : '���',
									sortable : true
								}, {
									name : 'Type',
									display : '����',
									sortable : true
								}, {
									name : 'customerName',
									display : '�ͻ�����',
									sortable : true
								}, {
									name : 'salesName',
									display : '���۸�����',
									sortable : true
								}, {
									name : 'scienceName',
									display : '����������',
									sortable : true
								}, {
									name : 'remark',
									display : '��ע',
									sortable : true
								}, {
									name : 'ExaStatus',
									display : '����״̬',
									sortable : true,
									width : 90,
									hide : true
								}, {
									name : 'ExaDT',
									display : '����ʱ��',
									sortable : true,
									hide : true
								}, {
									name : 'createName',
									display : '������',
									sortable : true,
									hide : true
								}],
						// ��������
						searchitems : [{
									display : '���',
									name : 'Code'
								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);