//��ȡbrowseҳ��߶�
function getBrowseHeight(){
	return document.documentElement.clientHeight;
}

//��ȡbrowseҳ����
function getBrowseWidth(){
	return document.documentElement.clientWidth;
}

//������������߲�߶�
function setLeftDivHeight(){
	height = getBrowseHeight() - 50;
	//$('#left').css('height', height);
}

//��ȡĳdiv���
function getDivWitdh(divName){
	return $('#' + divName).width();
}

//�򿪱���
function openBackgroundDiv(){
	$('#background_div').show();
}

//�رձ���
function closeBackgroundDiv(){
	$('#background_div').hide();
	if(!$('#background_div').is(':hidden')){
		$('#background_div').hide();
	}
}

//�򿪶Ի���
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

//�رնԻ���
function closeDialog(dialogName){
	$('#' + dialogName).dialog('close');
}

//ˢ��Gird
function reloadGrid(gridName){
	$('#' + gridName).datagrid('reload');
}

//ˢ��Tree
function reloadTree(TreeName){
	$('#' + TreeName).tree('reload');
}

//��ȡGrid��ǰѡ����
function getGridRow(gridName){
	return $('#' + gridName).datagrid('getSelected');
}

//��ȡTree��ǰѡ����
function getTreeRow(treeName){
	return $('#' + treeName).tree('getSelected');
}

//��ʾ
function alertResult(msg){
	$.messager.alert('������ʾ',msg);
}

//Tag�л�
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
			    title: 'ͳ������',
			    width: width,
			    height: height,
			    modal:true,
			    buttons:[{
					text:'ͳ������',
					iconCls:'icon-ok',
					handler:function(){
						updateBomDetail(id,'post');
					}
				}
//				,{
//					text:'����',
//					handler:function(){
//						 picking(id);
//					}
//				},{
//					text:'�ɹ�',
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
		$.messager.confirm('��ʾ','ȷ��ɾ��������ͳ��������?',function(r){
			if(r){
				url = "?model=stock_material_management&action=statistics_remove";
				$.post(url,{id:id}, function (data) {
					if(data == '1'){
//						$('#div_statistics').html('');
//						$('#r_button').hide();
//						materialsTree();
						alert('ɾ���ɹ�!');
						location.reload();
					}else{
						$.messager.alert('��ʾ','ɾ��ʧ��,��ˢ��ҳ�����²���!');
					}
				});
			}

		});
	}else{
		$.messager.alert('��ʾ','ɾ��ʧ��,��ˢ��ҳ�����²���!');
	}
}
function picking(id){
	var FId = new Array();
	FId[0] = [];
	FId[0][0] = [];

	$.messager.confirm('��ʾ','��ȷ���Ƿ���ͳ������?',function(r){
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

			//��������ҳ�棬���Ͻӿ�
			openPostWindow("?model=produce_plan_picking&action=toAddByMaterial", FId, '����');
			// var url = "?model=stock_material_management&action=picking_record";
			// $.post(url,{id:id,vals:vals},function(data){
			// 	if(data > 0){
			// 		$.messager.alert('��ʾ','���ϼ�¼�����ɣ���������ҳ����к�������!');
			// 		}else{
			// 			$.messager.alert('��ʾ','����ʧ�ܣ������²���!');
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

	$.messager.confirm('��ʾ','��ȷ���Ƿ���ͳ������?',function(r){
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

			//�����ɹ�ҳ�棬�ɹ��ӿ�
			openPostWindow("?model=purchase_external_external&action=toAddByMaterial", FId, '�ɹ�');
		}
	});
}
//����
function statisticsExport(id){
	if(id){
		if (confirm('��ȷ��Ҫ������ǰ������')) {
			location.href = "?model=stock_material_management&action=statistics_export&id=" + id;
		}
	}else{
		$.messager.alert('��ʾ','����ʧ��,��ˢ��ҳ�����²���!');
	}
}

//ͳ������
function updateBomDetail(id,state){
	if(id){
		var input = $("#div_statistics input[class=but_r]");
		var vals = '';
		input.each(function () {
			vals += $(this).attr("sf_id")+'^'+$(this).val()+'#';
		});
		vals = vals.substring(0,vals.length-1);
		if(vals != ""){
			if (confirm('��ȷ��Ҫͳ��������ȷ��������ϵͳ���ݡ�')) {
	    		$.post("?model=stock_material_material&action=update_details", { id: id ,val:vals}, function (msg) {
	    			if(msg == '0') {
	    				getBomDetail(id,state);
	    			}else{
	    				return $.messager.alert("ʧ��", "���ִ���!");
	    			}
	    		});
			}
		}else{
			$.messager.alert('��ʾ','û�пɸ��µ�����!');
		}
	}else{
		$.messager.alert('��ʾ','ͳ������ʧ��,��ˢ��ҳ�����²���!');
	}
}