/**
 * �������������������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_accessorder', {
				options : {
					hiddenId : 'id',
					nameCol : 'docCode',
					gridOptions : {
						showcheckbox : false,
						model : 'service_accessorder_accessorder',
						action : 'pageJson',
						pageSize : 10,
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'docCode',
									display : '���ݱ��',
									sortable : true
								}, {
									name : 'docDate',
									display : 'ǩ������',
									sortable : true
								}, {
									name : 'customerName',
									display : '�ͻ�����',
									sortable : true
								}, {
									name : 'contactUserName',
									display : '�ͻ���ϵ��',
									sortable : true,
									hide : true
								}, {
									name : 'telephone',
									display : '��ϵ�绰',
									sortable : true,
									hide : true
								}, {
									name : 'adress',
									display : '�ͻ���ַ',
									sortable : true,
									hide : true
								}, {
									name : 'chargeUserName',
									display : '����������',
									sortable : true
								}, {
									name : 'ExaStatus',
									display : '����״̬',
									sortable : true
								}, {
									name : 'ExaDT',
									display : '����ʱ��',
									sortable : true,
									hide : true
								}, {
									name : 'auditerUserName',
									display : '����������',
									sortable : true,
									hide : true
								}, {
									name : 'docStatus',
									display : '����״̬',
									sortable : true,
									process : function(v, row) {
										if (row.docStatus == 'WZX') {
											return "δִ��";
										} else {
											return "ִ����";
										}
									}
								}, {
									name : 'saleAmount',
									display : '�������',
									sortable : true,
									process : function(v, row) {
										return moneyFormat2(v);
									}
								}, {
									name : 'areaLeaderName',
									display : '������������',
									sortable : true,
									hide : true
								}, {
									name : 'areaName',
									display : '��������',
									sortable : true,
									hide : true
								}, {
									name : 'remark',
									display : '��ע',
									sortable : true,
									hide : true
								}, {
									name : 'createName',
									display : '������',
									sortable : true,
									hide : true
								}, {
									name : 'createTime',
									display : '��������',
									sortable : true,
									hide : true
								}, {
									name : 'updateName',
									display : '�޸���',
									sortable : true,
									hide : true
								}, {
									name : 'updateTime',
									display : '�޸�����',
									sortable : true,
									hide : true
								}],
						// ��������
						searchitems : [{
									display : '��������',
									name : 'areaName'
								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);