// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#esmmemberGrid").esmmemberGrid("reload");
};

(function($) {
	$.woo.yxgrid.subclass('woo.esmmemberGrid', {
		options : {
			sortname : "id",
			isViewAction : false,
			isEditAction : false,
			// Ĭ������˳��
			model : 'engineering_team_esmmember',
			action : 'pageJson&pjId=' + pjId,
			sortorder : "ASC",
			colModel : [{
						display : 'id',
						name : 'id',
						sortable : true,
						hide : true
					}, {
						display : '��Ա����',
						name : 'memberName',
						sortable : true,
						width : 180
					}, {
						display : '��ɫ',
						name : 'projectCode',
						sortable : true
					}, {
						display : '����',
						name : 'officeName',
						sortable : true
					}, {
						display : '��Ŀ�ܹ�ͼ',
						name : 'managerName',
						sortable : true
					}],// ��������
			searchitems : [{
						display : '��Ա����',
						name : 'searchmemberName'
					}],
			toAddConfig : {
				text : '����',
				/**
				 * Ĭ�ϵ��������ť�����¼�
				 */
				toAddFn : function(p) {
					showThickboxWin("?model="
							+ p.model
							+ "&action=toAdd"
							+ '&pjId=' + pjId
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ 200 + "&width=" + 600);
				}
			}
		}
	});
})(jQuery);