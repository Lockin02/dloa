// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".assessGrid").yxgrid("reload");
};
$(function() {
	var assId = $("#assId").val();
	$(".assessGrid").yxgrid({
		url : '?model=supplierManage_assess_sapeople&action=sapMyAssList&pj=1',
		model : 'supplierManage_assess_sapeople',
		action : 'sapMyAssList',
		title :'�Ҳ������������',
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		isToolBar : false,
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
					display : '��������Id',
					name : 'aid',
					sortable : true,
					hide : true
				}, {
					display : '��ԱId',
					name : 'id',
					sortable : true,
					hide : true
				},{
					display : '������������',
					name : 'aassesName',
					sortable : true,
					width : 200
				}, {
					display : '������',
					name : 'acreateName',
					sortable : true,
					width : 200
				}, {
					display : '��������',
					name : 'aassesTypeName',
					sortname : 'a.assesType',
					sortable : true,
					width : 150
				}, {
					display : '����Ԥ�ƿ�ʼ����',
					name : 'abeginDate',
					sortable : true,
					width : 150
				}, {
					display : '����Ԥ�ƽ�������',
					name : 'aendDate',
					sortable : true,
					width : 150
				}, {
					display : '��������ʱ��',
					name : 'acreateTime',
					sortable : true,
					width : 170
				}, {
					display : '״̬',
					name : 'astatusC',
					sortname : 'c.status',
					sortable : true,
					width : 120
				}],
		// ��չ��ť
		buttonsEx : [{
					name : 'saaStart',
					text : "����",
					icon : 'edit',
					action : function(row,rows,grid) {
						if( row ){
								parent.location="?model=supplierManage_assess_sapeople&action=assessment&assId="+row.aid ;
						}else{
							alert("��ѡ��һ������");
						}
					}
				}, {
					separator : true
				},{
					name : 'close',
					text : "�ύ",
					icon : 'edit',
					action : function(row,rows,grid) {
						//$.showDump(row);
						if(row ){
							if( confirm("ȷ���ύ����������"+ row.aassesName +"����") ){
								location="?model=supplierManage_assess_assessment&action=submit&assId="+row.aid;
							}
						}else{
							alert("��ѡ��һ������");
						}
					}
				}],
		menusEx : [{
					name : 'saaStart',
					text : "����",
					icon : 'edit',
					action : function(row,rows,grid) {
						if( row ){
								parent.location="?model=supplierManage_assess_sapeople&action=assessment&assId="+row.aid ;
						}else{
							alert("��ѡ��һ������");
						}
					}
				}, {
					name : 'close',
					text : "�ύ",
					icon : 'edit',
					action : function(row,rows,grid) {
						//$.showDump(row);
						if(row ){
							if( confirm("ȷ���ύ����������"+ row.aassesName +"����") ){
								location="?model=supplierManage_assess_assessment&action=submit&assId="+row.aid;
							}
						}else{
							alert("��ѡ��һ������");
						}
					}
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
						showOpenWin("?model=supplierManage_assess_assessment&action=saaViewTab&assId="+rowObj.data('data').aid );
					}else{
						alert("������ѡ��һ������");
					}
				}
		}
	});
});