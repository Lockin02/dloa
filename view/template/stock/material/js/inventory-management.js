$(function () {
	function inventory_target(){
	
	var inventoryTarget = $("#inventort_list"),
		importDialog = $("#import_dialog"),
	    url = "index1.php?model=" + model + "&action=get_inventory_list";
	  
	inventoryTarget.datagrid({
		title: "生产部库存",
		url: url,
		columns: [[
	        { field: "stock_code", title: "OA编码", align: "center", width: 150 },
	        { field: "stock_model", title: "物料类型", align: "center", width: 150 },
	        { field: "stock_name", title: "物料名称", align: "center", width: 150 },
	        { field: "stock_packaging", title: "封装", align: "center", width: 150 },
	        { field: "stock_factory", title: "品牌", align: "center", width: 150 },
	        { field: "actNum", title: "生产部库存", align: "center", width: 150, editor: { type: "text" } },
	        { field: "operation", title: "编辑", align: "center", width: 150, formatter: function (value, row, index) {
	        	var str = "";
	        	if(row.editing) {
	        		str += "<a href=\"#\" onclick=\"saveRow(" + index + ")\">保存</a>";
	        		str += "&nbsp;&nbsp;";
	        		str += "<a href=\"#\" onclick=\"cancelRow(" + index + ")\">取消</a>";
	        	} else {
	        		str += "<a href=\"#\" onclick=\"deleteRow(" + index + ", " + row["stock_code"] + ")\">删除</a>";
	        		str += "&nbsp;&nbsp;";
	        		str += "<a href=\"#\" onclick=\"editRow(" + index + ", " + row["stock_code"] + ")\">编辑</a>";
	        	}
	        	
	        	return str;
	        } }
		]],
		singleSelect: true,
		fitColumns: true,
		toolbar: [{
			iconCls: "icon-add",
			handler: function () {
				importDialog.removeClass("hidden").dialog({
					title: "导入库存",
					closed: false,
					width: 350,
					height: 150
				});
			},
			text: "导入库存"
		}],
		pageSize: 20,
		pagination: true,
		onLoadSuccess: function () {
		},
		onBeforeEdit: function (index, row) {
			row.editing = true;
			updateActions(index);
		},
		onAfterEdit: function(index, row) {
			editInventory(row);
			
	        row.editing = false;
	        updateActions(index);
	    },
	    onCancelEdit: function(index, row) {
	        row.editing = false;
	        updateActions(index);
	    },
	});
	}
	function updateActions(index) {
		inventoryTarget.datagrid("updateRow", { index: index, row: { operation: "" } });
	}
	
	function editInventory(data) {
		var url = "index1.php?model=stock_material_management&action=inventory_edit";
		$.post(url, { data: data }, function (json) {
			if(json.result !== "1") {
				$.messager.alert("提示信息", json.result);
				return;
			}
			
			$.messager.alert("提示信息", "编辑成功");
		}, "json");
	}
	inventory_target();
});
function c_import(){
	$("#import_dialog").dialog("close");
	alert('导入成功！');
}
function editRow(index) {
	$("#inventort_list").datagrid("beginEdit", index);
}

function cancelRow(index) {
	$("#inventort_list").datagrid("cancelEdit", index);
}

function saveRow(index) {
	$("#inventort_list").datagrid("endEdit", index);
}

function deleteRow(index, id) {
	$.messager.confirm("提示", "确认删除?", function (result) {
		if(!result) {
			return;
		}
		
		$.post("index1.php?model=stock_material_management&action=delete_inventory", { id: id }, function (json) {
			if(json.error != "") {
				$.messager.alert("提示", "删除失败");
				return;
			}
			
			$.messager.alert("提示", "删除成功");
			$("#inventort_list").datagrid("deleteRow", index);
		}, "json");
	});
}