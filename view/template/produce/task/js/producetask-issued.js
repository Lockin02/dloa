$(document).ready(function() {
	$("#applyUserName").yxselect_user({
		hiddenId : 'applyUserCode',
		formCode : 'produceIssuedUser'
	})
	$("#tableDiv").width(document.documentElement.clientWidth - 30);

	validate({
		"chargeUserName" : {
			required : true
		}
	});

	var itemSize = $("#itembody tr").size();
	if (itemSize == "0") {
		if (confirm("该申请单没有未下达信息!")) {
			window.close();
		}
	}
})

function reloadItemCount() {
	var i = 1;
	$("#itembody").children("tr").each(function() {
		if ($(this).css("display") != "none") {
			$(this).children("td").eq(1).text(i);
			i++;

		}
	})
}
// 删除
function delItem(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		$(obj).parent().parent().hide();
		$(obj).parent().append(
				'<input type="hidden" name="producetask[items][' + rowNo
						+ '][isDelTag]" value="1" id="isDelTag' + rowNo
						+ '" />');
		reloadItemCount();
	}
}

/**
 * 表单校验
 */
function checkForm() {
	var itemSize = $("#itembody tr").size();
	var items = 0;
	for ( var i = 0; i < itemSize; i++) {
		if ($("#isDelTag" + i).val() != "1") {
			if (parseInt($("#taskNum" + i).val()) > parseInt($("#notExeNum" + i)
					.val())) {
				alert("物料" + $("#productCode" + i).val() + "下达数量大于未下达数量!");
				return false;
			}
			items++;
	
		if ($("#planStartDate" + i).val() == "") {
			alert("计划开始时间不能为空！");
			return false;
		}
		if ($("#planEndDate" + i).val() == "") {
			alert("计划结束时间不能为空！");
			return false;
		}
		}
	}
	if (items == "0") {
		alert("信息不完整，无法下达！");
	}
	return true;
}

/**
 * 设置清单计划开始时间
 */
function setStartDate() {
	var itemSize = $("#itembody tr").size();
	for ( var i = 0; i < itemSize; i++) {
		$("#planStartDate" + i).val($("#planStartDate").val());
		// $("#planStartDate" + i).trigger("blur");
	}
	for ( var i = 0; i < itemSize; i++) {
		$("#planStartDate" + i).trigger("blur");
	}
}

/**
 * 设置清单计划开始时间
 */
function setEndDate() {
	var itemSize = $("#itembody tr").size();
	for ( var i = 0; i < itemSize; i++) {
		$("#planEndDate" + i).val($("#planEndDate").val());
	}
	for ( var i = 0; i < itemSize; i++) {
		$("#planEndDate" + i).trigger("blur");
	}
}

/**
 * 计算两个日期间工作日
 * 
 * @param fromStr
 * @param toStr
 * @returns {Number}
 */
function calculateWorkDays(fromStr, toStr) {
	var from = new Date();
	var to = new Date();
	var reg = new RegExp("-", "g");
	var nfromStr = fromStr.replace(reg, "/");
	var ntoStr = toStr.replace(reg, "/");
	var fromTime = Date.parse(nfromStr);
	var toTime = Date.parse(ntoStr);
	from.setTime(fromTime);
	to.setTime(toTime);
	if (from.getTime() > to.getTime()) {
		return 0;
	}

	// 把起始日都修正到星期六 javascript的星期从0开始，所以+1来处理
	var sDayofWeek = from.getDay() + 1;
	var workdays = 0;
	// 修正到星期六之后，再修正多出来的非假日
	if (sDayofWeek > 1 && sDayofWeek < 7) {
		from.setDate(from.getDate() - (sDayofWeek % 7));
		workdays -= ((sDayofWeek - 2) > 0) ? sDayofWeek - 2 : 0;
	}
	var totalDays = (to.getTime() - from.getTime()) / (1000 * 60 * 60 * 24) + 1;
	workdays += Math.floor(totalDays / 7) * 5;
	// 修正最后剩余天数
	if ((totalDays % 7 - 2) > 0) {
		workdays += (totalDays % 7 - 2);
	}
	return workdays;
}

/**
 * 设置计划工期及计划工作量
 */
function setEstimateInfo(rowNum) {
	var workDay = calculateWorkDays($("#planStartDate" + rowNum).val(), $(
			"#planEndDate" + rowNum).val());
	$("#estimateDay" + rowNum).val(workDay);
	$("#estimateHour" + rowNum).val(workDay * 7);
}