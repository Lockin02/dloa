var show_page = function(page) {

	$(".subitemGrid").yxgrid("reload");
};
$(function() {
			$(".subitemGrid").yxgrid({
						model : 'stock_inventoryinfo_inventoryinfo',
						action : 'pageSubItemJson',
						title : "���״̬��Ϣ",
						isToolBar : true,
						isAddAction : false,
						isViewAction : false,
						isEditAction : false,
						isRightMenu : false,
						isDelAction : false,
						isRightMenu : false,
						// ����Ϣ
						colModel : [{
									display : '���ϱ��',
									name : 'productCode',
									sortable : true,
									align : 'center'
								}, {
									display : '��������',
									name : 'productName',
									sortable : true,
									width : 250
								}, {
									name : 'initialNum',
									display : '�ڳ����',
									sortable : true,
									width : 80

								}, {
									name : 'actNum',
									display : '��ʱ���',
									sortable : true,
									width : 80
								}, {
									name : 'exeNum',
									display : '��ִ�п��',
									sortable : true,
									width : 80
								}, {
									name : 'assigedNum',
									display : '�ѷ�����',
									sortable : true,
									width : 80
								}, {
									name : 'lockedNum',
									display : '������',
									sortable : true,
									width : 80
								}, {
									name : 'planInstockNum',
									display : 'Ԥ�������',
									sortable : true,
									width : 80
								}],
						showcheckbox : false, // ��ʾcheckbox
						// ��������
						searchitems : [{
									display : '��������',
									name : 'productName'
								}, {
									display : '���ϱ��',
									name : 'productCode'
								}],
						// Ĭ������˳��
						sortorder : "ASC"

					});
		});