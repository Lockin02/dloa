/**
 * �����з���Ŀ������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_rdprojectfordl', {
		isDown : false,
		setValue : function(rowData) {
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
		options : {
			hiddenId : 'projectCode',
			nameCol : 'projectName',
			searchName : 'searhDProjectCode',
			openPageOptions : {
				url : '?model=rdproject_project_rdproject&action=pageForDL',
				width : '750'
			},
			closeCheck : false,// �ر�״̬,����ѡ��
			gridOptions : {
				showcheckbox : false,
				model : 'rdproject_project_rdproject',
				action : 'pageJsonForDL',
				// ����Ϣ
				colModel : [{
						display : '��Ŀ���',
						name : 'projectCode',
						width : 130
					}, {
						display : '��Ŀ����',
						name : 'projectName',
						width : 150
					}, {
						display : '��Ŀ����',
						name : 'managerName',
						width : 80
					}, {
						display : '��Ŀ����',
						name : 'description',
						width : 300
					}
				],
				// ��������
				searchitems : [{
						display : '��Ŀ����',
						name : 'searhDProjectName'
					},{
						display : '��Ŀ���',
						name : 'searhDProjectCode'
					}
				],
				// Ĭ�������ֶ���
				sortname : "number",
				// Ĭ������˳��
				sortorder : "DESC"
			}
		}
	});
})(jQuery);