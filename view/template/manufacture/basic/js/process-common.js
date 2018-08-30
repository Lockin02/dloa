$(document).ready(function () {
	var configInfoObj = $("#productConfigInfo");
	configInfoObj.yxeditgrid({
		objName: 'process[info]',
		colModel: [{
			display: '',
			name: 'column0',
			process: function ($input) {
				$input.after('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}
		}, {
			display: '',
			name: 'column1',
			process: function ($input) {
				$input.after('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}
		}, {
			display: '',
			name: 'column2',
			process: function ($input) {
				$input.after('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}
		}, {
			display: '',
			name: 'column3',
			process: function ($input) {
				$input.after('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}
		}],
		event: {
			addRow: function (e, rowNum) {
				var theadObj = $("#productConfigInfo > table > thead > tr > th > .divChangeLine");
				var theadNum = theadObj.length;
				var tbodyNum = $("#productConfigInfo > table > tbody > tr:eq(" + rowNum + ") > td").length - 2;

				if (rowNum > 0) {
					for (var i = 0; i < 4; i++) { //������ɾ��Ĭ���е����
						if (!$("#productConfigInfo_cmp_thead" + i)[0]) {
							$("#productConfigInfo_cmp_column" + i + rowNum).parent().remove();
						}
					}
				}

				theadObj.each(function () {
					var columnId = $(this).children().children(":eq(0)").attr('id') + ''; //ת���ַ���
					var columnNum = columnId.substring(27);
					if (columnNum >= 4) {
						if ($("#productConfigInfo_cmp_column" + columnNum + rowNum)) {
							var columnHtml = '<td style="text-align: center;"><input type="text" ' + 'id="productConfigInfo_cmp_column'
							+ columnNum + rowNum + '" class="txtmiddle" ' + 'name="process[info]['
							+ rowNum + '][column' + columnNum + ']">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
							$("#productConfigInfo > table > tbody > tr:eq(" + rowNum + ")").append(columnHtml);
						}
					}
				});
			}
		}
	});

	$("#productConfigInfo > table > thead > tr > th > .divChangeLine").each(function (num) {
		//����Զ����ͷ
		var htmlStr = '<nobr><input type="text" style="background:transparent;border-color:green;color:#2E5CB8" '
					+ 'id="productConfigInfo_cmp_thead' + num + '"'
					+ ' class="txtmiddle" name="process[thead][' + num + ']">'
					+ '<span class="removeBn" onclick="removeColumn(this,' + num + ');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></nobr>';
		$(this).append(htmlStr);

		//ĩβ��������а�ť
		if (num + 1 == $("#productConfigInfo > table > thead > tr > th > .divChangeLine").length) {
			var addHtml = '<th width="10"><span class="addBn" onclick="addColumn(this,' + num + ');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></th>';
			$(this).parent().parent().append(addHtml);
		}
	});

	validate({
		"templateName": {
			required: true
		}
	});
});

//�Ƴ���
function removeColumn(obj ,num) {
	$(obj).parent().parent().parent().remove();
	$("#productConfigInfo > table > tbody > tr").each(function (i) {
		$("#productConfigInfo_cmp_column" + num + i).parent().remove();
	});
}

//������
function addColumn(obj ,num) {
	num++;
	var htmlStr = '<th><div class="divChangeLine"><nobr><input type="text" style="background:transparent;border-color:green;color:#2E5CB8" '
				+ 'id="productConfigInfo_cmp_thead' + num + '"'
				+ ' class="txtmiddle" name="process[thead][' + num + ']">'
				+ '<span class="removeBn" onclick="removeColumn(this,' + num + ');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></nobr></div></th>';
	var addHtml = '<span class="addBn" onclick="addColumn(this,' + num + ');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
	$(obj).parent().before(htmlStr).empty().append(addHtml);

	$("#productConfigInfo > table > tbody > tr").each(function (i) {
		var addHtml = '<td style="text-align: center;"><input type="text" '
					+ 'id="productConfigInfo_cmp_column' + num + i + '" class="txtmiddle" '
					+ 'name="process[info][' + i + '][column' + num + ']">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
		$(this).append(addHtml);
	});
}