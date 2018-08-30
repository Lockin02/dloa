//产品配置js -- shiheng 2015/7/8

$(document).ready(function() {
	materialsTree();

	$('#butAProduce').click(function(){
		var url = publicUrl + '&action=toAdd';
		$.post(url, function(data) {
			if(data){
				$('#produceName').html('<input type=text class="txt" value="" id=inp_produceName>');
				$('#productCode').html('<input type=text class="txt" value="" id=inp_productCode>');
				$('#processName').html(data.process);
				$('#parentId').html(data.parent);
				$('#createName').html('<input type=text readonly="" class="readOnlyText" value="'+createName+'">');
				$('#createTime').html('<input type=text readonly="" class="readOnlyText" value="'+createTime+'">');
				$('#updateName').html('<input type=text readonly="" class="readOnlyText" value="">');
				$('#updateTime').html('<input type=text readonly="" class="readOnlyText" value="">');
				$('#remark').html('<textarea class="txt_txtarea_font" style="width:500px;" id="inp_remark"></textarea>');
				getProductCode();

				$('#s_tem').show();
				$("#inp_classify").combotree({
  					url: "index1.php?model=manufacture_basic_template&action=load_classify_tree",
   					valueField:'id',
    				textField:'text',
				    onSelect: function(node) {
				    	var url = publicUrl + '&action=classifyTemplate';
						$.post(url, {id:node.id}, function(select) {
							$('#C_template').html(select);
						});
					}
				});
			}
		},'json');

		$('#C_template').html('');
		$('#configurationInfo').show();
		$('#configurationInfo').dialog({
			iconCls:'icon-edit',
			title:'新增产品配置',
			width:800,
			height:400,
			closed:false,
			modal:true,
			buttons:[{
	        	text:'保存',
	        	iconCls:'icon-save',
	        	handler:function(){

	        		if(verify()){
	        			var url = publicUrl + '&action=add';
		        		$.post(
		        			url,{
			        			produceName:$('#inp_produceName').val(),
								productCode:$('#inp_productCode').val(),
								parentId:$('#inp_parentId').val(),
								processId:$('#inp_processId').val(),
								classifyId:$('#inp_classifyId').val(),
								templateId:$('#C_template select').val(),
								remark:$('#inp_remark').val()
			        		},function(status) {
								if(status){
									$.messager.alert('提示','新增成功!');
									location.reload();
								}else{
									$.messager.alert('提示','新增失败,请重新操作！');
									}
			        		});
		        		}
		        	}
		        }]
			});
		});
});

//列表树结构
function materialsTree() {
	$("#materials_tree").tree({
		url: publicUrl + "&action=load_produceconfiguration_tree",
		lines: true,
		onlyLeafCheck: true,
		onClick: function(node) {
			cofigurationData(node.text,node.id);
		}
	});
}

function cofigurationData( title , id ){

	$("#configurationList").datagrid({
		title: title,
		singleSelect: true,
		fitColumns: true,
		toolbar: [{
			iconCls: "icon-ok",
			text: "查看配置",
			handler: function () {

				var url = publicUrl + '&action=toView';
				$.post(url, {id:id}, function(data) {
					if(data){
						$('#s_tem').hide();
						$('#produceName').html(data.configuration.produceName);
						$('#productCode').html(data.configuration.productCode);
						$('#processName').html(data.process['0'].templateName);
//						$('#classifyName').html(data.classify['0'].classifyName);
						$('#C_template').html(data.classify['0'].classifyName+' > '+data.template.templateName);
						$('#parentId').html(data.configuration.parentName);
						$('#createName').html(data.configuration.createName);
						$('#createTime').html(data.configuration.createTime);
						$('#updateName').html(data.configuration.updateName);
						$('#updateTime').html(data.configuration.updateTime);
						$('#remark').html(data.configuration.remark);
					}
				},'json');

				$('#configurationInfo').show();
				$('#configurationInfo').dialog({
					iconCls:'icon-ok',
					title:'查看配置',
					width:800,
					height:300,
					closed:false,
					modal:true
				});
				$('#configurationInfo > .dialog-button').remove();
			}
		},{
			iconCls: "icon-edit",
			text: "修改配置",
			handler: function () {
				var url = publicUrl + '&action=toEdit';
				$.post(url, {id:id}, function(data) {
					if(data){
						$('#produceName').html('<input type=text class="txt" value="'+data.configuration.produceName+'" id=inp_produceName>');
						$('#productCode').html('<input type=text class="txt" value="'+data.configuration.productCode+'" id=inp_productCode>');
						$('#processName').html(data.process);
//						$('#classifyName').html(data.classify);
						$('#parentId').html(data.parent);
						$('#createName').html('<input type=text readonly="" class="readOnlyText" value="'+data.configuration.createName+'">');
						$('#createTime').html('<input type=text readonly="" class="readOnlyText" value="'+data.configuration.createTime+'">');
						$('#updateName').html('<input type=text readonly="" class="readOnlyText" value="'+data.configuration.updateName+'">');
						$('#updateTime').html('<input type=text readonly="" class="readOnlyText" value="'+data.configuration.updateTime+'">');
						$('#remark').html('<textarea class="txt_txtarea_font" style="width:500px;" id="inp_remark">'+data.configuration.remark+'</textarea>');
						getProductCode();
						$('#s_tem').show();
						var url = publicUrl + '&action=classifyTemplate';
						$("#inp_classify").combotree({
      						url: "index1.php?model=manufacture_basic_template&action=load_classify_tree",
           					valueField:'id',
            				textField:'text',
            				onLoadSuccess:function(){

//								$('.tree-node').each(function(){
//									if($(this).attr("node-id") == data.classify['0'].id ){
//										$(this).attr("class","tree-node-selected");
//										$('.combo-value').val(data.classify['0'].id);
//									}
//								});


								$.post(url, {id:data.classify['0'].id,val:data.template.id}, function(select) {
									$('#C_template').html(select);
								});
							},
						    onSelect: function(node) {
								$.post(url, {id:node.id}, function(select) {
									$('#C_template').html(select);
								});
							}
						});

//						$("#inp_parent").combotree({
//      						url: publicUrl + "&action=load_produceconfiguration_tree",
//           					valueField:'id',
//            				textField:'text'
//						});
					}
				},'json');

				$('#configurationInfo').show();
				$('#configurationInfo').dialog({
					iconCls:'icon-edit',
					title:'修改配置',
					width:800,
					height:400,
					closed:false,
					modal:true,
					buttons:[{
			        	text:'保存修改',
			        	iconCls:'icon-save',
			        	handler:function(){

			        		if(verify()){
			        			var url = publicUrl + '&action=edit';
				        		$.post(
				        			url,{
					        			id:id,
					        			produceName:$('#inp_produceName').val(),
										productCode:$('#inp_productCode').val(),
										parentId:$('#inp_parentId').val(),
										processId:$('#inp_processId').val(),
										classifyId:$('.combo-value').val(),
										templateId:$('#C_template select').val(),
										remark:$('#inp_remark').val()
					        		},function(status) {
										if(status){
											$.messager.alert('提示','修改成功!');
											location.reload();
										}else{
											$.messager.alert('提示','修改失败,请重新操作！');
										}
				        		});
			        		}
			        	}
			        }]
				});
			}
		},{
			iconCls: "icon-remove",
			text: "删除产品",
			handler: function () {

				if(confirm('确定要删除此产品？')){
					var url = publicUrl + '&action=delete';
	        		$.post(url,{id:id},function(status) {
						if(status){
							$.messager.alert('提示','删除成功!');
							location.reload();
						}else{
							$.messager.alert('提示','删除失败,请重新操作！');
						}
	        		});
				}

			}
		}]
	});

	$('#templateDiv').show();
	var url = publicUrl + '&action=configuration';
	$.post(url, {id:id}, function(configuration) {
		$('#productConfigInfo').html('');
		$.ajax({
			type : 'POST',
			url: '?model=manufacture_basic_productconfig&action=listJson',
			data: {
				processId: configuration.processId,
				dir: 'ASC'
			},
			success: function (data) {
				if (data && data != 'false') {
					var tHead = eval('(' + data + ')');
					var tableHead = [];
					$.each(tHead ,function(k ,v) {
						tableHead.push({
							name : v.colCode,
							display : v.colName
						});
					});
					$('#productConfigInfo').yxeditgrid({
						url : '?model=manufacture_basic_productconfigItem&action=tableJson',
						param : {
							processId :configuration.processId,
							dir : 'ASC'
						},
						type : 'view',
						colModel : tableHead
					});
				}
			}
		});
		$("#equInfo").html('');
		$("#equInfo").yxeditgrid({
			url : '?model=manufacture_basic_processequ&action=listJson',
			param : {
				parentId : configuration.processId,
				dir : 'ASC'
			},
			type : 'view',
			colModel : [{
				display : 'id',
				name : 'id',
				type : 'hidden'
			},{
				display : '工  序',
				name : 'process',
				width : '15%'
			},{
				display : '项目名称',
				name : 'processName',
				width : '30%',
				align : 'left'
			},{
				display : '工序时间（秒）',
				name : 'processTime',
				width : '15%'
			},{
				display : '接收人',
				name : 'recipient',
				width : 50,
				align : 'left'
			},{
				display : '接收人ID',
				name : 'recipientId',
				type : 'hidden'
			},{
				display : '备注',
				name : 'remark',
				width : '20%',
				align : 'left'
			}]
		});

		$("#classifyInfo").html('');
		$("#classifyInfo").yxeditgrid({
			url : '?model=manufacture_basic_template&action=product',
			param : {
				id : configuration.templateId
			},
			type : 'view',
			colModel : [{
				display : 'id',
				name : 'id',
				type : 'hidden'
			},{
				display : '物料类型',
				name : 'proType',
				width : '20%'
			},{
				display : '物料编码',
				name : 'productCode',
				width : '10%',
				align : 'left'
			},{
				display : '物料名称',
				name : 'productName',
				width : '30%'
			},{
				display : '规格类型',
				name : 'pattern',
				width : '20%',
				align : 'left'
			},{
				display : '单位名称',
				name : 'unitName',
				width : '10%'
			},{
				display : '数量',
				name : 'num',
				width : '10%'
			}]
		});
	},'json');
}

function getProductCode(){

	$('#inp_productCode').yxcombogrid_product({
		hiddenId : 'inp_productCode',
		width : 500,
		height : 300,
		gridOptions : {
			showcheckbox : false,
			event : {
				row_dblclick : function(e ,row ,data) {
					$('#inp_productCode').val(data.productCode);
				}
			}
		}
	});
}

function verify(){

	if(!$('#inp_produceName').val()){
		$.messager.alert('提示','产品名不能为空');
		return false;
	}

	if(!$('#inp_productCode').val()){
		$.messager.alert('提示','物料编码不能为空');
		return false;
	}

	if(!$('#inp_processId').val()){
		$.messager.alert('提示','请选择工序模板');
		return false;
	}
	if(!$('#C_template select').val()){
		$.messager.alert('提示','请选择正确的分类模板');
		return false;
	}
	if(!$('#inp_produceName').val()){
		$.messager.alert('提示','产品名不能为空');
		return false;
	}

	return true;
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

	$('#template-add').show();
	$('#template-add').removeClass("hidden").dialog({
		title: "编辑分类模板",
		closed: false,
		width: 1000,
		height: 800
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