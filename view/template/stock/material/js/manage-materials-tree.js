function materialsTree() {
//	var tableName = "configuration_table",
//		action = "load_configuration_list",
//		conditions = "id=";

	$("#materials_tree").tree({
		url: publicUrl + "&action=load_materials_tree",
		checkbox: false,
		onClick: function(node) {
			var id = node.id.split("_")[0];
//			productsName = {};
//			if (typeof(node.state) == "undefined") {
//				var condition = "id=" + node.id,
//					type =  node.id.split("_")[1];
//				
//				if(type == 0) {
//					$("#moreProductConfigName").val("");
//				} else {
//					$("#moreProductConfigName").val(node.text);
//				}
//				
//				productsName[node.id] = node.text;
//				
//				$("#current_product_id").val(node.id);
//				
//				
//				var url = publicUrl + '&action=' + action + '&' + conditions;
//				$('#configuration_table').datagrid({
//					title: node.text
//				});
//				
				getBomDetail(id,'view');
//			}
		},
		onLoadSuccess: function (t, datas) {
			conditions = "id=";
			
			if(typeof datas == "undefined" || typeof datas[0] == "undefined" || typeof datas[0]["children"] == "undefined" || typeof datas[0]["children"][0] == "undefined") {
				return;
			}
		}
	});
}

function removeBom() {
	$.messager.confirm("警告", "确认删除", function (result) {
		if(!result) {
			return;
		}
		
		var nodes = $("#materials_tree").tree("getChecked"),
			ids = getIds(nodes);
	
		$.post("index1.php?model=stock_material_management&action=delete_excel_more", { ids: ids }, function () {
			$.messager.alert("提示信息", "删除成功");
			materialsTree();
		});
		
		function getIds() {
			var bomIds = [];
			
			getId(nodes);
			
			return bomIds;
			
			function getId(nodes) {
				for(var i in nodes) {
					var n = nodes[i];
					if(typeof n.children != "undefined" && n.children.length != 0) {
						getId(n.children);
					} else {
						bomIds.push(n.id);
					}
				}
			}
		}
	});
}