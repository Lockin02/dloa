// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".assessGrid").yxgrid("reload");
};
$(function() {
	var assId = $("#assId").val();
	$(".assessGrid").yxgrid({
		model : 'supplierManage_assess_assessment',
		action : 'saaPJManage',
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
					sortable : true
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
		sortname : "updateTime",
		// Ĭ������˳��
		sortorder : "DESC",
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