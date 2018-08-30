$(document).ready(function() {
//	operationGrid();
	materialsTree();

	$('#butAClassify').click(function(){
		$('#classifyName').val('');
		$('#C_parent').html(classify_sel);
		$('#C_remark').val('');
		$('#C_createName').val(createName);
		$('#C_createTime').val(createTime);
		$('#C_createId').val(createId);
		$('#C_id').val('');

		$('#classifyAdd').show();
		$('#classifyAdd').removeClass("hidden").dialog({
			title: "新增分类",
			closed: false,
			width: 900,
			height: 500
		});
	});
});

//function operationGrid() {
//	$('#operation_table').datagrid({
//		idField: 'id',
//		singleSelect: true,
//		toolbar: operationGridButton(),
//		pagination: false
//	});
//}

//function operationGridButton() {
//	var button = new Array();
//	button.push({
//		iconCls: 'icon-add',
//		text: '下载导入模板',
//		handler: function() {
//			openImport(true);
//		}
//	});
//	button.push({
//		iconCls: 'icon-add',
//		text: '导入模板',
//		handler: function() {
//			openImport(true);
//		}
//	});
//	return button;
//}

/*
 * 左侧树结构
 */

function materialsTree() {

	$("#materials_tree").tree({
		url: publicUrl + "&action=load_classify_tree",
		lines: true,
//		checkbox: true,
		onlyLeafCheck: true,
		onClick: function(node) {
			templateList(node.text,node.id);
//			operationGrid();
//			alert(node.id);
//			if (node.id) {
//				var id = node.id;
//				var url = publicUrl + '&action=load_parts_SF';
//				$.post(url, {id:id}, function(data) {
//					$('#parts_table').datagrid({
//						title: data[0]['name'] +' /'+ data[0]['code'] + ' <a href=# onclick=editSF('+id+');>编辑</a>',
//
//					});
//				},'json');
//				getConfig(node.id.split("_")[0]);
//			}
		},onLoadSuccess: function (t, datas) {
			templateList(datas[0].text,datas[0].id);// ID2194 默认显示根分类的物料模板
//			if(datas){
//				$('#materials_tree').html('');
//			}
		}
	});
}

function templateList( title , id ){

	var url = "index1.php" + publicUrl + "&action=get_template_list&id="+id;
	$("#template-list").datagrid({
		title: title,
		url: url,
		columns: [[
		 	{ field: "id", title: "id", hidden : true, width: 180},
	        { field: "templateName", title: "模板名称", align: "center", width: 150 },
	        { field: "createTime", title: "创建时间", align: "center", width: 150 },
	        { field: "createName", title: "创建人", align: "center", width: 150 },
	        { field: "updateTime", title: "修改时间", align: "center", width: 150 },
	        { field: "updateName", title: "修改人", align: "center", width: 150 },
	        { field: "remark", title: "备注", align: "center", width: 300 },
	        { field: "operation", title: "操作", align: "center", width: 150, formatter: function (value, row, index)
	        {
	        	var str = "";
	        	str += "<a href=\"#\" onclick=\"edit(" + row.id + ")\">编辑</a>";
	        	str += "&nbsp;&nbsp;";
	        	str += "<a href=\"#\" onclick=\"del('" + row.id + "')\">删除</a>";
				return str;
	        } }
		]],
		singleSelect: true,
		fitColumns: true,
		toolbar: [{
			iconCls: "icon-add",
			text: "查看分类",
			handler: function () {
				classifyView(id);
			}
		},{
			iconCls: "icon-add",
			text: "编辑分类",
			handler: function () {
				classifyEdit(id);
			}
		},{
			iconCls: "icon-add",
			text: "新增分类模板",
			handler: function () {
				$('#importTemp').show();
				$('#template-add').show();
				$('#template-add').removeClass("hidden").dialog({
					title: "新增分类模板",
					closed: false,
					width: 1000,
					height: 500
				});
				materialCode();
			}
		}],
		view: detailview,
	    detailFormatter: function(mainIndex, mainRow) {
	    	return '<div style="padding:2px"><table id="parts_grid_' + mainIndex + '"></table></div>';
	    },
		onExpandRow: function(mainIndex, mainRow) {

			var url = publicUrl + '&action=product&id=' + mainRow.id;
	    	$('#parts_grid_' + mainIndex).datagrid({
			url: url,
			fitColumns: true,
			singleSelect: true,
			rownumbers: true,
			height: 'auto',
			idField: 'id',
			columns: [
					[	{ field : 'id', title : 'id', hidden : true },
						{ field: 'productType', title: '物料类型', align: 'center', width: 100 },
						{ field: 'productCode', title: '物料编码', align: 'center', width: 100 },
						{ field: 'productName', title: '物料名称', align: 'center', width: 150 },
						{ field: 'pattern', title: '规格类型', align: 'center', width: 100 },
						{ field: 'unitName', title: '单位名称', align: 'center', width: 50 },
						{ field: 'num', title: '数量', align: 'center', width: 50 }
					]
				]
			});
		},
		pageSize: 20,
		pagination: true,
		fit: true,   //自适应大小
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
	    }
	});
}

function materialCode(){

	var itemsObj = $("#items");
	itemsObj.empty();
	$('#templateName').val('');
	$('#classify').val('');
	$('#remark').val('');
	$('#type').val('add');
	$('#createId').val(createId);
	$('#createName').val(createName);
	$('#createTime').val(createTime);
	itemsObj.yxeditgrid({
		objName : 'product[items]',
		isFristRowDenyDel : true,

		colModel : [{
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '物料类型Id',
			name : 'proTypeId',
			type : 'hidden'
		},{
			display : '物料类型',
			name : 'proType',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '物料编码',
			name : 'productCode',
			process : function ($input) {
				var rowNum = $input.data("rowNum");
				$input.yxcombogrid_product({
					hiddenId : 'items_cmp_productId' + rowNum,
					width : 500,
					height : 300,
					gridOptions : {
						showcheckbox : false,
						event : {
							row_dblclick : function(e ,row ,data) {
								itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productCode").val(data.productCode);
								itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productName").val(data.productName);
								itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"pattern").val(data.pattern);
								itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"unitName").val(data.unitName);
								itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"proType").val(data.proType);
								itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"proTypeId").val(data.proTypeId);
							}
						}
					}
				});
			}
		},{
			display : '物料名称',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		},{
			display : '规格型号',
			name : 'pattern',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '单位名称',
			name : 'unitName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '数量',
			name : 'num',
			tclass : 'txtshort',
			validation : {
				custom : ['onlyNumber']
			}
		}]
	});
}

function edit(id){
	var mark = true;
	var url = publicUrl + '&action=template';
	$.post(url, {id:id}, function(data) {
		if(data){

			$('#templateName').val(data.templateName);
			$('#classify').val(data.classify);
			$('#remark').val(data.remark);
			$('#createName').val(data.createName);
			$('#createTime').val(data.createTime);
			$('#type').val('edit');
			$('#id').val(data.id);
		}else{
			mark = false;
		}
	},'json');
	$('#importTemp').hide();
	$('#template-add').show();
	$('#template-add').removeClass("hidden").dialog({
		title: "编辑分类模板",
		closed: false,
		width: 1000,
		height: 400
	});

	var url =  publicUrl + '&action=product&id='+id;
	var itemsObj = $("#items");
	itemsObj.empty();
	$('#templateName').val('');
	$('#classify').val('');
	$('#remark').val('');
	itemsObj.yxeditgrid({
		objName : 'product[items]',
		isFristRowDenyDel : true,
		url:url,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '物料类型Id',
			name : 'proTypeId',
			type : 'hidden'
		},{
			display : '物料类型',
			name : 'proType',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '物料编码',
			name : 'productCode',
			process : function ($input) {
				var rowNum = $input.data("rowNum");
				$input.yxcombogrid_product({
					hiddenId : 'items_cmp_productId' + rowNum,
					width : 500,
					height : 300,
					gridOptions : {
						showcheckbox : false,
						event : {
							row_dblclick : function(e ,row ,data) {
								itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productCode").val(data.productCode);
								itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productName").val(data.productName);
								itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"pattern").val(data.pattern);
								itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"unitName").val(data.unitName);
								itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"proType").val(data.proType);
								itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"proTypeId").val(data.proTypeId);
							}
						}
					}
				});
			}
		},{
			display : '物料名称',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		},{
			display : '规格型号',
			name : 'pattern',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '单位名称',
			name : 'unitName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '数量',
			name : 'num',
			tclass : 'txtshort',
			validation : {
				custom : ['onlyNumber']
			}
		}]
	});
}

function del(id){

	if(id){
		var url = publicUrl + '&action=del';
		$.post(url, {id:id}, function(status) {
			if(status){
				alert('操作成功！');
				location.reload();
			}else{
				alert('操作失败，请重新操作。');
			}
		},'json');
	} else {
		alert('操作错误');
	}

}
function checkForm() {

	if($('#templateName').val()){
		var produceNumObj = $("#items").yxeditgrid("getCmpByCol" ,"num");
		if (produceNumObj.length == 0) {
			alert("没有物料！");
			return false;
		}
		for (var i = 0 ;i < produceNumObj.length ;i++) {
			if (produceNumObj[i].value <= 0) {
				alert("申请数量必须大于0");
				return false;
			}
		}
		return true;
	}else{
		alert('模板名称不能为空。');
		return false;
	}

}

function classifyEdit(id){
	var url = publicUrl + '&action=toEdit';
	$.post(url, {id:id}, function(data) {
		if(data){
			$('#classifyName').val(data.classifyName);
			$('#C_parent').html(data.parent);
			$('#C_remark').val(data.remark);
			$('#C_createName').val(data.createName);
			$('#C_createTime').val(data.createTime);
			$('#C_createId').val(data.createId);
			$('#C_id').val(data.id);
		}
	},'json');

	$('#classifyAdd').show();
	$('#classifyAdd').removeClass("hidden").dialog({
		title: "编辑分类",
		closed: false,
		width: 800,
		height: 400
	});
}

function classifyView(id){
	var url = publicUrl + '&action=toEdit';
	$.post(url, {id:id}, function(data) {
		if(data){
			$('#V_classifyName').html(data.classifyName);
			$('#V_parent').html(data.n_parent);
			$('#V_remark').html(data.remark);
			$('#V_createName').html(data.createName);
			$('#V_createTime').html(data.createTime);
			$('#V_updateName').html(data.createName);
			$('#V_updateTime').html(data.createTime);
		}
	},'json');

	$('#classifyView').show();
	$('#classifyView').removeClass("hidden").dialog({
		title: "查看分类",
		closed: false,
		width: 600,
		height: 400
	});
}

function checkAddForm() {
	if(!$('#classifyName').val()){
		alert('分类名称不能为空。');
		return false;
	}
}