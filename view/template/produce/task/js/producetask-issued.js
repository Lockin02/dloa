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
		if (confirm("�����뵥û��δ�´���Ϣ!")) {
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
// ɾ��
function delItem(obj) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
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
 * ��У��
 */
function checkForm() {
	var itemSize = $("#itembody tr").size();
	var items = 0;
	for ( var i = 0; i < itemSize; i++) {
		if ($("#isDelTag" + i).val() != "1") {
			if (parseInt($("#taskNum" + i).val()) > parseInt($("#notExeNum" + i)
					.val())) {
				alert("����" + $("#productCode" + i).val() + "�´���������δ�´�����!");
				return false;
			}
			items++;
	
		if ($("#planStartDate" + i).val() == "") {
			alert("�ƻ���ʼʱ�䲻��Ϊ�գ�");
			return false;
		}
		if ($("#planEndDate" + i).val() == "") {
			alert("�ƻ�����ʱ�䲻��Ϊ�գ�");
			return false;
		}
		}
	}
	if (items == "0") {
		alert("��Ϣ���������޷��´");
	}
	return true;
}

/**
 * �����嵥�ƻ���ʼʱ��
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
 * �����嵥�ƻ���ʼʱ��
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
 * �����������ڼ乤����
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

	// ����ʼ�ն������������� javascript�����ڴ�0��ʼ������+1������
	var sDayofWeek = from.getDay() + 1;
	var workdays = 0;
	// ������������֮��������������ķǼ���
	if (sDayofWeek > 1 && sDayofWeek < 7) {
		from.setDate(from.getDate() - (sDayofWeek % 7));
		workdays -= ((sDayofWeek - 2) > 0) ? sDayofWeek - 2 : 0;
	}
	var totalDays = (to.getTime() - from.getTime()) / (1000 * 60 * 60 * 24) + 1;
	workdays += Math.floor(totalDays / 7) * 5;
	// �������ʣ������
	if ((totalDays % 7 - 2) > 0) {
		workdays += (totalDays % 7 - 2);
	}
	return workdays;
}

/**
 * ���üƻ����ڼ��ƻ�������
 */
function setEstimateInfo(rowNum) {
	var workDay = calculateWorkDays($("#planStartDate" + rowNum).val(), $(
			"#planEndDate" + rowNum).val());
	$("#estimateDay" + rowNum).val(workDay);
	$("#estimateHour" + rowNum).val(workDay * 7);
}