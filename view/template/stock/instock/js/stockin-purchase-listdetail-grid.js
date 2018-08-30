var show_page = function(page) {
	$("#stockinGrid").yxgrid("reload");
};
$(function() {
	$("#stockinGrid").yxgrid({
		model : 'stock_instock_stockin',
		action : 'calPageJson',
		title : "�ɹ��ɱ���ϸ��: " + '������ : ' + $("#beginYear").val() + '.' + $("#beginMonth").val() + ' ��  ' + $("#endYear").val() + '.' + $("#endMonth").val(),
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		param : {
			'docStatus' : 'YSH',
			'docType' : $("#docType").val(),
			'beginDate' : $("#beginYear").val() + '-' + $("#beginMonth").val() + '-01'  ,
			'endDate' : $("#endYear").val() + '-' + $("#endMonth").val() + '-31',
			'supplierId' : $("#supplierId").val(),
			'iProductId' : $("#productId").val(),
			'catchStatus' : $("#catchStatus").val(),
			'isRed' : $("#isRed").val(),
			'ifshow' : $("#ifshow").val(),
			'docCode' : $("#docCode").val(),
			'iInStockId' : $("#itemInStockId").val(),
			'iPattern' : $("#pattern").val(),
			'iActNum' : $("#actNum").val(),
			'iPrice' : $("#price").val(),
			'iSubPrice' : $("#subPrice").val(),
			'createId' : $("#createId").val(),
			'purchaserCode' : $("#purchaserCode").val(),
			'auditerCode' : $("#auditerCode").val(),
			'auditDate' : $("#auditDate").val()
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
			}, {
				name : 'inStockName',
				display : '�ջ��ֿ�',
				sortable : true,
				width : 140
			}, {
				name : 'purchaserDept',
				display : '�ɹ�����',
				sortable : true,
				width : 100,
				process : function (v,row){
					if(row.purchaserCode == ''){
						return row.purchaserName;
					}else{
						return '';
					}
				}
			}, {
				name : 'purchaserName',
				display : '�ɹ���Ա',
				sortable : true,
				width : 100,
				process : function (v,row){
					if(row.purchaserCode != ''){
						return v;
					}else{
						return '';
					}
				}
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
				name : 'pattern',
				display : '����ͺ�',
				sortable : true,
				width : 100
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
				display : '����',
				sortable : true,
				process : function (v){
					return moneyFormat2(v);
				}
			}, {
				name : 'subPrice',
				display : '���',
				sortable : true,
				process : function (v,row){
					return moneyFormat2(v);
				}
			}, {
				name : 'createName',
				display : '�Ƶ�',
				sortable : true,
				width : 60
			}, {
				name : 'auditerName',
				display : '�����',
				sortable : true,
				width : 60
			}, {
				name : 'auditDate',
				display : '�������',
				sortable : true,
				width : 80
			}, {
				name : 'remark',
				display : '��ע',
				sortable : true,
				width : 150
			}, {
				name : 'catchStatus',
				display : '����״̬',
				sortable : true,
				datacode : 'CGFPZT',
				width : 70
			}, {
				name : 'unHookNumber',
				display : 'δ��������',
				width : 80
			}, {
				name : 'unHookAmount',
				display : 'δ�������',
				process : function (v,row){
					return moneyFormat2(v);
				}
			}, {
				name : 'hookNumber',
				display : '�ѹ�������',
				width : 80
			}, {
				name : 'hookAmount',
				display : '�ѹ������',
				process : function (v,row){
					return moneyFormat2(v);
				}
			}, {
				name : 'ZY',
				display : 'ժҪ',
				sortable : true,
				width : 40
			}, {
				name : 'YS',
				display : '����',
				sortable : true,
				width : 40
			}, {
				name : 'BG',
				display : '����',
				sortable : true,
				width : 40
			}, {
				name : 'YWY',
				display : 'ҵ��Ա',
				sortable : true,
				width : 40
			}, {
				name : 'FZR',
				display : '������',
				sortable : true,
				width : 40
			}
		],
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
								+"&titleName="+"�ɹ��ɱ���ϸ�� " + $("#beginYear").val() + '-' + $("#beginMonth").val() + '��' + $("#endYear").val() + '-' + $("#endMonth").val()
								+ "&docStatus=YSH" + "&docType=" + $("#docType").val()
								+ "&beginDate=" + $("#beginYear").val() + '-' + $("#beginMonth").val() + '-01'
								+ "&endDate=" +  $("#endYear").val() + '-' + $("#endMonth").val() + '-31'
								+ "&supplierId=" + $("#supplierId").val() 
								+ "&iProductId=" + $("#productId").val()
								+ "&isRed=" + $("#isRed").val()
								+ "&docCode=" + $("#docCode").val()
								+ "&iInStockId=" + $("#itemInStockId").val()
								+ "&iPattern=" + $("#pattern").val()
								+ "&iActNum=" + $("#actNum").val()
								+ "&iPrice=" + $("#price").val()
								+ "&iSubPrice=" + $("#subPrice").val()
								+ "&createId=" + $("#createId").val()
								+ "&purchaserCode=" + $("#purchaserCode").val()
								+ "&auditerCode=" + $("#auditerCode").val()
								+ "&auditDate=" + $("#auditDate").val()
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
		sortorder : "ASC"

	});
});