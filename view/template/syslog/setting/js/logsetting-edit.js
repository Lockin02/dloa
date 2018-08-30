$(document).ready(function() {

	$.formValidator.initConfig({
				theme : "Default",
				submitOnce : true,
				formID : "form1",
				onError : function(msg, obj, errorlist) {
					alert(msg);
				}
			});


	/** 验证业务名称 * */
	$("#businessName").formValidator({
				onShow : "请输入业务名称",
				onFocus : "业务名称不能为空",
				onCorrect : "业务名称有效"
			}).inputValidator({
				min : 1,
				max : 50,
				onError : "业务名称不能为空，请重新输入"
			})

	/** 验证业务主键字段名 * */
	$("#pkName").formValidator({
				onShow : "请输入业务主键字段名",
				onFocus : "业务主键字段名不能为空",
				onCorrect : "业务主键字段名有效"
			}).inputValidator({
				min : 0,
				max : 50,
				onError : "业务主键字段名不能为空，请重新输入"
			})
})

/**
 * 动态添加从表数据
 */
function addItems() {
	var mycount = parseInt($("#itemscount").val());
	var itemtable = document.getElementById("itemtable");
	i = itemtable.rows.length;
	oTR = itemtable.insertRow([i]);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "28px";
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png"  onclick="delItem(this);" title="删除行">';
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = mycount + 1;
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = '<select name="logsetting[items][' + mycount
			+ '][columnName]" id="columnName' + mycount
			+ '" class="select" ></select>';
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = '<input type="text" name="logsetting[items][' + mycount
			+ '][columnText]" id="columnText' + mycount + '" class="txt" />';
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = '<input type="text" name="logsetting[items][' + mycount
			+ '][columnDataType]" id="columnDataType' + mycount
			+ '" class="txt" />';
	$("#itemscount").val(parseInt($("#itemscount").val()) + 1);
	reloadColumnOption(mycount);
}

// 删除
function delItem(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="logsetting[items]['
				+ rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo
				+ '" />');
		reloadItemCount();
	}
}
/**
 * 初始化设置清单
 */
function reloadItems() {
	var itemscount = $('#itemscount').val();
	$("#itembody").empty();
	$('#itemscount').val(0);
	addItems();
}

/**
 * 重新计算清单序列号
 */
function reloadItemCount() {
	var i = 1;
	$("#itembody").children("tr").each(function() {
				if ($(this).css("display") != "none") {
					$(this).children("td").eq(1).text(i);
					i++;

				}
			})
}

/**
 * 重置列选择下列框
 * 
 */
function reloadColumnOption(mycount) {
	$.ajax({
				type : "POST",
				async : false,
				url : "?model=syslog_setting_logsetting&action=findTableColumn",
				data : {
					tableName : $("#tableName").val()
				},
				dataType : "json",
				success : function(result) {
					for (var i = 0; i < result.length; i++) {
						$("#columnName" + mycount).append("<option value='"
								+ result[i] + "'>" + result[i] + "</option>"); // 为Select追加一个Option(下拉项)
					}

				}
			})
}
