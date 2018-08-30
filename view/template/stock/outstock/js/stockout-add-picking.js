$(document).ready(function(){
	//发料人
	$("#salesmanName").yxselect_user({
		hiddenId : 'salesmanCode'
	});

	//发料仓库
	$("#stockName").yxcombogrid_stockinfo({
		hiddenId : 'stockId',
		nameCol : 'stockName',
		gridOptions : {
			showcheckbox : false,
			event : {
				row_dblclick : function(e ,row ,data) {
					$("#stockCode").val(data.stockCode);
					var stockObjs = $("#itemtable").yxeditgrid('getCmpByCol' ,'stockName');
					for(var i = 0 ;i < stockObjs.length ;i++) {
						$("#itemtable_cmp_stockId" + i).val(data.id);
						$("#itemtable_cmp_stockCode" + i).val(data.stockCode);
						$("#itemtable_cmp_stockName" + i).val(data.stockName);
					}
				}
			}
		}
	});

	//判断是否有审核权限
	if($('#auditLimit').val() != "1") {
		$("#auditButton").hide();
	}

	$("#itemtable").yxeditgrid({
		objName : 'stockout[items]',
		url : '?model=produce_plan_pickingitem&action=listJson',
		param : {
			pickingId : $("#relDocId").val()
		},
		isAdd : false,
		colModel : [{
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '物料编号',
			name : 'productCode',
			type : 'statictext',
			isSubmit : true,
			width : 100
		},{
			display : '物料名称',
			name : 'productName',
			type : 'statictext',
			isSubmit : true,
			width : 300
		},{
			display : '规格型号',
			name : 'pattern',
			type : 'statictext',
			isSubmit : true,
			width : 100
		},{
			display : '单位',
			name : 'unitName',
			type : 'statictext',
			isSubmit : true,
			width : 80
		},{
			display : '批次',
			name : 'batchNum'
		},{
			display : '发料仓库',
			name : 'stockName',
			validation : {
				required : true
			},
			process : function ($input) {
				var rowNum = $input.data("rowNum");
				$input.yxcombogrid_stockinfo({
					hiddenId : 'itemtable_cmp_stockId' + rowNum,
					nameCol : 'itemtable_cmp_stockName' + rowNum,
					gridOptions : {
						showcheckbox : false,
						event : {
							row_dblclick : function(e ,row ,data) {
								$("#itemtable_cmp_stockName" + rowNum).val(data.stockName);
								$("#itemtable_cmp_stockCode" + rowNum).val(data.stockCode);
							}
						}
					}
				});
			}
		},{
			display : '发料仓库Id',
			name : 'stockId',
			type : 'hidden'
		},{
			display : '发料仓库Code',
			name : 'stockCode',
			type : 'hidden'
		},{
			display : '源单Id',
			name : 'relDocId',
			type : 'hidden',
			process : function ($input ,row) {
				$input.val(row['id']);
			}
		},{
			display : '合同Id',
			name : 'contractId',
			type : 'hidden',
			process : function ($input ,row) {
				$input.val($('#contractId').val());
			}
		},{
			display : '合同名称',
			name : 'contractName',
			type : 'hidden',
			process : function ($input ,row) {
				$input.val($('#contractName').val());
			}
		},{
			display : '合同编号',
			name : 'contractCode',
			type : 'hidden',
			process : function ($input ,row) {
				$input.val($('#contractCode').val());
			}
		},{
			display : '申请数量',
			name : 'applyNum',
			type : 'statictext',
			width : 70
		},{
			display : '申请数量',
			name : 'shouldOutNum',
			type : 'hidden',
			width : 70,
			process : function ($input ,row) {
				$input.val(row.applyNum);
			}
		},{
			display : '实发数量',
			name : 'actOutNum',
			width : 70,
			validation : {
				required : false,
				custom : ['onlyNumber']
			},
			process : function ($input ,row) {
				var validNum = row.applyNum - row.realityNum;
				var rowNum = $input.data("rowNum");
				$input.val(validNum);
				$input.blur(function () {
					if ($(this).val() > validNum) {
						alert('实发数量不能超过申请数量');
						$(this).val(validNum);
					}else{
						FloatMul('itemtable_cmp_actOutNum' + rowNum ,'itemtable_cmp_cost' + rowNum ,'itemtable_cmp_subCost' + rowNum);
					}
				});
			}
		},{
			display : '序列号',
			name : 'serialnoName',
			tclass : 'readOnlyTxtMiddleLong',
			readonly : true,
			process : function ($input) {
				var rowNum = $input.data("rowNum");
				$input.before('&nbsp;<img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo(' + rowNum + ');" title="选择序列号">&nbsp;');
			}
		},{
			display : '单价',
			name : 'cost',
			type : 'moneySix',
			process : function ($input) {
				var rowNum = $input.data("rowNum");
				$input.change(function () {
					$(this).trigger('blur');
					$('#itemtable_cmp_cost' + rowNum).trigger('blur');
				});
				$('#itemtable_cmp_cost' + rowNum).blur(function () {
					FloatMul('itemtable_cmp_actOutNum' + rowNum ,'itemtable_cmp_cost' + rowNum ,'itemtable_cmp_subCost' + rowNum);
				});
			}
		},{
			display : '金额',
			name : 'subCost',
			tclass : 'readOnlyTxt',
			readonly : true,
			type : 'money'
		},{
			display : '生产/采购日期',
			name : 'prodDate',
			readonly : true,
			type : 'date',
			tclass : 'txtshort'
		},{
			display : '保质期(天)',
			name : 'shelfLife',
			tclass : 'txtshort'
		},{
			display : '有效期至',
			name : 'validDate',
			readonly : true,
			type : 'date',
			tclass : 'txtshort'
		}]
	});

	validate({
		"auditDate" : {
			required : true
		}
	});
});

function checkForm() {// 表单校验
	var productIdObjs = $("#itemtable").yxeditgrid('getCmpByCol' ,'productId');
	if (productIdObjs.length == 0) {
		alert("没有物料信息！");
		return false;
	}
    // 所属板块
    if($("#module").val() == ""){
    	alert("所属板块不能为空");
    	return false;
    }
	return true;
}

/**
 * 查看合同审批情况
 */
function viewContracAudit() {
	if ($("#contractId").val() == "") {
		alert("请先选择需要查看的合同");
	} else {
		showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_contract_contract&pid='
			+ $("#contractId").val()
			+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
	}
}

//审核确认
function confirmAudit() {
	var auditDate = $("#auditDate").val();
	if (couldAudit(auditDate)) {
		if (confirm("审核后单据将不可修改，你确定要审核吗?")) {
			if (checkForm()) {
				//检查是否完成所有必填
				var result = true;
				$.each($('[class*=validate[required]]') ,function (i ,k) {
					if (($.trim($(this).val())).length == 0) {
						result = false;
						return false; //退出循环
					}
				});
				if (!result) {
					$("#form1").submit(); //触发提示必填
					return false; //退出函数
				}
				if (checkProNumIntime()) {
					if (result) {
						$("#docStatus").val("YSH");
						$("#form1").attr("action" ,"?model=stock_outstock_stockout&action=add&actType=audit");
					}
					$("#form1").submit();
				}
			}
		}
	}
}

/**
 * 校验即时库存
 */
function checkProNumIntime() {
	var checkResult = true;
	var productIdObjs = $("#itemtable").yxeditgrid('getCmpByCol' ,'productId');
	var productCodeObjs = $("#itemtable").yxeditgrid('getCmpByCol' ,'productCode');
	var stockIdObjs = $("#itemtable").yxeditgrid('getCmpByCol' ,'stockId');
	var stockNameObjs = $("#itemtable").yxeditgrid('getCmpByCol' ,'stockName');
	var actOutNumObjs = $("#itemtable").yxeditgrid('getCmpByCol' ,'actOutNum');
	for (var i = 0 ;i < productIdObjs.length ;i++) {
		$.ajax({// 获取对应库存信息
			type : "POST",
			dataType : "json",
			async : false,
			url : "?model=stock_inventoryinfo_inventoryinfo&action=getInTimeObj",
			data : {
				productId : $(productIdObjs[i]).val(),
				stockId : $(stockIdObjs[i]).val()
			},
			success : function(result) {
				if (isNum($(actOutNumObjs[i]).val())) {
					if ($("#isRed").val() == "0") {// 蓝色出库才校验库存数量
						if (result != "0") {
							if (parseInt($(actOutNumObjs[i]).val())>result['exeNum'] ) {
								alert("库存不足! " + $(stockNameObjs[i]).val()
									+ "中编号为\""
									+ $(productCodeObjs[i]).val()
									+ "\"的物料可执行数量是" + result['exeNum']);
								checkResult = false;
							}
						} else {
							alert("库存不足!" + $(stockNameObjs[i]).val()
								+ "中不存在编号为\""
								+ $(productCodeObjs[i]).val() + "\"的物料");
							checkResult = false;
						}
					}
				} else {
					alert("发货数量填写有误!");
					checkResult = false;
				}
			}
		});
		if (!checkResult) {
			return checkResult;
		}
	}
	return true;
}

//财务是否已关账,单据所在财务周期是否已结账判断
function couldAudit(auditDate) {
	var resultTrue = true;
	$.ajax({
		type : "POST",
		async : false,
		url : "?model=finance_period_period&action=isClosed",
		data : {},
		success : function(result) {
			if (result == 1) {
				alert("财务已关账，不能进行审核，请联系财务人员进行反关账！")
				resultTrue = false;
			}
		}
	});
	$.ajax({
		type : "POST",
		async : false,
		url : "?model=finance_period_period&action=isLaterPeriod",
		data : {
			thisDate : auditDate
		},
		success : function(result) {
			if (result == 0) {
				alert("单据所在财务周期已经结账，不能进行审核，请联系财务人员进行反结账！")
				resultTrue = false;
			}
		}
	});

	return resultTrue;
}

/**
 * 选择序列号
 */
function chooseSerialNo(seNum) {
	var productIdVal = $("#itemtable_cmp_productId" + seNum).val();
	var stockIdVal = $("#itemtable_cmp_stockId" + seNum).val();
	var serialnoId = $("#itemtable_cmp_serialnoId" + seNum).val();
	var serialnoName = $("#itemtable_cmp_serialnoName" + seNum).val();

	var cacheResult = false;
	var productCodeSeNum = $("#itemtable_cmp_productCode" + seNum).val() + "_" + seNum;
	$.ajax({// 缓存序列号
		type : "POST",
		async : false,
		url : "?model=stock_serialno_serialno&action=cacheSerialno",
		data : {
			serialSequence : serialnoName,
			productCodeSeNum : productCodeSeNum
		},
		success : function(result) {
			if (result == 1) {
				cacheResult = true;
			}
		}
	})
	if (cacheResult) {
		if (productIdVal != "") {
			if (stockIdVal != "" && parseInt($("#itemtable_cmp_stockId" + seNum).val()) != 0) {
				showThickboxWin(
					"?model=stock_serialno_serialno&action=toChooseFrame&serialnoId="
						+ serialnoId
						+ "&productId="
						+ productIdVal
						+ "&elNum="
						+ seNum
						+ "&stockId="
						+ stockIdVal
						+ "&isRed="
						+ $("#isRed").val()
						+ "&productCodeSeNum="
						+ productCodeSeNum
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=600",
					"选择序列号")
			} else {
				alert("请先选择仓库!");
			}
		} else {
			alert("请先选择物料!");
		}
	}
}