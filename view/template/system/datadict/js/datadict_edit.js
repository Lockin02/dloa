// ѡ���Ʒ����

$(function() {
	$("#parentName").yxcombotree({
		hiddenId : 'parentId',
		treeOptions : {
			event : {
				"node_click" : function(event, treeId, treeNode) {
					// alert(treeId)
				},
				"node_change" : function(event, treeId, treeNode) {
					// alert(treeId)
				}
			},
			url : "?model=system_datadict_datadict&action=getChildren"
		}
	});
});

// ��չ�ֶ� ���ؿ���
function checkDiv(obj) {

	// var addressdiv=document.getElementById("mydiv");
	if (obj.value == "n") {
		document.getElementById("mydiv").style.display = "none";

	} else {
		document.getElementById("mydiv").style.display = "";
	}
}

$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		// autotip: true,
		onerror : function(msg) {
			// alert(msg);
		}

	});

	/**
	 * ������֤
	 */
	$("#dataName").formValidator({
		onshow : "����������",
		onfocus : "��������1���ַ������100���ַ�",
		oncorrect : "�������������Ч"
	}).inputValidator({
		min : 1,
		max : 100,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "�������߲���Ϊ��"
		},
		onerror : "����������Ʋ��Ϸ�������������"
	});

	/**
	 * ������֤
	 */
	$("#dataCode").formValidator({
		onshow : "��������",
		onfocus : "�������1���ַ������50���ַ�",
		oncorrect : "������ı����Ч"
	}).inputValidator({
		min : 1,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "������߲���Ϊ��"
		},
		onerror : "������ı�Ų��Ϸ�������������"
	}).ajaxValidator({
		type : "get",
		url : "index1.php",
		data : "model=system_datadict_datadict&action=ajaxDataCode",
		datatype : "json",

		success : function(data) {

			if (data == "1") {
				return true;
			} else {
				return false;
			}
		},

		// buttons: $("#submitSave"),
		error : function() {

			alert("������û�з������ݣ����ܷ�����æ��������");
		},
		onerror : "�ñ�Ų����ã������",
		onwait : "���ڶԱ�Ž��кϷ���У�飬���Ժ�..."
	});

	/**
	 * ������֤ (�༭)
	 */
	$("#dataCodeEdit").formValidator({
		onshow : "��������",
		onfocus : "�������1���ַ������50���ַ�",
		oncorrect : "������ı����Ч"
	}).inputValidator({
		min : 1,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "������߲���Ϊ��"
		},
		onerror : "������ı�Ų��Ϸ�������������"
	});
	//�ͻ��Ǳ߻�����ı���ֻ���������������߲��ᣬ����ô�������ͻ��Ǳ߻��᲻��
	$("input").removeAttr("readonly");
	$("input").removeAttr("disabled");
});