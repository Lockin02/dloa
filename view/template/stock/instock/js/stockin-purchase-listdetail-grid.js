var show_page = function(page) {
	$("#stockinGrid").yxgrid("reload");
};
$(function() {
	$("#stockinGrid").yxgrid({
		model : 'stock_instock_stockin',
		action : 'calPageJson',
		title : "采购成本明细表: " + '财务期 : ' + $("#beginYear").val() + '.' + $("#beginMonth").val() + ' 至  ' + $("#endYear").val() + '.' + $("#endMonth").val(),
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
				name : 'relDocName',
				display : '源单名称',
				sortable : true,
				hide : true

			}, {
				name : 'supplierName',
				display : '供应商名称',
				sortable : true,
				width : 140
			}, {
				name : 'inStockName',
				display : '收货仓库',
				sortable : true,
				width : 140
			}, {
				name : 'purchaserDept',
				display : '采购部门',
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
				display : '采购人员',
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
				display : '物料编号',
				sortable : true
			}, {
				name : 'productName',
				display : '物料名称',
				sortable : true,
				width : 110
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
				name : 'actNum',
				display : '实收数量',
				sortable : true,
				width : 80
			}, {
				name : 'price',
				display : '单价',
				sortable : true,
				process : function (v){
					return moneyFormat2(v);
				}
			}, {
				name : 'subPrice',
				display : '金额',
				sortable : true,
				process : function (v,row){
					return moneyFormat2(v);
				}
			}, {
				name : 'createName',
				display : '制单',
				sortable : true,
				width : 60
			}, {
				name : 'auditerName',
				display : '审核人',
				sortable : true,
				width : 60
			}, {
				name : 'auditDate',
				display : '审核日期',
				sortable : true,
				width : 80
			}, {
				name : 'remark',
				display : '备注',
				sortable : true,
				width : 150
			}, {
				name : 'catchStatus',
				display : '钩稽状态',
				sortable : true,
				datacode : 'CGFPZT',
				width : 70
			}, {
				name : 'unHookNumber',
				display : '未钩稽数量',
				width : 80
			}, {
				name : 'unHookAmount',
				display : '未钩稽金额',
				process : function (v,row){
					return moneyFormat2(v);
				}
			}, {
				name : 'hookNumber',
				display : '已钩稽数量',
				width : 80
			}, {
				name : 'hookAmount',
				display : '已钩稽金额',
				process : function (v,row){
					return moneyFormat2(v);
				}
			}, {
				name : 'ZY',
				display : '摘要',
				sortable : true,
				width : 40
			}, {
				name : 'YS',
				display : '验收',
				sortable : true,
				width : 40
			}, {
				name : 'BG',
				display : '保管',
				sortable : true,
				width : 40
			}, {
				name : 'YWY',
				display : '业务员',
				sortable : true,
				width : 40
			}, {
				name : 'FZR',
				display : '负责人',
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
			text : "导出",
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
								+"&titleName="+"采购成本明细表 " + $("#beginYear").val() + '-' + $("#beginMonth").val() + '～' + $("#endYear").val() + '-' + $("#endMonth").val()
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
				text : '返回',
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