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
		isToolBar : false,
		isRightMenu : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox:false,
		//action : 'pageJson',
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
		// title : '�ͻ���Ϣ',
		// ҵ���������
		boName : '����ָ��',
		// Ĭ�������ֶ���
		sortname : "id",
		// Ĭ������˳��
		sortorder : "ASC"
	});
});