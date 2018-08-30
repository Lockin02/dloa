(function($) {

	$.woo.yxgrid.subclass('woo.yxgrid_tracklog', {
		options : {
			model : 'system_log_tracklog',
			// ��
			colModel : [{
						display : 'id',
						name : 'id',
						sortable : true,
						hide : true
					}, {
						display : '������',
						name : 'createName'
					}, {
						display : '����ʱ��',
						name : 'createTime',
						width : 150
					}, {
						display : '��������',
						name : 'op'
					}, {
						display : '����ҵ����',
						name : 'reObjCode',
						process : function(v, data) {
							if (data.reObjType == "outplan") {
								return "<a href='javascript:window.open(\"?model=stock_outplan_outplan&action=outplandetailTab&planId="
										+ data.reObjId
										+ "&docType="
										+ data.objType
										+ "&skey="
										+ data.skey_ + "\")'>" + v + "</a>";
							}
						}

					}, {
						display : '��ע',
						name : 'remark',
						width:200
					}],
			/**
			 * ��������
			 */
			searchitems : [{
						display : '��������',
						name : 'op'
					}],
			sortorder : "DESC",
			sortname : "id",
			isAddAction : false,
			isDelAction : false,
			isEditAction : false,
			title : 'ҵ���������켣��Ϣ'
		}
	});
})(jQuery);