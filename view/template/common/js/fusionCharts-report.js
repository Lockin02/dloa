var chart;
// 图形类型选项定义
var chartsUrl = {
	'Charts1' : {
		"Column2D.swf" : "2D柱状图",
		"Column3D.swf" : "3D柱状图",
		"Doughnut2D.swf" : "2D环图",
		"Doughnut3D.swf" : "3D环图",
		"Pie2D.swf" : "2D饼图",
		"Pie3D.swf" : "3D饼图",
		"SSGrid.swf" : "网格图"
		// "Area2D.swf" : "2D区域图",
		// "Bar2D.swf" : "2D横向柱状图"
	},
	'Charts2' : {
		"MSColumn2D.swf" : "2D柱状图",
		"ScrollColumn2D.swf" : "2D柱状滚动图",
		"MSColumnLine3D.swf" : "3D柱状图",
		"StackedColumn3D.swf" : "3D叠加图",
		"grid" : "网格图"
	}
};

var swfWidth = 800;

/**
 * 更新下拉图形类型
 *
 * @param {}
 *            chartType
 */
function updateChartTypeSelect(chartType) {
	if (!chartType)
		chartType = "Charts1";
	var types = chartsUrl[chartType];
	$("#chartType").attr('charttype', chartType);
	$("#chartType").empty();
	for (var key in types) {
		var $option = $("<option value='" + key + "'>" + types[key]
				+ "</option>");
		$("#chartType").append($option);
	}
}

// 二维表格
function processGrid(obj) {
	gridDataArr = [];
	// 三维
	var categories = obj.chart['categories']['category'];

	var dataset = obj.chart['dataset'];
	$("#chart").hide();
	$("#grid").show();
	$("thead>tr", "#grid").empty();
	$("tbody", "#grid").empty();
	$("thead>tr", "#grid").append("<th width='200'>统计分类/统计条件</th>");
	if (categories) {
		var dataset = obj.chart['dataset'];
		if (categories.label) {
			$("thead>tr", "#grid").append("<th>" + categories.label + "</th>");
		} else {
			for (var i = 0; i < categories.length; i++) {
				$("thead>tr", "#grid").append("<th>" + categories[i].label
						+ "</th>");
			}
			$("thead>tr", "#grid").append("<th>合计</th>");
		}

		if (dataset) {
			var ycountArr = [];// 列合计数组
			if (dataset['set']) {// x轴分类只有一条情况
				var $tr = $("<tr>");
				$tr.append("<td>" + dataset.seriesName + "</td>");

				var xcount = 0;
				if (dataset['set'].value) {// x轴只有一条情况
					$tr.append("<td>" + dataset['set'].value + "</td>");
					ycountArr[0] = dataset['set'].value;
				} else {
					for (var j = 0; j < dataset['set'].length; j++) {
						var v = dataset['set'][j].value;
						$tr.append("<td>" + v + "</td>");
						xcount = accAdd(xcount, v);
						ycountArr[j] = v;
					}
					$tr.append("<td>" + xcount + "</td>");
				}
				$("tbody", "#grid").append($tr);
			}

			for (var i = 0; i < dataset.length; i++) {
				var setArr = dataset[i]['set'];

				var v = setArr.value;
				var $tr = $("<tr>");
				$tr.append("<td>" + dataset[i].seriesName + "</td>");

				if (v) {
					$tr.append("<td>" + v + "</td>");
					ycountArr[0] = accAdd(ycountArr[0], v);
					$("tbody", "#grid").append($tr);
				} else {
					var xcount = 0;
					for (var j = 0; j < setArr.length; j++) {
						$tr.append("<td>" + moneyFormat2(setArr[j].value, 2, 0)
								+ "</td>");
						xcount = accAdd(xcount, setArr[j].value);
						ycountArr[j] = accAdd(ycountArr[j], setArr[j].value);
					}
					$tr.append("<td>" + xcount + "</td>");
				}
				$("tbody", "#grid").append($tr);
			}
			var $trycount = $("<tr>");
			$trycount.append("<td>合计：</td>");// 列合计
			var allCount = 0;
			for (var i = 0; i < ycountArr.length; i++) {
				$trycount.append("<td>" + ycountArr[i] + "</td>");
				allCount = accAdd(allCount, ycountArr[i]);
			}
			if (!categories.label) {
				$trycount.append("<td>" + allCount + "</td>");
			}
			$("tbody", "#grid").append($trycount);

		}
	}
}
/**
 * 计算总计
 *
 * @param {}
 *            obj
 */
function processAllCount(obj) {
	var analysisTypeData = $("#analysisTypeData").val();
	var analysisTypeValue = $("#analysisTypeValue").val();
	var allCount = 0;
	var allCountObj = {};
	var analysisData = $("#analysisData").val();
	var length = 1;
	if (obj && obj.chart && obj.chart['dataset']) {// 多维
		var dataset = obj.chart['dataset'];

		if (dataset['set']) {// 如果dataset只有一个的情况
			if (!allCountObj[dataset.seriesName]) {
				allCountObj[dataset.seriesName] = 0;
			}
			length = dataset['set'].length;
			for (var i = 0; i < length; i++) {
				var setArr = dataset['set'][i];
				if (setArr) {
					var v = setArr.value;

					allCountObj[dataset.seriesName] = accAdd(
							allCountObj[dataset.seriesName], v, 2);
					allCount = accAdd(allCount, v, 2);
				}
			}
		} else {
			for (var i = 0; i < dataset.length; i++) {
				length = 0;
				var setArr = dataset[i]['set'];
				var seriesName = dataset[i].seriesName;
				if (!allCountObj[seriesName]) {
					allCountObj[seriesName] = 0;
				}
				if (setArr) {
					if (setArr.value) {// set只有一个的情况
						length++;
						var v = setArr.value;
						allCountObj[seriesName] = accAdd(
								allCountObj[seriesName], v, 2);
						allCount = accAdd(allCount, v, 2);
					} else {
						length = length + setArr.length;
						for (var j = 0; j < setArr.length; j++) {
							var v = setArr[j].value;
							allCountObj[seriesName] = accAdd(
									allCountObj[seriesName], v, 2);
							allCount = accAdd(allCount, v, 2);
						}
					}
				}
			}
		}

	} else {// 一维
		allCount = 0;
		if (obj&&obj.chart) {
			var setArr = obj.chart['set'];
			if (setArr && setArr.value) {
				allCount = setArr.value;
			} else if (setArr) {
				length = setArr.length;
				for (var i = 0; i < length; i++) {
					var v = setArr[i].value;
					allCount = accAdd(allCount, v);
				}
			}
		}
	}
	// 处理百分比
	if (analysisData == 'incomePercent' || analysisData == 'invoicePercent') {
		for (var key in allCountObj) {
			// /alert(allCount[key]+"/"+length)
			allCountObj[key] = accDiv(allCountObj[key], length, 2);
		}
		allCount = accDiv(allCount, length, 2);
	}
	$("#allCount").empty();
	if (analysisTypeData == 'sameAttribute' && analysisTypeValue != '') {
		for (var key in allCountObj) {
			allCountObj[key] = moneyFormat2(allCountObj[key]);
			$("#allCount").append(key + ":" + allCountObj[key] + " ");
		}
	} else {
		allCount = moneyFormat2(allCount);
		$("#allCount").append(allCount);
	}

}

/**
 * 更新图形报表
 */
function updateChart() {
	var type = $("#chartType").attr('charttype');
	var v = $("#chartType").val();
	var chartSWF1 = "FusionCharts/swf/" + type + "/" + v;
	// var chartSWF2 = "FusionCharts/swf/ZoomLine.swf";SSGrid.swf
	// var chartSWF2 = "FusionCharts/swf/Charts2/MSColumn2D.swf";

	// strurl = escape(strurl);
	var param = {};
	param.analysisData = $("#analysisData").val();
	param.analysisDataText = $("#analysisDataText").val();
	param.analysisConditionData = $("#analysisConditionData").val();
	param.analysisConditionValue = $("#analysisConditionValue").val();
	param.analysisCondition = $("#analysisCondition").val();

	param.analysisTypeData = $("#analysisTypeData").val();
	param.analysisTypeValue = $("#analysisTypeValue").val();
	param.analysisType = $("#analysisType").val();

	param.startTime = $("#startTime").val();
	param.endTime = $("#endTime").val();

	var chartSWF = chartSWF1;
	// if (param.analysisTypeValue != '') {
	// chartSWF = chartSWF2;
	// }
	var strurl = "?model=common_fusionCharts&action=ajaxReport";
	var data = $.ajax({
				url : strurl,
				type : 'post',
				data : param,// 参数从这里传
				async : false
			}).responseText;
	var i = data.indexOf("<");
	var obj = $.xml2json("<xml>" + data.substr(i) + "</xml>");
	if (v == 'grid') {// 表格
		// 多维表格处理
		processGrid(obj);
	} else {
		$("#chart").show();
		$("#grid").hide();
		if (!chart) {
			// var i = data.indexOf("<");
			// var width = data.substr(0, i);
			swfWidth = $("#swfWidth").val();
			if (isNaN(swfWidth)) {
				swfWidth = 800;
			} else {
				if (swfWidth > 20000) {
					alert("宽度不能大于20000");
					swfWidth = 20000;
				}
				if (swfWidth <= 0) {
					swfWidth = 800;
				}
			}
			$("#swfWidth").val(swfWidth);
			// if (v != 'Column2D.swf' && v != 'Column3D.swf'
			// && v != 'MSColumn2D.swf' && v != 'MSColumnLine3D.swf') {
			// width = 800;
			// }
			var chart = new FusionCharts(
					'FusionCharts/swf/ChartCrack.swf?chartUrl=' + chartSWF,
					"chartSwf", swfWidth, "400", "0", "1");
			chart.setDataXML(data);
			chart.render("chart");
		} else {
			chart.setDataXML(data);
		}
	}
	// 总计
	processAllCount(obj);
}

/** * 定义数据 * */
var contractArr = {
	// 统计数据同类
	analysisDataRelation : [['contractNum'],
			['contractMoney', 'invoiceMoney', 'incomeMoney'],
			['incomePercent', 'invoicePercent']],
	// 统计数据
	analysisData : {
		contractNum : '合同数量',
		contractMoney : "合同金额",
		invoiceMoney : '开票金额',
		incomeMoney : '到款金额',
		incomePercent : '到款百分比',
		invoicePercent : '开票百分比'
	},
	// 统计条件
	analysisConditionData : {
		areaPrincipal : [
				'区域总监',
				'?model=system_region_region&action=listAreaPrincipalJson&addRoot=1',
				'areaPrincipal', 'id'],
		contractProvince : [
				'省份',
				'?model=system_procity_province&action=listJsonAndRoot&addRoot=1',
				'provinceName', 'id'],
		contractCity : [
				'地市',
				'?model=system_procity_province&action=listProAndCity&addRoot=1',
				'name', 'id'],
		customerTypeName : [
				'客户性质',
				'?model=system_datadict_datadict&action=listByParentCode&addRoot=1&parentCode=KHLX',
				'dataName', 'dataCode'],
		contractType : ['合同性质', [{
							id : 'order',
							name : '销售合同'
						}, {
							id : 'service',
							name : '服务合同'
						}, {
							id : 'lease',
							name : '租赁合同'
						}, {
							id : 'rdproject',
							name : '研发合同'
						}]],
		contractNatureName : [
				'合同属性',
				'?model=system_datadict_datadict&action=getChildren&parentCode=HTSX&addRoot=1',
				'name', 'code']
	},
	// 统计分类
	analysisTypeData : {
		areaPrincipal : [
				'区域总监',
				'?model=system_region_region&action=listAreaPrincipalJson&addRoot=1',
				'areaPrincipal', 'id'],
		contractProvince : [
				'省份',
				'?model=system_procity_province&action=listJsonAndRoot&addRoot=1',
				'provinceName', 'id'],
		customerTypeName : [
				'客户性质',
				'?model=system_datadict_datadict&action=listByParentCode&parentCode=KHLX&addRoot=1',
				'dataName', 'dataCode'],
		contractType : ['合同性质', [{
							id : 'order',
							name : '销售合同'
						}, {
							id : 'service',
							name : '服务合同'
						}, {
							id : 'lease',
							name : '租赁合同'
						}, {
							id : 'rdproject',
							name : '研发合同'
						}]],
		sameAttribute : ['同类属性', []]
	}
};

var $lastRemoveOption;

/**
 * 更新统计条件统计值
 */
var updateAnalysisConditionValue = function(value) {
	$("#analysisCondition").val("");
	$("#analysisConditionValue").val("");
	$("#analysisType").val("");
	$("#analysisTypeValue").val("");
	$("#analysisCondition").yxcombotree("remove");

	// 把统计分类中一样的值去除
	$("#analysisTypeData").children().each(function() {
				if ($(this).val() == value) {
					$("#analysisTypeData").append($lastRemoveOption);
					$lastRemoveOption = $(this);
					$(this).remove();
					$("#analysisTypeData").trigger('change');
				}
			});

	var data = contractArr.analysisConditionData[value];
	var urlData = data[1];
	var treeOptions = {
		nameCol : data[2],
		checkable : true,
		event : {
			"node_click" : function(event, treeId, treeNode) {
				// alert(treeId)
			},
			"node_change" : function(event, treeId, treeNode) {
				// alert(treeId)
			}
		}
	}
	if ($.isArray(urlData)) {
		treeOptions.data = urlData;
	} else {
		treeOptions.url = urlData;
	}
	$("#analysisCondition").yxcombotree({
				hiddenId : 'analysisConditionValue',
				nameCol : data[2],
				valueCol : data[3],
				treeOptions : treeOptions
			});
}

/**
 * 更新统计类型
 *
 * @param {}
 *            value
 */
var updateAnalysisTypeValue = function(value) {
	$("#analysisType").val("");
	$("#analysisTypeValue").val("");
	$("#analysisType").yxcombotree("remove");
	if (value != '') {
		var data = contractArr.analysisTypeData[value];
		var urlData = data[1];
		var treeOptions = {
			nameCol : data[2],
			checkable : true,
			event : {
				"node_click" : function(event, treeId, treeNode) {
					// alert(treeId)
				},
				"node_change" : function(event, treeId, treeNode) {
					// alert(treeId)
				}
			}
		}
		if ($.isArray(urlData)) {
			treeOptions.data = urlData;
		} else {
			treeOptions.url = urlData;
		}
		treeOptions.event = {
			after_node_change : function() {
				if ($("#analysisTypeValue").val() == '') {
					updateChartTypeSelect('Charts1');
				} else {
					updateChartTypeSelect('Charts2');
				}

			}
		};

		$("#analysisType").yxcombotree({
					hiddenId : 'analysisTypeValue',
					nameCol : data[2],
					valueCol : data[3],
					treeOptions : treeOptions
				});
	}
}

$(function() {
	updateChartTypeSelect();
	$("#chartType").change(function() {
				updateChart();
			});

	for (var key in contractArr.analysisData) {
		var $option = $("<option value=" + key + ">"
				+ contractArr.analysisData[key] + "</option>");
		$("#analysisData").append($option);
	}
	$("#analysisDataText").val($($("#analysisData").children().get(0)).text());
	// 统计数据
	$("#analysisData").change(function() {
				// updateChartTypeSelect('Charts1');
				var v = $(this).val();
				// 金额宽度设置大一点
				if (v.indexOf("Money") > 0) {
					var w = $("#swfWidth").val();
					if (w < 1500) {
						$("#swfWidth").val(1500);
					}
				}
				var text = $(this).find("option:selected").text();
				$("#analysisDataText").val(text);
				// 同类属性值，根据不同的统计分类值不一
				for (var i = 0; i < contractArr.analysisDataRelation.length; i++) {
					var rv = contractArr.analysisDataRelation[i];
					if (rv.indexOf(v) >= 0) {
						contractArr.analysisTypeData.sameAttribute[1] = [];
						for (var j = 0; j < rv.length; j++) {
							var vv = rv[j];
							if (v != vv) {
								contractArr.analysisTypeData.sameAttribute[1]
										.push({
													id : vv,
													name : contractArr.analysisData[vv]
												});
							}
						}
					}
				}

				// 如果选择了同类属性，更新同类属性值
				if ($("#analysisTypeData").val() == 'sameAttribute') {
					updateAnalysisTypeValue('sameAttribute');
					updateChartTypeSelect('Charts1');
				}
			});

	// 统计条件
	for (var key in contractArr.analysisConditionData) {
		var $option = $("<option value=" + key + ">"
				+ contractArr.analysisConditionData[key][0] + "</option>");
		$("#analysisConditionData").append($option);
	}
	$("#analysisConditionData").change(function() {
				updateAnalysisConditionValue($(this).val());
				updateChartTypeSelect();
			});

	for (var key in contractArr.analysisTypeData) {
		var $option = $("<option value=" + key + ">"
				+ contractArr.analysisTypeData[key][0] + "</option>");
		$("#analysisTypeData").append($option);
	}
	$("#analysisTypeData").change(function() {
				updateAnalysisTypeValue($(this).val());
				updateChartTypeSelect();
			});

	updateAnalysisConditionValue($("#analysisConditionData").val());
	updateAnalysisTypeValue($("#analysisTypeData").val());
	updateChart();
	$("#searchButton").click(function() {
				var startTime = $("#startTime").val();
				var endTime = $("#endTime").val();
				updateChart();

			});
	$("#exportButton").click(function() {
		if ($("#chartType").val() != 'grid') {
			// var myExportComponent = new FusionChartsExportObject(
			// "fcExporter_all", "FusionCharts/swf/FCExporter.swf");
			// // 这里的第一个参数必须和数据文件中exportHandler的值相同
			// myExportComponent.sourceCharts = ['chartSwf'];
			// myExportComponent.componentAttributes.btnFontSize = '12';
			// myExportComponent.componentAttributes.btndisabledtitle = '等待导出';
			// myExportComponent.componentAttributes.btnsavetitle = '下载';
			// myExportComponent.componentAttributes.fullMode = '1';
			// myExportComponent.componentAttributes.defaultExportFormat =
			// 'png';
			// myExportComponent.componentAttributes.saveMode = 'batch';
			// myExportComponent.componentAttributes.bgColor = '111111';
			if (swfWidth > 2880) {
				alert("宽度超过2880图片无法导出.");
			} else {
				var chartObject = getChartFromId('chartSwf');
				chartObject.exportChart();
			}
		} else {

			var gridDataArr = [];// 存储表格数据，用于导出excel传到后台 一维列，二维行
			gridDataArr[0] = [];
			$("thead>tr>th", "#grid").each(function(i) {
						gridDataArr[0][i] = $(this).text();
					});
			$("tbody>tr", "#grid").each(function(rownum) {
						var rownum = rownum + 1;
						gridDataArr[rownum] = [];
						$("td", $(this)).each(function(colnum) {
									gridDataArr[rownum][colnum] = $(this)
											.text();
								})
					});
			openPostWindow('?model=common_fusionCharts&action=exportExcel',
					gridDataArr, 'excelWin');
		}
	});
});
