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
    var requireId = $("#relDocId").val();

	$("#purchaseProductTable").yxeditgrid({
		objName : 'apply[applyItem]',
		event : {
			removeRow : function(t, rowNum, rowData) {
				countAmount();
			}
		},
        url : "?model=asset_purchase_apply_apply&action=getPurchaseDetail",
        param: {requireId: requireId},
		isAdd : false,
		isAddOneRow : true,
		colModel : [{
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '物料编码',
			name : 'productCode',
			tclass : 'readOnlyTxtNormal',
			width : 120,
			readonly : true
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '资产名称',
			name : 'inputProductName',
			process : function ($input, rowData) {
				$input.val(rowData['devicename']);
			},
			validation : {
				required : true
			},
			tclass : 'txt'
		}, {
			display : '资产描述',
			name : 'description',
			process : function ($input, rowData) {
				$input.val(rowData['devicedescription']);
			},
			validation : {
				required : true
			},
			tclass : 'txt'
		}, {
			display : 'requireItemId',
			name : 'requireItemId',
			process : function ($input, rowData) {
				$input.val(rowData['detailId']);
			},
			type : "hidden"
		}, {
			display : '物料类别',
			name : 'productCategoryCode',
			validation : {
				required : true
			},
			tclass : 'txtshort',
			type : 'select',
			datacode : 'CGWLLB',
			processData : function(data) {
				var newData = [{
					dataName : '',
					dataCode : ''
				}];
				for (var i = 0; i < data.length; i++) {
					newData.push(data[i]);
				}
				return newData;
			}
		},{
			display : '规格',
			name : 'pattem',
			validation : {
				required : true
			}
		}, {
			display : '申请数量',
			name : 'applyAmount',
			tclass : 'txtshort',
			validation : {
				custom : ['onlyNumber']
			},
			event : {
				blur : function(e){
					var rownum = $(this).data('rowNum');// 第几行
					var grid = $(this).data('grid');// 表格组件

					var applyAmount = grid.getCmpByRowAndCol(rownum, 'applyAmount').val();
					var maxAmount = grid.getCmpByRowAndCol(rownum, 'maxAmount').val();

					if($(this).val() *1 <= 0){
						alert("数量必须大于0！");
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
			process : function ($input, rowData) {
				$input.val(rowData['borrowdate']);
			},
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
			process : function ($input, rowData) {
				$input.val(rowData['inquiryamount']);
			},
			type : 'money',
			tclass : 'txtshort'
		}, {
			display : '行政意见',
			name : 'suggestion',
			process : function ($input, rowData) {
				$input.val(rowData['advice']);
			},
			type : 'textarea',
			cols : '40',
			rows : '3'
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

	$("#applicantName").yxselect_user({
		hiddenId : 'applicantId',
		isGetDept : [true, "applyDetId", "applyDetName"]
	});

	// 根据采购种类来显示资产用途
	purchCategoryArr = getData('CGZL');
	addDataToSelect(purchCategoryArr, 'purchCategory');
	$('#purchCategory').change(function() {
		$('#assetUseCode').empty();
		assetUseArr = getData($('#purchCategory').val());
		addDataToSelect(assetUseArr, 'assetUseCode');
		if ($('#assetUseCode').val()) {
			$('#assetUse').val($('#assetUseCode').get(0).options[$('#assetUseCode').get(0).selectedIndex].innerText);
		}
	});

	// 根据采购类型来显示部分字段（计划编号、计划年度）
	$("#hiddenA").hide();
	$('#purchaseType').change(function() {
		if ($("#purchaseType").val() == "CGLX-JHN") {
			$("#hiddenA").show();
		} else {
			$('#planYear').val("");
			$("#hiddenA").hide();
		}
	});

	// 根据采购种类为“研发类”时来显示部分字段（采购分类、重大专项名称、募集资金项目、其它研发项目）
	$("#hiddenD").hide();
	$("#hiddenE").hide();
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
		"useType" : {
			required : true
		},
		"userName" : {
			required : true
		},
		"applicantName" : {
			required : true
		},
		"businessBelongName" : {
			required : true
		},
		"applyTime" : {
			custom : ['date']
		},
//		"userTel" : {
//			required : false,
//			custom : ['onlyNumber']
//		},
		"assetUseCode" : {
			required : true
		},
		"amounts" : {
			required : true
		}
	});

	$("#purchaseViewTable").yxeditgrid({
		objName : 'apply[applyItem]',
		url : '?model=asset_purchase_apply_applyItem&action=preListJson',
		delTagName : 'isDelTag',
		type : 'view',
		param : {
			relDocId : requireId,
			isDel : '0'
		},
		colModel : [{
			display : '物料名称',
			name : 'inputProductName',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '规格',
			name : 'pattem',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '申请数量',
			name : 'applyAmount',
			tclass : 'txtshort'
		}, {
			display : '供应商',
			name : 'supplierName',
			tclass : 'txtmiddle'
		}, {
			display : '单位',
			name : 'unitName',
			tclass : 'readOnlyTxtItem'
		}, {
//			display : '采购数量',
//			name : 'purchAmount',
//			tclass : 'txtshort'
//		}, {
			display : '希望交货日期',
			name : 'dateHope',
			type : 'date'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	});

	$("#itemTable").yxeditgrid({
		url : "?model=asset_purchase_apply_apply&action=getRequireDetail",
        param: {requireId: requireId},
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'detailId',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '设备描述',
			name : 'devicedescription',
			tclass : 'txt'
		}, {
			display : '数量',
			name : 'amount',
			tclass : 'txtshort'
		}, {
			display : '已发货数量',
			name : 'executedNum',
			tclass : 'txtshort'
		}, {
			display : '预计金额',
			name : 'expectAmount',
			tclass : 'txtshort',
			process : function(v,row){
				v = accMul(row.deviceprice,row.amount);
				return moneyFormat2(v);
			}
		}, {
			display : '预计交货日期',
			name : 'borrowdate',
			type:'date',
			tclass : 'txtshort'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}, {
			display : '询价金额',
			name : 'inquiryamount',
			process : function(v,row) {
				return moneyFormat2(v);
			},
			tclass : 'txtshort'
		}, {
			display : '行政意见',
			name : 'advice',
			type : 'textarea'
		}]
	})
});

$(function(){
	// 添加需求审批记录
	if(typeof($("#extra_approvalItems").val()) != 'undefined' &&  $("#extra_approvalItems").val() == 'xq'){
		xq_approval();
	}
});

/*
 * 审核确认
 */
function confirmAudit(act) {
	//部门是否有不同
//	var deptDiff = false;
//	var markDept;
//	$("select[id^='purchaseProductTable_cmp_purchDept']").each(function(i,n){
//		//如果不是删除数据，才处理
//		if($("#apply[applyItem]_"+ i +"_isDelTag").length == 0){
//			if(!markDept){
//				markDept = this.value;
//			}else if(markDept != this.value){
//				deptDiff = true;
//				return false;
//			}
//		}
//	});
//	//如果部门不同，则不能提交表单
//	if(deptDiff == true){
//		alert('单次下达采购申请必须保持采购部门一致');
//		return false;
//	}

	// if (confirm("确定要提交吗?")) {
	// 	$("#form1").attr("action","?model=asset_purchase_apply_apply&action=add&actType=audit").submit();
	// } else {
	// 	return false;
	// }
	if(act == 'submit'){
		if (confirm("确定要提交吗?")) {
			var purchaseDept = $('#purchaseDept').val();
			// PMS 636 交付采购流程也需要总资产管理员审批
			var audit = 'submit';//(purchaseDept == '1')? 'noaudit' : 'submit';
			var action = 'addBeforeConfirm';//(purchaseDept == '1')? 'add' : 'addBeforeConfirm';
			// $("#form1").attr("action","?model=asset_purchase_apply_apply&action=addBeforeConfirm&actType="+audit).submit();
			$("#form1").attr("action","?model=asset_purchase_apply_apply&action="+action+"&actType="+audit).submit();
		} else {
			return false;
		}
	}else{
		// 保存
		$("#form1").attr("action","?model=asset_purchase_apply_apply&action=addBeforeConfirm").submit();
	}
}
// 根据从表的残值动态计算应付总金额
function countAmount() {
	// 获取当前的行数即卡片的资产数
	var curRowNum = $("#purchaseProductTable").yxeditgrid("getCurRowNum")
	$("#amounts").val(curRowNum);

	var rowAmountVa = 0;
	var cmps = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "amounts");
	cmps.each(function() {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	$("#amounts").val(rowAmountVa);
	$("#amounts_v").val(moneyFormat2(rowAmountVa));
	return true;
}
