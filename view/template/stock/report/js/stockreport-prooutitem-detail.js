var isShow = false;
function showReportData(dataSql) {
	$("#reportDiv").empty();
	$("#reportDiv").show();
	// 用查询显示控件展现报表，从URL“../grf/1a.grf”获取报表膜板定义，从URL“../data/xmlCustomer.php”获取XML形式的报表数据，
	var html = getDisplayViewerHtml(
			"view/template/stock/report/stockreport-prooutitem.grf",
			"view/template/stock/report/stockreport-prooutitemdetail.php?conditionSql="
					+ encodeURIComponent(dataSql));
	$("#reportDiv").html(html);
	isShow = true;
}

function searchBtn() {
	// window.open("?model=stock_report_stockreport&action=toProOutSubSearch"
	// + "&beginDate=" + document.getElementById("beginDate").value
	// + "&endDate=" + document.getElementById("endDate").value
	// + "&productId=" + document.getElementById("productId").value
	// + "&productCode=" + document.getElementById("productCode").value
	// + "&productName=" + document.getElementById("productName").value
	// ,'newwindow','height=500,width=800');
	$("#reportDiv").hide();
	if (isShow) {
		$("body").yxadvsearch("show");
	} else {
		$("body").yxadvsearch({
			afterConfirmAction : 'hide',
			windowOptions : {
				showModal:false,
				onClose : function() {
//					if (!isShow) {
//						showReportData("");
//					}
					isShow=false;
					$("#reportDiv").show();
				}
			},
			// 高级搜索
			advSearchOptions : {
				modelName : 'prooutitemdetail',
				// 选择字段后进行重置值操作
				selectFn : function($valInput) {
					$valInput.yxcombogrid_product("remove");
					// $valInput.yxcombogrid_customer("remove");
				},

				searchConfig : [{
							name : '物料编号',
							value : 'g.productCode'
						}, {
							name : '物料名称',
							value : 'g.productName'
						}, {
							name : '统计年份',
							value : 'g.thisYear'
						}, {
							name : '统计月份',
							value : 'g.thisMonth'
						}, {
							name : '交付类型',
							value : 'g.actType',
							type : 'select',
							options : [{
									'dataName' : '合同出库',
									'dataCode' : '合同出库'
								},{
									'dataName' : '合同退库',
									'dataCode' : '合同退库'
								},{
									'dataName' : '借用',
									'dataCode' : '借用'
								},{
									'dataName' : '归还',
									'dataCode' : '归还'
							}]
						}, {
//							name : '日期',
//							value : 'g.actDate',
//							changeFn : function($t, $valInput) {
//								$valInput.click(function() {
//								WdatePicker({
//									dateFmt : 'yyyy-MM-dd'
//								});
//							});
//							}
//						}, {
							name : '客户名称',
							value : 'g.customerName'
						}, {
							name : '合同编号',
							value : 'g.contractCode'
						}]
			},
			event : {
				confirmAdvsearch : function(e, g) {
					// alert(123)
					var searchArr = g.getAdvSearchArr();
					$.dump(searchArr);
					var dataSql = $.ajax({
						url : 'index1.php?model=system_adv_advcase&action=getAdvsearchSql',
						type : 'POST',
						data : {
							advArr : searchArr
						},
						async : false
					}).responseText;
					// alert(dataSql)
					showReportData(dataSql);
				}
			}
		});
	}
}

$(function() {
	searchBtn();

	//设值高度
	var thisHeight = document.documentElement.clientHeight - 40;
	$("#reportDiv").height(thisHeight);
});