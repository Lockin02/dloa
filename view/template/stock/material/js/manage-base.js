//获取browse页面高度
function getBrowseHeight(){
	return document.documentElement.clientHeight;
}

//获取browse页面宽度
function getBrowseWidth(){
	return document.documentElement.clientWidth;
}

//设置主版面左边层高度
function setLeftDivHeight(){
	height = getBrowseHeight() - 50;
	//$('#left').css('height', height);
}

//获取某div宽度
function getDivWitdh(divName){
	return $('#' + divName).width();
}

//打开背景
function openBackgroundDiv(){
	$('#background_div').show();
}

//关闭背景
function closeBackgroundDiv(){
	$('#background_div').hide();
	if(!$('#background_div').is(':hidden')){
		$('#background_div').hide();
	}
}

//打开对话框
function openDialog(dialogName, title, width, height){
	if(width == 'full'){
		widthVal = getBrowseWidth();
	}else{
		widthVal = 400;
	}

	if(height == 'full'){
		heightVal = getBrowseHeight();
	}else{
		heightVal = 200;
	}

	$('#' + dialogName).dialog({
		width:widthVal,
		height:heightVal,
		title:title,
		closed:false
	});
}

//关闭对话框
function closeDialog(dialogName){
	$('#' + dialogName).dialog('close');
}

//刷新Gird
function reloadGrid(gridName){
	$('#' + gridName).datagrid('reload');
}

//刷新Tree
function reloadTree(TreeName){
	$('#' + TreeName).tree('reload');
}

//获取Grid当前选中行
function getGridRow(gridName){
	return $('#' + gridName).datagrid('getSelected');
}

//获取Tree当前选中行
function getTreeRow(treeName){
	return $('#' + treeName).tree('getSelected');
}

//提示
function alertResult(msg){
	$.messager.alert('错误提示',msg);
}

//Tag切换
function processReviewTag(e, operation){
	var len = 'import_sheet_'.length;
	var reviewTagId = e.id.substring(len);
	var sheetList = document.getElementsByName('import_sheet_tag[]');
	for(var i = 0; i < sheetList.length; i++){
		id = sheetList[i].id.substring(len);
		if(id == reviewTagId){
			document.getElementById("import_sheet_" + id).style.backgroundColor = "#B0C4DE";
		}else{
			document.getElementById("import_sheet_" + id).style.backgroundColor = "buttonface";
		}
	}
	reviewGrid(operation, reviewTagId);
}

function getInputVal(){
	var input = $("input[class=but_r]");
	var values = id = val ='';
	input.each(function () {
		values += $(this).attr("sf_id")+'&'+$(this).val()+'^';
	});
	return values;
}

function getBomDetail(id,state) {
	if(state == 'but' || state == 'post'){
		var vals = getInputVal();
	}

	var width = $('#right').width();
	var height = $('#right').height()-30;

	url = "?model=stock_material_management&action=view_more_tags";
	$.post(url,{id:id,val:vals}, function (table) {


		if(state != 'post'){
			$('#div_statistics').html('');
			$("<input id='FId' value='"+id+"' type='hidden'>").appendTo($('#div_statistics'));
			$(table).appendTo($('#div_statistics'));
			$('#div_statistics').panel({
				  width:width,
				  height:height,
			});
			$('#r_button').show();
			$("#but").attr("onclick","updateBomDetail("+id+",'but')");
			$("#picking").attr("onclick","picking("+id+")");
			$("#purchase").attr("onclick","purchase("+id+")");
			$("#butRemove").attr("onclick","statisticsRemove("+id+")");
			$("#export").attr("onclick","statisticsExport("+id+")");
		}else{
			closeDialog('div_configuration');
			closeDialog('finishedSave');
			if($('#div_statistics > table').height() > 0){
				height = ($('#div_statistics > table').height() < height)?($('#div_statistics > table').height()+70):height;
			}
			if($("#div_statistics").html()){
				$("#div_statistics > div > div").html(table);
				$("<input id='FId' value='"+id+"' type='hidden'>").appendTo($('#div_statistics > div > div'));
			}else{
				$("<input id='FId' value='"+id+"' type='hidden'>").appendTo($('#div_statistics'));
				$(table).appendTo($('#div_statistics'));
			}
			width = $('#main').width()-50;
			$('#div_statistics').dialog({
				iconCls:'icon-ok',
			    title: '统计用料',
			    width: width,
			    height: height,
			    modal:true,
			    buttons:[{
					text:'统计用料',
					iconCls:'icon-ok',
					handler:function(){
						updateBomDetail(id,'post');
					}
				}
//				,{
//					text:'领料',
//					handler:function(){
//						 picking(id);
//					}
//				},{
//					text:'采购',
//					handler:function(){
//						purchase(id);
//					}
//				}
				]
			});
		}
	});

}
function statisticsRemove(id){
	if(id){
		$.messager.confirm('提示','确定删除此物料统计数据吗?',function(r){
			if(r){
				url = "?model=stock_material_management&action=statistics_remove";
				$.post(url,{id:id}, function (data) {
					if(data == '1'){
//						$('#div_statistics').html('');
//						$('#r_button').hide();
//						materialsTree();
						alert('删除成功!');
						location.reload();
					}else{
						$.messager.alert('提示','删除失败,请刷新页面重新操作!');
					}
				});
			}

		});
	}else{
		$.messager.alert('提示','删除失败,请刷新页面重新操作!');
	}
}
function picking(id){
	var FId = new Array();
	FId[0] = [];
	FId[0][0] = [];

	$.messager.confirm('提示','请确认是否已统计用料?',function(r){
		if(r){
			FId[0][0]['bomConfigId'] = $("#FId").val();
			var vals = getInputVal();
			var input = $("input[name=items]");
			var html = val = '';
			input.each(function () {
				if($(this).attr("checked") == 'checked'){
					val = $('#issue'+$(this).val()).html();
					html += "<input type=\"hidden\" name=\"" + $(this).val() + "\" value=\"" + val + "\" />";
					FId[0][0][$(this).val()] = val;
				}
			});

			//弹出领料页面，领料接口
			openPostWindow("?model=produce_plan_picking&action=toAddByMaterial", FId, '领料');
			// var url = "?model=stock_material_management&action=picking_record";
			// $.post(url,{id:id,vals:vals},function(data){
			// 	if(data > 0){
			// 		$.messager.alert('提示','领料记录已生成，请在领料页面进行后续操作!');
			// 		}else{
			// 			$.messager.alert('提示','领料失败，请重新操作!');
			// 		}
			// });
		}
	});
//	var datas = $("#configuration_table").datagrid("getData")["rows"],
//		form = $("#buyForm").html(""),
//		data,
//		value,
//		name,
//		input,
//		html = "";
//
//	for(var i in datas) {
//		data = datas[i];
//		for(var filed in data) {
//			name = "rows[" + i + "][" + filed + "]";
//			value = data[filed];
//			input = "<input type=\"hidden\" name=\"" + name + "\" value=\"" + value + "\" />";
//			html += input;
//		}
//	}
//
//	form.html(html);
//	form.submit();

}
function purchase(id){
	var FId = new Array();
	FId[0] = [];
	FId[0][0] = [];

	$.messager.confirm('提示','请确认是否已统计用料?',function(r){
		if(r){
			FId[0][0]['planId'] = $("#FId").val();
			var vals = getInputVal();
			var input = $("input[name=items]");
			var html = val = '';
			input.each(function () {
				if($(this).attr("checked") == 'checked'){
					// val = $('#lack'+$(this).val()).html();
					val = $('#lack'+$(this).val()).parent().next().children(':eq(0)').val();
					FId[0][0][$(this).val()] = val;
				}
			});

			//弹出采购页面，采购接口
			openPostWindow("?model=purchase_external_external&action=toAddByMaterial", FId, '采购');
		}
	});
}
//导出
function statisticsExport(id){
	if(id){
		if (confirm('您确定要导出当前数据吗？')) {
			location.href = "?model=stock_material_management&action=statistics_export&id=" + id;
		}
	}else{
		$.messager.alert('提示','导出失败,请刷新页面重新操作!');
	}
}

//统计用料
function updateBomDetail(id,state){
	if(id){
		var input = $("#div_statistics input[class=but_r]");
		var vals = '';
		input.each(function () {
			vals += $(this).attr("sf_id")+'^'+$(this).val()+'#';
		});
		vals = vals.substring(0,vals.length-1);
		if(vals != ""){
			if (confirm('您确定要统计用料吗？确定后会更新系统数据。')) {
	    		$.post("?model=stock_material_material&action=update_details", { id: id ,val:vals}, function (msg) {
	    			if(msg == '0') {
	    				getBomDetail(id,state);
	    			}else{
	    				return $.messager.alert("失败", "出现错误!");
	    			}
	    		});
			}
		}else{
			$.messager.alert('提示','没有可更新的数据!');
		}
	}else{
		$.messager.alert('提示','统计用料失败,请刷新页面重新操作!');
	}
}