$(document).ready(function() {
	setLeftDivHeight();
	materialsTree();
//	configurationGrid();
});

//取消编辑
function cancel(index) {
	$('#' + subTable).datagrid('cancelEdit', index);
}

function edit(index) {
	$('#' + subTable).datagrid('endEdit', index);
	$('#' + subTable).datagrid('selectRow', index);
	var row = $('#' + subTable).datagrid('getSelected');
}

/*********************** 确认发料编辑begin ***********************/
var editrows = [];

function editrow(index) {
	$("#stockListTable").datagrid("beginEdit", index);
}

function saverow(index) {
	$("#stockListTable").datagrid("endEdit", index);
}

function cancelrow(index) {
	$("#stockListTable").datagrid("cancelEdit", index);
	
	$("#stockListTable").datagrid("updateRow",{
		index: index,
		row: {
			outStockNum: changeDatas[index].outStockNum
		}
	});
}

function updateAction(index) {
	var datas = {
        index: index,
        row: { OPERATION: "" }
    };
		
    $("#stockListTable").datagrid("updateRow", datas);
}
/*********************** 确认发料编辑end ***********************/


/*********************** open dialog ***********************/
function materialsModeProductConfigTree() {
	$('#materials_moreProductConfig_tree').tree({
		url: publicUrl + '&action=load_materials_tree&no_more=1',
		onClick: function(node) {
			var action;
			var tableName;
			var conditions;
			if (typeof(node.state) == 'undefined') {
				product_name_data(node);
			}
		}
	});
}

//导出功能
function export_bom() {
	var current_id = $("#current_product_id").val().split("_"),
		product_id = current_id[0],
		type = current_id[current_id.length - 1];
	
	if(type == 1) {
		$.messager.alert("提示", "多产品配置无法导出BOM");
		return;
	}
	
	if ($("#current_product_id").val()) {
		url = publicUrl + '&action=export_excel&product_id=' + product_id;
		if (confirm('您确定要导出当前数据吗？')) {
			location.href = url;
		}
	} else {
		alertResult('请选择导出产品');
	}
}

//导出物料清单(统计后)
function export_materials(pids, ext) {
	if (pids) {
		url = publicUrl + '&action=export_materials&pids=' + pids + '&ext=' + ext;
		if (confirm('您确定要导出当前数据吗？')) {
			location.href = url;
		}
	} else {
		alertResult('物料清单为空！');
	}
}



//多产品选择
function reviewMuchProduct() {
	$("#moreProductConfigName").val("");
	
	openDialog("moreProductConfig_dialog", "多产品配置", "full", "full");

	materialsModeProductConfigTree();
}

//js插入数据

function product_name_data(node) {
	var table = '';
	table += "<tr id='node_" + node.id + "' style='font-size:12px;'><td>" + node.text + "<input class='data_hidden' type='hidden' value='" + node.id + "'/></td><td><a href='#' onclick='removeProduct(\"" + node.id + "\");'>删除</a></td></tr>";

	$('#product_name_data').prepend(table);
}

function removeProduct(productId) {
	var temp = document.getElementById('node_' + productId);
	temp.parentElement.removeChild(temp);
}

//生成多产品配置单
var productsName = {};  //保存产品名称, 键值是id
function goMuchProduct() {
	var moreProductName = $.trim($("#moreProductConfigName").val());
	if(!moreProductName) {
		alertResult("请输入配置名!");
		return;
	}
	
	$.post("index1.php?model=stock_material_management&action=exists_config", { name: moreProductName }, function (json) {
		if(json.result) {
			$.messager.alert("错误", "此配置名已存在!");
			return;
		}
		
		closePublicDialog();
		
		productsName = {};  //重置产品名称
		
		var product_ids = "";
		$(".data_hidden").each(function() {
			product_ids += $(this).val() + ',';
		});
		
		$("#product_name_data tr").each(function () {
			var el = $(this);
			if(!el.attr("id")) {
				return true;
			}
			
			productsName[el.attr("id").split("_")[1]] = el.find("td").eq(0).text();
		});
		
		
			
		if(!product_ids) {
			alertResult("请选择产品!");
			return;
		}	

		$.post(publicUrl + "&action=view_config_info", { product_ids: product_ids }, function(data) {
			if (data) {
				$('#product_sheet').html('');
				$('#dialog_data_board').html(data);
				openDialog('public_dialog', "查看配置单 - " + moreProductName, 'full', 'full');
			} else {
				alertResult('数据有误！');
			}
		});
	});
}

//配置单中单产品的点击遍历
function choseOneProduct(pid, ids) {
	var url = publicUrl + "&action=view_config_info";
	$.post(url, {
		product_ids: ids,
		onePid: pid
	}, function(data) {
		if (data) {
			$('#dialog_data_board').html(data);
		} else {
			alertResult('数据为空！');
		}

	});
}

//多产品框的返回点击
function closeProduct() {
	$("#product_name_data tr").each(function () {
		var el = $(this);
		if(el.attr("id")) {
			el.remove();
		}
	});
	$("#moreProductConfigName").val("");
	closeDialog("moreProductConfig_dialog");
}

//更新bom
function updateImportDialog() {
	var product_id = $('input[name=current_product_id]').val();
	if (product_id) {
		var title = '更新BOM';
		var url = publicUrl + '&action=update_excel&product_id=' + product_id;
		var data = "<form id='uploadForm2' name='uploadForm2' method='post' action='" + url + "' enctype='multipart/form-data' target='public_iframe' >" + "<table width='90%'>" + "<tr>" + "<td><input type='file' id='upload_excel_file2' name='upload_excel_file2' /></td>" + "</tr>" + "<tr>" + "<td align='center'>" + "<input type='submit' id='uploadButton2' name='uploadButton2' value='上传' />" + "<input type='reset' id='uploadtResetButton2' name='uploadtResetButton2' value='重置' />" + "<input type='button' id='closeButton' name='closeButton' value='关闭' onclick='closePublicDialog();' />" + "</td>" + "</tr>" + "</table>" + "</form>";
		data += "<div align='right'><a href='view/template/stock/material/template/bom_template.xlsx'>BOM导入模板</a></div>";
		$('#dialog_data_board').html(data);
		openDialog('public_dialog', title, '', '');
	} else {
		alertResult('请选择需要更新的产品！');
	}
}



//配置信息保存成功后的返回信息

function showConfigMsg(json) {
	$("#loading").removeClass("hidden");
	
	var url = publicUrl + "&action=save_config_list_form";
	var img = "<span class='save_b' style='color:red;font-size:18px;position:absolute;left:500px;bottom:300px;'><img src='images/roading.gif'/></span>";
	$.post(url, {
		'product_id': json
	}, function(data) {
		$("#loading").addClass("hidden");
		if (data) {
			$('.save_b').remove();
			$.messager.alert("提示信息", "保存成功");
		} else {
			$.messager.alert("提示信息", "保存失败");
		}
	});
}

//统计物料信息
function countMeterials(json) {
	var div = '';
	var dialogcount;
	var product_id = $('input[name=pid]').val();
	var productName = $('input[name=productName]').val();
	var url = publicUrl + "&action=search_config_pid";
	$.post(url, {
		pid: json
	}, function(data) {
		if (data != '0') {
			var div = '';
			div += "<div id='countMeterial' class='easyui-dialog' style='width:650px;padding:10px 0;text-align:center;'>" + data + "</div>";
			$('#dialog_data').html(div);
			dialogcount = $('#dialog_data').dialog({
				title: '统计消息框',
				buttons: [{
					text: '统计',
					handler: function() {
						$('#dialog_data').dialog('close');
						createMergeList();
					}
				}, {
					text: '取消',
					handler: function() {
						$('#dialog_data').dialog('close');
					}
				}],
			});

		} else {
			$.messager.alert('消息框', '请先保存配置再统计');
		}
	}, 'html');
}

var changeDatas = {}; //保存修改的数据

//生成发料清单
function createMergeList() {
	var product_id = $('input[name=pid]').val();
	var mNum = $('input[name=num]').val();
	var gridWidth = getBrowseWidth() - 30;
	var gridHeight = getBrowseHeight() - 150;
	var a = [];
	var b = [];
	var pids = '';
	$('.pro_name').each(function(i, v) {
		var pid = $(this).attr('pid');
		pids += pid + ':';
		$('.relevance_name_' + pid).each(function(index, value) {
			var vid = $(this).attr('rid');
			pids += vid + '_' + $('.relevance_num_' + pid + '_' + vid).val() + '-';
		});
		var n = pids.length;
		pids = pids.substr(0, n - 1);
		pids += ',';
	});
	var n = pids.length;
	pids = pids.substr(0, n - 1);

	openDialog('stocklist_dialog', '发料清单', 'full', 'full');

	var table = "<table id='stockListTable' style='width: " + gridWidth + "px; height: " + gridHeight + "px;' ></table>";

	$("#stocklist_dialog_data").append(table);

	//执行数据表格
	var url = publicUrl + "&action=mergeDataSub&product_id=" + product_id + "&mNum=" + mNum + "&pids=" + pids + "&type=issue";

	showMaterialListDatagrid(url);

	//第一层数据表格
	function showMaterialListDatagrid(url) {
		var columns= [
		    { field: 'stock_code', title: '物料编码', align: 'center', width:80 },
		    { field: 'stock_name', title: '物料名称', align: 'center', width:80 },
		    { field: 'stock_model', title: '物料型号', align: 'center', width:80 },
		    { field: 'stock_packaging', title: '封装', align: 'center', width:80 },
		    { field: 'stock_serial_number', title: '元件序号', align: 'center', width:80 },
		    { field: 'stock_factory', title: '厂商', align: 'center', width:80 },
		    { field: 'unitName', title: '最小单位', align: 'center', width:70 },
		    { field: 'actNum', title: '库存数', align: 'center', width:70 },
		    { field: 'stock_total', title: '实际需求', align: 'center', width:60 },
		    { field: 'stock_loss_total', title: '总需求(含损耗)', align: 'center', width:100 },
		    { field: 'outStockNum', title: '发料数', align: 'center', width:60, editor:"numberbox" },
		    { field: 'shortage', title: '缺货数', align: 'center', width:60,
		    	styler: function( value, rowData, rowIndex) {
			    	if(value < 0) {
			    		return'background:#FFCCCC';
			    	}
			    }
		    },
		    { field: 'OPERATION', title: '操作', align: 'center', width:100,
		    	formatter: function (value, row, index) {
		    		var edit = "<a href=\"#\" onclick=\"editrow(" + index + ")\">编辑</a>";
		    		var save = "<a href=\"#\" onclick=\"saverow(" + index + ")\">保存</a>";
		    		var cancel = "<a href=\"#\" onclick=\"cancelrow(" + index + ")\">取消</a>";
		    		var action = "";
		    		if(row.editing) {
		    			action = save + "  " + cancel;
		    		} else {
		    			action = edit;
		    		}
		    		return action;
		    	}
		    }
		];
		
		if(pids.split(",").length > 1) {
			columns = addProductNameToColumns(pids, columns);
		} else {
			columns = [columns];
		}
		
		$("#stockListTable").datagrid({
			idField: "id",
			title: false,
			singleSelect: true,
			pagination: false,
			fitColumns: true,
			url: url,
			toolbar: [
			    {
					text: "确认发料",
					iconCls: 'icon-ok',
					handler: function() {
						var rows = $("#stockListTable").datagrid("getRows");
						var rowcount = rows.length;
						for(var i=0; i<rowcount; i++) {
							if(rows[i].editing) {
								$.messager.alert("提示", "请保存编辑");
								return;
							}
						}
						
						var datas = $("#stockListTable").datagrid("getData");
						creatSendList(datas);
					}
				}, {
					text: "<input id=\"loss_rate\" value=\"5\" type=\"text\" />% 损耗率"
				}
			],
			columns: columns,
			loadFilter: function (datas) {
				//本地计算损耗率
				var loss_rate = parseInt($("#loss_rate").val()) / 100 + 1;
				for(var i in datas) {
					datas[i]["shortage"] = datas[i]["shortage"] > 0 ? -(+datas[i]["shortage"]) : 0;
					
					datas[i]["stock_loss_total"] = Math.ceil(datas[i]["stock_total"] * loss_rate);
					datas[i]['useTotal'] = datas[i]['outStockNum'] - datas[i]['realOutNum'];
				}
				
				return {
					rows: datas,
					total: datas.length
				};
			},
			onLoadSuccess: function () {
				//改变损耗率, 重新获取数据
				$("#loss_rate").unbind("change");
				$("#loss_rate").change(function () {
					$(this).val($(this).val().replace(/[^0-9]/g, ""));
					$("#stockListTable").datagrid("reload");
				});
				
				changeDatas = {};
			},
			onBeforeEdit: function (index, row) {
				if(typeof changeDatas[index] == "undefined") {
					changeDatas[index] = {
						actNum: row.actNum,
						outStockNum: row.outStockNum	
					};
				}
				
				row.editing = true;
				updateAction(index);
			},
			onAfterEdit: function (index, row, changes) {
				if(+changes.outStockNum > +changeDatas[index].actNum || +row.outStockNum > +changeDatas[index].actNum) {
					$.messager.alert("提示", "发料数不能大于库存");
					$("#stockListTable").datagrid("beginEdit", index);
					return;
				}
				
				changeDatas[index] = row;
								
				row.editing = false;
				updateAction(index);
			},
			onCancelEdit: function (index, row) {				
				row.editing = false;
				updateAction(index);
			}
		});
	}
	
	function creatSendList(datas) {
		/*------------------把产品名插入表头------------------*/
		var columns = [{
			field: 'stock_code',
			title: '物料编码',
			align: 'center',
			width: 80
		}, {
			field: 'stock_name',
			title: '物料名称',
			align: 'center',
			width: 80
		}, {
			field: 'stock_model',
			title: '物料型号',
			align: 'center',
			width: 80
		}, {
			field: 'stock_packaging',
			title: '封装',
			align: 'center',
			width: 80
		}, {
			field: 'stock_factory',
			title: '厂商',
			align: 'center',
			width: 80
		}, {
			field: 'stock_total',
			title: '实际需求',
			align: 'center',
			width: 60
		}, {
			field: 'stock_loss_total',
			title: '总需求(含损耗)',
			align: 'center',
			width: 100
		}, {
			field: 'unitName',
			title: '最小单位',
			align: 'center',
			width: 60
		}, {
			field: 'actNum',
			title: '库存数',
			align: 'center',
			width: 60
		}, {
			field: 'mustOutNum',
			title: '应退数',
			align: 'center',
			width: 60
		}, {
			field: 'outStockNum',
			title: '发料数',
			align: 'center',
			width: 60
		}, {
			field: 'shortage',
			title: '缺货数',
			align: 'center',
			width: 60,
			styler: function(value, rowData, rowIndex) {
				if (value > 0) {
					return 'background:#FFCCCC';
				}
			}
		}];
		columns = addProductNameToColumns(pids, columns);
		/*------------------把产品名插入表头------------------*/
		
		var loss_rate = $("#loss_rate").val();
		var config_name = $.trim($("#moreProductConfigName").val());
		
		/* 更换table */
		$("#stocklist_dialog_data").html("");
		$("#stocklist_dialog_data").append(table);
		
		var sendListId = 0;
		$("#stockListTable").datagrid({
			title: "发料记录",
			method: "post",
			url: publicUrl + "&action=confirmSendStock",
			queryParams: {
				pids: pids,
				loss_rate: loss_rate,
				config_name: config_name,
				data: datas
			},
			toolbar: [{
		    	text: "导出",
		    	iconCls: "icon-ok",
		    	handler: function () {
					$("#columns_box").dialog({
						title: "导出",
						buttons: [
							{
								text: "导出",
								handler: function () {
									$("#columns_form").submit();
									$("#columns_form :checkbox").prop("checked", true);
									$("#columns_box").dialog("close");
								}
							},
							{
								text: "取消",
								handler: function () {
									$("#columns_box").dialog("close");
								}
							}
						]
					}).dialog("open");
					
					$("#columns_form").attr("action", "index1.php?model=stock_material_management&action=export_send_list&id=" + sendListId);
				}
			}],
			columns: columns,
			fitColumns: true,
			onLoadSuccess: function() {
				$.messager.alert("提示", "发料成功");
			},
			loadFilter: function (datas) {
				sendListId = datas.id;
				
				return {
					rows: datas.data,
					total: datas.data.length
				};
			}
		});
	}
	
	function addProductNameToColumns(pids, columns) {
		var columns_pids = pids.split(",");
		var columns_add = [];
		for(var i in columns_pids) {
			var pid = columns_pids[i].split(":")[0];
			columns_add.push({
				title: productsName[pid],
				field: pid,
				align: 'center',
				width: 60
			});
		}
		columns_add.unshift(5, 0);
		Array.prototype.splice.apply(columns, columns_add);
		columns = [columns];
		return columns;
	}
}


//新建产品页面
function openCreateProductDialog() {
	var url = publicUrl + "&action=load_attribute_name";
	$.get(url, function(optionsStr) {
		if (optionsStr != "") {
			var table = "<form id='create_product_form' name='create_product_form' method='post'  >" + "<table align='center' style='width:50%;' >" + "<tr>" + "<td style='width: 80px;' align='right' >" + "BOM名称:" + "</td>"

			+ "<td>" + "<input type='text' id='producttype[proType]' name='producttype[proType]' value='' style='width:150px;' >" + "</td>"

			+ "<td style='width: 80px;' align='right' >" + "BOM类型:" + "</td>"

			+ "<td>" + "<select id='producttype[parentId]' name='producttype[parentId]' >" + "<option value='309' >成品</option>" + "<option value='307' >半成品</option>" + "</select>" + "</td>" + "</tr>"

			+ "<tr>" + "<td style='width: 80px;' align='right' >" + "BOM属性:" + "</td>" + "<td>" + "<select id='producttype[properties]' name='producttype[properties]' >" + optionsStr + "</select>" + "</td>" + "<td>" + "&nbsp;" + "</td>" + "<td>" + "&nbsp;" + "</td>" + "</tr>"

			+ "<tr>" + "<td align='center' colspan='4' >" + "<input type='hidden' id='method' name='method' value='create' />" + "<input type='button' id='saveButton' name='saveButton' value='保存' onclick='doCreate();' />" + "<input type='button' id='goback' name='goback' value='返回清单' onclick='openReviewDialog(\"successful\");' />" + "<input type='button' id='closeButton' name='closeButton' value='关闭' onclick='closePublicDialog();' />" + "</td>" + "</tr>" + "</table>"; + "</form>"
			$('#dialog_data_board').html(table);
		} else {
			alertResult('操作失败,请联系管理员');
		}
	});
}

//打开新建配置单页面
function openCreateConfigListDialog() {
	closePublicDialog();
	var productId = $('#current_product_id').val(),
		type = productId.split("_")[1],
		action = type == 0 ? "view_config_info" : "view_config_list_info";
	if (productId != "") {
		openDialog('public_dialog', '生成配置单', 'full', 'full');
		var url = publicUrl + "&action=" + action;
		var data = {
			current_product_id: productId
		};

		$.post(url, data, function(str) {
			$('#dialog_data_board').html(str);
		})
	} else {
		alertResult('操作失败,请联系管理员');
	}
}

function loadConfigPage(product_id, tagId) {
	
}

//关闭对话框
function closePublicDialog() {
	$('#dialog_data_board').html("");
	closeDialog('public_dialog');
}

function closeConfigListDialog() {
	$('#config_list_board').html("");
	closeDialog('config_list_dialog');
}

/*********************** do function ***********************/
function doCreate() {
	var url = publicUrl + "&action=create_product_and_save_session";
	$("#loading").removeClass("hidden");
	$.post(url, $('#create_product_form').serialize(), function(id) {
		$("#loading").addClass("hidden");
		if (id != 0) {
			$.messager.alert("提示", "添加成功");
		} else {
			$.messager.alert("提示", "添加失败, 请联系管理员");
		}
		closePublicDialog();
	});
}

//public

//母单Tag选中
function changeConfigTab(tabId) {
	var divConfig = document.getElementsByName('config_tag[]');
	var len = "config_tag_".length;
	for (var i = 0; i < divConfig.length; i++) {
		id = divConfig[i].id.substring(len);
		if (id == tabId) {
			$('#div_config_' + id).show();
			document.getElementById("config_tag_" + id).style.backgroundColor = "#B0C4DE";
		} else {
			$('#div_config_' + id).hide();
			document.getElementById("config_tag_" + id).style.backgroundColor = "buttonface";
		}
	}

	//如果有二次导入Tag则隐藏
	var nameEle = document.getElementsByName('div_config_new[]');
	var divCount = nameEle.length;
	if (divCount > 0) {
		for (var i = 0; i < divCount; i++) {
			$('#' + nameEle[i].id).hide();
		}
	}
}

//标记方法
function notNeedMark(id, pid, type) {
	var style = document.getElementById('tr_' + id + '_' + pid).style.background;
	if (style) {
		var mark = '0';
		$('#tr_' + id + '_' + pid).css({
			background: ''
		});
	} else {
		var mark = '1';
		$('#tr_' + id + '_' + pid).css({
			background: '#ccc'
		});
	}
	var url = publicUrl + "&action=update_detail_mark";
	var data = {
		id: pid,
		mark: mark
	};
	$.post(url, data, function(uid) {
		if (uid == 1) {
			/*$.messager.show({
				title: '提示',
				msg: '标记成功！',
				timeout: 3000,
				showType: 'show'
			});*/
		} else {
			$.messager.show({
				title: '提示',
				msg: '标记失败！',
				timeout: 3000,
				showType: 'show'
			});
		}
	});

}

//二次导入窗口
function openAddConifgInfoList() {
	var title = '添加新项';
	var url = publicUrl + '&action=add_config_list';
	var data = "<form id='uploadForm' name='uploadForm' method='post' action='" + url + "' enctype='multipart/form-data' target='public_iframe' >" + "<table width='90%'>" + "<tr>" + "<td><input type='file' id='upload_excel_file' name='upload_excel_file' /></td>" + "</tr>" + "<tr>" + "<td align='center'>" + "<input type='submit' id='uploadButton' name='uploadButton' value='上传' />" + "<input type='reset' id='uploadtResetButton' name='uploadtResetButton' value='重置' />" + "<input type='button' id='closeButton' name='closeButton' value='关闭' onclick='closeConfigListDialog();' />" + "</td>" + "</tr>" + "</table>" + "</form>";
	data += "<div align='right'><a href='view/template/stock/material/template/bom_template.xlsx'>BOM导入模板</a></div>";
	$('#config_list_board').html(data);
	openDialog('config_list_dialog', title, '', '');
}

//接驳二次导入Tag及内容至母单内容中
function processAddConfigInfoResult(jsonStr) {
	closeConfigListDialog();
	var titleCon = jsonStr.title.length;
	if (titleCon > 0) {
		for (var i = 0; i < titleCon; i++) {
			$('#div_header_config').append(jsonStr.title[i].tagSpan);
		}
		$('#div_header_config').append("<br/>");
	}
	var dataCon = jsonStr.detail.length;
	if (dataCon > 0) {
		for (var i = 0; i < dataCon; i++) {
			$('#div_body_config').append(jsonStr.detail[i].tagDiv);
		}
	}
}

//二次导入Tag选中
function changeAddConfigTab(id) {
	var len = "div_config_".length;
	var nameEle = document.getElementsByName('div_config_new[]');
	var divCount = nameEle.length;
	for (var i = 0; i < divCount; i++) {
		var newId = nameEle[i].id.substring(len);
		if (newId == id) {
			$('#' + nameEle[i].id).show();
		} else {
			$('#' + nameEle[i].id).hide();
		}
	}

	//隐藏母单Tag
	var divConfig = document.getElementsByName('div_config[]');
	for (var i = 0; i < divConfig.length; i++) {
		$('#' + divConfig[i].id).hide();
	}
}

//导出配置单
function export_config(pid) {
	var url = publicUrl + "&action=export_config";
	$.post(url, {
		pid: pid
	}, function(data) {

	});
}

function selectChecked(){
	if ($('#selectC').attr("checked")) {  
		$("input[name=items]").each(function() {  
			if( !$(this).attr("disabled")){
				$(this).attr("checked", true);  
			}
		});  
	} else {  
	    $("input[name=items]").each(function() {  
	    	$(this).attr("checked", false);  
	    });  
	}  
}