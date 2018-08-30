//�嵥���
function reviewGrid(operation, tag) {

	var url = publicUrl + '&action=review_table&operation=' + operation + '&tag=' + tag,  //��������
	subGridUrl = publicUrl + '&action=sub_review_table&operation=' + operation + '&tag=' + tag;  //�ӱ�����

	$('#review_grid').datagrid({
		idField: 'id',
		singleSelect: true,
		url: url,
		columns: [
			[{
				field: 'name',
				title: '����',
				align: 'center',
				width: 200
			}, {
				field: 'clash',
				title: '����',
				align: 'center',
				width: 300
			}, {
				field: 'description',
				title: '����',
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
						title: '���ϱ���',
						align: 'center',
						width: 100,
						editor: {
							type: 'text'
						}
					}, {
						field: 'name',
						title: '����',
						align: 'center',
						width: 100
					}, {
						field: 'model',
						title: '�ͺ�',
						align: 'center',
						width: 150
					}, {
						field: 'packaging',
						title: '��װ',
						align: 'center',
						width: 100
					}, {
						field: 'total',
						title: '����',
						align: 'center',
						width: 45
					}, {
						field: 'serial_number',
						title: 'Ԫ�����',
						align: 'center',
						width: 180
					}, {
						field: 'factory',
						title: '����',
						align: 'center',
						width: 100
					}, {
						field: 'description',
						title: '��ע ',
						align: 'center',
						width: 100
					}, {
						field: 'pickingInfo',
						title: '���ϱ�ʶ',
						align: 'center',
						width: 60
					}, {
						field: 'action',
						title: '����',
						width: 100,
						align: 'center',
						formatter: function(value, row, index) {
							var action = "";
							if (operation != "view") {
								if (row.editing) {
									action += "<a href='#' onclick='reviewGridSaveRow(" + index + ", \"" + subTable + "\", \"" + mainRow.type + "\", " + tag + ");' >����</a> ";
									action += "<a href='#' onclick='reviewGridCancelRow(" + index + ", \"" + subTable + "\");' >ȡ��</a> ";
								} else {
									action += "<a href='#' onclick='reviewGridEditRow(" + index + ", \"" + subTable + "\");' >�༭</a> ";
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

//�༭�嵥�������
function reviewGridEditRow(index, tableName) {
	$('#' + tableName).datagrid('beginEdit', index);
}

//ɾ���嵥�������
/*
function reviewGridDeleteRow(index, tableName){
    $.messager.confirm('Confirm', 'Are you sure?', function(r){
        if (r){
            $('#' + tableName).datagrid('deleteRow', index);
        }
    });
}
*/

//�����嵥�������

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
	if(mainTableType != 'undefined'){//�����δ�������ݿ⣬ֻ���µ�session
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
			$.messager.alert("��ʾ��Ϣ", "����ɹ�");
		}
		if(result == '2'){
			$.messager.alert("��ʾ��Ϣ", "�˱�����ϲ�����");
			$('#' + subTableName).datagrid('updateRow',{//�������Ϊ��
				index: subTableIndex,
				row: {
					code: ''
				}
			});
			$('#' + subTableName).datagrid('beginEdit', subTableIndex);
		}
	});
}

//ȡ���ı��嵥�������
function reviewGridCancelRow(index, tableName) {
	$('#' + tableName).datagrid('cancelEdit', index);
}

function statisticsForm(code,num,id) {
	var width = $('#right').width();
	var height = $('#right').height()-30;

	url = "?model=stock_material_management&action=view_more_tags";
	$.post(url,{code:code,num:num,cid:id}, function (table) {
			if(table == 0){
				return $.messager.alert("��ʾ��Ϣ", "�����ڴ����ã��������ã�");
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
			    title: 'ͳ������',
			    width: width,
			    height: height,
			    modal:true,
			    buttons:[
//			             {
//					text:'ͳ������',
//					iconCls:'icon-ok',
//					handler:function(){
//						statisticsForm(code,num);
//					}
//				},
//				{
//					text:'����',
//					iconCls:'icon-ok',
//					handler:function(){
//						 picking(id);
//					}
//				},{
//					text:'�ɹ�',
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
			return $.messager.alert("��ʾ��Ϣ", "�����ڴ����ã��������ã�");
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