// 添加需求审批记录表的方法
function xq_approval(){
	var itemType = $("#xq_itemType").val();
	var pid = $("#xq_pid").val();
	var relDocId = $("#xq_relDocId").val();
	var isChange = $("#isChange").val();
	if ($("#isChange").length == 0) {//是否带出变更审批意见
		var isChange = 0;
	} else {
		var isChange = $("#isChange").val();
	}
	if ($("#appFormName").length == 0) {
		var appFormName = "";
	} else {
		var appFormName = $("#appFormName").val();
	}
	if ($("#isPrint").length == 0) {//是否带出变更审批意见
		var isPrint = 0;
	} else {
		var isPrint = $("#isPrint").val();
	}

	$.post("?model=common_approvalView&action=getXqApproval", {
		pid : pid,
		relDocId : relDocId,
		itemtype : itemType,
		isChange : isChange,
		isPrint : isPrint
	}, function(data) {
		if (data == 0) { //没有审批意见时，赋值为空行
			var datahtml = "<tr><td></td></tr>";
		}
		if (data != 0) {
			if(isPrint == "1"){
				var $html = $('<table width="100%"  class="form_in_table" id="approvalTable">'
					+ '<thead>'
					+ '<tr  > '
					+ '<td width="100%" colspan="6" class="form_header"><B>'
					+ appFormName
					+ '需求审批记录</B></td>'
					+ '</tr>'
					+ '<tr class="main_tr_header">'
					+ '<th width="12%">步骤名</th>'
					+ '<th width="12%">审批人</th>'
					+ '<th width="18%" nowrap="nowrap">审批日期</th>'
					+ '<th width="10%">审批结果</th>'
					+ '<th>审批意见</th>'
					+ '</tr>' + '</thead>');
			}else{
				var $html = $('<table width="100%"  class="form_in_table" id="approvalTable">'
					+ '<thead>'
					+ '<tr  > '
					+ '<td width="100%" colspan="6" class="form_header"><B>'
					+ appFormName
					+ '需求审批记录</B></td>'
					+ '</tr>'
					+ '<tr class="main_tr_header">'
					+ '<th width="12%">序号</th>'
					+ '<th width="12%">步骤名</th>'
					+ '<th width="12%">审批人</th>'
					+ '<th width="18%" nowrap="nowrap">审批日期</th>'
					+ '<th width="10%">审批结果</th>'
					+ '<th>审批意见</th>'
					+ '</tr>' + '</thead>');
			}
			var $html2 = $('</table>');
			if (data == 0) {
				var $tr = $(datahtml);
			} else {
				var $tr = $(data);
			}
			$html.append($tr);
			$html.append($html2);
			$("#xq_approvalView").append($html);
		}
	});
}
$(function() {
	$("#purchaseProductTable").yxeditgrid({
		objName : 'apply[applyItem]',
		// delTagName : 'isDelTag',
		url : '?model=asset_purchase_apply_applyItem&action=editListJson',
		param : {
			applyId : $("#applyId").val()
		},
		isAdd : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '物料编码',
			name : 'productCode',
			tclass : 'readOnlyTxtNormal',
			width :　120,
			readonly : true
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			width :　200,
			readonly : true
		}, {
			display : '资产名称',
			name : 'inputProductName',
			width :　200,
			validation : {
				required : true
			},
			tclass : 'txtlong'
		}, {
			display : '资产描述',
			name : 'description',
			validation : {
				required : true
			},
			width :　200,
			tclass : 'txt'
		}, {
			display : '规格',
			name : 'pattem',
			width :　100,
			validation : {
				required : true
			}
		}, {
			display : '申请数量',
			name : 'applyAmount',
			tclass : 'txtshort',
			width :　60,
			validation : {
				custom : ['onlyNumber']
			},
			event : {
				blur : function(e){
					var rownum = $(this).data('rowNum');// 第几行
					var grid = $(this).data('grid');// 表格组件

					var applyAmount = grid.getCmpByRowAndCol(rownum, 'applyAmount').val();
					var maxAmount = grid.getCmpByRowAndCol(rownum, 'maxAmount').val();

					if(!isNum($(this).val())){
						alert("请输入正整数");
						$(this).val(maxAmount);
					}

					if($(this).val() *1 > maxAmount *1){
						alert("数量不能大于剩余可申请数量");
						$(this).val(maxAmount);
					}
				}
			}
		}, {
			display : '最大数量',
			name : 'maxAmount',
			type : "hidden"
		}, {
			display : '预计金额',
			name : 'amounts',
			tclass : 'txtshort',
			type : 'money',
			// blur 失焦触发计算金额和数量的方法
			event : {
				blur : function() {
					countAmount();
				}
			}
		}, {
			display : '单位',
			name : 'unitName',
			tclass : 'txtshort',
			validation : {
				required : true
			}
		}, {
			display : '希望交货日期',
			name : 'dateHope',
			type : 'date',
			tclass : 'txtshort',
			validation : {
				custom : ['date']
			}
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}, {
			display : '询价金额',
			name : 'inquiryAmount',
			type : 'money',
			tclass : 'txtshort'
		}, {
			display : '行政意见',
			name : 'suggestion',
			type : 'textarea',
			width : 200
		}]
	});
	//归属公司
	$("#businessBelongName").yxcombogrid_branch({
		hiddenId: 'businessBelong',
		height: 250,
		isFocusoutCheck: false,
		gridOptions: {
			showcheckbox: false
		}
	});
	// 选择人员组件
	$("#userName").yxselect_user({
		hiddenId : 'userId',
		isGetDept : [true, "useDetId", "useDetName"]
	});
	$("#applicantName").yxselect_user({
		hiddenId : 'applicantId',
		isGetDept : [true, "applyDetId", "applyDetName"]
	});

	// 根据采购类型来显示部分字段（计划编号、预估总价）
	if ($("#purchaseType").val() != "CGLX-JHN") {
		$("#hiddenA").hide();
		// $("#hiddenB").hide();
	}

	// 根据采购种类来显示资产用途
	$('#purchCategory').change(function() {
		$('#assetUseCode').empty();
		assetUseArr = getData($('#purchCategory').val());
		addDataToSelect(assetUseArr, 'assetUseCode');
		if ($('#assetUseCode').val()) {
			$('#assetUse').val($('#assetUseCode').get(0).options[$('#assetUseCode').get(0).selectedIndex].innerText);
		}
	});

	$('#purchaseType').change(function() {
		if ($("#purchaseType").val() == "CGLX-JHN") {
			$("#hiddenA").show();
			// $("#hiddenB").show();
		} else {
			$('#planYear').val("");
			$("#hiddenA").hide();
			// $("#hiddenB").hide();
		}
	});

	// 根据采购种类为“研发类”时来显示部分字段（采购分类、重大专项名称、募集资金项目、其它研发项目）
	// alert($("#purchCategory").val());
	if ($("#purchCategory").val() == "CGZL-YFL") {
		$("#hiddenC").hide();
		$("#hiddenD").show();
		$("#hiddenE").show();
	} else {
		$("#hiddenC").show();
		$("#hiddenD").hide();
		$("#hiddenE").hide();
	}

	$("#purchCategory").change(function() {
		if ($("#purchCategory").val() == "CGZL-YFL") {
			$('#assetUseCode').val("");
			$('#assetUse').val("");
			$("#hiddenC").hide();
			$("#hiddenD").show();
			$("#hiddenE").show();
		} else {
			$('#assetClass').val("");
			$('#importProject').val("");
			$('#moneyProject').val("");
			$('#otherProject').val("");
			$("#hiddenC").show();
			$("#hiddenD").hide();
			$("#hiddenE").hide();
		}
	});

	/**
	 * 验证信息
	 */
	validate({
		"businessBelongName" : {
			required : true
		},
		"userName" : {
			required : true
		},
		"applicantName" : {
			required : true
		},
		"applyTime" : {
			custom : ['date']
		},
		"amounts_v" : {
			required : true
		}
	});

	// 添加需求审批记录
	if(typeof($("#extra_approvalItems").val()) != 'undefined' &&  $("#extra_approvalItems").val() == 'xq'){
		xq_approval();
	}
});

//审核确认
function confirmAudit() {
	if(checkForm()){
		$("#purchaseProductTable").yxeditgrid("showRow",2);
		if (confirm("确定要提交吗?")) {
			var purchaseDept = $('#purchaseDept').val();
			var audit = 'submit'; //(purchaseDept == '1')? 'noaudit' : 'submit';
			var action ='editBeforeConfirm';// (purchaseDept == '1')? 'edit' : 'editBeforeConfirm';
			// $("#form1").attr("action","?model=asset_purchase_apply_apply&action=edit&actType="+audit);
			$("#form1").attr("action","?model=asset_purchase_apply_apply&action="+action+"&actType="+audit);
			$("#form1").submit();
		} else {
			return false;
		}
	}
}

//根据从表的残值动态计算应付总金额
function countAmount() {
	// 获取当前的行数即卡片的资产数
	var curRowNum = $("#purchaseProductTable").yxeditgrid("getCurRowNum");
	var rowAmountVa = 0;
	var cmps = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "amounts");
	cmps.each(function() {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	$("#amounts").val(rowAmountVa);
	$("#amounts_v").val(moneyFormat2(rowAmountVa));
	return true;
}

//提交时验证
function checkForm(){
	if($("#purchaseProductTable").yxeditgrid("getCurShowRowNum") === 0){
		alert("采购申请明细不能为空");
		return false;
	}
	return true;
}