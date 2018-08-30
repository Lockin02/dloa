// 选择产品类型

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

// 扩展字段 隐藏控制
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
	 * 名称验证
	 */
	$("#dataName").formValidator({
		onshow : "请输入名称",
		onfocus : "名称至少1个字符，最多100个字符",
		oncorrect : "您输入的名称有效"
	}).inputValidator({
		min : 1,
		max : 100,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "名称两边不能为空"
		},
		onerror : "您输入的名称不合法，请重新输入"
	});

	/**
	 * 编码验证
	 */
	$("#dataCode").formValidator({
		onshow : "请输入编号",
		onfocus : "编号至少1个字符，最多50个字符",
		oncorrect : "您输入的编号有效"
	}).inputValidator({
		min : 1,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "编号两边不能为空"
		},
		onerror : "您输入的编号不合法，请重新输入"
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

			alert("服务器没有返回数据，可能服务器忙，请重试");
		},
		onerror : "该编号不可用，请更换",
		onwait : "正在对编号进行合法性校验，请稍候..."
	});

	/**
	 * 编码验证 (编辑)
	 */
	$("#dataCodeEdit").formValidator({
		onshow : "请输入编号",
		onfocus : "编号至少1个字符，最多50个字符",
		oncorrect : "您输入的编号有效"
	}).inputValidator({
		min : 1,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "编号两边不能为空"
		},
		onerror : "您输入的编号不合法，请重新输入"
	});
	//客户那边会出现文本框只读的情况，我们这边不会，先这么处理看看客户那边还会不会
	$("input").removeAttr("readonly");
	$("input").removeAttr("disabled");
});