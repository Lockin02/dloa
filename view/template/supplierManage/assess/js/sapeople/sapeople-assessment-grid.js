// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".assessGrid").yxgrid("reload");
};
$(function() {
	var assId = $("#assId").val();
	$(".assessGrid").yxgrid({
		url : '?model=supplierManage_assess_sapeople&action=assessment&pj=1&assesId='+$("#assId").val(),
		model : 'supplierManage_assess_sapeople',
		action : 'assessment',
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		// ����Ϣ
		colModel : [{
					display : '��Ӧ��Id',
					name : 'ssuppId',
					sortable : true,
					hide : true
				}, {
					display : '��Ӧ�̶���Id',
					name : 'suppPjId',
					sortable : true,
					hide : true
				}, {
					display : '����Id',
					name : 'assesId',
					sortable : true,
					hide : true
				}, {
					display : '��Ӧ������',
					name : 'ssuppName',
					sortable : true
				}, {
					display : '��ǰ����',
					name : 'ssuppLevelC',
					sortable : true,
					width : 200
				}],
		// ��չ��ť
		buttonsEx : [{
					name : 'saaStart',
					text : "����",
					icon : 'edit',
					action : function(row,rows,grid) {
						if( row ){
								var url="?model=supplierManage_assess_sapeople&action=assessmentToWork&suppId="+row.suppPjId+"&assesId="+row.assesId;
								showThickboxWin(url + "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
						}else{
							alert("��ѡ��һ������");
						}
					}
				}, {
					separator : true
				}],
		menusEx : [{
					text : '����',
					icon : 'edit',
					action : function(row,rows,grid) {
						if( row ){
								var url="?model=supplierManage_assess_sapeople&action=assessmentToWork&suppId="+row.suppPjId+"&assesId="+row.assesId;
								showThickboxWin(url + "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
						}else{
							alert("��ѡ��һ������");
						}
					}
				}],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		boName : '��������-��Ӧ��',
		// Ĭ�������ֶ���
		sortname : "id",
		// Ĭ������˳��
		sortorder : "ASC",
		toViewConfig : {
				text : '�鿴��Ӧ��',
				toViewFn : function(p, g) {
					var rowObj = g.getSelectedRow();
					if (rowObj) {
						showThickboxWin("?model=supplierManage_formal_flibrary&action=toRead&id="
							+ rowObj.data('data').ssuppId
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=950");
					} else {
						alert('��ѡ��һ�м�¼��');
					}
				}
			}
	});
});