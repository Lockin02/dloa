// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".suppassessGrid").yxgrid("reload");
};
$(function() {
	var assId = $("#assId").val();
	$(".suppassessGrid").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		url : '?model=supplierManage_assess_suppassess&action=sasPageJson&assId='+assId,
		model : 'supplierManage_assess_suppassess',
		isEditAction : false,
		showcheckbox : true,
		isViewAction:false,
		// action : 'pageJson',
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : 'suppId',
					name : '��Ӧ��Id',
					sortable : true,
					hide : true
				}, {
					display : '��Ӧ������',
					name : 'suppName',
					sortable : true,
					width : 500,
					// ���⴦���ֶκ���
					process : function(v, row) {
						return row.suppName;
					}
				}, {
					display : '���',
					name : 'lshortName',
					sortable : true
				}, {
					display : '������',
					name : 'lmanageUserName',
					sortable : true
				}, {
					display : '��˾����',
					name : 'lcompanyNature',
					sortable : true
				}, {
					display : '��˾��ģ',
					name : 'lcompanySize',
					sortable : true
				}],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		boName : '��Ӧ��',
		// Ĭ�������ֶ���
		sortname : "id",
		// Ĭ������˳��
		sortorder : "ASC",
		// �����չ��Ϣ
		toAddConfig : {
			text : '��ӹ�Ӧ��',
			/**
			 * ���������õĺ�̨����
			 */
			action : 'sasToAdd',
			/**
			 * ��������
			 */
			plusUrl : '&assId='+assId
			/**
			 * ������Ĭ�Ͽ��
			 */
			//formWidth : 800,
			/**
			 * ������Ĭ�ϸ߶�
			 */
			//formHeight : 600

		},
		menusEx : [{
					text : '�鿴��Ӧ��',
					icon : 'view',
					action : function(row,rows,grid) {
						if(row){
							showThickboxWin("?model=supplierManage_formal_flibrary&action=toRead&id="
								+ row.suppId+"&skey="+row['skey_']
								+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=1000");
						}else{
						   alert("��ѡ��һ������");
						}
					}

				}]
	});
});