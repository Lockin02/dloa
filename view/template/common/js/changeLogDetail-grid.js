(function($) {
	// ����Ҫȡ���µı����Ϣ�������
	$.woo.yxgrid.subclass('woo.yxgrid_changeLogDetail', {
		options : {
			model : 'common_changeLog',
			action : 'pageJsonDetail',
			showcheckbox : false,
			isToolBar : false,
			/**
			 * �Ƿ���ʾ�Ҽ��˵������Ϊflase�����Ҽ��˵�ʧЧ
			 *
			 * @type Boolean
			 */
			isRightMenu : false,
			// ��
			colModel : [{
						name : 'objField',
						width : 200,
						display : '��������'
					}, {
						name : 'changeFieldCn',
						width : 150,
						display : '�������'
					}, {
						name : 'oldValue',
						width : 150,
						display : '���ǰֵ'
					}, {
						name : 'newValue',
						width : 150,
						display : '�����ֵ'
					}],
			sortorder : "DESC",
			sortname : "id",
			title : '�����Ϣ',
			buttonsEx : [{
				text : "�鿴���α��",
				icon : 'view',
				action : function(rowData, rows, rowIds, g) {
					g.options.param.isLast=true;
					g.reload();
				}
			},{
				text : "�鿴����",
				icon : 'view',
				action : function(rowData, rows, rowIds, g) {
					delete g.options.param.isLast;
					g.reload();
				}
			}]
		}
	});
})(jQuery);