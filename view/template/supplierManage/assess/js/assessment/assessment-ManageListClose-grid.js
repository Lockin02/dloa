// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".assessGrid").yxgrid("reload");
};
$(function() {
	var assId = $("#assId").val();
	$(".assessGrid").yxgrid({
		model : 'supplierManage_assess_assessment',
		action : 'saaPJManageClose',
		//isViewAction : false,
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		isViewAction:false,
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
//		toViewConfig : {
//				text : '�鿴����',
//				toViewFn : function(p, g) {
//					var rowObj = g.getSelectedRow();
//					if( row ){
//						showOpenWin("?model=supplierManage_assess_assessment&action=saaViewTab&assId="+row.id );
//					}else{
//						alert("������ѡ��һ������");
//					}
//				}
//		},
		//��չ�Ҽ��˵�
		menusEx : [{
					text : '�鿴',
					icon : 'view',
					action : function(row,rows,grid) {
						if(row){
						showOpenWin("?model=supplierManage_assess_assessment&action=saaViewTab&assId="+row.id+"&skey="+row['skey_'] );
						}else{
						   alert("��ѡ��һ������");
						}
					}

				}
		]
	});
});