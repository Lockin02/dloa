//清单表格
function reviewGrid(operation, tag) {

	var url = publicUrl + '&action=review_table&operation=' + operation + '&tag=' + tag,  //主表链接
	subGridUrl = publicUrl + '&action=sub_review_table&operation=' + operation + '&tag=' + tag;  //子表链接

	$('#review_grid').datagrid({
		idField: 'id',
		singleSelect: true,
		url: url,
		columns: [
			[{
				field: 'name',
				title: '名称',
				align: 'center',
				width: 200
			}, {
				field: 'clash',
				title: '兼容',
				align: 'center',
				width: 300
			}, {
				field: 'description',
				title: '描述',
				align: 'center',
				width: 500
			}]
		],
		view: detailview,
		detailFormatter: function(mainIndex, mainRow) {
			return '<div style="padding:2px"><table id="sub_review_grid_' + mainIndex + '"></table></div>';
		},
		onExpandRow: function(mainIndex, mainRow) {
			var subTable = 'sub_review_grid_' + mainIndex;
			$('#sub_review_grid_' + mainIndex).datagrid({
				url: subGridUrl + '&id=' + mainRow.id + '&type=' + mainRow.type,
				fitColumns: true,
				singleSelect: true,
				rownumbers: true,
				height: 'auto',
				idField: 'serial_number',
				nowrap: false,
				rowStyler: function(index, row) {
					if (row.is_right != "Y" && operation != "view") {
						return 'background-color:#6293BB;color:#fff;';
					}
				},
				columns: [
					[{
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
						width: 100
					}, {
						field: 'model',
						title: '型号',
						align: 'center',
						width: 150
					}, {
						field: 'packaging',
						title: '封装',
						align: 'center',
						width: 100
					}, {
						field: 'total',
						title: '数量',
						align: 'center',
						width: 45
					}, {
						field: 'serial_number',
						title: '元件序号',
						align: 'center',
						width: 180
					}, {
						field: 'factory',
						title: '厂商',
						align: 'center',
						width: 100
					}, {
						field: 'description',
						title: '备注 ',
						align: 'center',
						width: 100
					}, {
						field: 'pickingInfo',
						title: '领料标识',
						align: 'center',
						width: 60
					}, {
						field: 'action',
						title: '操作',
						width: 100,
						align: 'center',
						formatter: function(value, row, index) {
							var action = "";
							if (operation != "view") {
								if (row.editing) {
									action += "<a href='#' onclick='reviewGridSaveRow(" + index + ", \"" + subTable + "\", \"" + mainRow.type + "\", " + tag + ");' >保存</a> ";
									action += "<a href='#' onclick='reviewGridCancelRow(" + index + ", \"" + subTable + "\");' >取消</a> ";
								} else {
									action += "<a href='#' onclick='reviewGridEditRow(" + index + ", \"" + subTable + "\");' >编辑</a> ";
								}
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
					var d = $("#" + subTable).find("tr[datagrid-row-index=\"" + index + "\"]");
					console.log(d);
					$("#" + subTable).parent().find("tr[datagrid-row-index=" + index + "]").removeAttr('style');
				},
				onCancelEdit: function(index, row) {
					row.editing = false;
					$('#' + subTable).datagrid('refreshRow', index);
				}
			});
			$('#review_grid').datagrid('fixDetailRowHeight', mainIndex);
		}
	});
}

//编辑清单表格内容
function reviewGridEditRow(index, tableName) {
	$('#' + tableName).datagrid('beginEdit', index);
}

//删除清单表格内容
/*
function reviewGridDeleteRow(index, tableName){
    $.messager.confirm('Confirm', 'Are you sure?', function(r){
        if (r){
            $('#' + tableName).datagrid('deleteRow', index);
        }
    });
}
*/

//保存清单表格内容

function reviewGridSaveRow(subTableIndex, subTableName, mainTableType, mainTag) {
//	var editor = $('#' + subTableName).datagrid('getEditor', {
//		index: subTableIndex,
//		field: 'code'
//	});
//	var editorVal = editor.target.val();

	$('#' + subTableName).datagrid('endEdit', subTableIndex);
	$('#' + subTableName).datagrid('selectRow', subTableIndex);
	var row = $('#' + subTableName).datagrid('getSelected');
	var editType = '';
	if(mainTableType != 'undefined'){//导入后未存入数据库，只更新到session
		editType = 'session';
	}

	var url = publicUrl + '&action=edit_Material_detail';
	var data = {
			id				: row.id,
			code			: row.code,
			name			: row.name,
			model			: row.model,
			packaging		: row.packaging,
			total			: row.total,
			serial_number	: row.serial_number,
			factory			: row.factory,
			description		: row.description,
			pickingInfo		: row.pickingInfo,
			subTableIndex   : subTableIndex,
			subTableName   	: subTableName,
			mainTableType   : mainTableType,
			mainTag   		: mainTag,
			editType        : editType
//			stock_index: subTableIndex,
//			main_type: mainTableType,
//			main_tag: mainTag
	};
	$("#loading").removeClass("hidden");
	$.post(url, data, function(result) {
		if(result == '1'){
			$("#loading").addClass("hidden");
			$('#' + subTableName).datagrid('endEdit', subTableIndex);
			$.messager.alert("提示信息", "保存成功");
		}
		if(result == '2'){
			$.messager.alert("提示信息", "此编号物料不存在");
			$('#' + subTableName).datagrid('updateRow',{//将编号设为空
				index: subTableIndex,
				row: {
					code: ''
				}
			});
			$('#' + subTableName).datagrid('beginEdit', subTableIndex);
		}
	});
}

//取消改变清单表格内容
function reviewGridCancelRow(index, tableName) {
	$('#' + tableName).datagrid('cancelEdit', index);
}

function statisticsForm(code,num,id) {
	var width = $('#right').width();
	var height = $('#right').height()-30;

	url = "?model=stock_material_management&action=view_more_tags";
	$.post(url,{code:code,num:num,cid:id}, function (table) {
			if(table == 0){
				return $.messager.alert("提示信息", "不存在此配置，请先配置！");
			}
			closeDialog('div_configuration');
			closeDialog('finishedSave');
//			if($('#div_statistics > table').height() > 0){
//				height = ($('#div_statistics > table').height() < height)?($('#div_statistics > table').height()+70):height;
//			}
			if($("#div_statistics").html()){
				$("#div_statistics > div > div").html(table);
			}else{
				$(table).appendTo($('#div_statistics'));
			}
			width = $('#main').width()-50;
			$('#div_statistics').dialog({
				iconCls:'icon-ok',
			    title: '统计用料',
			    width: width,
			    height: height,
			    modal:true,
			    buttons:[
//			             {
//					text:'统计用料',
//					iconCls:'icon-ok',
//					handler:function(){
//						statisticsForm(code,num);
//					}
//				},
//				{
//					text:'领料',
//					iconCls:'icon-ok',
//					handler:function(){
//						 picking(id);
//					}
//				},{
//					text:'采购',
//					iconCls:'icon-ok',
//					handler:function(){
//						purchase(id);
//					}
//				}
				]
			});
	});

}

function statistics_form(code,num,id) {
	var width = $('#main').width();
	var height = $('#main').height()-40;

	url = "?model=stock_material_management&action=view_more_tags";
	$.post(url,{code:code,num:num,cid:id}, function (table) {
		if(table == 0){
			return $.messager.alert("提示信息", "不存在此配置，请先配置！");
		}

		$('#div_statistics').html('');
		$("<input id='FId' value='"+id+"' type='hidden'>").appendTo($('#div_statistics'));
		$(table).appendTo($('#div_statistics'));
		$('#div_statistics').panel({
			  width:width,
			  height:height
		});
		$('#r_button').show();
		$("#but").attr("onclick","getBomDetail("+id+",'but')");
		$("#picking").attr("onclick","picking("+id+")");
		$("#purchase").attr("onclick","purchase("+id+")");
		$("#butRemove").attr("onclick","statisticsRemove("+id+")");

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