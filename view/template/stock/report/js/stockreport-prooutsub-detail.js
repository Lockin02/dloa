var isShow = false;
function showReportData(dataSql) {
	//循环设置值
	var thisGrid = $("#advsearchgrid");
	var $cmps = thisGrid.yxeditgrid('getCmpByCol','searchField');
	var v,thisYearVal,productCodeVal,productNameVal;
	$cmps.each(function(i){
		var thisValue = strTrim(thisGrid.yxeditgrid('getCmpByRowAndCol',i,'value').val());
		//年份
		if(this.value  =='g.thisYear' && thisValue != ''){
			$("#thisYearView").html(thisValue);
			thisYearVal = 1;
		}
		//物料编号
		if($(this).val()=='g.productCode' && thisValue != ''){
			$("#productCodeView").html(thisValue);
			productCodeVal = 1;
		}
		//物料名称
		if($(this).val()=='g.productName' && thisValue != ''){
			$("#productNameView").html(thisValue);
			productNameVal = 1;
		}
	});

	if(thisYearVal != 1){
		$("#thisYearView").html($("#thisYear").val());
	}

	if(productCodeVal != 1){
		$("#productCodeView").html('');
	}

	if(productNameVal != 1){
		$("#productNameView").html('');
	}

	$("#reportDiv").empty();
	$("#reportDiv").show();


	// 用查询显示控件展现报表，从URL“../grf/1a.grf”获取报表膜板定义，从URL“../data/xmlCustomer.php”获取XML形式的报表数据，
	var html = getDisplayViewerHtml(
			"view/template/stock/report/stockreport-prooutsub1.grf",
			"view/template/stock/report/stockreport-prooutsubdetail.php?"
				+ "conditionSql="
				+ encodeURIComponent(dataSql));
	$("#reportDiv").html(html);
	isShow = true;
}

function searchBtn() {
	$("#reportDiv").hide();
	if (isShow) {
		$("body").yxadvsearch("show");
	} else {
		$("body").yxadvsearch({
			afterConfirmAction : 'hide',
			windowOptions : {
				showModal : false,
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
				modelName : 'stockreportdetail',
				// 选择字段后进行重置值操作
				selectFn : function($valInput) {
					// $valInput.yxcombogrid_product("remove");
					// $valInput.yxcombogrid_customer("remove");
				},

				searchConfig : [{
							name : '物料编号',
							value : 'g.productCode'
					}, {
							name : '物料名称',
							value : 'g.productName'
					},{
							name : '统计年份',
							value : 'g.thisYear'
					}]
			},
			event : {
				confirmAdvsearch : function(e, g) {
					var searchArr = g.getAdvSearchArr();
					var dataSql = $.ajax({
						url : 'index1.php?model=system_adv_advcase&action=getAdvsearchSql',
						type : 'POST',
						data : {
							advArr : searchArr
						},
						async : false
					}).responseText;
					showReportData(dataSql);
				}
			}
		});
	}
}

$(function() {
	searchBtn();
	//初始设置

	//设值高度
	var thisHeight = document.documentElement.clientHeight - 40;
	$("#reportDiv").height(thisHeight);
});