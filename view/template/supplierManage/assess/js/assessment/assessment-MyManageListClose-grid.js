// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".assessGrid").yxgrid("reload");
};
$(function() {
	var assId = $("#assId").val();
	$(".assessGrid").yxgrid({
		model : 'supplierManage_assess_assessment',
		action : 'saaPJMyManageClose',
		//isViewAction : false,
		title:'�Ҹ������������',
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		isToolBar : false,
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '������������',
					name : 'assesName',
					sortable : true,
					width : 200
				}, {
					display : '������',
					name : 'createName',
					sortable : true,
					width : 200
				}, {
					display : '��������',
					name : 'assesTypeName',
					sortable : true,
					width : 150
				}, {
					display : '����Ԥ�ƿ�ʼ����',
					name : 'beginDate',
					sortable : true,
					width : 150
				}, {
					display : '����Ԥ�ƽ�������',
					name : 'endDate',
					sortable : true,
					width : 150
				}, {
					display : '��������ʱ��',
					name : 'createTime',
					sortable : true,
					width : 170
				}, {
					display : '״̬',
					name : 'statusC',
					sortable : true,
					width : 120
				}],
		// ��������
		searchitems : [{
					display : '������������',
					name : 'assesName',
					isdefault : true
				}, {
					display : '������',
					name : 'createName'
				}],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		boName : '��������',
		// Ĭ�������ֶ���
		sortname : "id",
		// Ĭ������˳��
		sortorder : "ASC",
		toViewConfig : {
				text : '�鿴����',
				toViewFn : function(p, g) {
					var rowObj = g.getSelectedRow();
					if( rowObj ){
						showOpenWin("?model=supplierManage_assess_assessment&action=saaViewTab&assId="+rowObj.data('data').id );
					}else{
						alert("������ѡ��һ������");
					}
				}
		}
	});
});