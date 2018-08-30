$(document).ready(function() {
	if ($("#projectName").val() != '') {
		$("#department0").hide();
		$("#department1").hide();
	}
	
	//验证
	validate({
		"purposeCode" : {
			required : true
		},
		"technologyCode" : {
			required : true
		},
		"recipient" : {
			required : true
		}
	});
	
	var type = "text";
	//下达walktour任务时处理
	if($("#taskTypeName").val() == "walktour任务"){
		$("#technologyCode").removeClass("validate[required]").parent("td").hide().prev("td").hide();//隐藏工艺
		$("#purposeCode").parent("td").attr("colspan","3");//扩展用途
		$("#customerName").parents("tr:first").hide();//隐藏客户名称，销售代表
		$("#isFirstInspection").parent("td").hide().prev("td").hide();//隐藏是否首件确认
		$("#recipient").parent("td").attr("colspan","3");//扩展指定接收人
		type = "hidden";//隐藏工序配置模板，物料模板
	}
	var configPro = $("#configProduct");
	configPro.yxeditgrid({
		url : '?model=produce_apply_produceapplyitem&action=listJsonByTask',
		objName : 'producetask[configPro]',
		param : {
			applyDocId : $("#applyDocId").val(),
			applyDocItemId : $("#applyDocItemId").val(),
			stateIn: "0,1"
		},
//		isAdd : false,
		realDel: true,
		event : {
			reloadData : function(event, g,data) {
				if(!data || data.length == 0){
					alert('没有可下达的数量');
					closeFun();
				}
			}
		},
		colModel : [{
			display : '物料ID',
			name : 'productId',
			type : 'hidden'
		},{
			display : '物料编号',
			name : 'productCode',
//			width : '20%',
			validation : {
				required : true
			},
			process : function ($input) {
				var rowNum = $input.data("rowNum");
				$input.yxcombogrid_product2({
					hiddenId : 'configProduct_cmp_productId' + rowNum,
					width : 500,
					height : 300,
					isAdd : false,
					isFocusoutCheck : false,
					gridOptions : {
						param: {
							mainId: $("#applyDocId").val()
						},
						showcheckbox : false,
						event : {
							row_dblclick : function(e ,row ,data) {
								var codeObj = configPro.yxeditgrid("getCmpByCol" ,"productCode");
								var nameObj = configPro.yxeditgrid("getCmpByCol" ,"productName");
								var isRepeat = false;
								codeObj.each(function (i) {
									if (this.value == data.productCode && nameObj[i].value == data.productName && i != rowNum) {
										isRepeat = true;
										return false;
									}
								});
								if (isRepeat) {
									alert('不能选择相同名称的配置！');
									configPro.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productCode").val("");
									configPro.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productId").val("");
									configPro.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productName").val("");
									configPro.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"pattern").val("");
									configPro.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"unitName").val("");
									configPro.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"planId").val("");
									return false;
								}
								var num = data.produceNum - data.exeNum;
								if (num > 0) {
									configPro.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"num").val(num);
									configPro.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"maxNum").val(num);
									configPro.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"num").blur(function () {
										if ($(this).val() > num) {
											alert('任务数量超过可下达任务数量！');
											$(this).val(num);
										}
									});
								} else {
									configPro.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"num").val(0);
									alert('可下达任务数量为0！');
								}

								configPro.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productCode").val(data.productCode);
								configPro.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productName").val(data.productName);
								configPro.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"pattern").val(data.pattern);
								configPro.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"unitName").val(data.unitName);
								configPro.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"planId").val(data.id);
								$("#proTypeId").val(data.proTypeId);
								$("#proType").val(data.proType);
							}
						}
					}
				});
			}
		},{
			display : '物料名称',
			name : 'productName',
			readonly : true,
			tclass : 'readOnlyTxtMiddle',
//			width : '20%'
		},{
			display : '规格型号',
			name : 'pattern',
			width : '15%'
		},{
			display : '数量',
			name : 'num',
//			width : '5%',
			validation : {
				custom : ['onlyNumber']
			},
			event : {
				blur : function(e){
					var rownum = $(this).data('rowNum');// 第几行
					var grid = $(this).data('grid');// 表格组件

					var maxNum = grid.getCmpByRowAndCol(rownum, 'maxNum').val();

					if($(this).val() *1 <= 0){
						alert("申请数量必须大于0！");
						$(this).val(maxNum);
					}

					if($(this).val() *1 > maxNum *1){
						alert("申请数量不能大于" + maxNum);
						$(this).val(maxNum);
					}
				}
			}
		},{
			display : '最大数量',
			name : 'maxNum',
			process:function($input,row){
				if(row){
					$input.val(row.num);
				}
			},
			type : 'hidden'
		},{
			display : '工序配置模板',
			name : 'processModel',
//			width : '20%',
			type : type,
			validation : {
				required : true
			},
			process : function ($input) {
				var rowNum = $input.data("rowNum");
				$input.yxcombogrid_process({
					hiddenId : 'configProduct_cmp_processModelId' + rowNum,
					width : 500,
					height : 300,
					gridOptions : {
						showcheckbox : false,
						event : {
							row_dblclick : function(e ,row ,data) {
								var tableHtml = '<div style="overflow-x:scroll;"><table class="form_main_table"><tr><fieldset>'
									+ '<legend class="legend" onclick="showAndHideDiv(\'productConfigInfoImg' + rowNum + '\',\'productConfigInfo' + rowNum + '\')">' + configPro.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productCode").val() + '配置'
									+ '<img src="images/icon/info_up.gif" id="productConfigInfoImg' + rowNum + '"/></legend>'
									+ '<div id="productConfigInfo' + rowNum + '"></div></fieldset></tr></table></div>';
								var processHtml = '<div style="overflow-x:scroll;"><table class="form_main_table"><tr><fieldset>'
									+ '<legend class="legend" onclick="showAndHideDiv(\'processInfoImg' + rowNum + '\',\'processInfo' + rowNum + '\')">' + configPro.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productCode").val() + '工序'
									+ '<img src="images/icon/info_up.gif" id="processInfoImg' + rowNum + '"/></legend>'
									+ '<div id="processInfo' + rowNum + '"></div></fieldset></tr></table></div>';
								if ($("#productConfigInfo" + rowNum).length == 0) {
									$("#fiel_process").show();
									$("#productInfo").append(tableHtml);
									$("#productInfo").append(processHtml);
									createTable(rowNum);
									addConfigData(data.id,rowNum);
									addProcessequ(data.id,rowNum);
								} else {
									$("#productConfigInfo" + rowNum).parent().parent().remove();
									$("#processInfo" + rowNum).parent().parent().remove();
									$("#productInfo").append(tableHtml);
									$("#productInfo").append(processHtml);
									createTable(rowNum);
									addConfigData(data.id,rowNum);
									addProcessequ(data.id,rowNum);
								}
							}
						}
					}
				});
			}
		},{
			display : '物料模板',
			name : 'templateModel',
//			width : '20%',
			type : type,
			validation : {
				required : true
			},
			process : function ($input) {
				var rowNum = $input.data("rowNum");
				$input.yxcombogrid_classify({
					hiddenId : 'configProduct_cmp_templateModelId' + rowNum,
					width : 500,
					height : 300,
					gridOptions : {
						showcheckbox : false,
						event : {
							row_dblclick : function(e ,row ,data) {
								templateData(data.id,rowNum);
							}
						}
					}
				});
			}
		},{
			display : '需求明细ID',
			name : 'planId',
			type : 'hidden'
		},{
			display : '配置模板id',
			name : 'configModelId',
			type : 'hidden'
		},{
			display : '单位名称',
			name : 'unitName',
			type : 'hidden'
		}],
		event : {
			removeRow : function (e ,rowNum) {
				$("#productConfigInfo" + rowNum).parent().parent().remove();
				$("#processInfo" + rowNum).parent().parent().remove();
				$("#templateData" + rowNum).parent().parent().remove();
			}
		}
	});

	//组织机构选择
	$(function() {
		$("#recipient").yxselect_user({
			hiddenId : 'recipientId'
		});
	});
});

//添加配置表格 @number 第几个表格
function createTable(number) {
	var configInfoObj = $("#productConfigInfo" + number);
	configInfoObj.yxeditgrid({
		objName : 'producetask[info][' + number + ']',
		colModel : [{
			display : '',
			name : 'column0',
			process : function ($input) {
				$input.after('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}
		},{
			display : '',
			name : 'column1',
			process : function ($input) {
				$input.after('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}
		},{
			display : '',
			name : 'column2',
			process : function ($input) {
				$input.after('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}
		},{
			display : '',
			name : 'column3',
			process : function ($input) {
				$input.after('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}
		}],
		event : {
			addRow : function (e ,rowNum) {
				var theadObj = $("#productConfigInfo" + number + " > table > thead > tr > th > .divChangeLine");
				var theadNum = theadObj.length;
				var tbodyNum = $("#productConfigInfo" + number + " > table > tbody > tr:eq(" + rowNum + ") > td").length - 2;

				if (rowNum > 0) {
					for (var i = 0 ;i < 4 ;i++) { //处理有删除默认列的情况
						if (!$("#productConfigInfo" + number + "_cmp_thead" + i)[0]) {
							$("#productConfigInfo" + number + "_cmp_column" + i + rowNum).parent().remove();
						}
					}
				}

				theadObj.each(function () {
					var columnId = $(this).children().children(":eq(0)").attr('id') + ''; //转成字符串
					var columnNum = columnId.substring(27 + number.toString().length);
					if (columnNum >= 4) {
						if ($("#productConfigInfo" + number + "_cmp_column" + columnNum + rowNum)) {
							var columnHtml = '<td style="text-align: center;"><input type="text" '
											+ 'id="productConfigInfo' + number + '_cmp_column' + columnNum + rowNum + '" class="txtmiddle" '
											+ 'name="producetask[info][' + number + '][' + rowNum + '][column' + columnNum + ']">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
							$("#productConfigInfo" + number + " > table > tbody > tr:eq(" + rowNum + ")").append(columnHtml);
						}
					}
				});
			}
		}
	});

	$("#productConfigInfo" + number + " > table > thead > tr > th > .divChangeLine").each(function (num) {
		//添加自定义表头
		var htmlStr = '<nobr><input type="text" style="background:transparent;border-color:green;color:#2E5CB8" '
					+ 'id="productConfigInfo' + number + '_cmp_thead' + num + '"'
					+ ' class="txtmiddle" name="producetask[thead][' + number + '][' + num + ']">'
					+ '<span class="removeBn" onclick="removeColumn(this,' + num + ',' + number + ');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></nobr>';
		$(this).append(htmlStr);

		//末尾添加增加列按钮
		if (num + 1 == $("#productConfigInfo" + number + " > table > thead > tr > th > .divChangeLine").length) {
			var addHtml = '<th width="10"><span class="addBn" onclick="addColumn(this,' + num + ',' + number + ');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></th>';
			$(this).parent().parent().append(addHtml);
		}
	});
}

//加载模板配置数据
function addConfigData(id,number){
	// 获取表头数据
	$.ajax({
		type: 'POST',
		url: '?model=manufacture_basic_productconfig&action=listJson',
		data: {
			processId:id,
			dir: 'ASC'
		},
		success: function (data) {
			if (data && data != 'false') {
				var tHead = eval('(' + data + ')');

				// 先弄表头
				var tHeadObj = $('input[id^=productConfigInfo' + number + '_cmp_thead]');
				if (tHeadObj.length > tHead.length) { // 少于默认列数
					for (var i = tHeadObj.length; i >= tHead.length; i--) {
						$('#productConfigInfo' + number + '_cmp_thead' + i).next().trigger('click');
					}
				} else if (tHeadObj.length < tHead.length) { // 多于默认列数
					for (var i = tHeadObj.length; i < tHead.length; i++) {
						$('#productConfigInfo'+number+' > table > thead > tr > th').last().children().trigger('click');
					}
				}

				// 再弄表头数据
				for (var i = 0; i < tHead.length; i++) {
					$('#productConfigInfo' + number + '_cmp_thead' + i).val(tHead[i].colName);
				}

				// 获取表格内容数据
				$.ajax({
					type: 'POST',
					url: '?model=manufacture_basic_productconfigItem&action=tableJson',
					data: {
						processId: id,
						dir: 'ASC'
					},
					success: function (data2) {
						if (data2 && data2 != 'false') {
							var tBody = eval('(' + data2 + ')');
							var i, j;
							for (i = 0; i < tBody.length; i++) {
								if (i > 0) {
									$('#productConfigInfo'+number+'  > table > thead > tr > th').first().children().trigger('click');
								}
								j = 0;
								$.each(tBody[i] ,function(k ,v) {
									$('#productConfigInfo' + number + '_cmp_column' + j + i).val(v);
									j++;
								});
							}
						}
					}
				});
			}
		}
	});
}

//加载工序
function addProcessequ(id,number){
	var equTableObj = $("#processInfo"+number);
	equTableObj.yxeditgrid({
		objName: 'producetask[configPro]['+number+'][process]',
		url: '?model=manufacture_basic_processequ&action=listJson',
		param: {
			parentId: id,
			dir: 'ASC'
		},
		isFristRowDenyDel: true,
		realDel :true,
		colModel: [{
			display: '工  序',
			name: 'process',
			width: '15%',
			validation: {
				required: true
			}
		}, {
			display: '项目名称',
			name: 'processName',
			width: '30%',
			validation: {
				required: true
			},
			process: function ($input) {
				var rowNum = $input.data("rowNum");
				$input.yxcombogrid_processequ({
					hiddenId: 'equInfo_cmp_processName' + rowNum,
					gridOptions: {
						event: {
							row_dblclick: function (e, row, data) {
								equTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "process").val(data.process);
								equTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "processTime").val(data.processTime);
								equTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "recipient").val(data.recipient);
								equTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "recipientId").val(data.recipientId);
								equTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "remark").val(data.remark);
							}
						}
					}
				});
			}
		}, {
			display: '工序时间（秒）',
			name: 'processTime',
			width: '10%',
			validation: {
				required: true,
				custom: ['percentageNum']
			}
		}, {
			display: '接收人',
			name: 'recipient',
			width: 180,
			readonly: true,
			process: function ($input) {
				var rowNum = $input.data("rowNum");
				$input.yxselect_user({
					mode: 'check',
					hiddenId: 'processInfo'+number+'_cmp_recipientId' + rowNum
				});
			}
		}, {
			display: '接收人ID',
			name: 'recipientId',
			type: 'hidden'
		}, {
			display: '备注',
			name: 'remark',
			type: 'textarea',
			width: '20%'
		}]
	});
}

//移除列
function removeColumn(obj ,num ,number) {
	$(obj).parent().parent().parent().remove();
	$("#productConfigInfo" + number + " > table > tbody > tr").each(function (i) {
		$("#productConfigInfo" + number + "_cmp_column" + num + i).parent().remove();
	});
}

//增加列
function addColumn(obj ,num ,number) {
	num++;
	var htmlStr = '<th><div class="divChangeLine"><nobr><input type="text" style="background:transparent;border-color:green;color:#2E5CB8" '
				+ 'id="productConfigInfo' + number + '_cmp_thead' + num + '"'
				+ ' class="txtmiddle" name="producetask[thead][' + number + '][' + num + ']">'
				+ '<span class="removeBn" onclick="removeColumn(this,' + num + ',' + number + ');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></nobr></div></th>';
	var addHtml = '<span class="addBn" onclick="addColumn(this,' + num + ',' + number + ');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
	$(obj).parent().before(htmlStr).empty().append(addHtml);

	$("#productConfigInfo" + number + " > table > tbody > tr").each(function (i) {
		var addHtml = '<td style="text-align: center;"><input type="text" '
					+ 'id="productConfigInfo' + number + '_cmp_column' + num + i + '" class="txtmiddle" '
					+ 'name="producetask[info][' + number + '][' + i + '][column' + num + ']">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
		$(this).append(addHtml);
	});
}


function templateData(id,rowNum){
	var templateHtml = '<div style="overflow-x:scroll;"><table class="form_main_table"><tr><fieldset>'
			+ '<legend class="legend" onclick="showAndHideDiv(\'templateImg' + rowNum + '\',\'templateData' + rowNum + '\')">' + $("#configProduct").yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productCode").val() + '物料'
			+ '<img src="images/icon/info_up.gif" id="templateImg' + rowNum + '"/></legend>'
			+ '<div id="templateData' + rowNum + '"></div></fieldset></tr></table></div>';

	if ($("#templateData" + rowNum).length == 0) {
		$("#fiel_classify").show();
		$("#templateData").append(templateHtml);
	}else{
		$("#templateData" + rowNum).parent().remove();
		$("#templateData").append(templateHtml);
	}
	$('#fiel_classify').show();
	var itemsObj = $("#templateData" + rowNum);
	var url =  '?model=manufacture_basic_template&action=product&id=' + id;
	itemsObj.yxeditgrid({
		objName : 'producetask[configPro]['+rowNum+'][classify]',
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
//					width : 500,
//					height : 300,
					gridOptions : {
//						showcheckbox : false,
						event : {
							row_dblclick : function(e ,row ,data) {
								itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productId").val(data.id);
								itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productCode").val(data.productCode);
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

//提交时验证
function checkForm(){
	if($("#configProduct").yxeditgrid('getCurShowRowNum') === 0){
		alert("配置不能为空");
		return false;
	}
}