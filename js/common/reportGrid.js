//处理复合表头
function tableHead(objId ,colModel) {
	var trObj = $("#" + objId + " > table > thead > tr:eq(0)");
	var tdObj = trObj.children();
	var detailArr = [];
	var detailStateArr = [];
	var parentCol;
	var trHTML = "<tr class='main_tr_header'>";

	for (var i = 0; i < colModel.length ;i++) {
		if (colModel[i].parentCol >= 0) {
			parentCol = colModel[i].parentCol;
			if (!detailArr[parentCol]) {
				detailArr[parentCol] = [];
			}
			detailArr[parentCol].push(colModel[i].display);
			trHTML += "<th style='background-color:SkyBlue'><div class='divChangeLine'>" + colModel[i].display + "</div></th>"
		}
	}

	tdObj.each(function (i) {
		for (var j = 0 ;j < detailArr.length ;j++) {
			var isHave = false;
			if ($.inArray($(this).text() ,detailArr[j]) == -1) {
				$(this).attr("rowspan" ,2);
				isHave = true;
			}
			if (!isHave) {
				var parentCol = colModel[i - 1].parentCol;
				if (detailStateArr[parentCol] != true) { //是否第一次
					detailStateArr[parentCol] = true;
					$(this).after("<th style='background-color:SkyBlue' colspan='" + detailArr[parentCol].length + "'><div class='divChangeLine'>" + parentColName[parentCol] + "</div></th>");
				}
				$(this).remove();
			}
		}
	});
	trObj.after(trHTML + "</tr>");
}