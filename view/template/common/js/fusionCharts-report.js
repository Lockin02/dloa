var chart;
// ͼ������ѡ���
var chartsUrl = {
	'Charts1' : {
		"Column2D.swf" : "2D��״ͼ",
		"Column3D.swf" : "3D��״ͼ",
		"Doughnut2D.swf" : "2D��ͼ",
		"Doughnut3D.swf" : "3D��ͼ",
		"Pie2D.swf" : "2D��ͼ",
		"Pie3D.swf" : "3D��ͼ",
		"SSGrid.swf" : "����ͼ"
		// "Area2D.swf" : "2D����ͼ",
		// "Bar2D.swf" : "2D������״ͼ"
	},
	'Charts2' : {
		"MSColumn2D.swf" : "2D��״ͼ",
		"ScrollColumn2D.swf" : "2D��״����ͼ",
		"MSColumnLine3D.swf" : "3D��״ͼ",
		"StackedColumn3D.swf" : "3D����ͼ",
		"grid" : "����ͼ"
	}
};

var swfWidth = 800;

/**
 * ��������ͼ������
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

// ��ά���
function processGrid(obj) {
	gridDataArr = [];
	// ��ά
	var categories = obj.chart['categories']['category'];

	var dataset = obj.chart['dataset'];
	$("#chart").hide();
	$("#grid").show();
	$("thead>tr", "#grid").empty();
	$("tbody", "#grid").empty();
	$("thead>tr", "#grid").append("<th width='200'>ͳ�Ʒ���/ͳ������</th>");
	if (categories) {
		var dataset = obj.chart['dataset'];
		if (categories.label) {
			$("thead>tr", "#grid").append("<th>" + categories.label + "</th>");
		} else {
			for (var i = 0; i < categories.length; i++) {
				$("thead>tr", "#grid").append("<th>" + categories[i].label
						+ "</th>");
			}
			$("thead>tr", "#grid").append("<th>�ϼ�</th>");
		}

		if (dataset) {
			var ycountArr = [];// �кϼ�����
			if (dataset['set']) {// x�����ֻ��һ�����
				var $tr = $("<tr>");
				$tr.append("<td>" + dataset.seriesName + "</td>");

				var xcount = 0;
				if (dataset['set'].value) {// x��ֻ��һ�����
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
			$trycount.append("<td>�ϼƣ�</td>");// �кϼ�
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
 * �����ܼ�
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
	if (obj && obj.chart && obj.chart['dataset']) {// ��ά
		var dataset = obj.chart['dataset'];

		if (dataset['set']) {// ���datasetֻ��һ�������
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
					if (setArr.value) {// setֻ��һ�������
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

	} else {// һά
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
	// ����ٷֱ�
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
 * ����ͼ�α���
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
				data : param,// ���������ﴫ
				async : false
			}).responseText;
	var i = data.indexOf("<");
	var obj = $.xml2json("<xml>" + data.substr(i) + "</xml>");
	if (v == 'grid') {// ���
		// ��ά�����
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
					alert("��Ȳ��ܴ���20000");
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
	// �ܼ�
	processAllCount(obj);
}

/** * �������� * */
var contractArr = {
	// ͳ������ͬ��
	analysisDataRelation : [['contractNum'],
			['contractMoney', 'invoiceMoney', 'incomeMoney'],
			['incomePercent', 'invoicePercent']],
	// ͳ������
	analysisData : {
		contractNum : '��ͬ����',
		contractMoney : "��ͬ���",
		invoiceMoney : '��Ʊ���',
		incomeMoney : '������',
		incomePercent : '����ٷֱ�',
		invoicePercent : '��Ʊ�ٷֱ�'
	},
	// ͳ������
	analysisConditionData : {
		areaPrincipal : [
				'�����ܼ�',
				'?model=system_region_region&action=listAreaPrincipalJson&addRoot=1',
				'areaPrincipal', 'id'],
		contractProvince : [
				'ʡ��',
				'?model=system_procity_province&action=listJsonAndRoot&addRoot=1',
				'provinceName', 'id'],
		contractCity : [
				'����',
				'?model=system_procity_province&action=listProAndCity&addRoot=1',
				'name', 'id'],
		customerTypeName : [
				'�ͻ�����',
				'?model=system_datadict_datadict&action=listByParentCode&addRoot=1&parentCode=KHLX',
				'dataName', 'dataCode'],
		contractType : ['��ͬ����', [{
							id : 'order',
							name : '���ۺ�ͬ'
						}, {
							id : 'service',
							name : '�����ͬ'
						}, {
							id : 'lease',
							name : '���޺�ͬ'
						}, {
							id : 'rdproject',
							name : '�з���ͬ'
						}]],
		contractNatureName : [
				'��ͬ����',
				'?model=system_datadict_datadict&action=getChildren&parentCode=HTSX&addRoot=1',
				'name', 'code']
	},
	// ͳ�Ʒ���
	analysisTypeData : {
		areaPrincipal : [
				'�����ܼ�',
				'?model=system_region_region&action=listAreaPrincipalJson&addRoot=1',
				'areaPrincipal', 'id'],
		contractProvince : [
				'ʡ��',
				'?model=system_procity_province&action=listJsonAndRoot&addRoot=1',
				'provinceName', 'id'],
		customerTypeName : [
				'�ͻ�����',
				'?model=system_datadict_datadict&action=listByParentCode&parentCode=KHLX&addRoot=1',
				'dataName', 'dataCode'],
		contractType : ['��ͬ����', [{
							id : 'order',
							name : '���ۺ�ͬ'
						}, {
							id : 'service',
							name : '�����ͬ'
						}, {
							id : 'lease',
							name : '���޺�ͬ'
						}, {
							id : 'rdproject',
							name : '�з���ͬ'
						}]],
		sameAttribute : ['ͬ������', []]
	}
};

var $lastRemoveOption;

/**
 * ����ͳ������ͳ��ֵ
 */
var updateAnalysisConditionValue = function(value) {
	$("#analysisCondition").val("");
	$("#analysisConditionValue").val("");
	$("#analysisType").val("");
	$("#analysisTypeValue").val("");
	$("#analysisCondition").yxcombotree("remove");

	// ��ͳ�Ʒ�����һ����ֵȥ��
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
 * ����ͳ������
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
	// ͳ������
	$("#analysisData").change(function() {
				// updateChartTypeSelect('Charts1');
				var v = $(this).val();
				// ��������ô�һ��
				if (v.indexOf("Money") > 0) {
					var w = $("#swfWidth").val();
					if (w < 1500) {
						$("#swfWidth").val(1500);
					}
				}
				var text = $(this).find("option:selected").text();
				$("#analysisDataText").val(text);
				// ͬ������ֵ�����ݲ�ͬ��ͳ�Ʒ���ֵ��һ
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

				// ���ѡ����ͬ�����ԣ�����ͬ������ֵ
				if ($("#analysisTypeData").val() == 'sameAttribute') {
					updateAnalysisTypeValue('sameAttribute');
					updateChartTypeSelect('Charts1');
				}
			});

	// ͳ������
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
			// // ����ĵ�һ����������������ļ���exportHandler��ֵ��ͬ
			// myExportComponent.sourceCharts = ['chartSwf'];
			// myExportComponent.componentAttributes.btnFontSize = '12';
			// myExportComponent.componentAttributes.btndisabledtitle = '�ȴ�����';
			// myExportComponent.componentAttributes.btnsavetitle = '����';
			// myExportComponent.componentAttributes.fullMode = '1';
			// myExportComponent.componentAttributes.defaultExportFormat =
			// 'png';
			// myExportComponent.componentAttributes.saveMode = 'batch';
			// myExportComponent.componentAttributes.bgColor = '111111';
			if (swfWidth > 2880) {
				alert("��ȳ���2880ͼƬ�޷�����.");
			} else {
				var chartObject = getChartFromId('chartSwf');
				chartObject.exportChart();
			}
		} else {

			var gridDataArr = [];// �洢������ݣ����ڵ���excel������̨ һά�У���ά��
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
