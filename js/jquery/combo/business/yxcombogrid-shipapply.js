/**
 * ����������������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_shipapply', {
		options : {
			hiddenId : 'id',
			nameCol : 'contractNo',
			gridOptions : {
				model : 'stock_shipapply_shipapply',
				// ����Ϣ
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'shipApplyNo',
					display : '�������뵥��',
					sortable : true
				}, {
					name : 'shipApplyDate',
					display : '��������',
					sortable : true
				}, {
					name : 'shipType',
					display : '��������',
					sortable : true
				}, {
					name : 'contractId',
					display : '��ͬid',
					sortable : true,
					hide : true
				}, {
					name : 'contractNo',
					display : 'ϵͳ��ͬ��',
					sortable : true
				}, {
					name : 'contractName',
					display : '��ͬ����',
					sortable : true
				}, {
					name : 'contractUnitId',
					display : '��ͬ��λid',
					sortable : true,
					hide : true
				}, {
					name : 'contractUnitName',
					display : '��ͬ��λ',
					sortable : true,
					hide : true
				}, {
					name : 'customerName',
					display : '�ջ���λ����',
					sortable : true,
					hide : true
				}, {
					name : 'customerId',
					display : '�ջ���λId',
					sortable : true,
					hide : true
				}, {
					name : 'address',
					display : '�ջ���ַ',
					sortable : true,
					hide : true
				}, {
					name : 'reachDate',
					display : '������������',
					sortable : true,
					hide : true
				}, {
					name : 'isMail',
					display : '�Ƿ��ʼ�',
					sortable : true,
					hide : true
				}, {
					name : 'stockId',
					display : '�ֿ�Id',
					sortable : true,
					hide : true
				}, {
					name : 'stockName',
					display : '�ֿ�����',
					sortable : true
				}, {
					name : 'confimManId',
					display : 'ȷ����id',
					sortable : true,
					hide : true
				}, {
					name : 'confirmMan',
					display : 'ȷ��������',
					sortable : true,
					hide : true
				}, {
					name : 'confirmTime',
					display : 'ȷ��ʱ��',
					sortable : true,
					hide : true
				}, {
					name : 'confirmStatus',
					display : 'ȷ��״̬',
					sortable : true,
					hide : true
				}, {
					name : 'remark',
					display : '��ע',
					sortable : true,
					hide : true
				}, {
					name : 'createId',
					display : '������ID',
					sortable : true,
					hide : true
				}, {
					name : 'createName',
					display : '����������',
					sortable : true
				}, {
					name : 'createTime',
					display : '����ʱ��',
					sortable : true,
					hide : true
				}, {
					name : 'updateId',
					display : '�޸���ID',
					sortable : true,
					hide : true
				}, {
					name : 'updateName',
					display : '�޸�������',
					sortable : true,
					hide : true
				}, {
					name : 'updateTime',
					display : '�޸�ʱ��',
					sortable : true,
					hide : true
				}, {
					name : 'ExaStatus',
					display : '����״̬',
					sortable : true,
					hide : true
				}, {
					name : 'ExaDT',
					display : '��������',
					sortable : true,
					hide : true
				}, {
					name : 'projectSendDate',
					display : 'Ԥ�Ʒ���ʱ��',
					sortable : true,
					hide : true
				}],
				// ��������
				searchitems : [{
					display : '��������',
					name : 'shipApplyNo'
				}],
				// Ĭ�������ֶ���
				sortname : "shipApplyNo",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);