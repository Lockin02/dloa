$(document).ready(function() {
//	setLeftDivHeight();
	materialsTree();
	operationGrid();

});

function getIds(nodes) {
	var bomIds = [];
	getId(nodes);
	return bomIds;

	function getId(nodes) {
		for(var i in nodes) {
			var n = nodes[i];
			if(typeof n.children != "undefined" && n.children.length != 0) {
				getId(n.children);
			} else if( typeof n.id != "undefined") {
				bomIds.push(n.id.split("_")[0]+'&^'+n.text);
			}
		}
	}
}

function beforeGenerateConfiguration(){
	if(!$('#div_configuration').parent('div').attr("id")){
		$('#div_configuration').parent('div').html("");
	}
	var nodes = $("#materials_tree").tree("getChecked"),id_name = String(getIds(nodes));

	if(id_name){
		$.messager.confirm("提示", "确认生成此配置单？", function (result) {
			if(!result) {
				return;
			}
			$("<div id='div_configuration'></div>").appendTo($('#right'));
			var idArray = id_name.split(","),semifinishedId,add_div,sName;
			for(var i in idArray){
				if( typeof idArray[i] != 'function'){
					semifinishedId = idArray[i].split("&^")[0];
					add_div = "<div id='configName"+semifinishedId+"'></div>";
					add_div += "<div id='configuration"+semifinishedId+"'></div><br/>";
					$(add_div).appendTo($('#div_configuration'));
					$('#configName'+semifinishedId).datagrid({
						title: idArray[i].split("&^")[1]+
						"<div style='float:right'>需求数： <input id='semiF_num_"+semifinishedId+"' size=6 value=1 onkeyup=isNotANum(this) class='but_r' sf_id='"+semifinishedId+"'></div>"
					});
				}

			}

		var width = $('#main').width()-70;
		var height = $('#main').height()-70;
		 $('#div_configuration').dialog({
			    title: '配置单',
			    iconCls: 'icon-ok',
			    width: width,
			    height: height,
			    closed: false,
//			    cache: false,
			    modal: true,
	            buttons:[{
	            	text:'确认',
	            	iconCls:'icon-save',
	            	handler:function(){
	            		$.messager.confirm("提示", "确认提交需求数？", function (result) {
	            			if(!result) {
	            				return;
	            			}
		        			for(var i in idArray){
		        				if( typeof idArray[i] != 'function'){
		        					semifinishedId = idArray[i].split("&^")[0];
		        					var num = $("#semiF_num_"+semifinishedId).val();
		        					idArray[i] = idArray[i] + "#" + num;
		        				}
	
		        			}
		        			closeDialog('div_configuration');
		            		generateConfiguration(idArray);
	            		});
	            	}
	            }]
			});
			$('#div_configuration').dialog('open');
		});
	}else{
		return $.messager.alert("提示", "请选择半成品!");
	}
}

function generateConfiguration(idArray){

	if(!$('#div_configuration').parent('div').attr("id")){
		$('#div_configuration').parent('div').html("");
	}

	if(idArray){
		$("<div id='div_configuration'></div>").appendTo($('#right'));

		for(var i in idArray){

			if( typeof idArray[i] != 'function'){
				semifinishedId = idArray[i].split("&^")[0];
				num = idArray[i].split("&^")[1].split("#")[1];
				add_div = "<div id='configName"+semifinishedId+"'></div>";
				add_div += "<div id='configuration"+semifinishedId+"'></div><br/>";
				$(add_div).appendTo($('#div_configuration'));
				$('#configuration'+semifinishedId).datagrid({
					iconCls: 'icon-ok',
					idField: 'id',
					fitColumns:true,
					rownumbers: true,
					onLoadSuccess: function () {
						$("input[type='checkbox']").prop("checked",true);
					},
					url: publicUrl+'&action=load_Configuration&semifinishedId='+semifinishedId,
					columns: [[ {
		            	checkbox:true,
		            	field:'semiF'+semifinishedId,

		            },{
		                field: 'code',
		                title: '物料编码',
		                width: 210,
		                align: 'left',
		                editor: 'text'
		            }, {
		                field: 'name',
		                title: '名称',
		                width: 240,
		                align: 'left',
		                editor: 'textarea',
		            },{
		                field: 'model',
		                title: '型号',
		                width: 170,
		                align: 'left',
		                editor: 'text'
		            },{
		                field: 'packaging',
		                title: '封装',
		                width: 170,
		                align: 'left',
		                editor: 'text'
		            },{
		                field: 'total',
		                title: '数量',
		                width: 170,
		                align: 'left',
		                editor: 'text'
		            },{
		                field: 'serial_number',
		                title: '元件序号',
		                width: 170,
		                align: 'left',
		                editor: 'text'
		            },{
		                field: 'factory',
		                title: '厂 商',
		                width: 170,
		                align: 'left',
		                editor: 'text'
		            },{
		                field: 'description',
		                title: '备注',
		                width: 170,
		                align: 'left',
		                editor: 'text'
		            }]]
				});
				$('#configName'+semifinishedId).datagrid({
					title: "<span class='datagrid-row-collapse' style='display:inline-block;width:16px;height:16px;cursor:pointer;' onclick='configDetailToggle(this);'></span>"+idArray[i].split("&^")[1].split("#")[0]+
					"<div style='float:right'>需求数： <input id='semiF_num_"+semifinishedId+"' size=6 value="+num+" onkeyup=isNotANum(this) class='but_r' sf_id='"+semifinishedId+"'></div>"
				});
			}

		}
//			if(canOperate.indexOf('1') >= 0){
//				$('#t_record').datagrid('getPager').pagination({
//		            buttons: [
//		                      	{
//				                    iconCls: 'icon-add',
//				                    text: '添加项目',
//				                    handler: function(){
//				                    	insert_change();
//				                    }
//		            			}
//		                      ]
//		        });
//			}

	var width = $('#main').width()-70;
	var height = $('#main').height()-70;
	 $('#div_configuration').dialog({
		    title: '配置单',
		    iconCls: 'icon-ok',
		    width: width,
		    height: height,
//			    closed: false,
//			    cache: false,
		    modal: true,
            buttons:[{
            	text:'保存并统计用料',
            	iconCls:'icon-save',
            	handler:function(){
            		$.messager.confirm("提示", "确认需求？", function (result) {
	            		if(!result) {
	            			return;
	            		}
	            		$('#CPCode').yxcombogrid_product({
	        		    	hiddenId : 'productId',
	        				nameCol : 'productCode',
//		        				gridOptions : {
//		        					showcheckbox : false,
//		        					event : {
//		        						'row_dblclick' : function(e, row, data){
//		        							$('#CPCode').val(data.productCode);
//		        					  	}
//		        					}
//		        				},event : {
//		        					'clear' : function() {
//		        						$('#CPCode').val("");
//		        					}
//		        				}
	        		    });
//		            		$('#CPName').show();
	            		$('#finishedSave').show();
	            		$('#finishedSave').dialog({
	            			iconCls:'icon-search',
	            			title:'请输入成品名称',
	            			width:300,
	            			height:225,
	            			closed:false,
	            			modal:true,
	            			buttons:[{
	            	        	text:'保存',
	            	        	iconCls:'icon-save',
	            	        	handler:function(){
	            	        		var FName = $('#finishedName').val();
	            	        		var CPCode = $('#CPCode').val();
	            	        		if(FName){
	            	        			var datas='';
	        		            		for(var a in idArray){
	        		            			if( typeof idArray[a] != 'function'){
			    		            			semifinishedId = idArray[a].split("&^")[0];
			    		            			datas += '#'+semifinishedId;
			    		            			$("[name='semiF"+semifinishedId+"']:checked").each(function(){
			    		            				datas += ','+$(this).val();
			    		            			})
			    		            			datas += '^'+$('#semiF_num_'+semifinishedId).val();
			    		            			datas += '^'+idArray[a].split("&^")[1].split("#")[0];
	        		            			}
	        		            		}

	        		            		$.post(publicUrl+"&action=insert_details", { datas: datas ,FName:FName,CPCode:CPCode}, function (ID) {
		    		            			if(isNaN(ID)) {
		    		            				return $.messager.alert("失败", "出现错误！"+ID);
		    		            			}else{
		    		            				getBomDetail(ID,'post');
		    		            			}
	        		            		});
	            	        		}else{
	            	        			return $.messager.alert("提示", "成品名称不允许为空!");
	            	        		}

//		            	        		doCreate();
//		            	        		$('#div_inspect').html("");
//		            	        		closeDialog('div_inspect');
//		            	        		closeDialog('finishedSave');
//		            	        		materialsTree();

	            	        	}
	            	        }]
	            		});


//		            		$('#finishedName').combobox({
//		            		    url:publicUrl + "&action=finishedName",
//		            		    valueField:'id',
//		            		    textField:'name',
//		            		    width:'285'
//
//		            		});

//		            		var input = $("input[class=but_r]");
//		            		var datas = '';
//		            		input.each(function () {
//		            			datas += '#'+$(this).attr("sf_id")+'^'+$(this).val();
//		            		});

//		            		var datas = '';
//		            		for(var a in idArray){
//		            			semifinishedId = idArray[a].split("&^")[0];
//		            			datas += '#'+semifinishedId;
//		            			$("[name='semiF"+semifinishedId+"']:checked").each(function(){
//		            				datas += ','+$(this).val();
//		            			})
//		            			datas += '^'+$('#semiF_num_'+semifinishedId).val();
//		            			datas += '^'+idArray[a].split("&^")[1];
//		            		}
//
//		            		$.post(publicUrl+"&action=insert_details", { datas: datas ,name:}, function (ID) {
//
//		            			if(isNaN(ID)) {
//		            				return $.messager.alert("失败", "出现错误！"+ID);
//		            			}else{
//		            				getBomDetail(ID,'but');
////		            				statistical(ID);
////		            				return $.messager.alert("成功", "已保存");
//		            			}
//		            		});
            		});
            	}
            }]
		});
		$('#div_configuration').dialog('open');
	}else{
		return $.messager.alert("提示", "请选择半成品!");
	}
}
function statistical(finishedId){

	$('#div_configuration').html("");
	$('#div_configuration').datagrid({
		fitColumns: true,
		rownumbers: true,
		url: publicUrl+'&action=load_statistical&finishedId='+finishedId,
		columns: [[{
	        field: 'code',
	        title: '物料编码',
	        width: 100,
	        align: 'left',
	        editor: 'text'
	    }, {
	        field: 'name',
	        title: '名称',
	        width: 100,
	        align: 'left',
	        editor: 'textarea',
	    },{
	        field: 'model',
	        title: '型号',
	        width: 100,
	        align: 'left',
	        editor: 'text'
	    },{
	        field: 'packaging',
	        title: '封装',
	        width: 100,
	        align: 'left',
	        editor: 'text'
	    },{
	        field: 'serial_number',
	        title: '元件序号',
	        width: 100,
	        align: 'left',
	        editor: 'text'
	    },{
	        field: 'factory',
	        title: '厂 商',
	        width: 100,
	        align: 'left',
	        editor: 'text'
	    },{
	        field: 'unitName',
	        title: '最小单位',
	        width: 100,
	        align: 'left',
	        editor: 'text'
	    },{
	        field: 'actNum',
	        title: '库存数',
	        width: 100,
	        align: 'left',
	        editor: 'text'
	    },{
	        field: 'actTotal',
	        title: '实际需求',
	        width: 100,
	        align: 'left',
	        editor: 'text'
	    },{
	        field: 'allTotal',
	        title: '总需求(含损耗)',
	        width: 100,
	        align: 'left',
	        editor: 'text'
	    },{
	        field: 'stockIssueNum',
	        title: '发料数',
	        width: 100,
	        align: 'left',
	        editor: 'text'
	    },{
	        field: 'stockOutNum',
	        title: '缺货数',
	        width: 100,
	        align: 'left',
	        editor: 'text'
	    }]]
	});
	$('#div_configuration').dialog({
		title: '统计用料',
		iconCls: 'icon-ok',
		width: 1080,
		height: 800,
		closed: false,
		cache: false,
		modal: true,
        buttons:[{
        	text:'保存并统计用料',
         	iconCls:'icon-save',
         	handler:function(){

         	}
        }]
	});
//		$('#div_configuration').dialog('open');
}
function operationGrid() {
	$('#operation_table').datagrid({
		idField: 'id',
		singleSelect: true,
		toolbar: operationGridButton(),
		pagination: false
	});
}
function operationGridButton() {
	var button = new Array();
	button.push({
		iconCls: 'icon-add',
		text: '导入BOM',
		handler: function() {
			openImport(true);
		}
	});
/*	button.push({
		iconCls: 'icon-remove',
		text: '导出BOM',
		handler: function() {
			export_bom();
		}
	});*/
	button.push({
		iconCls: 'icon-remove',
		text: '删除半成品',
		handler: function() {
			deleteProduct();
		}
	});
	button.push({
		iconCls: 'icon-remove',
		text: '删除成品',
		handler: function() {
			deleteSF();
		}
	});
	button.push({
		iconCls: 'icon-ok',
		text: '生成配置单',
		handler: function() {
			beforeGenerateConfiguration();
		}
	});
	return button;
}
/*
 * 左侧树结构 parts
 */
function materialsTree() {
//	var tableName = "configuration_table",
//		action = "load_parts_list",

	$("#materials_tree").tree({
		url: publicUrl + "&action=load_materials_tree",
		lines: true,
		checkbox: true,
		onlyLeafCheck: true,
		onClick: function(node) {
			if (typeof(node.state) == "undefined") {
				var id = node.id.split("_")[0];
				var url = publicUrl + '&action=load_parts_SF';
				$.post(url, {id:id}, function(data) {
					$('#parts_table').datagrid({
						title: data[0]['name'] +' /'+ data[0]['code'] + ' <a href=# onclick=editSF('+id+');>编辑</a>',

					});
				},'json');
				getConfig(node.id.split("_")[0]);
			}
		},
		onLoadSuccess: function (t, datas) {
			conditions = "id=";
			if(typeof datas == "undefined" || typeof datas[0] == "undefined" || typeof datas[0]["children"] == "undefined" || typeof datas[0]["children"][0] == "undefined") {
				return;
			}
		}
	});
}
function editSF(id){
	var url = publicUrl + "&action=edit_SF";
	$.post(url, {id:id}, function(table) {

//		if(state == 'N'){
//			$.messager.alert('提示', '请更改不正确的物料编码！');
//		}else{
			$('#editSF').html('');
			$('#editSF').append(table);
			$('#editSF').dialog({
				iconCls:'icon-search',
				title:'编辑半成品',
				width:400,
				closed:false,
				modal:true,
				buttons:[{
		        	text:'保存修改',
		        	iconCls:'icon-save',
		        	handler:function(){
		        		var code = $('#SFCode').val();
		        		var info = $('#SFInfo').val();
		        		var name = $('#SFNameVal').val();
		        		var url = publicUrl + "&action=update_SF";
		        		$.post(url, {id:id,code:code,info:info,name:name}, function(state) {
		        			if(state == '1'){
		        				closeDialog('editSF');
		        				$.messager.alert('提示', '修改成功！');
		        				var url = publicUrl + '&action=load_parts_SF';
		        				$.post(url, {id:id}, function(data) {
		        					$('#parts_table').datagrid({
		        						title: data[0]['name'] +' /'+ data[0]['code'] + ' <a href=# onclick=editSF('+id+');>编辑</a>',

		        					});
		        				},'json');
		        			}else{
		        				$.messager.alert('提示', '修改失败！');
		        			}
		        		});
		        	}
		        }]
			});
			$('#SFCode').yxcombogrid_product({
		    	hiddenId : 'productId',
				nameCol : 'productCode',
//				gridOptions : {
//					showcheckbox : false,
//					event : {
//						'row_dblclick' : function(e, row, data){
//							$('#SFCode').val(data.productCode);
//					  	}
//					}
//				},event : {
//					'clear' : function() {
//						$('#SFCode').val("");
//					}
//				}
		    });
//		}
	});
}
function isNotANum(obj){
	obj.value = obj.value.replace(/[^\d]/g,'');
}
function getConfig(semifinishedId) {
	var height = $('#right').height()- 30;
	$('#config_table').datagrid({
	    url:publicUrl + "&action=load_config&semifinishedId=" + semifinishedId,
	    idField: 'id',
	    height: height,
		singleSelect: true,
	    columns:[[
	        {field:'name',title:'名称',width:140,align:'center'},
	        {field:'clash',title:'兼容',width:200,align:'center'},
	        {field:'description',title:'描述',width:500,align:'center'}
	    ]],
	    view: detailview,
	    detailFormatter: function(mainIndex, mainRow) {
	    	return '<div style="padding:2px"><table id="parts_grid_' + mainIndex + '"></table></div>';
	    },
	    onExpandRow: function(mainIndex, mainRow) {
	    	var subTable = 'parts_grid_' + mainIndex;
	    	$('#parts_grid_' + mainIndex).datagrid({
			url: publicUrl+"&action=load_parts&semifinishedId=" + semifinishedId + "&configId=" + mainRow.id + '&type=' + mainRow.type + '&listType=all',
			fitColumns: true,
			singleSelect: true,
			rownumbers: true,
			height: 'auto',
			idField: 'id',
			columns: [
				[{
	                field : 'id',
	                title : 'id',
	                hidden : true
	            },{
					field: 'code',
					title: '物料编码',
					align: 'center',
					width: 100,
					editor: {
						type: 'text'
					}
				}, {
					field: 'name',
					title: '名称',
					align: 'center',
					width: 100,
					editor: {
						type: 'text'
					}
				}, {
					field: 'model',
					title: '型号',
					align: 'center',
					width: 150,
					editor: {
						type: 'text'
					}
				}, {
					field: 'packaging',
					title: '封装',
					align: 'center',
					width: 100,
					editor: {
						type: 'text'
					}
				}, {
					field: 'total',
					title: '数量',
					align: 'center',
					width: 45,
					editor: {
						type: 'text'
					}
				}, {
					field: 'serial_number',
					title: '元件序号',
					align: 'center',
					width: 180,
					editor: {
						type: 'text'
					}
				}, {
					field: 'factory',
					title: '厂商',
					align: 'center',
					width: 100,
					editor: {
						type: 'text'
					}
				}, {
					field: 'description',
					title: '备注 ',
					align: 'center',
					width: 100,
					editor: {
						type: 'text'
					}
				}, {
					field: 'pickingInfo',
					title: '领料标识',
					align: 'center',
					width: 80,
					editor: {
						type: 'text'
					}
				}, {
					field: 'action',
					title: '操作',
					width: 100,
					align: 'center',
					formatter: function(value, row, index) {
						var action = "";
						if (row.editing) {
							action += "<a href='#' onclick='reviewGridSaveRow(" + index + ", \"" + subTable + "\", \"" + mainRow.type + "\", 0);' >保存</a> ";
							action += "<a href='#' onclick='reviewGridCancelRow(" + index + ", \"" + subTable + "\");' >取消</a> ";
						} else {
							action += "<a href='#' onclick='reviewGridEditRow(" + index + ", \"" + subTable + "\");' >编辑</a> ";
						}
						return action;
					}
				}]
			],
			onResize: function() {
				$('#review_grid').datagrid('fixDetailRowHeight', mainIndex);
			},
			onLoadSuccess: function() {
				setTimeout(function() {
					$('#review_grid').datagrid('fixDetailRowHeight', mainIndex);
				}, 0);
			},
			onBeforeEdit: function(index, row) {
				row.editing = true;
				$('#' + subTable).datagrid('refreshRow', index);
			},
			onAfterEdit: function(index, row) {
				row.editing = false;
				$('#' + subTable).datagrid('refreshRow', index);
			},
			onCancelEdit: function(index, row) {
				row.editing = false;
				$('#' + subTable).datagrid('refreshRow', index);
			}
		});
		$('#review_grid').datagrid('fixDetailRowHeight', mainIndex);
	}
	});




//	var productId = 0,
//		detailBox = $("#bom_detail_box"),
//		url,
//		type,
//		action;
//
//	if(operation == "view") {
//		productId = $("#current_product_id").val();
//	}
//
//	if(!productId) {
//		return;
//	}

//	type = productId.split("_")[1];
//	action = type == 0 ? "view_tags" : "view_more_tags";
//	var url = publicUrl + "&action=loca_config&product_id=" + productId;
//	$.post(url, {}, function (result) {
//		var html = "";
//
//		$("#bom_detail_box").show();
//		if(result == "") {
//			$("#bom_detail_box").html("此配置数据为空");
//			return;
//		}
//
//		result = "<div style='margin-bottom: 5px;'>" + result + "</div>";
//
//		var grid = "<table id='review_grid' style='height: 500px;'></table>",
//			startTag = 0,
//			table = "",
//			tagButton;
//
//		table += "<table style='width:100%;'>";
//		table += "<tr>";
//		table += "<td align='center' colspan='1'>";
//		table += "<input type='button' id='saveButton' name='saveButton' value='生成配置单' onclick='openCreateConfigListDialog();' />";
//		table += "<input type='button' id='closeButton' name='closeButton' value='关闭' onclick='closePublicDialog();' />";
//		table += "</td>";
//		table += "</tr>";
//		table += "</table>";
//
//		tagButton = document.getElementsByName('import_sheet_tag[]');
//		if (tagButton.length > 0) {
//			var len = 'import_sheet_'.length;
//			var startTag = tagButton[0].id.substring(len);
//		}
//
//		html = result + grid + table;
//		detailBox.html(html);
//		reviewGrid(operation, startTag);
//
//		processReviewTag($(result).find("input").get(0), 'view');
//	}, "html");
}

//打开导入对话框
function openImport(isCreate) {
	$('#open-import').window('open');
//	var title = '导入BOM';
//	var url = publicUrl + '&action=import_excel';
//	var data = "<form id='uploadForm' name='uploadForm' method='post' action='" + url + "' enctype='multipart/form-data' target='public_iframe' >" + "<table width='90%'>" + "<tr>" + "<td><input type='file' id='upload_excel_file' name='upload_excel_file' /></td>" + "</tr>" + "<tr>" + "<td align='center'>" + "<input type='submit' id='uploadButton' name='uploadButton' value='上传' />" + "<input type='reset' id='uploadtResetButton' name='uploadtResetButton' value='重置' />" + "<input type='button' id='closeButton' name='closeButton' value='关闭' onclick='closePublicDialog();' />" + "</td>" + "</tr>" + "</table>" + "</form>";
//	data += "<div align='right'><a href='view/template/stock/material/template/bom_template.xlsx'>BOM导入模板</a></div>";
//	$('#dialog_data_board').html(data);
//	openDialog('public_dialog', title, '', '');
}

//导入后数据检查
function openReviewDialog() {
//	closePublicDialog();
//	openDialog('', '检查导入清单信息', 'full', 'full');
	$('#upload_excel_file').val('');
	closeDialog('open-import');
	$('#div_inspect').dialog({
		iconCls:'icon-search',
		title:'检查导入清单信息',
		width:1070,
		height:700,
		closed:false,
		buttons:[{
        	text:'确认',
        	iconCls:'icon-save',
        	handler:function(){

//        		doCreate();
//        		$('#div_inspect').html("");
//        		closeDialog('div_inspect');
//        		materialsTree();
        		addFinishedName();
        	}
        }]
	});

	var url = publicUrl + "&action=view_tags&operation=import";
	$('#div_inspect_load').load(url, function() {
		var grid = "<table id='review_grid' style='width:1050px; height:600px;' ></table>";
		$('#div_inspect_load').append(grid);
		reviewGrid('import', 0);
	});
}

function doCreate() {

//	var FNId = $('#finishedName').combobox('getValue');

	var selC = $("input[name*=pCode]");
	var sf = '';
	selC.each(function () {
		if($(this).val() != 'undefined'){
			sf += ','+$(this).val();
		}else{
			$.messager.alert('提示','请选择半成品!');
			return;
		}
	});

	var selI = $("select[name*=pInfo]");
	var si = '';
	selI.each(function () {
		if($(this).val() != 'undefined'){
			si += ','+$(this).val();
		}else{
			si += ',否';
		}
	});

	var cps_v = $("#FPCode").val();
	var cpsi_v = $("#CPSI").val();
	var cps_i = $('#CPSI').attr('readonly');
	var cpsn_v = $("#CPSN").val();

	if(!cps_i == 'readonly'){
		$.messager.alert('提示','请选择或输入成品名!');
	}else{
		var url = publicUrl + "&action=create_product_and_save_session";
		$("#loading").removeClass("hidden");
		$.post(url, {SFC:sf,SFI:si,CPSI:cpsi_v,CPSC:cps_v,CPSN:cpsn_v}, function(id) {
			$("#loading").addClass("hidden");
			if (id != 0) {
				$.messager.alert("提示", "添加成功!");
				closeDialog('finishedInfo');
        		closeDialog('div_inspect');
//        		closeDialog('finishedSave');

			} else {
				$.messager.alert("提示", "数据新增失败!");
			}

		});
	}
}

function addFinishedName(){
	var url = publicUrl + "&action=detection_import_session";
	$.post(url, {}, function(state) {

		if(state == 'N'){
			$.messager.alert('提示', '请更改不正确的物料编码！');
		}else{
			$('#finishedInfo').html('');
			$('#finishedInfo').append(state);
			$('#finishedInfo').dialog({
				iconCls:'icon-search',
				title:'成品信息',
				width:400,
//				height:210,
				closed:false,
				modal:true,
				buttons:[{
		        	text:'保存清单',
		        	iconCls:'icon-save',
		        	handler:function(){

		        		doCreate();
		        		materialsTree();

		        	}
		        }]
			});

			var a = 1;
			$('input[name*=pCode]').each(function(){
				$(this).yxcombogrid_product({
			    	hiddenId : 'productId',
					nameCol : 'productName',
					nameCol : 'productCode',
//					gridOptions : {
//						showcheckbox : false,
//						event : {
//							'row_dblclick' : function(e, row, data){
//								$('input[name=pCode'+ (a++) +']').val(data.productCode);
//								alert(a);
//						  	}
//						}
//					},event : {
//						'clear' : function() {
//							$('input[name=pCode'+ (a-1) +']').val("");
//						}
//					}

			    });
			});
			$('#FPCode').yxcombogrid_product({
		    	hiddenId : 'productId',
				nameCol : 'productCode',
//				gridOptions : {
//					showcheckbox : false,
//					event : {
//						'row_dblclick' : function(e, row, data){
//							$('#FPCode').val(data.productCode);
//					  	}
//					}
//				},event : {
//					'clear' : function() {
//						$('#FPCode').val("");
//					}
//				}
		    });
//			$("#pName").yxcombogrid_product("show");
//			$('#finishedName').combobox({
//			    url:publicUrl + "&action=finishedName",
//			    valueField:'id',
//			    textField:'name',
//			    width:'285'
//			});
		}


	});





//	var url = publicUrl + "&action=view_tags&operation=import";
//	$('#div_inspect_load').load(url, function() {
//		var grid = "<table id='review_grid' style='width:1050px; height:600px;' ></table>";
//		$('#div_inspect_load').append(grid);
//		reviewGrid('import', 0);
//	});

}

//删除产品

function deleteProduct() {

	var nodes = $("#materials_tree").tree("getChecked"),id_name = String(getIds(nodes));
	var idNA = id_name.split(','),idA,id = '';

	for(var a=0;a<idNA.length;a++){
		idA = idNA[a].split('&^');
		id += idA[0]+',';
	}

	if (id_name) {
		var url = publicUrl + '&action=delete_SF';
		$.messager.confirm('提示', '确定要删除该半成品吗？', function(result) {
			if (result) {
				$.post(url, { id: id }, function(data) {
					$.messager.alert('操作提示', '删除成功');
					materialsTree();
				});
			}
		});

	} else {
		alertResult('请选择需要删除的半成品！');
	}
}

//关闭对话框
function closePublicDialog() {
	$('#dialog_data_board').html("");
	closeDialog('public_dialog');
}

function addCP(a) {

	if(a == 'a'){
		$('#addCP').attr('style','display:none');
		$('#cTd').attr('style','display:none');
		$('#cancelCP').attr('style','');
		$('#addTd').attr('style','');
		$('#CPSI').removeAttr('readonly','')
	}else{
		$('#cancelCP').attr('style','display:none');
		$('#addTd').attr('style','display:none');
		$('#addCP').attr('style','');
		$('#cTd').attr('style','');
		$('#CPSI').attr('readonly','readonly');
		$('#CPSI').val('');
	}
}

function deleteSF(){
	$("#F_tree").tree({
		url: publicUrl + "&action=load_SF_tree",
		lines: true,
		checkbox: true,
		onlyLeafCheck: true,
		onClick: function(node) {
		},
		onLoadSuccess: function (t, datas) {
			conditions = "id=";
			if(typeof datas == "undefined" || typeof datas[0] == "undefined" || typeof datas[0]["children"] == "undefined" || typeof datas[0]["children"][0] == "undefined") {
				return;
			}
		}
	});

	$('#FDIV').dialog({
		iconCls:'icon-remove',
		title:'删除成品',
		width:400,
		height:500,
		closed:false,
		buttons:[{
        	text:'删除',
        	iconCls:'icon-save',
        	handler:function(){
        		var nodes = $("#F_tree").tree("getChecked"),id_name = String(getIds(nodes));
        		var idNA = id_name.split(','),idA,id = '';
        		for(var a=0;a<idNA.length;a++){
        			idA = idNA[a].split('&^');
        			id += idA[0]+',';
        		}

        		if (id_name) {
        			var url = publicUrl + '&action=delete_finished';
        			$.messager.confirm('提示', '确定要删除该成品吗？', function(result) {
        				if (result) {
        					$.post(url, { id: id }, function(data) {
        						$.messager.alert('操作提示', '删除成功');
        						closeDialog('FDIV');
        						materialsTree();
        					});
        				}
        			});

        		} else {
        			alertResult('请选择需要删除的成品！');
        		}
        	}
        }]
	});
}
//配置单生成页显示/隐藏物料信息
function configDetailToggle(obj){
	if($(obj).attr("class") == "datagrid-row-expand"){
		$(obj).attr("class","datagrid-row-collapse").parents(".panel .datagrid").next(".panel .datagrid").show();
	}else{
		$(obj).attr("class","datagrid-row-expand").parents(".panel .datagrid").next(".panel .datagrid").hide();
	}
}
