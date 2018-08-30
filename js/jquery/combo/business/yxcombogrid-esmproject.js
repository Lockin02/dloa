/**
 * �����ͻ�������
 */
(function ($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_esmproject', {
		isDown: true,
		setValue: function (rowData) {
			if (rowData) {
				var t = this, p = t.options, el = t.el;
				p.rowData = rowData;
				if (p.gridOptions.showcheckbox) {
					if (p.hiddenId) {
						p.idStr = rowData.idArr;
						$("#" + p.hiddenId).val(p.idStr);
						p.nameStr = rowData.text;
						$(el).val(p.nameStr);
						$(el).attr('title', p.nameStr);
					}
				} else if (!p.gridOptions.showcheckbox) {
					if (p.hiddenId) {
						p.idStr = rowData[p.valueCol];
						$("#" + p.hiddenId).val(p.idStr);
						p.nameStr = rowData[p.nameCol];
						$(el).val(p.nameStr);
						$(el).attr('title', p.nameStr);
					}
				}
			}
		},
		options: {
			hiddenId: 'projectId',
			nameCol: 'projectCode',
			searchName: 'projectSearch',
			openPageOptions: {
				url: '?model=engineering_project_esmproject&action=pageSelect',
				width: '750'
			},
			gridOptions: {
				showcheckbox: false,
				title: "��Ŀ��Ϣ",
				isTitle: true,
				model: 'engineering_project_esmproject',
				action: 'jsonSelect',
				// ����Ϣ
				colModel: [{
					display: '��Ŀ����',
					name: 'projectName',
					width: 140
				}, {
					display: '��Ŀ���',
					name: 'projectCode',
					width: 130
				}, {
					display: '��������',
					name: 'officeName',
					width: 70
				}, {
					display: '��Ŀ����',
					name: 'managerName',
					width: 80
				}, {
					display: '����ID',
					name: 'deptId',
					hide: true
				}, {
					display: '��������',
					name: 'deptName',
					width: 80,
					hide: true
				}, {
					display: 'Ԥ�ƽ�������',
					name: 'planDateClose',
					hide: true,
					width: 80
				}, {
					display: '��Ŀ״̬',
					name: 'statusName',
					width: 80
				}],
				// ��������
				searchitems: [{
					display: '����',
					name: 'officeName'
				}, {
					display: '��Ŀ���',
					name: 'projectCodeSearch'
				}, {
					display: '��Ŀ����',
					name: 'projectName'
				}, {
					display: '��Ŀ����',
					name: 'managerName'
				}],
				// Ĭ�������ֶ���
				sortname: "id",
				// Ĭ������˳��
				sortorder: "DESC"
			}
		}
	});
})(jQuery);