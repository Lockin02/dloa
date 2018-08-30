var show_page = function(page) {
	$("#stockinGrid").yxgrid("reload");
};
$(function() {
	$("#stockinGrid").yxgrid({
		model : 'stock_instock_stockin',
		action : 'calPageJson',
		title : "���������ϸ��: "+ $("#beginYear").val() + '.' + $("#beginMonth").val() + ' ��  ' + $("#endYear").val() + '.' + $("#endMonth").val(),
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		param : {
			'docStatus' : $("#docStatus").val(),
			'docType' : $("#docType").val(),
			'beginDate' : $("#beginYear").val() + '-' + $("#beginMonth").val() + '-01'  ,
			'endDate' : $("#endYear").val() + '-' + $("#endMonth").val() + '-31',
			'supplierId' : $("#supplierId").val(),
			'productId' : $("#productId").val(),
			'isRed' : $("#isRed").val()
		},
		isShowNum : false,
		usepager : false, // �Ƿ��ҳ

		// ����Ϣ
		colModel : [
			{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			},{
				display : '��id',
				name : 'mainId',
				sortable : true,
				hide : true
			}, {
				name : 'auditDate',
				display : '��������',
				sortable : true,
				width : 80
			}, {
				name : 'docStatus',
				display : '����״̬',
				sortable : true,
				width : 50,
				process : function(v, row) {
					if (row.docStatus == 'WSH') {
						return "δ���";
					} else if(row.docStatus == 'YSH'){
						return "�����";
					}
				}
			}, {
				name : 'docCode',
				display : '���ݱ��',
				sortable : true,
				process : function(v,row){
					if(row.isRed == 1 ){
						return "<span class='red'>" + v + "</span>";
					}else{
						return v;
					}
				}
			}, {
				name : 'relDocName',
				display : 'Դ������',
				sortable : true,
				hide : true

			}, {
				name : 'supplierName',
				display : '��Ӧ������',
				sortable : true,
				width : 140
			},{
				name : 'inStockName',
				display : '���ϲֿ�',
				sortable : true
			}, {
				name : 'productCode',
				display : '���ϱ��',
				sortable : true
			}, {
				name : 'productName',
				display : '��������',
				sortable : true,
				width : 110
			}, {
				name : 'unitName',
				display : '��λ',
				sortable : true,
				width : 60
			}, {
				name : 'actNum',
				display : 'ʵ������',
				sortable : true,
				width : 80
			}, {
				name : 'price',
				display : '��λ�ɱ�',
				sortable : true,
				process : function (v){
					return moneyFormat2(v);
				}
			}, {
				name : 'subPrice',
				display : '�ɱ�',
				sortable : true,
				process : function (v,row){
					return moneyFormat2(v);
				}
			}
		],

/*		comboEx:[{
				text:'����״̬',
				key:'docStatus',
				data:[{
				   text:'δ���',
				   value:'WSH'
				},{
				   text:'�����',
				   value:'YSH'
				}]
			}],*/
		menusEx : [
			{
				name : 'view',
				text : "�鿴",
				icon : 'view',
				action : function(row, rows, grid) {
					showThickboxWin("?model=stock_instock_stockin&action=toView&id="
						+ row.mainId
						+ "&docType="
						+ row.docType + "&skey=" + row.skey_
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}
			}
		],
		buttonsEx : [
		    {
			name : 'export',
			text : "����",
			icon : 'excel',
			action : function(row) {
				var i = 1;
				var colId = "";
				var colName = "";
				$("#stockinGrid_hTable").children("thead").children("tr")
						.children("th").each(function() {
							if ($(this).css("display") != "none"
									&& $(this).attr("colId") != undefined) {
								colName += $(this).children("div").html() + ",";
								colId += $(this).attr("colId") + ",";
								i++;
							}
						})

				window.open("?model=stock_instock_stockin&action=purchaseExportExcel&colId="
								+ colId + "&colName=" + colName
								+"&titleName="+"���������ϸ��  "+ $("#beginYear").val() + '-' + $("#beginMonth").val() + '��' + $("#endYear").val() + '-' + $("#endMonth").val()
								+ "&docStatus=" + $("#docStatus").val()+ "&docType=" + $("#docType").val()
								+ "&beginDate=" + $("#beginYear").val() + '-' + $("#beginMonth").val() + '-01'
								+ "&endDate=" +  $("#endYear").val() + '-' + $("#endMonth").val() + '-31'
								+ "&supplierId=" + $("#supplierId").val() + "&productId=" + $("#productId").val()
								+ "&1width=200,height=200,top=200,left=200,resizable=yes")
			}
		},{
				name : 'back',
				text : '����',
				icon : 'edit',
				action : function() {
					location='?model=stock_instock_stockin&action=toDetailList&docType=' + $("#docType").val();
				}
			}
		],
		sortname : "auditDate",
		sortorder : "DESC"

	});
});