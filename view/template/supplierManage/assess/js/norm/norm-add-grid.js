// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".normGrid").yxgrid("reload");
};
$(function() {
	var assId = $("#assId").val();
	$(".normGrid").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		url : '?model=supplierManage_assess_norm&action=sanPageJson&assId='+assId,
		model : 'supplierManage_assess_norm',
		//isViewAction : false,
		//isEditAction : false,
		 action : 'pageJson',
		 showcheckbox : true,
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '����ָ������',
					name : 'normName',
					sortable : true,
					width : 200
				}, {
					display : 'ָ�����',
					name : 'normCode',
					sortable : true,
					width : 200
				}, {
					display : 'Ȩ��',
					name : 'weight',
					sortable : true,
					width : 200
				}, {
					display : 'ָ���ܷ�',
					name : 'normTotal',
					sortable : true,
					width : 200
				}],
		boName : '����ָ��',
		sortname : "id",
		sortorder : "ASC",
		toAddConfig : {
			text : '���ָ��',
			action : 'sanToAdd',
			plusUrl : '&assId='+assId

		},
		toViewConfig : {
				text : '�鿴',
				action : 'sasRead'
		},
		toEditConfig : {
				text : '�༭',
				action : 'sasToEdit'
		},
		toDelConfig : {
				text : 'ɾ��',
				toDelFn : function(p, g) {
					var rowObj = g.getSelectedRow();
					if (rowObj) {
						if (window.confirm("ȷ��Ҫɾ��?")) {
							$.ajax({
										type : "POST",
										url : "?model=" + p.model + "&action="
												+ p.toDelConfig.action
												+ p.toDelConfig.plusUrl,
										data : {
											id : rowObj.data('data').id
													.toString()
											// ת������,������ʽ
										},
										success : function(msg) {
											if (msg == 1) {
												g.reload();
												alert('ɾ���ɹ���');
											}
										}
									});
						}
					} else {
						alert('��ѡ��һ�м�¼��');
					}
				}
			}
	});
});