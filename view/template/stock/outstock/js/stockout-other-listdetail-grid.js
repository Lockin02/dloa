var show_page = function(page) {
	$("#stockoutGrid").yxgrid("reload");
};
$(function() {
	var productCode = "" ;
	if($("#productId").val() !="" ){
		productCode = "  物料编码 ：" + $("#productCode").val();
	}

	var pickName = "";
	if($("#pickCode").val() !="" ){
		pickName = "  领料人 ：" + $("#pickName").val();
	}

	var deptName = "";
	if($("#deptCode").val() !="" ){
		deptName = "  领料部门 ：" + $("#deptName").val();
	}

	var customerName = "";
	if($("#customerId").val() !="" ){
		customerName = "  客户名称 ：" + $("#customerName").val();
	}
	$("#stockoutGrid").yxgrid({
		model : 'stock_outstock_stockout',
		action : 'calPageJson',
		title : "其他出库明细表: " + '财务期 : ' + $("#beginYear").val() + '.' + $("#beginMonth").val() + ' 至  ' + $("#endYear").val() + '.' + $("#endMonth").val() + customerName + productCode + deptName + pickName,
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
		usepager : false, // 是否分页

		// 列信息
		colModel : [
			{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			},{
				display : '表单id',
				name : 'mainId',
				sortable : true,
				hide : true
			}, {
				name : 'auditDate',
				display : '单据日期',
				sortable : true,
				width : 80
			}, {
				name : 'docStatus',
				display : '单据状态',
				sortable : true,
				width : 50,
				process : function(v, row) {
					if (row.docStatus == 'WSH') {
						return "未审核";
					} else if(row.docStatus == 'YSH'){
						return "已审核";
					}
				}
			}, {
				name : 'docCode',
				display : '单据编号',
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
				display : '合同编号',
				sortable : true,
				width : 140
			}, {
				name : 'customerName',
				display : '客户名称',
				sortable : true,
				width : 140
			}, {
				name : 'deptName',
				display : '领料部门',
				sortable : true,
				width : 80
			}, {
				name : 'pickName',
				display : '领料人',
				sortable : true,
				width : 80
			}, {
				name : 'productCode',
				display : '物料编号',
				sortable : true
			}, {
				name : 'productName',
				display : '物料名称',
				sortable : true
			}, {
				name : 'pattern',
				display : '规格型号',
				sortable : true,
				width : 100
			}, {
				name : 'unitName',
				display : '单位',
				sortable : true,
				width : 60
			}, {
				name : 'actOutNum',
				display : '数量',
				sortable : true,
				width : 60
			}, {
				name : 'cost',
				display : '单位成本',
				sortable : true,
				process : function (v){
					return moneyFormat2(v);
				}
			}, {
				name : 'subCost',
				display : '成本',
				sortable : true,
				process : function (v){
					return moneyFormat2(v);
				}
			}, {
				name : 'toUse',
				display : '出库用途',
				datacode : 'CHUKUYT',
				sortable : true,
				width : 100
			}, {
				name : 'serialnoName',
				display : '序列号',
				sortable : true,
				width : 150
			}, {
				name : 'remark',
				display : '备注',
				sortable : true,
				width : 150
			}, {
				name : 'YWY',
				display : '业务员',
				sortable : true,
				width : 40
			}, {
				name : 'ZG',
				display : '主管',
				sortable : true,
				width : 40
			}, {
				name : 'ZY',
				display : '摘要',
				sortable : true,
				width : 40
			}
		],
		menusEx : [
			{
				name : 'view',
				text : "查看",
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
			text : "导出",
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
				text : '返回',
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