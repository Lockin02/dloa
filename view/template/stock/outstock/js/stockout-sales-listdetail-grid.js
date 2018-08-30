var show_page = function(page) {
	$("#stockoutGrid").yxgrid("reload");
};
$(function() {
	var contractInfo = "" ;
	if($("#objId").val() !="" ){
		contractInfo = "  ��ͬ��� ��" + $("#objCode").val();
	}

	var customerName = "";
	if($("#customerId").val() !="" ){
		customerName = "  �ͻ����� ��" + $("#customerName").val();
	}
	$("#stockoutGrid").yxgrid({
		model : 'stock_outstock_stockout',
		action : 'calPageJson',
		title : "���۳ɱ���ϸ��: " + '������ : ' + $("#beginYear").val() + '.' + $("#beginMonth").val() + ' ��  ' + $("#endYear").val() + '.' + $("#endMonth").val() + customerName + contractInfo,
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
			'customerId' : $("#customerId").val(),
			'contractId' : $("#objId").val(),
			'isRed' : $("#isRed").val(),
			'ifshow' : $("#ifshow").val(),
			'docCode' : $("#docCode").val(),
			'deptName' : $("#deptName").val(),
			'toUseLike' : $("#toUse").val(),
			'iPattern' : $("#pattern").val(),
			'iSerialnoName' : $("#serialnoName").val(),
			'iProductId' : $("#productId").val(),
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
				name : 'moduleName',
				display : '�������',
				sortable : true,
				width : 60
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
				width : 80
			}, {
				name : 'actOutNum',
				display : '����',
				sortable : true,
				width : 80
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
				name : 'deptName',
				display : '���ϲ���',
				sortable : true,
				width : 100
			}, {
				name : 'pickName',
				display : '������',
				sortable : true,
				width : 80
			}, {
				name : 'toUse',
				display : '������;',
				sortable : true,
				datacode : 'CHUKUYT',
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

				window.open("?model=stock_outstock_stockout&action=salesExportExcel&colId="
								+ colId + "&colName=" + colName
								+ "&docStatus=YSH" + "&docType=" + $("#docType").val()
								+ "&beginDate=" + $("#beginYear").val() + '-' + $("#beginMonth").val() + '-01'
								+ "&endDate=" +  $("#endYear").val() + '-' + $("#endMonth").val() + '-31'
							    + "&customerId=" + $("#customerId").val()
							    + "&contractId=" +  $("#objId").val()
							    + "&docCode=" +  $("#docCode").val()
							    + "&deptName=" +  $("#deptName").val()
							    + "&toUseLike=" +  $("#toUse").val()
							    + "&iPattern=" +  $("#pattern").val()
							    + "&iSerialnoName=" +  $("#serialnoName").val()
							    + "&iProductId=" +  $("#productId").val()
							    + "&iActOutNum=" +  $("#actOutNum").val()
							    + "&iCost=" +  $("#cost").val()
							    + "&iSubCost=" +  $("#subCost").val()
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