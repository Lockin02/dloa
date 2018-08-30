//��Ʒ����js -- shiheng 2015/7/8

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
			title:'������Ʒ����',
			width:800,
			height:400,
			closed:false,
			modal:true,
			buttons:[{
	        	text:'����',
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
									$.messager.alert('��ʾ','�����ɹ�!');
									location.reload();
								}else{
									$.messager.alert('��ʾ','����ʧ��,�����²�����');
									}
			        		});
		        		}
		        	}
		        }]
			});
		});
});

//�б����ṹ
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
			text: "�鿴����",
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
					title:'�鿴����',
					width:800,
					height:300,
					closed:false,
					modal:true
				});
				$('#configurationInfo > .dialog-button').remove();
			}
		},{
			iconCls: "icon-edit",
			text: "�޸�����",
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
					title:'�޸�����',
					width:800,
					height:400,
					closed:false,
					modal:true,
					buttons:[{
			        	text:'�����޸�',
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
											$.messager.alert('��ʾ','�޸ĳɹ�!');
											location.reload();
										}else{
											$.messager.alert('��ʾ','�޸�ʧ��,�����²�����');
										}
				        		});
			        		}
			        	}
			        }]
				});
			}
		},{
			iconCls: "icon-remove",
			text: "ɾ����Ʒ",
			handler: function () {

				if(confirm('ȷ��Ҫɾ���˲�Ʒ��')){
					var url = publicUrl + '&action=delete';
	        		$.post(url,{id:id},function(status) {
						if(status){
							$.messager.alert('��ʾ','ɾ���ɹ�!');
							location.reload();
						}else{
							$.messager.alert('��ʾ','ɾ��ʧ��,�����²�����');
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
				display : '��  ��',
				name : 'process',
				width : '15%'
			},{
				display : '��Ŀ����',
				name : 'processName',
				width : '30%',
				align : 'left'
			},{
				display : '����ʱ�䣨�룩',
				name : 'processTime',
				width : '15%'
			},{
				display : '������',
				name : 'recipient',
				width : 50,
				align : 'left'
			},{
				display : '������ID',
				name : 'recipientId',
				type : 'hidden'
			},{
				display : '��ע',
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
				display : '��������',
				name : 'proType',
				width : '20%'
			},{
				display : '���ϱ���',
				name : 'productCode',
				width : '10%',
				align : 'left'
			},{
				display : '��������',
				name : 'productName',
				width : '30%'
			},{
				display : '�������',
				name : 'pattern',
				width : '20%',
				align : 'left'
			},{
				display : '��λ����',
				name : 'unitName',
				width : '10%'
			},{
				display : '����',
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
		$.messager.alert('��ʾ','��Ʒ������Ϊ��');
		return false;
	}

	if(!$('#inp_productCode').val()){
		$.messager.alert('��ʾ','���ϱ��벻��Ϊ��');
		return false;
	}

	if(!$('#inp_processId').val()){
		$.messager.alert('��ʾ','��ѡ����ģ��');
		return false;
	}
	if(!$('#C_template select').val()){
		$.messager.alert('��ʾ','��ѡ����ȷ�ķ���ģ��');
		return false;
	}
	if(!$('#inp_produceName').val()){
		$.messager.alert('��ʾ','��Ʒ������Ϊ��');
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
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '��������Id',
			name : 'proTypeId',
			type : 'hidden'
		},{
			display : '��������',
			name : 'proType',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '���ϱ���',
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
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		},{
			display : '����ͺ�',
			name : 'pattern',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '��λ����',
			name : 'unitName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '����',
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
		title: "�༭����ģ��",
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
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '��������Id',
			name : 'proTypeId',
			type : 'hidden'
		},{
			display : '��������',
			name : 'proType',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '���ϱ���',
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
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		},{
			display : '����ͺ�',
			name : 'pattern',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '��λ����',
			name : 'unitName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '����',
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
				alert('�����ɹ���');
				location.reload();
			}else{
				alert('����ʧ�ܣ������²�����');
			}
		},'json');
	} else {
		alert('��������');
	}

}
function checkForm() {

	if($('#templateName').val()){
		var produceNumObj = $("#items").yxeditgrid("getCmpByCol" ,"num");
		if (produceNumObj.length == 0) {
			alert("û�����ϣ�");
			return false;
		}
		for (var i = 0 ;i < produceNumObj.length ;i++) {
			if (produceNumObj[i].value <= 0) {
				alert("���������������0");
				return false;
			}
		}
		return true;
	}else{
		alert('ģ�����Ʋ���Ϊ�ա�');
		return false;
	}

}