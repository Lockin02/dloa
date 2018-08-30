/**
 * �����з���Ŀ������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_rdmember', {
		options : {
			hiddenId : 'memberId',
			nameCol : 'memberName',
			valueCol : 'memberId',
			gridOptions : {
				showcheckbox : true,
				model : 'rdproject_team_rdmember',
				action : 'pageJsonProject',
				// ����Ϣ
				colModel : [{
							display : 'id',
							name : 'id',
							hide : true
						}, {
							display : '��Ŀ����',
							name : 'projectName',
							width : 180
						}, {
							display : '��Ŀ���',
							name : 'projectCode',
							width : 130,
							hide : true
						}, {
							display : '��Ա����',
							name : 'memberName'
						}, {
							display : '��Ա����id',
							name : 'memberId',
							hide : true
						}, {
							display : '������Ϣ',
							name : 'description',
							width : 150
						}],
				// ��������
				searchitems : [{
							display : '��Ŀ����',
							name : 'projectName'
						}],
				// Ĭ�������ֶ���
				sortname : "memberName",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);