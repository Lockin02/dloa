
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_milestone', {
		options : {
			hiddenId : 'id',
			nameCol : 'milestoneName',
			gridOptions : {
				showcheckbox : false,
				model : 'engineering_milestone_esmmilestone',
				// ����Ϣ
				colModel : [{
						display : '��̱�����',
						name : 'milestoneName'
					}, {
						display : '�ƻ���ʼ����',
						name : 'planBeginDate'
					}, {
						display : '�ƻ��������',
						name : 'planEndDate'
					}, {
						display : '�Ƿ�ʹ��',
						name : 'isUsing'
					}
				],
				// ��������
				searchitems : [{
						display : '��̱�����',
						name : 'milestoneName'
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