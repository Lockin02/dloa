$(document).ready(function() {
	setLeftDivHeight();
	materialsTree();
//	configurationGrid();
});

//ȡ���༭
function cancel(index) {
	$('#' + subTable).datagrid('cancelEdit', index);
}

function edit(index) {
	$('#' + subTable).datagrid('endEdit', index);
	$('#' + subTable).datagrid('selectRow', index);
	var row = $('#' + subTable).datagrid('getSelected');
}

/*********************** ȷ�Ϸ��ϱ༭begin ***********************/
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
/*********************** ȷ�Ϸ��ϱ༭end ***********************/


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

//��������
function export_bom() {
	var current_id = $("#current_product_id").val().split("_"),
		product_id = current_id[0],
		type = current_id[current_id.length - 1];
	
	if(type == 1) {
		$.messager.alert("��ʾ", "���Ʒ�����޷�����BOM");
		return;
	}
	
	if ($("#current_product_id").val()) {
		url = publicUrl + '&action=export_excel&product_id=' + product_id;
		if (confirm('��ȷ��Ҫ������ǰ������')) {
			location.href = url;
		}
	} else {
		alertResult('��ѡ�񵼳���Ʒ');
	}
}

//���������嵥(ͳ�ƺ�)
function export_materials(pids, ext) {
	if (pids) {
		url = publicUrl + '&action=export_materials&pids=' + pids + '&ext=' + ext;
		if (confirm('��ȷ��Ҫ������ǰ������')) {
			location.href = url;
		}
	} else {
		alertResult('�����嵥Ϊ�գ�');
	}
}



//���Ʒѡ��
function reviewMuchProduct() {
	$("#moreProductConfigName").val("");
	
	openDialog("moreProductConfig_dialog", "���Ʒ����", "full", "full");

	materialsModeProductConfigTree();
}

//js��������

function product_name_data(node) {
	var table = '';
	table += "<tr id='node_" + node.id + "' style='font-size:12px;'><td>" + node.text + "<input class='data_hidden' type='hidden' value='" + node.id + "'/></td><td><a href='#' onclick='removeProduct(\"" + node.id + "\");'>ɾ��</a></td></tr>";

	$('#product_name_data').prepend(table);
}

function removeProduct(productId) {
	var temp = document.getElementById('node_' + productId);
	temp.parentElement.removeChild(temp);
}

//���ɶ��Ʒ���õ�
var productsName = {};  //�����Ʒ����, ��ֵ��id
function goMuchProduct() {
	var moreProductName = $.trim($("#moreProductConfigName").val());
	if(!moreProductName) {
		alertResult("������������!");
		return;
	}
	
	$.post("index1.php?model=stock_material_management&action=exists_config", { name: moreProductName }, function (json) {
		if(json.result) {
			$.messager.alert("����", "���������Ѵ���!");
			return;
		}
		
		closePublicDialog();
		
		productsName = {};  //���ò�Ʒ����
		
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
			alertResult("��ѡ���Ʒ!");
			return;
		}	

		$.post(publicUrl + "&action=view_config_info", { product_ids: product_ids }, function(data) {
			if (data) {
				$('#product_sheet').html('');
				$('#dialog_data_board').html(data);
				openDialog('public_dialog', "�鿴���õ� - " + moreProductName, 'full', 'full');
			} else {
				alertResult('��������');
			}
		});
	});
}

//���õ��е���Ʒ�ĵ������
function choseOneProduct(pid, ids) {
	var url = publicUrl + "&action=view_config_info";
	$.post(url, {
		product_ids: ids,
		onePid: pid
	}, function(data) {
		if (data) {
			$('#dialog_data_board').html(data);
		} else {
			alertResult('����Ϊ�գ�');
		}

	});
}

//���Ʒ��ķ��ص��
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

//����bom
function updateImportDialog() {
	var product_id = $('input[name=current_product_id]').val();
	if (product_id) {
		var title = '����BOM';
		var url = publicUrl + '&action=update_excel&product_id=' + product_id;
		var data = "<form id='uploadForm2' name='uploadForm2' method='post' action='" + url + "' enctype='multipart/form-data' target='public_iframe' >" + "<table width='90%'>" + "<tr>" + "<td><input type='file' id='upload_excel_file2' name='upload_excel_file2' /></td>" + "</tr>" + "<tr>" + "<td align='center'>" + "<input type='submit' id='uploadButton2' name='uploadButton2' value='�ϴ�' />" + "<input type='reset' id='uploadtResetButton2' name='uploadtResetButton2' value='����' />" + "<input type='button' id='closeButton' name='closeButton' value='�ر�' onclick='closePublicDialog();' />" + "</td>" + "</tr>" + "</table>" + "</form>";
		data += "<div align='right'><a href='view/template/stock/material/template/bom_template.xlsx'>BOM����ģ��</a></div>";
		$('#dialog_data_board').html(data);
		openDialog('public_dialog', title, '', '');
	} else {
		alertResult('��ѡ����Ҫ���µĲ�Ʒ��');
	}
}



//������Ϣ����ɹ���ķ�����Ϣ

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
			$.messager.alert("��ʾ��Ϣ", "����ɹ�");
		} else {
			$.messager.alert("��ʾ��Ϣ", "����ʧ��");
		}
	});
}

//ͳ��������Ϣ
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
				title: 'ͳ����Ϣ��',
				buttons: [{
					text: 'ͳ��',
					handler: function() {
						$('#dialog_data').dialog('close');
						createMergeList();
					}
				}, {
					text: 'ȡ��',
					handler: function() {
						$('#dialog_data').dialog('close');
					}
				}],
			});

		} else {
			$.messager.alert('��Ϣ��', '���ȱ���������ͳ��');
		}
	}, 'html');
}

var changeDatas = {}; //�����޸ĵ�����

//���ɷ����嵥
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

	openDialog('stocklist_dialog', '�����嵥', 'full', 'full');

	var table = "<table id='stockListTable' style='width: " + gridWidth + "px; height: " + gridHeight + "px;' ></table>";

	$("#stocklist_dialog_data").append(table);

	//ִ�����ݱ��
	var url = publicUrl + "&action=mergeDataSub&product_id=" + product_id + "&mNum=" + mNum + "&pids=" + pids + "&type=issue";

	showMaterialListDatagrid(url);

	//��һ�����ݱ��
	function showMaterialListDatagrid(url) {
		var columns= [
		    { field: 'stock_code', title: '���ϱ���', align: 'center', width:80 },
		    { field: 'stock_name', title: '��������', align: 'center', width:80 },
		    { field: 'stock_model', title: '�����ͺ�', align: 'center', width:80 },
		    { field: 'stock_packaging', title: '��װ', align: 'center', width:80 },
		    { field: 'stock_serial_number', title: 'Ԫ�����', align: 'center', width:80 },
		    { field: 'stock_factory', title: '����', align: 'center', width:80 },
		    { field: 'unitName', title: '��С��λ', align: 'center', width:70 },
		    { field: 'actNum', title: '�����', align: 'center', width:70 },
		    { field: 'stock_total', title: 'ʵ������', align: 'center', width:60 },
		    { field: 'stock_loss_total', title: '������(�����)', align: 'center', width:100 },
		    { field: 'outStockNum', title: '������', align: 'center', width:60, editor:"numberbox" },
		    { field: 'shortage', title: 'ȱ����', align: 'center', width:60,
		    	styler: function( value, rowData, rowIndex) {
			    	if(value < 0) {
			    		return'background:#FFCCCC';
			    	}
			    }
		    },
		    { field: 'OPERATION', title: '����', align: 'center', width:100,
		    	formatter: function (value, row, index) {
		    		var edit = "<a href=\"#\" onclick=\"editrow(" + index + ")\">�༭</a>";
		    		var save = "<a href=\"#\" onclick=\"saverow(" + index + ")\">����</a>";
		    		var cancel = "<a href=\"#\" onclick=\"cancelrow(" + index + ")\">ȡ��</a>";
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
					text: "ȷ�Ϸ���",
					iconCls: 'icon-ok',
					handler: function() {
						var rows = $("#stockListTable").datagrid("getRows");
						var rowcount = rows.length;
						for(var i=0; i<rowcount; i++) {
							if(rows[i].editing) {
								$.messager.alert("��ʾ", "�뱣��༭");
								return;
							}
						}
						
						var datas = $("#stockListTable").datagrid("getData");
						creatSendList(datas);
					}
				}, {
					text: "<input id=\"loss_rate\" value=\"5\" type=\"text\" />% �����"
				}
			],
			columns: columns,
			loadFilter: function (datas) {
				//���ؼ��������
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
				//�ı������, ���»�ȡ����
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
					$.messager.alert("��ʾ", "���������ܴ��ڿ��");
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
		/*------------------�Ѳ�Ʒ�������ͷ------------------*/
		var columns = [{
			field: 'stock_code',
			title: '���ϱ���',
			align: 'center',
			width: 80
		}, {
			field: 'stock_name',
			title: '��������',
			align: 'center',
			width: 80
		}, {
			field: 'stock_model',
			title: '�����ͺ�',
			align: 'center',
			width: 80
		}, {
			field: 'stock_packaging',
			title: '��װ',
			align: 'center',
			width: 80
		}, {
			field: 'stock_factory',
			title: '����',
			align: 'center',
			width: 80
		}, {
			field: 'stock_total',
			title: 'ʵ������',
			align: 'center',
			width: 60
		}, {
			field: 'stock_loss_total',
			title: '������(�����)',
			align: 'center',
			width: 100
		}, {
			field: 'unitName',
			title: '��С��λ',
			align: 'center',
			width: 60
		}, {
			field: 'actNum',
			title: '�����',
			align: 'center',
			width: 60
		}, {
			field: 'mustOutNum',
			title: 'Ӧ����',
			align: 'center',
			width: 60
		}, {
			field: 'outStockNum',
			title: '������',
			align: 'center',
			width: 60
		}, {
			field: 'shortage',
			title: 'ȱ����',
			align: 'center',
			width: 60,
			styler: function(value, rowData, rowIndex) {
				if (value > 0) {
					return 'background:#FFCCCC';
				}
			}
		}];
		columns = addProductNameToColumns(pids, columns);
		/*------------------�Ѳ�Ʒ�������ͷ------------------*/
		
		var loss_rate = $("#loss_rate").val();
		var config_name = $.trim($("#moreProductConfigName").val());
		
		/* ����table */
		$("#stocklist_dialog_data").html("");
		$("#stocklist_dialog_data").append(table);
		
		var sendListId = 0;
		$("#stockListTable").datagrid({
			title: "���ϼ�¼",
			method: "post",
			url: publicUrl + "&action=confirmSendStock",
			queryParams: {
				pids: pids,
				loss_rate: loss_rate,
				config_name: config_name,
				data: datas
			},
			toolbar: [{
		    	text: "����",
		    	iconCls: "icon-ok",
		    	handler: function () {
					$("#columns_box").dialog({
						title: "����",
						buttons: [
							{
								text: "����",
								handler: function () {
									$("#columns_form").submit();
									$("#columns_form :checkbox").prop("checked", true);
									$("#columns_box").dialog("close");
								}
							},
							{
								text: "ȡ��",
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
				$.messager.alert("��ʾ", "���ϳɹ�");
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


//�½���Ʒҳ��
function openCreateProductDialog() {
	var url = publicUrl + "&action=load_attribute_name";
	$.get(url, function(optionsStr) {
		if (optionsStr != "") {
			var table = "<form id='create_product_form' name='create_product_form' method='post'  >" + "<table align='center' style='width:50%;' >" + "<tr>" + "<td style='width: 80px;' align='right' >" + "BOM����:" + "</td>"

			+ "<td>" + "<input type='text' id='producttype[proType]' name='producttype[proType]' value='' style='width:150px;' >" + "</td>"

			+ "<td style='width: 80px;' align='right' >" + "BOM����:" + "</td>"

			+ "<td>" + "<select id='producttype[parentId]' name='producttype[parentId]' >" + "<option value='309' >��Ʒ</option>" + "<option value='307' >���Ʒ</option>" + "</select>" + "</td>" + "</tr>"

			+ "<tr>" + "<td style='width: 80px;' align='right' >" + "BOM����:" + "</td>" + "<td>" + "<select id='producttype[properties]' name='producttype[properties]' >" + optionsStr + "</select>" + "</td>" + "<td>" + "&nbsp;" + "</td>" + "<td>" + "&nbsp;" + "</td>" + "</tr>"

			+ "<tr>" + "<td align='center' colspan='4' >" + "<input type='hidden' id='method' name='method' value='create' />" + "<input type='button' id='saveButton' name='saveButton' value='����' onclick='doCreate();' />" + "<input type='button' id='goback' name='goback' value='�����嵥' onclick='openReviewDialog(\"successful\");' />" + "<input type='button' id='closeButton' name='closeButton' value='�ر�' onclick='closePublicDialog();' />" + "</td>" + "</tr>" + "</table>"; + "</form>"
			$('#dialog_data_board').html(table);
		} else {
			alertResult('����ʧ��,����ϵ����Ա');
		}
	});
}

//���½����õ�ҳ��
function openCreateConfigListDialog() {
	closePublicDialog();
	var productId = $('#current_product_id').val(),
		type = productId.split("_")[1],
		action = type == 0 ? "view_config_info" : "view_config_list_info";
	if (productId != "") {
		openDialog('public_dialog', '�������õ�', 'full', 'full');
		var url = publicUrl + "&action=" + action;
		var data = {
			current_product_id: productId
		};

		$.post(url, data, function(str) {
			$('#dialog_data_board').html(str);
		})
	} else {
		alertResult('����ʧ��,����ϵ����Ա');
	}
}

function loadConfigPage(product_id, tagId) {
	
}

//�رնԻ���
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
			$.messager.alert("��ʾ", "��ӳɹ�");
		} else {
			$.messager.alert("��ʾ", "���ʧ��, ����ϵ����Ա");
		}
		closePublicDialog();
	});
}

//public

//ĸ��Tagѡ��
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

	//����ж��ε���Tag������
	var nameEle = document.getElementsByName('div_config_new[]');
	var divCount = nameEle.length;
	if (divCount > 0) {
		for (var i = 0; i < divCount; i++) {
			$('#' + nameEle[i].id).hide();
		}
	}
}

//��Ƿ���
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
				title: '��ʾ',
				msg: '��ǳɹ���',
				timeout: 3000,
				showType: 'show'
			});*/
		} else {
			$.messager.show({
				title: '��ʾ',
				msg: '���ʧ�ܣ�',
				timeout: 3000,
				showType: 'show'
			});
		}
	});

}

//���ε��봰��
function openAddConifgInfoList() {
	var title = '�������';
	var url = publicUrl + '&action=add_config_list';
	var data = "<form id='uploadForm' name='uploadForm' method='post' action='" + url + "' enctype='multipart/form-data' target='public_iframe' >" + "<table width='90%'>" + "<tr>" + "<td><input type='file' id='upload_excel_file' name='upload_excel_file' /></td>" + "</tr>" + "<tr>" + "<td align='center'>" + "<input type='submit' id='uploadButton' name='uploadButton' value='�ϴ�' />" + "<input type='reset' id='uploadtResetButton' name='uploadtResetButton' value='����' />" + "<input type='button' id='closeButton' name='closeButton' value='�ر�' onclick='closeConfigListDialog();' />" + "</td>" + "</tr>" + "</table>" + "</form>";
	data += "<div align='right'><a href='view/template/stock/material/template/bom_template.xlsx'>BOM����ģ��</a></div>";
	$('#config_list_board').html(data);
	openDialog('config_list_dialog', title, '', '');
}

//�Ӳ����ε���Tag��������ĸ��������
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

//���ε���Tagѡ��
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

	//����ĸ��Tag
	var divConfig = document.getElementsByName('div_config[]');
	for (var i = 0; i < divConfig.length; i++) {
		$('#' + divConfig[i].id).hide();
	}
}

//�������õ�
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