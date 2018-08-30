$(document).ready(function() {
	if ($("#projectName").val() != '') {
		$("#department0").hide();
		$("#department1").hide();
	}
	
	//��֤
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
	//�´�walktour����ʱ����
	if($("#taskTypeName").val() == "walktour����"){
		$("#technologyCode").removeClass("validate[required]").parent("td").hide().prev("td").hide();//���ع���
		$("#purposeCode").parent("td").attr("colspan","3");//��չ��;
		$("#customerName").parents("tr:first").hide();//���ؿͻ����ƣ����۴���
		$("#isFirstInspection").parent("td").hide().prev("td").hide();//�����Ƿ��׼�ȷ��
		$("#recipient").parent("td").attr("colspan","3");//��չָ��������
		type = "hidden";//���ع�������ģ�壬����ģ��
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
					alert('û�п��´������');
					closeFun();
				}
			}
		},
		colModel : [{
			display : '����ID',
			name : 'productId',
			type : 'hidden'
		},{
			display : '���ϱ��',
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
									alert('����ѡ����ͬ���Ƶ����ã�');
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
											alert('���������������´�����������');
											$(this).val(num);
										}
									});
								} else {
									configPro.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"num").val(0);
									alert('���´���������Ϊ0��');
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
			display : '��������',
			name : 'productName',
			readonly : true,
			tclass : 'readOnlyTxtMiddle',
//			width : '20%'
		},{
			display : '����ͺ�',
			name : 'pattern',
			width : '15%'
		},{
			display : '����',
			name : 'num',
//			width : '5%',
			validation : {
				custom : ['onlyNumber']
			},
			event : {
				blur : function(e){
					var rownum = $(this).data('rowNum');// �ڼ���
					var grid = $(this).data('grid');// ������

					var maxNum = grid.getCmpByRowAndCol(rownum, 'maxNum').val();

					if($(this).val() *1 <= 0){
						alert("���������������0��");
						$(this).val(maxNum);
					}

					if($(this).val() *1 > maxNum *1){
						alert("�����������ܴ���" + maxNum);
						$(this).val(maxNum);
					}
				}
			}
		},{
			display : '�������',
			name : 'maxNum',
			process:function($input,row){
				if(row){
					$input.val(row.num);
				}
			},
			type : 'hidden'
		},{
			display : '��������ģ��',
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
									+ '<legend class="legend" onclick="showAndHideDiv(\'productConfigInfoImg' + rowNum + '\',\'productConfigInfo' + rowNum + '\')">' + configPro.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productCode").val() + '����'
									+ '<img src="images/icon/info_up.gif" id="productConfigInfoImg' + rowNum + '"/></legend>'
									+ '<div id="productConfigInfo' + rowNum + '"></div></fieldset></tr></table></div>';
								var processHtml = '<div style="overflow-x:scroll;"><table class="form_main_table"><tr><fieldset>'
									+ '<legend class="legend" onclick="showAndHideDiv(\'processInfoImg' + rowNum + '\',\'processInfo' + rowNum + '\')">' + configPro.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productCode").val() + '����'
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
			display : '����ģ��',
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
			display : '������ϸID',
			name : 'planId',
			type : 'hidden'
		},{
			display : '����ģ��id',
			name : 'configModelId',
			type : 'hidden'
		},{
			display : '��λ����',
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

	//��֯����ѡ��
	$(function() {
		$("#recipient").yxselect_user({
			hiddenId : 'recipientId'
		});
	});
});

//������ñ�� @number �ڼ������
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
					for (var i = 0 ;i < 4 ;i++) { //������ɾ��Ĭ���е����
						if (!$("#productConfigInfo" + number + "_cmp_thead" + i)[0]) {
							$("#productConfigInfo" + number + "_cmp_column" + i + rowNum).parent().remove();
						}
					}
				}

				theadObj.each(function () {
					var columnId = $(this).children().children(":eq(0)").attr('id') + ''; //ת���ַ���
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
		//����Զ����ͷ
		var htmlStr = '<nobr><input type="text" style="background:transparent;border-color:green;color:#2E5CB8" '
					+ 'id="productConfigInfo' + number + '_cmp_thead' + num + '"'
					+ ' class="txtmiddle" name="producetask[thead][' + number + '][' + num + ']">'
					+ '<span class="removeBn" onclick="removeColumn(this,' + num + ',' + number + ');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></nobr>';
		$(this).append(htmlStr);

		//ĩβ��������а�ť
		if (num + 1 == $("#productConfigInfo" + number + " > table > thead > tr > th > .divChangeLine").length) {
			var addHtml = '<th width="10"><span class="addBn" onclick="addColumn(this,' + num + ',' + number + ');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></th>';
			$(this).parent().parent().append(addHtml);
		}
	});
}

//����ģ����������
function addConfigData(id,number){
	// ��ȡ��ͷ����
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

				// ��Ū��ͷ
				var tHeadObj = $('input[id^=productConfigInfo' + number + '_cmp_thead]');
				if (tHeadObj.length > tHead.length) { // ����Ĭ������
					for (var i = tHeadObj.length; i >= tHead.length; i--) {
						$('#productConfigInfo' + number + '_cmp_thead' + i).next().trigger('click');
					}
				} else if (tHeadObj.length < tHead.length) { // ����Ĭ������
					for (var i = tHeadObj.length; i < tHead.length; i++) {
						$('#productConfigInfo'+number+' > table > thead > tr > th').last().children().trigger('click');
					}
				}

				// ��Ū��ͷ����
				for (var i = 0; i < tHead.length; i++) {
					$('#productConfigInfo' + number + '_cmp_thead' + i).val(tHead[i].colName);
				}

				// ��ȡ�����������
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

//���ع���
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
			display: '��  ��',
			name: 'process',
			width: '15%',
			validation: {
				required: true
			}
		}, {
			display: '��Ŀ����',
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
			display: '����ʱ�䣨�룩',
			name: 'processTime',
			width: '10%',
			validation: {
				required: true,
				custom: ['percentageNum']
			}
		}, {
			display: '������',
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
			display: '������ID',
			name: 'recipientId',
			type: 'hidden'
		}, {
			display: '��ע',
			name: 'remark',
			type: 'textarea',
			width: '20%'
		}]
	});
}

//�Ƴ���
function removeColumn(obj ,num ,number) {
	$(obj).parent().parent().parent().remove();
	$("#productConfigInfo" + number + " > table > tbody > tr").each(function (i) {
		$("#productConfigInfo" + number + "_cmp_column" + num + i).parent().remove();
	});
}

//������
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
			+ '<legend class="legend" onclick="showAndHideDiv(\'templateImg' + rowNum + '\',\'templateData' + rowNum + '\')">' + $("#configProduct").yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productCode").val() + '����'
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

//�ύʱ��֤
function checkForm(){
	if($("#configProduct").yxeditgrid('getCurShowRowNum') === 0){
		alert("���ò���Ϊ��");
		return false;
	}
}