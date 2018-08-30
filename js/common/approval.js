/**�������js*/
function getQueryString(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	var r = window.location.search.substr(1).match(reg);
	if (r != null)
		return unescape(r[2]);
	return null;
}
$(function() {
	var itemType = $("#itemType").val();
	var pid = $("#pid").val();
	var isChange = $("#isChange").val();
	var gdbtable = getQueryString('gdbtable');
	if ($("#isChange").length == 0) {//�Ƿ��������������
		var isChange = 0;
	} else {
		var isChange = $("#isChange").val();
	}
	if ($("#appFormName").length == 0) {
		var appFormName = "";
	} else {
		var appFormName = $("#appFormName").val();
	}
	if ($("#isPrint").length == 0) {//�Ƿ��������������
		var isPrint = 0;
	} else {
		var isPrint = $("#isPrint").val();
	}

	$.post("?model=common_approvalView&action=getApproval", {
		pid : pid,
		itemtype : itemType,
		isChange : isChange,
		gdbtable : gdbtable,
		isPrint : isPrint
	}, function(data) {
		if (data == 0) { //û���������ʱ����ֵΪ����
			var datahtml = "<tr><td></td></tr>";
		}
		if (data != 0) {
			if(isPrint == "1"){
				var $html = $('<table width="100%"  class="form_in_table" id="approvalTable">'
						+ '<thead>'
						+ '<tr  > '
						+ '<td width="100%" colspan="6" class="form_header"><B>'
						+ appFormName
						+ '�������</B></td>'
						+ '</tr>'
						+ '<tr class="main_tr_header">'
							+ '<th width="12%">������</th>'
							+ '<th width="12%">������</th>'
							+ '<th width="18%" nowrap="nowrap">��������</th>'
							+ '<th width="10%">�������</th>'
							+ '<th>�������</th>'
						+ '</tr>' + '</thead>');
			}else{
				var $html = $('<table width="100%"  class="form_in_table" id="approvalTable">'
						+ '<thead>'
						+ '<tr  > '
						+ '<td width="100%" colspan="6" class="form_header"><B>'
						+ appFormName
						+ '�������</B></td>'
						+ '</tr>'
						+ '<tr class="main_tr_header">'
							+ '<th width="12%">���</th>'
							+ '<th width="12%">������</th>'
							+ '<th width="12%">������</th>'
							+ '<th width="18%" nowrap="nowrap">��������</th>'
							+ '<th width="10%">�������</th>'
							+ '<th>�������</th>'
						+ '</tr>' + '</thead>');
			}
			var $html2 = $('</table>');
			if (data == 0) {
				var $tr = $(datahtml);
			} else {
				var $tr = $(data);
			}
			$html.append($tr);
			$html.append($html2);
			$("#approvalView").append($html);
		}
	});
	if (isChange == '1') {
		//���һ�α������
		$.post("?model=common_approvalView&action=changeGetApproval", {
			pid : pid,
			itemtype : itemType,
			isChange : isChange,
			gdbtable : gdbtable
		}, function(data) {
			if (data == 0) { //û���������ʱ����ֵΪ����
				var datahtml = "<tr><td></td></tr>";
			}
			if (data != 0) {
				var $html = $('<table width="100%"  class="form_in_table" id="approvalChangeTable">'
						+ '<thead>'
						+ '<tr  > '
						+ '<td width="100%" colspan="6" class="form_header"><B>���һ�α���������</B></td>'
						+ '</tr>'
						+ '<tr class="main_tr_header">'
						+ '<th width="12%">������</th>'
						+ '<th width="12%">������</th>'
						+ '<th width="18%" nowrap="nowrap">��������</th>'
						+ '<th width="10%">�������</th>'
						+ '<th>�������</th>'
						+ '</tr>' + '</thead>');
				var $html2 = $('</table>');
				if (data == 0) {
					var $tr = $(datahtml);
				} else {
					var $tr = $(data);
				}
				$html.append($tr);
				$html.append($html2);
				$("#approvalView").append($html);
			}
		});
	} else if (isChange == 'all') {
		var $html = $('<table width="100%"  class="form_in_table" id="approvalChangeTable">'
				+ '<thead>'
				+ '<tr class="main_tr_header">'
				+ '<th width="12%">���</th>'
				+ '<th width="12%">������</th>'
				+ '<th width="12%">������</th>'
				+ '<th width="18%" nowrap="nowrap">��������</th>'
				+ '<th width="10%">�������</th>'
				+ '<th>�������</th>'
				+ '</tr>'
				+ '</thead>');
		var $html2 = $('</table>');
		//���б������
		$.post("?model=common_approvalView&action=getApprovalAll", {
			pid : pid,
			itemtype : itemType,
			isChange : isChange,
			gdbtable : gdbtable,
			isPrint : isPrint
		}, function(data) {
			if (data == 0) { //û���������ʱ����ֵΪ����
				var datahtml = "<tr><td></td></tr>";
			}
			if (data != 0) {
				if (data == 0) {
					var $tr = $(datahtml);
				} else {
					var $tr = $(data);
				}
				$html.append($tr);
				$("#appView").append($html);
			}
		});
		$.post("?model=common_approvalView&action=changeGetApprovalAll", {
			pid : pid,
			itemtype : itemType,
			isChange : isChange,
			gdbtable : gdbtable
		}, function(data) {
			if (data == 0) { //û���������ʱ����ֵΪ����
				var datahtml = "<tr><td></td></tr>";
			}
			if (data != 0) {

				if (data == 0) {
					var $tr = $(datahtml);
				} else {
					var $tr = $(data);
				}
				$html.append($tr);
				$html.append($html2);
				$("#appView").append($html);
			}
		});
	}
});