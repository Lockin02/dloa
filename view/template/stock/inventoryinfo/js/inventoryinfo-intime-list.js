/*******************************************************************************
 * ��ʱ����ѯ,�ṩһ�����õĿ���ѯ��������ѯ������Ϣ 2011-02-16 by can
 */
var show_page = function(page) {
	$(".stockcheckGrid").yxgrid("reload");
};
function viewProDetail(productId) {
	showThickboxWin("?model=stock_productinfo_productinfo&action=view&id="
			+ productId
			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");

}

$(function() {
	$("#stockTree").yxtree({
				showLine : false,
				url : '?model=stock_stockinfo_stockinfo&action=getTreeStock',
				event : {
					"node_click" : function(event, treeId, treeNode) {
						var stockcheckGrid = $(".stockcheckGrid")
								.data('yxgrid');
						$("#stockId").val(treeNode.id);
						stockcheckGrid.options.extParam['stockId'] = treeNode.id;
						stockcheckGrid.option("newp",1);//�ָ���һҳ
						stockcheckGrid.reload();
					},
					"node_success" : function() {
						$("#stockTree").yxtree("expandAll");
					}
				}
			});

	$(".stockcheckGrid").yxgrid({
		model : 'stock_inventoryinfo_inventoryinfo',
		action : 'pageInTimeJson&stockId=' + $("#stockId").val()
				+ "&productId=" + $("#productId").val(),
		title : "��ʱ����ѯ",
		// �Ƿ���ʾ������
		isToolBar : false,
		// �Ƿ���ʾ�鿴��ť/�˵�
		isViewAction : false,
		// �Ƿ���ʾ�޸İ�ť/�˵�
		isEditAction : false,
		// �Ƿ���ʾ��Ӱ�ť/�˵�
		isAddAction : false,
		// �Ƿ���ʾɾ����ť/�˵�
		isDelAction : false,
		isRightMenu : false,
		// �Ƿ���ʾ��ѡ��
		showcheckbox : false,
		isPrintAction : true,

		// menusEx : [{
		// name : 'view',
		// text : "�鿴",
		// icon : 'view',
		// action : function(row, rows, grid) {
		// showOpenWin("?model=stock_inventoryinfo_inventoryinfo&action=viewInfo&id="
		// + row.id
		// + "&typecode="
		// + row.typecode
		// +
		// "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
		// }
		// }],

		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '�ֿ�����',
					name : 'stockName',
					width : 100,
					sortable : true
				}, {
					display : '��������',
					name : 'proType',
					sortable : true,
					align : 'center'
				}, {
					display : '���ϱ��',
					name : 'productCode',
					sortable : true,
					align : 'center',
					process : function(v, row) {
						return "<a href='#' onclick='viewProDetail("
								+ row.productId
								+ ")' >"
								+ v
								+ " <img title='������ϸ' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
					}
				}, {
					display : '��������',
					name : 'productName',
					width : 170,
					sortable : true

				}, {
					display : 'k3����',
					name : 'k3Code',
					width : 170,
					sortable : true

				}, {
					name : 'actNum',
					display : '��ʱ���',
					width : 70,
					sortable : true
				}, {
					name : 'exeNum',
					display : '��ִ�п��',
					width : 70,
					sortable : true

				}, {
					name : 'assigedNum',
					display : '�ѷ�����',
					width : 70,
					sortable : true,
					hide : true
				}, {
					name : 'lockedNum',
					display : '������',
					width : 70,
					process : function(v, row) {
						// row.productId,row.stockId
						return "<a href='?model=stock_lock_lock&action=readLockNum&productId="
								+ row.productId
								+ "&stockId="
								+ row.stockId
								+ "'><b>" + v + "</></a>";
					},
					sortable : true
				}, {
					name : 'auditNum',
					display : '��������',
					width : 70,
					sortable : true
				}, {
					name : 'planInstockNum',
					display : '��;����',
					width : 70,
					sortable : true
				}, {
					name : 'initialNum',
					display : '�ڳ����',
					width : 70,
					sortable : true
				}, {
					name : 'safeNum',
					display : '��ȫ���',
					width : 50,
					sortable : true
				}, {
					name : 'docStatus',
					display : '���״̬',
					sortable : true,
					hide : true
				}],
		// ��������
		searchitems : [{
					display : '��������',
					name : 'productName'
				}, {
					display : '���ϱ��',
					name : 'productCode'

				}, {
					display : 'k3����',
					name : 'k3Code'
				}],
		// Ĭ������˳��
		sortorder : "ASC",
		// ��չ��ť
		buttonsEx : [{
			name : 'excel',
			text : '����EXCEL',
			icon : 'excel',
			action : function(rowData, rows, rowIds, grid) {
				var searchCondition = "";
				for (var t in grid.options.searchParam) {
					if (t != "") {
						searchCondition += "&" + t + "="
								+ grid.options.searchParam[t];

					}
				}
				window.open(
						"?model=stock_inventoryinfo_inventoryinfo&action=exportExcelInTime&stockId="
								+ $("#stockId").val() + "&productId="
								+ $("#productId").val() + searchCondition, "",
						"width=200,height=200,top=200,left=200");
			}
		}]

	});
});