var colModel;
var parentColName;
var presentation; //显示方式
$(function () {
	$("#showHideBtn").toggle(
		function () {
			$("#recordDiv").show('slow' ,function () {
				$("#showHideBtn").attr("title" ,"隐藏菜单");
				$("#showHideBtn img").attr("src" ,"images/icon/icon003.gif");
			});
		},
		function () {
			$("#recordDiv").hide('slow' ,function () {
				$("#showHideBtn").attr("title" ,"显示菜单");
				$("#showHideBtn img").attr("src" ,"images/icon/icon001.gif");
			});
		}
	);

	$("#presentation").change(function () {
		if ($(this).val() == 1) {
			presentation = 1;
			unbindCheckFun();
		} else {
			//清空选择(保留第一个选中)
			var firstCheck = true;
			$(".checkItems").each(function () {
				if ($(this).attr("checked")) {
					if (firstCheck) {
						firstCheck = false;
						return true; //跳出循环
					}
					$(this).attr("checked" ,false);
				}
			});
			presentation = 2;
			bindCheckFun(); //绑定改为单选的事件
		}
	});

	$(".checkItems").each(function () {
		var objId = $(this).val() + 'Check';
		if ($("#" + objId).val() == 1) {
			$(this).attr('checked' ,true);
		}
	});
	$("#presentation option").each(function () {
		if ($(this).val() == $("#presentation").attr("val")) {
			$(this).attr("selected" ,true);
		}
	});
    $("#unit option").each(function () {
        if ($(this).val() == $("#unit").attr("val")) {
            $(this).attr("selected" ,true);
        }
    });
	$("#presentation").trigger("change");

	reloadGrid(); //加载视图
});

//加载表格
function reloadGrid() {
    //单位显示
    if($("#unit").val() == '2'){
        $("#unitView").html("(单位：万元)");
    }else{
        $("#unitView").html("(单位：元)");
    }
	//检查数据的有效性
	if (!checkData()) {
		return false;
	}

//	gridRecord(); //用户勾选记录保存

	colModel = [];
	parentColName = []; //复合指标名字数组
	var keyName = []; //键名
	var parentCode = []; //大标题数组下标
	$(".checkItems").each(function () {
		if ($(this).attr("checked")) {
			parentColName.push($(this).attr('val'));
			keyName.push($(this).val());
		}
	});

	//初始化
	var fixedThead = eval("(" + $("#fixedThead").text() + ")");

	for (var i = 0 ;i < fixedThead.length ;i++) {
		//设置宽度
		if (parseInt(fixedThead[i].width) == fixedThead[i].width) {
			var width = parseInt(fixedThead[i].width);
		} else {
			var width = fixedThead[i].width ? fixedThead[i].width : '100px';
		}

		colModel.push({
			name : fixedThead[i].name,
			display : fixedThead[i].display,
			width : width,
            align: 'left'
		});
		parentCode.push(fixedThead[i].name);
		parentCode.push(fixedThead[i].code);
	}

	for (var i = 0 ;i < parentColName.length ;i++) {
		colModel.push({
			name : keyName[i] + 'tar',
			display : '目标',
			width : '120px',
			parentCol : i,
			align : 'right',
			process : function (v) {
				if (!isNaN(v)) {
					return moneyFormat2(v); //金额千分位
				} else  {
					if(!v)
						return "-";
					else
						return v;
				}
			}
		});
		colModel.push({
			name : keyName[i] + 'imp',
			display : '实现',
			width : '120px',
			parentCol : i,
			align : 'right',
			process : function (v) {
				if (!isNaN(v)) {
					return moneyFormat2(v); //金额千分位
				} else  {
					if(!v)
						return "0.00";
					else
						return v;
				}
			}
		});

		//分月显示
		if (presentation == 2) {
			var monthArr = getMonthArr($(startMonth).val() ,$(endMonth).val());
			for (var j = 0 ;j < monthArr.length ;j++) {
				colModel.push({
					name : keyName[i] + j,
					display : monthArr[j] + '月',
					width : '120px',
					parentCol : i,
					align : 'right',
					process : function (v) {
						if (!isNaN(v)) {
							return moneyFormat2(v); //金额千分位
						} else  {
							return v;
						}
					}
				});
			}
		}
	}

	var colCode = []; //数组下标
	for (var i = 0 ;i < colModel.length ;i++) {
		colCode.push(colModel[i].name);
	}
	var colCodeStr = colCode.toString();
	var parentCodeStr = parentCode.toString() + ',' + keyName.toString();
	$("#productGrid").empty().yxeditgrid({
		url : $("#tableUrl").text(),
		param : {
			objCode : $("#objCode").val(),
			startMonth : $("#startMonth").val(),
			endMonth : $("#endMonth").val(),
			presentation : $("#presentation").val(),
            unit : $("#unit").val(),
			colCode : colCodeStr,
			parentCode : parentCodeStr
		},
		type : 'view',
		colModel : colModel
	});

	tableHead('productGrid' ,colModel);

    //获取区间内的最大版本号
    $.ajax({
        type : 'POST',
        url : '?model=contract_conproject_conproject&action=getMaxNum',
        data: {'endMonth': $("#endMonth").val()},
        async: false,
        success: function (data) {
            $("#version").html(" 版本： V"+data);
        }
    });
}

//根据两个月份返回包含的月份数组
function getMonthArr(startMonth ,endMonth) {
	var startMonthObj = new Date(startMonth.substr(0 ,4) ,startMonth.substr(5 ,2));
	var endMonthObj = new Date(endMonth.substr(0 ,4) ,endMonth.substr(5 ,2));
	var yearStart = startMonthObj.getFullYear();
	var yearEnd = endMonthObj.getFullYear();
	var monthStart = startMonthObj.getMonth();
	var monthEnd = endMonthObj.getMonth();
	var monthArr = [];
	if (yearStart < yearEnd) {
		var isFirst = true;
		var tmpYear = yearStart;
		var tmpMonth = monthStart;
		for (var i = tmpYear ;i <= yearEnd ;i++) {
			if (isFirst) { //首年
				for (var j = tmpMonth ;j <= 12 ;j++) {
					monthArr.push(j);
				}
				isFirst = false;
			} else if (i != yearEnd) { //中间年
				for (var j = 1 ;j <= 12 ;j++) {
					monthArr.push(j);
				}
			} else { //末年
				for (var j = 1 ;j <= monthEnd ;j++) {
					monthArr.push(j);
				}
			}
		}
	} else {
		for (var i = monthStart ;i <= monthEnd ;i++) {
			monthArr.push(i);
		}
	}

	return monthArr;
}

//导出Excel表格
function excelOut() {
	var colCode = []; //数组下标
	var parentCode = []; //大标题数组下标
	var keyName = []; //选中复合标题数组下标

	//初始化
	var fixedThead = eval("(" + $("#fixedThead").text() + ")");
	for (var i = 0 ;i < fixedThead.length ;i++) {
		parentCode.push(fixedThead[i].name);
		parentCode.push(fixedThead[i].code);
	}
	$(".checkItems").each(function () {
		if ($(this).attr("checked")) {
			keyName.push($(this).val());
		}
	});
	var parentCodeStr = parentCode.toString() + ',' + keyName.toString();

	for (var i = 0 ;i < colModel.length ;i++) {
		colCode.push(colModel[i].name);
	}
	var colCodeStr = colCode.toString();

	var colCodeStrHtml = '<input type="hidden" name=colCode value="' + colCodeStr + '"/>';
	var parentCodeStrHtml = '<input type="hidden" name=parentCode value="' + parentCodeStr + '"/>';
	var colModelHtml = '<input type="hidden" name=colModel value=\'' + JSON.stringify(colModel) + '\'/>';
	var parentColNameHtml = '<input type="hidden" name=parentColName value="' + parentColName + '"/>';
	var objCodeHtml = '<input type="hidden" name=objCode value="' + $("#objCode").val() + '"/>';
	var startMonthHtml = '<input type="hidden" name=startMonth value="' + $("#startMonth").val() + '"/>'; //开始月份
	var endMonthHtml = '<input type="hidden" name=endMonth value="' + $("#endMonth").val() + '"/>'; //结束月份
	var presentationHtml = '<input type="hidden" name=presentation value="' + $("#presentation").val() + '"/>'; //显示方式
	$("#form1").append(colCodeStrHtml)
			.append(parentCodeStrHtml)
			.append(colModelHtml)
			.append(parentColNameHtml)
			.append(objCodeHtml)
			.append(startMonthHtml)
			.append(endMonthHtml)
			.append(presentationHtml)
			.submit();
}

//保存用户勾选记录
function gridRecord() {
	var recordData = {};
	//获取指标名称
	$(".checkItems").each(function () {
		recordData[$(this).val()] = $(this).attr('checked') ? 1 : 0
	});

	recordData["startMonth"] = $("#startMonth").val();
	recordData["endMonth"] = $("#endMonth").val();
	recordData["presentation"] = $("#presentation").val();
	recordData["objCode"] = $("#objCode").val();

	$.ajax({
		type : 'POST',
		url : '?model=contract_gridreport_gridrecord&action=saveRecord',
		data : recordData
	});
}

//检查数据的有效性
function checkData() {
	//检查时间区间的有效性
	if ($("#presentation").val() == 2) {
		if (!checkTimeInterval()) {
			return false;
		}
	}

	//检查呈现视图的有效性
	if (!checkView()) {
		return false;
	}

	return true;
}

//检查时间区间的有效性
function checkTimeInterval() {
	var startMonth = $("#startMonth").val();
	var endMonth = $("#endMonth").val();
	if (startMonth > endMonth || startMonth == '' || endMonth == '') {
		alert('时间区间有误！');
		return false;
	}
	return true;
}

/**视图的显示方式：根据用户勾选值来显示；
 * 如呈现方式选择为“分月”，由于受视图限制该情况下只允许勾选1种指标；而“累计”方式，不做数量限制
 */
function checkView() {
	var checkNum = 0;
	$(".checkItems").each(function () {
		if ($(this).attr('checked')) {
			checkNum++;
		}
	});

	if (checkNum == 0) {
		alert('至少选择一种指标统计！');
		return false;
	} else if ($("#presentation").val() == 2) {
		if (checkNum > 1) {
			alert('按月份呈现只能勾选一种指标！');
			return false;
		}
	}
	return true;
}

//改为单选
function bindCheckFun() {
	$(".checkItems").each(function () {
		$(this).change(function () {
			var checkVal = $(this).val();
			if ($(this).attr("checked")) {
				$(".checkItems").each(function () {
					if ($(this).val() != checkVal) {
						$(this).attr("checked" ,false);
					}
				});
			}
		});
	});
}

//恢复多选
function unbindCheckFun() {
	$(".checkItems").each(function () {
		$(this).unbind("change");
	});
}