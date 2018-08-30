var show_page = function(page) {
	$("#stockoutGrid").yxgrid("reload");
};
$(function() {
	var productCode = "" ;
	if($("#productId").val() !="" ){
		productCode = "  ���ϱ��� ��" + $("#productCode").val();
	}

	var pickName = "";
	if($("#pickCode").val() !="" ){
		pickName = "  ������ ��" + $("#pickName").val();
	}

	var deptName = "";
	if($("#deptCode").val() !="" ){
		deptName = "  ���ϲ��� ��" + $("#deptName").val();
	}

	var customerName = "";
	if($("#customerId").val() !="" ){
		customerName = "  �ͻ����� ��" + $("#customerName").val();
	}
	$("#stockoutGrid").yxgrid({
		model : 'stock_outstock_stockout',
		action : 'calPageJson',
		title : "����������ϸ��: " + '������ : ' + $("#beginYear").val() + '.' + $("#beginMonth").val() + ' ��  ' + $("#endYear").val() + '.' + $("#endMonth").val() + customerName + productCode + deptName + pickName,
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
			'iProductId' : $("#productId").val(),
			'pickCode' : $("#pickCode").val(),
			'customerId' : $("#customerId").val(),
			'deptCode' : $("#deptCode").val(),
			'ifshow' : $("#ifshow").val(),
			'isRed' : $("#isRed").val(),
			'docCode' : $("#docCode").val(),
			'toUseLike' : $("#toUse").val(),
			'iPattern' : $("#pattern").val(),
			'iSerialnoName' : $("#serialnoName").val(),
			'iActOutNum' : $("#actOutNum").val(),
			'iCost' : $("#cost").val(),
			'iSubCost' : $("#subCost").val()
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
				name : 'contractCode',
				display : '��ͬ���',
				sortable : true,
				width : 140
			}, {
				name : 'customerName',
				display : '�ͻ�����',
				sortable : true,
				width : 140
			}, {
				name : 'deptName',
				display : '���ϲ���',
				sortable : true,
				width : 80
			}, {
				name : 'pickName',
				display : '������',
				sortable : true,
				width : 80
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
				sortable : true,
				width : 100
			}, {
				name : 'unitName',
				display : '��λ',
				sortable : true,
				width : 60
			}, {
				name : 'actOutNum',
				display : '����',
				sortable : true,
				width : 60
			}, {
				name : 'cost',
				display : '��λ�ɱ�',
				sortable : true,
				process : function (v){
					return moneyFormat2(v);
				}
			}, {
				name : 'subCost',
				display : '�ɱ�',
				sortable : true,
				process : function (v){
					return moneyFormat2(v);
				}
			}, {
				name : 'toUse',
				display : '������;',
				datacode : 'CHUKUYT',
				sortable : true,
				width : 100
			}, {
				name : 'serialnoName',
				display : '���к�',
				sortable : true,
				width : 150
			}, {
				name : 'remark',
				display : '��ע',
				sortable : true,
				width : 150
			}, {
				name : 'YWY',
				display : 'ҵ��Ա',
				sortable : true,
				width : 40
			}, {
				name : 'ZG',
				display : '����',
				sortable : true,
				width : 40
			}, {
				name : 'ZY',
				display : 'ժҪ',
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
					showThickboxWin("?model=stock_outstock_stockout&action=toView&id="
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
				$("#stockoutGrid_hTable").children("thead").children("tr")
						.children("th").each(function() {
							if ($(this).css("display") != "none"
									&& $(this).attr("colId") != undefined) {
								colName += $(this).children("div").html() + ",";
								colId += $(this).attr("colId") + ",";
								i++;
							}
						})

				window.open("?model=stock_outstock_stockout&action=otherExportExcel&colId="
								+ colId + "&colName=" + colName
								+ "&docStatus=YSH" + "&docType=" + $("#docType").val()
								+ "&beginDate=" + $("#beginYear").val() + '-' + $("#beginMonth").val() + '-01'
								+ "&endDate=" +  $("#endYear").val() + '-' + $("#endMonth").val() + '-31'
								+ "&iProductId" + $("#productId").val()
								+ "&pickCode=" + $("#pickCode").val() 
								+ "&deptCode=" + $("#deptCode").val() 
								+ "&customerId=" + $("#customerId").val()
								+ "&docCode=" + $("#docCode").val()
								+ "&toUseLike=" + $("#toUse").val()
								+ "&iPattern=" + $("#pattern").val()
								+ "&iSerialnoName=" + $("#serialnoName").val()
								+ "&iActOutNum=" + $("#actOutNum").val()
								+ "&iCost=" + $("#cost").val()
								+ "&iSubCost=" + $("#subCost").val()
								+ "&isRed=" + $("#isRed").val()
								+ "&1width=200,height=200,top=200,left=200,resizable=yes")
			}
		},{
				name : 'back',
				text : '����',
				icon : 'edit',
				action : function() {
					location='?model=stock_outstock_stockout&action=toDetailList&docType=' + $("#docType").val();
				}
			}
		],
		sortname : "auditDate",
		sortorder : "ASC"

	});
});