var show_page = function(page) {
	$("#allocationitemGrid").yxgrid("reload");
};
function showSerialno(serialName) {
	showThickboxWin("index1.php?model=stock_allocation_allocation&action=toViewSerialno&serialnoName="
			+ serialName
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=400");
}
$(function() {
			$("#allocationitemGrid").yxgrid({
				model : 'stock_allocation_allocationitem',
				title : '�黹�����嵥',
				action : 'pageBackGridJson',
				param : {
					relDocIdIn : $("#relDocId").val()
				},
				isAddAction : false,
				isViewAction : false,
				isEditAction : false,
				isDelAction : false,
				showcheckbox : false,
				// ����Ϣ
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							name : 'mainId',
							display : '������id',
							sortable : true,
							hide : true
						}, {
							name : 'productId',
							display : '����id',
							sortable : true,
							hide : true
						}, {
							name : 'productCode',
							display : '���ϱ��',
							sortable : true
						}, {
							name : 'productName',
							display : '��������',
							sortable : true
						}, {
							name : 'pattern',
							display : '����ͺ�',
							sortable : true
						}, {
							name : 'unitName',
							display : '��λ',
							sortable : true
						}, {
							name : 'aidUnit',
							display : '������λ',
							sortable : true,
							hide : true
						}, {
							name : 'converRate',
							display : '������',
							sortable : true,
							hide : true
						}, {
							name : 'batchNum',
							display : '����',
							sortable : true,
							hide : true
						}, {
							name : 'cost',
							display : '��λ�ɱ�',
							sortable : true,
							process : function(v, row) {
								return moneyFormat2(v);
							}
						}, {
							name : 'subCost',
							display : '�ɱ�',
							sortable : true,
							process : function(v, row) {
								return moneyFormat2(v);
							}
						}, {
							name : 'allocatNum',
							display : '�黹����',
							sortable : true,
							process : function(v, row) {
								return '<a  href="#" onclick="showSerialno(\''
										+ row.serialnoName + '\')" >' + v
										+ '</a>';

							}
						}, {
							name : 'outEndDate',
							display : '�黹����',
							sortable : true
						}, {
							name : 'serialnoName',
							display : '���к�',
							sortable : true,
							hide : true
						}, {
							name : 'remark',
							display : '��ע',
							sortable : true
						}],
				searchitems : [{
							display : '���ϴ���',
							name : 'productCode'
						}, {
							display : '��������',
							name : 'productName'
						}]
			});
		});