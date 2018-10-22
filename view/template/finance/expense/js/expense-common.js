//表单类型数组
var billTypeArr = [];
var billTypeStr;
var moduleArr = [];
var moduleStr;
var canChangeModule = false;
var currentModule = '';

function reloadModelList(){
	$("#modelTypeName").yxcombogrid_expensemodel('reload');
}

//实际日期计算
function actTimeCheck(){
	var startDate = $('#CostDateBegin').val();
	var endDate = $('#CostDateEnd').val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate);
	if(s < 0) {
		alert("开始日期不能比结束日期晚！");
		return false;
	}
	var actDays = s + 1;
	$("#days").val(actDays);
	$("#periodDays").val(actDays);

	var thisCostTypeId,showDays ;
	$("#invbody input[id^='costTypeId']").each(function(i,n){
		thisCostTypeId = this.value;
		//是否显示日期
		showDays = $("#showDays"+ thisCostTypeId).val();
		//则修改日期
		if(showDays == '1'){
			$("#days" + thisCostTypeId).val(actDays);
			detailSet(thisCostTypeId);
			countAll();
			//更新分摊金额
			$("input[id][^='days']").trigger('onchange');
		}
	});
}

//初始化最大天数
function initDays(){
	var startDate = $('#CostDateBegin').val();
	var endDate = $('#CostDateEnd').val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate) + 1;
	$("#periodDays").val(s);
}

//日期设置
function periodDaysCheck(){
	var days = $("#days").val();
	var newDays = days;
	var periodDays = $("#periodDays").val();
	if(periodDays < days){
		alert('此项天数不能大于费用期间总天数');
		$("#days").val(periodDays);
		newDays = periodDays;
	}

	var thisCostTypeId,showDays ;
	$("#invbody input[id^='costTypeId']").each(function(i,n){
		thisCostTypeId = this.value;
		//是否显示日期
		showDays = $("#showDays"+ thisCostTypeId).val();
		//则修改日期
		if(showDays == '1'){
			$("#days" + thisCostTypeId).val(newDays);
			detailSet(thisCostTypeId);
			countAll();
		}
	});
}

//获取表单类型
function getBillType() {
	var responseText = $.ajax({
		url : 'index1.php?model=common_otherdatas&action=getBillType',
		type : "POST",
		async : false
	}).responseText;
	var o = eval("(" + responseText + ")");
	return o;
}

//设置选择字符串
function rtBillTypeStr(data, costTypeId) {
	var defaultVal = $("#defaultInvoice" + costTypeId).val();
	var isReplace = $("#isReplace"+ costTypeId).val();
	var isSubsidy = $("#isSubsidy"+ costTypeId).val();
	if(isReplace == 1){
        var title =  '此费用允许替票';
	}else{
        var title =  '此费用不允许替票';
	}
	var str ;
	for (var i = 0, l = data.length; i < l; i++) {
		if(defaultVal == data[i].id && isSubsidy == "0"){
    		str +='<option value="'+ data[i].id +'" selected="selected" title="'+title+'">'+ data[i].name+'</option>';
        }else{
        	if(isReplace == '1'){
           		str +='<option value="'+ data[i].id +'" title="'+title+'">'+ data[i].name+'</option>';
        	}
        }
	}
	return str;
}

//设置选择字符串
function rtModuleStr(data, module) {
	var str = '';
	for (var i = 0, l = data.length; i < l; i++) {
		if(module == data[i].dataCode){
    		str +='<option value="'+ data[i].dataCode +'" selected="selected" title="'+data[i].dataName+'">'+ data[i].dataName+'</option>';
        }else{
       		str +='<option value="'+ data[i].dataCode +'" title="'+data[i].dataName+'">'+ data[i].dataName+'</option>';
        }
	}
	return str;
}

//普通输入框金额设置
function detailSet(thisNum){
	//取显示值，然后进行+号计算
	var costMoney = $("#costMoney" + thisNum + "_v").val();

	if(costMoney){
		costMoney = autoAdd(costMoney);
		setMoney("costMoney" + thisNum,costMoney);

		//获取选择对象
		var isSubsidy = $("#isSubsidy" + thisNum);
		if(isSubsidy.val() == "1"){
			var detailTable = $("#table_"+ thisNum +" select[id^='select_" + thisNum + "_']");
			if(detailTable.length == 0){
				var days = $("#days" + thisNum).val();
				costMoney = accMul(costMoney,days,2);
				setMoney("invoiceMoney_"+ thisNum + "_0",costMoney);

				//初始化一个发票数量
				$("#invoiceNumber_"+ thisNum + "_0").val(0);
			}
		}else{
			var detailTable = $("#table_"+ thisNum +" select[id^='select_" + thisNum + "_']");
			if(detailTable.length == 1){
				var days = $("#days" + thisNum).val();
				costMoney = accMul(costMoney,days,2);
				setMoney("invoiceMoney_"+ thisNum + "_0",costMoney);

				//初始化一个发票数量
				var invoiceNumberObj = $("#invoiceNumber_"+ thisNum + "_0");
				if(invoiceNumberObj.val() == ""){
					invoiceNumberObj.val(1);
				}
			}
		}

		//明细计算
	    countInvoiceMoney();
	    countInvoiceNumber();
	}
}

//发票金额输入框设置
function invMoneySet(thisNum){
	//取显示值，然后进行+号计算
	var invoiceMoney = $("#invoiceMoney_" + thisNum + "_v").val();
	if(invoiceMoney){
		invoiceMoney = autoAdd(invoiceMoney);
		setMoney("invoiceMoney_" + thisNum,invoiceMoney);
	}
}

//加法节奏
function autoAdd(thisVal){
	var thisValArr = thisVal.split("+");
	var rtMoney = 0;
	for(var i = 0;i< thisValArr.length;i++){
		rtMoney = accAdd(rtMoney,thisValArr[i],2);
	}
	return rtMoney;
}

//增加发票信息
function add_lnvoice(id){
	//实例化变量
	var costMoney , costType , detailMoney ,delTag ,lastMoney,days;
	//金额缓存
	costMoney = $("#costMoney" + id).val();
	days = $("#days" + id).val();
	costMoney = accMul(costMoney,days,2);
	//名称缓存
	costType = $("#costType" + id).val();
	//重新初始化金额
	var detailAll = 0;

	var isSubsidy = $("#isSubsidy" + id).val();
	if(isSubsidy == "1"){
		//如果不是补贴类，则直接处理
		detailMoney = $("#invoiceMoney_"+ id + "_0").val();
		detailAll = accAdd(detailAll,detailMoney,2);

		//特殊处理部分
		var k = 1;
		//如果不是补贴类，则直接处理
		$("select[id^='select_" + id + "_']").each(function(i,n){
			delTag = $("#feeTbl #isDelTag_"+ id + "_" + k).length;
			if(delTag == 0){
				detailMoney = $("#invoiceMoney_"+ id + "_" + k).val();
				detailAll = accAdd(detailAll,detailMoney,2);
			}
			k++;
		});
	}else{
		//如果不是补贴类，则直接处理
		$("select[id^='select_" + id + "_']").each(function(i,n){
			delTag = $("#feeTbl #isDelTag_"+ id + "_" + i).length;
			if(delTag == 0){
				detailMoney = $("#invoiceMoney_"+ id + "_" + i).val();
				detailAll = accAdd(detailAll,detailMoney,2);
			}
		});
	}
	lastMoney = accSub(costMoney,detailAll,2);
	var invoiceNumber = 1;
	if(lastMoney*1 <= 0){
		lastMoney = "";
		invoiceNumber = "";
	}

	//初始化发票类型
	billTypeStr = rtBillTypeStr(billTypeArr,id);

	//内置从表
	var tableObj = $("#table_" + id);
	//从表行对象
	var tableTrObj = $("#table_" + id + " tr");
	//从表行数
	var tableTrLength = tableTrObj.length;
	//内容Id后缀
	var countStr = id + "_" + tableTrLength;
	var str = '<tr id="tr_' + countStr + '">' +
			'<td width="30%"><select id="select_' + countStr + '" name="expense[expensedetail]['+ id + '][expenseinv][' + tableTrLength + '][BillTypeID]" style="width:90px"><option value="">请选择发票</option>' + billTypeStr +'</select></td>' +
			'<td width="25%"><input id="invoiceMoney_' + countStr + '" costTypeId="'+ id +'" rowCount="'+ countStr +'" name="expense[expensedetail]['+ id + '][expenseinv][' + tableTrLength + '][Amount]" type="text" class="txtshort" value="'+lastMoney+'" onblur="invMoneySet(\''+ countStr +'\');countInvoiceMoney();countAll();"/></td>' +
			'<td width="25%"><input id="invoiceNumber_' + countStr + '" costTypeId="'+ id +'" rowCount="'+ countStr +'"  name="expense[expensedetail]['+ id + '][expenseinv][' + tableTrLength + '][invoiceNumber]" type="text" class="txtshort" value="'+ invoiceNumber +'" onblur="countInvoiceNumber(this);"/>' +
			'<input type="hidden" id="invIsSubsidy_' + countStr + '" name="expense[expensedetail]['+ countStr +'][expenseinv][0][isSubsidy]" value="0"/></td>' +
            '<td width="20%"><img style="cursor:pointer;" src="images/removeline.png" title="删除本行发票" onclick="delete_lnvoice(' + id + ',this)"/></td>' +
		'</tr>';
	tableObj.append(str);
	//格式化金额
    createFormatOnClick('invoiceMoney_'+countStr);

    //备注高度调整
    var remarkObj = $("#remark" + id);
    remarkObj.animate({height:"+=33"},100);

	//明细计算
    countAll();
    countInvoiceMoney();
    countInvoiceNumber();
}

//删除发票信息
function delete_lnvoice(id,obj){
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="expense[expensedetail]['+
				id +'][expenseinv][' +
				rowNo + '][isDelTag]" id="isDelTag_'+ id +'_'+rowNo +'" value="1"/>');

	    //备注高度调整
	    var remarkObj = $("#remark" + id);
	    remarkObj.animate({height:"-=33"},100);


		//明细计算
	    countInvoiceNumber();
		countInvoiceMoney();
	    countAll();
	}
}

//增加发票信息
function addModule(id){
	//初始化所属板块
	if(currentModule == ''){currentModule = ($("#moduleVal").val() == undefined)? '' : $("#moduleVal").val();}
	moduleStr = (currentModule == '' || currentModule == undefined)? rtModuleStr(moduleArr,'') : rtModuleStr(moduleArr,currentModule);

	//内置从表
	var tableObj = $("#tableCostshare_" + id);
	//从表行对象
	var tableTrObj = $("#tableCostshare_" + id + " tr");
	//从表行数
	var tableTrLength = tableTrObj.length;
	//内容Id后缀
	var countStr = id + "_" + tableTrLength;

	// ----- 根据当前费用归属部门是否满足可编辑板块的条件来显示对应编辑框 ----- //
	// 费用归属部门
	var costBelongDeptId = $("#costBelongDeptId").val();
	// ------- 分摊板块可否修改权限控制 ------- //
	chkCanChangeModule(costBelongDeptId);
	// ------- ./分摊板块可否修改权限控制 ------- //
	var hideSelect = (canChangeModule)? '' : 'display:none';
	var hideInput = (canChangeModule)? 'display:none' : '';
	var currentModuleName = (currentModule == '' || currentModule == undefined)? '' : getDataByCode(currentModule);

	var moduleBoxStr =
		'<select id="selectCostshare_' + countStr + '" name="expense[expensecostshare]['+ id + '][expenseinv][' + tableTrLength + '][module]" style="width:90px;'+hideSelect+'"><option value="">请选择板块</option>' + moduleStr +'</select>' +
		'<input id="inputCostshare_' + countStr + '" type="text" value="'+currentModuleName+'"  style="width:90px;text-align:center;background-color: #EEEEEE;border: 1px solid #C0C2CF;'+hideInput+'" readonly/>';
	// ----- ./根据当前费用归属部门是否满足可编辑板块的条件来显示对应编辑框 ----- //

	var str = '<tr id="trCostshare_' + countStr + '">' +
			'<td width="49.5%"><input type="text" costTypeId="'+ id +'" rowCount="'+ countStr +'" name="expense[expensecostshare]['+ id + '][expenseinv][' + tableTrLength + '][CostMoney]" id="costMoneyCostshare_'+ countStr +'" style="width:146px" class="txtmiddle formatMoney" onblur="countAllCostshare();"/></td>' +
			'<td width="32.5%">'+moduleBoxStr+'</td>' +
            '<td width="18%"><img style="cursor:pointer;" src="images/removeline.png" title="删除本行信息" onclick="deleteModule(' + id + ',this)"/></td>' +
		'</tr>';
	tableObj.append(str);
	//格式化金额
    createFormatOnClick('costMoneyCostshare_'+countStr);

    //备注高度调整
    var remarkObj = $("#remarkCostshare" + id);
    remarkObj.animate({height:"+=33"},100);
}

//删除板块信息
function deleteModule(id,obj){
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="expense[expensecostshare]['+
				id +'][expenseinv][' +
				rowNo + '][isDelTag]" id="isDelTag_'+ id +'_'+rowNo +'" value="1"/>');

	    //备注高度调整
	    var remarkObj = $("#remarkCostshare" + id);
	    remarkObj.animate({height:"-=33"},100);

		//明细计算
	    countAllCostshare();
	}
}

//设置费用金额的title
function initAmountTitle(feeRegular,feeSubsidy){
	$("#feeRegularView").html(moneyFormat2(feeRegular));
	$("#feeSubsidyView").html(moneyFormat2(feeSubsidy));
}

//表单数据合计
function countAll(){
	//从表总金额
	var tableTrObj = $("#invbody input[id^='costTypeId']");
	var costTypeId , costMoney , countAll , days ,thisCostMoney , isSubsidy , detailInvMoney;
	var feeRegular = feeSubsidy  = 0;
	$.each(tableTrObj,function(i,n){
		costTypeId = this.value*1;
		delTag = $("#isDelTag_"+ costTypeId).length;

		if(delTag == 0){
			costMoney = $("#costMoney" + costTypeId).val();
			days = $("#days" + costTypeId).val();
			thisCostMoney = accMul(costMoney,days,2);

			//获取是否需要发票选项
			isSubsidy = $("#isSubsidy" + costTypeId).val();
			if(isSubsidy == 1){
				//如果发票是补贴，则根据金额计算补贴费用
				$("input[id^='invIsSubsidy_"+ costTypeId +"_']").each(function(i,n){
					var invIsDelTag = $("#feeTbl #isDelTag_" + costTypeId + "_" + i).length;
					if(invIsDelTag == 0){
						detailInvMoney = $("#invoiceMoney_" + costTypeId + "_" + i).val();
						if(this.value == "1"){
							feeSubsidy = accAdd(feeSubsidy,detailInvMoney,2);
						}else{
							feeRegular = accAdd(feeRegular,detailInvMoney,2);
						}
					}
				});
			}else{
				//计算表单的常规费用
				feeRegular = accAdd(feeRegular,thisCostMoney,2);
			}
			//计算表单的总金额
			countAll = accAdd(countAll,thisCostMoney,2);
		}
	});
	if(countAll*1 == 0 || countAll == undefined){
		countAll = "";
	}
	setMoney('countMoney', countAll );
	//设置title
	initAmountTitle(feeRegular,feeSubsidy);
	//金额设置
	$("#feeRegular").val(feeRegular);
	$("#feeSubsidy").val(feeSubsidy);
}

//表单发票金额合计
function countInvoiceMoney(){
	//从表总金额
	var tableTrObj = $("#invbody input[id^='invoiceMoney_']");
	var countAll , delObj , rowCount , mark ,costTypeId , isCount,isSubsidy;
	$.each(tableTrObj,function(i,n){
		//费用类型id
		costTypeId = $(this).attr('costTypeId');
		if(mark != costTypeId){
			mark = costTypeId;
			if($("#isDelTag_" + costTypeId ).length > 0){
				isCount = false;
			}else{
				isCount = true;
			}
		}

		rowCount = $(this).attr('rowCount');//明细id号
		if(rowCount){
			invIsSubsidy = $("#invIsSubsidy_" + rowCount).val()*1;
		}
		//判断是否有值
		if(this.value != "" && rowCount && isCount && invIsSubsidy == "0"){
			if($("#feeTbl #isDelTag_" + rowCount).length == 0){
				countAll = accAdd(countAll,this.value);
			}
		}
	});
	if(countAll*1 == 0 || !countAll){
		countAll = "";
	}
	//触发费用合计
	setMoney('invoiceMoney', countAll );
}

//分摊明细金额合计
function countAllCostshare(){
	//从表总金额
	var tableTrObj = $("#costsharebody input[id^='costMoneyCostshare_']");
	var countAll , delObj , rowCount , mark ,costTypeId , isCount;
	$.each(tableTrObj,function(i,n){
		//费用类型id
		costTypeId = $(this).attr('costTypeId');
		if(mark != costTypeId){
			mark = costTypeId;
			if($("#isDelTag_" + costTypeId ).length > 0){
				isCount = false;
			}else{
				isCount = true;
			}
		}

		rowCount = $(this).attr('rowCount');//明细id号
		//判断是否有值
		if(this.value != "" && rowCount && isCount){
			if($("#costshare #isDelTag_" + rowCount).length == 0){
				countAll = accAdd(countAll,this.value);
			}
		}
	});
	if(countAll*1 == 0 || !countAll){
		countAll = "";
	}
	//触发费用合计
	setMoney('allCostshareMoney', countAll );
}

//表单发票数量合计
function countInvoiceNumber(thisObj){
	//如果存在传入参数，则验证是否数字
	if(thisObj){
		var re = /^[1-9]d*|0$/;

		if (!re.test(thisObj.value)) {
			if (isNaN(thisObj.value)) {
				alert("请输入非负整数!");
				thisObj.value = "";
				thisObj.focus();
				return false;
			}
		}
	}
	//从表总金额
	var tableTrObj = $("#invbody input[id^='invoiceNumber_']");
	var countAll , delObj , rowCount , mark ,costTypeId , isCount;
	$.each(tableTrObj,function(i,n){
		//费用类型id
		costTypeId = $(this).attr('costTypeId');
		if(mark != costTypeId){
			mark = costTypeId;
			if($("#isDelTag_" + costTypeId).length > 0){
				isCount = false;
			}else{
				isCount = true;
			}
		}

		//判断是否有值 并且当前值计入统计中
		if(this.value != "" && isCount ){
			rowCount = $(this).attr('rowCount');
			if($("#feeTbl #isDelTag_" + rowCount).length == 0){
				countAll = accAdd(countAll,this.value);
			}
		}
	});
	if(countAll*1 == 0 || !countAll){
		countAll = "";
	}
	$("#invoiceNumber").val(countAll);
}

//表单验证
function checkForm(submitType){
	//事由
	var detailType = $("input[name='expense[DetailType]']:checked").val();
	if(strTrim(detailType) == "undefined"){
		alert('必须选择一种费用类型！');
		return false;
	}

	// 检查是否满足服务线报销限制条件
	var costTypeIds = costTypeNames = '';
	$.each($("#invbody").children("tr"),function(i,item){
		var costType = $(item).attr("id");
		if($(item).css("display") != "none"){
			costType = costType.split("tr");
			costTypeIds += (costTypeIds == '')? costType[1] : ((costType[1] != '')? ","+costType[1] : '');
			var costType = $("input[name='expense[expensedetail]["+costType[1]+"][costType]']").val();
			costTypeNames += (costTypeNames == '')?  costType : ((costType != '')? ","+costType : '');
		}
	});
	var chkResult = $.ajax({
		url : 'index1.php?model=system_configurator_configurator&action=checkBxLimitConfig',
		data: {expenseTypeId : detailType,costDepartID : $("#CostDepartID").val(),costTypeIds : costTypeIds,costTypeNames : costTypeNames},
		type : "POST",
		async : false
	}).responseText;
	var result = eval("("+chkResult+")");
	if(result.result == '1'){
		var limitTypes = result.data;
		alert('抱歉！因设置了报销限制，您所选择的\n ['+limitTypes+']\n 不能提交报销。如有疑问，请咨询部门领导或OA管理员。');
		return false;
	}

	// 检查填报区间内的阿里商旅是否已存在对应的费用项
	if(submitType == "audit"){
		// alert("提交审批前需要检查是否存在与阿里商旅重叠的费用项，请耐心等待, 谢谢!");
		var chkResult = $.ajax({
			url : 'index1.php?model=finance_expense_expense&action=checkAliTripCostRecord',
			data: {
				userId : $("#CostMan").val(),
				startDate : $("#CostDateBegin").val(),
				endDate : $("#CostDateEnd").val(),
				costTypeIds : costTypeIds,
				costTypeNames : costTypeNames
			},
			type : "POST",
			async : false
		}).responseText;
		var result = eval("("+chkResult+")");
		if(result.result.length > 0){
			console.log(result);
			if(!confirm("本报销区间内，存在与阿里商旅重叠的费用项，请核查是否继续提交。")){
				return false;
			}
		}
	}

	//费用期间表单验证
	var CostDateBegin = $("#CostDateBegin").val();
	var CostDateEnd = $("#CostDateEnd").val();
	var days = $("#days").val();
	if(CostDateBegin == "" || CostDateEnd == "" || days == ""){
		alert('请填写完整的费用期间');
		return false;
	}
	var s = DateDiff(CostDateBegin,CostDateEnd);
	if(s < 0) {
		alert("开始日期不能比结束日期晚！");
		$("#CostDateBegin").focus();
		return false;
	}
	var actDays = s + 1;
	if(actDays*1 < days*1){
		alert('费用期间天数不能大于费用期间的最大天数');
		$("#days").focus();
		return false;
	}

	// 售前费用类型,销售区域不能为空
	if(detailType == 4){
		if($('#salesAreaId').val() == 0 || $('#salesAreaId').val() == ''){
			alert('费用归属区域不得为空！');
			return false;
		}
	}

	//事由
	var PurposeObj = $("#Purpose");
	if(strTrim(PurposeObj.val()) == ""){
		alert('请填写报销事由');
		PurposeObj.focus();
		return false;
	}

	//报销人
	if(strTrim($("#CostManName").val()) == ""){
		alert('请选择报销人员');
		return false;
	}

	//同行人数
	var memberNumber = $("#memberNumber");
	if(memberNumber.val()*1 != memberNumber.val() || memberNumber.val()*1 < 0){
		alert('同行人数为正整数');
		memberNumber.val('').focus();
		return false;
	}

	//类型 对应特殊验证
	switch(detailType){
		case '1' :
			if($("#costBelongCom").val() == ""){
				alert("没有填写费用归属公司");
				return false;
			}
			if($("#costBelongDeptName").val() == ""){
				alert("没有填写费用归属部门");
				return false;
			}
			if($("#deptIsNeedProvince").val() == "1"){
				var province = $("#province").combobox('getValue');
				if(province == ""){
					alert("请选择所属省份");
					return false;
				}
			}
			//暂时停用
			break;
		case '2' :
			var projectId = $("#projectId").val();
			if(projectId == "" || projectId == "0"){
				alert("请选择该笔费用所在项目");
				return false;
			}
			if($("#projectCode").val() == ""){
				alert("请选择该笔费用所在项目");
				return false;
			}
			break;
		case '3' :
			var projectId = $("#projectId").val();
			if(projectId == "" || projectId == "0"){
				alert("请选择该笔费用所在项目");
				return false;
			}
			if($("#projectCode").val() == ""){
				alert("请选择该笔费用所在项目");
				return false;
			}
			break;
		case '4' :
			if($("#province").combobox('getValue') == ""){
				alert("请选择客户所在省份");
				return false;
			}
			if($("#city").combobox('getValues') == ""){
				alert("请选择客户所在城市");
				return false;
			}
			if($("#customerType").combobox('getValues') == ""){
				alert("请选择客户类型");
				return false;
			}
			if($("#costBelongerId").val() == ""){
				alert("请录入销售负责人，销售负责人可由商机、客户名称自动带出，或者通过客户省份、城市、类型由系统匹配");
				return false;
			}
			if($("#costBelongDeptId").val() == "" || $("#costBelongDeptName").combobox('getValue') ==""){
				alert("请选择费用归属部门");
				return false;
			}
			if($("#feeMan").val() == ""){
				alert("请选择费用承担人");
				return false;
			}
			break;
		case '5' :
			if($("#contractCode").val() == ""){
				alert("请选择该笔费用归属合同");
				return false;
			}
			if($("#costBelongDeptId").val() == "" || $("#costBelongDeptName").combobox('getValue') ==""){
				alert("请选择费用归属部门");
				return false;
			}
			if($("#feeMan").val() == ""){
				alert("请选择费用承担人");
				return false;
			}
			break;
		default : break;
	}
	
	//所属板块
//	if(strTrim($("#module").val()) == ""){
//		alert('请选择所属板块');
//		return false;
//	}

	//单据总金额判断
	if($("#countMoney").val()*1 == 0){
		alert('单据金额为空或0，不能进行保存');
		return false;
	}

	var rtVal = true;
	var costTypeId , costMoney , costType , detailMoney ,delTag ,isSubsidy;
	$("#invbody input[id^='costTypeId']").each(function(i,n){
		//类型id
		costTypeId = this.value*1;
		delTag = $("#isDelTag_"+ costTypeId).length;
		if(delTag == 0){
			//获取是否需要发票选项
			isSubsidy = $("#isSubsidy" + costTypeId).val();

			//金额缓存
			costMoney = $("#costMoney" + costTypeId).val();
			days = $("#days" + costTypeId).val();
			costMoney = accMul(costMoney,days,2);

			//名称缓存
			costType = $("#costType" + costTypeId).val();

			//如果不需要录入发票金额，则跳过验证
			if(isSubsidy == '0'){
				if(costMoney != 0){
					//重新初始化金额
					detailAll = 0;
					$("#table_"+ costTypeId +" select[id^='select_" + costTypeId + "_']").each(function(i,n){
						delTag = $("#feeTbl #isDelTag_"+ costTypeId + "_" + i).length;
						if(delTag == 0){
							//获取金额
							detailMoney = $("#invoiceMoney_"+ costTypeId + "_" + i).val();
							//金额 和类型验证
							if(this.value == "" && (detailMoney *1 != 0 || detailMoney != "")){
								alert( costType + ' 发票明细中存在无类型但有金额的发票明细项');
								rtVal = false;
								return false;
							}
							//计算发票金额
							detailAll = accAdd(detailAll,detailMoney,2);

							//发票数量必填验证
							detailNumber = $("#invoiceNumber_"+ costTypeId + "_" + i).val();
							if(detailNumber*1 == 0 || strTrim( detailNumber ) == ""){
								alert( costType + ' 发票明细中存在发票数量为0或空的项');
								rtVal = false;
							}
						}
					});
					if(rtVal == false){
						return false;
					}
					if(detailAll *1 != costMoney){
						alert( costType + ' 中费用金额' + costMoney + " 不等于发票合计金额 " + detailAll + ",请修改后再进行保存操作");
						rtVal = false;
						return false;
					}
				}
			}else{
				if(costMoney != 0){
					//重新初始化金额
					detailAll = 0;

					//计算发票金额
					detailMoney = $("#invoiceMoney_"+ costTypeId + "_0").val();
					detailAll = accAdd(detailAll,detailMoney,2);

					var k = 1;
					$("#table_"+ costTypeId +" select[id^='select_" + costTypeId + "_']").each(function(i,n){
						delTag = $("#feeTbl #isDelTag_"+ costTypeId + "_" + k).length;
						if(delTag == 0){
							//获取金额
							detailMoney = $("#invoiceMoney_"+ costTypeId + "_" + k).val();
							//金额 和类型验证
							if(this.value == "" && (detailMoney *1 != 0 || detailMoney != "")){
								alert( costType + ' 发票明细中存在无类型但有金额的发票明细项');
								rtVal = false;
								return false;
							}
							//计算发票金额
							detailAll = accAdd(detailAll,detailMoney,2);

							//发票数量必填验证
							detailNumber = $("#invoiceNumber_"+ costTypeId + "_" + k).val();
							alert(detailNumber);
							if(detailNumber*1 == 0 || strTrim( detailNumber ) == ""){
								alert( costType + ' 发票明细中存在发票数量为0或空的项');
								rtVal = false;
							}
						}
						k++;
					});

					if(rtVal == false){
						return false;
					}
					if(detailAll *1 != costMoney){
						alert( costType + ' 中费用金额' + costMoney + " 不等于发票合计金额 " + detailAll + ",请修改后再进行保存操作");
						rtVal = false;
						return false;
					}
				}
			}

			//备注
			if(costMoney*1 > 0 && strTrim($("#remark" + costTypeId).val()) == ""){
				alert('请填写 ' + costType + ' 的费用说明');
				rtVal = false;
				return false;
			}
		}
	});

	if(rtVal){
		$("#costsharebody input[id^='costTypeIdCostshare']").each(function(i,n){
			//类型id
			costTypeId = this.value*1;
			delTag = $("#isDelTag_"+ costTypeId).length;
			if(delTag == 0){
				//名称缓存
				costType = $("#costTypeCostshare" + costTypeId).val();
				//费用金额
				var costMoney = strTrim($("#costMoney"+costTypeId).val());
				isSubsidy = $("#isSubsidy" + costTypeId).val();//isSubsidy为1,则需要乘以天数
				if(isSubsidy == '1'){
					var days = strTrim($("#days" + costTypeId).val());
					if(costMoney != "" && days != ""){
						costMoney = accMul(costMoney,days);
					}
				}
				var costMoneyCostshare = 0;
				var k = 0;
				$("#tableCostshare_"+ costTypeId +" select[id^='selectCostshare_" + costTypeId + "_']").each(function(i,n){
					delTag = $("#costshare #isDelTag_"+ costTypeId + "_" + k).length;
					if(delTag == 0){
						//获取分摊金额
						var thisCostMoneyCostshare = strTrim($("#costMoneyCostshare_"+ costTypeId + "_" + k).val());
						if(costMoney != "" && thisCostMoneyCostshare == ""){
							alert( costType + ' 分摊金额不能为空');
							rtVal = false;
							return false;
						}
						//金额 和类型验证
						if(costMoney != "" && this.value == ""){
							alert( costType + ' 所属板块不能为空');
							rtVal = false;
							return false;
						}
						costMoneyCostshare = accAdd(costMoneyCostshare,thisCostMoneyCostshare,2);
					}
					k++;
				});
				if(rtVal == false){
					return false;
				}
				//分摊金额
				costMoney == "" ? 0 : costMoney;
				if(costMoney != costMoneyCostshare){
					alert( costType + ' 分摊金额之和不等于费用信息的金额');
					rtVal = false;
					return false;
				}
				//备注
//				if(costMoney != 0 && strTrim($("#remarkCostshare" + costTypeId).val()) == ""){
//					alert('请填写 ' + costType + ' 的分摊说明');
//					rtVal = false;
//					return false;
//				}
			}
		});
	}

	// 如果提交操作不是保存，验证项目是否超支
	if ($("#thisAuditType").val() != 'save') {
		checkProjectOverspend();
		var projectIdObj = $("#projectId");
		var projectOverspendObj = $("#projectOverspend");
		if (projectIdObj.length == 1 && projectOverspendObj.length == 1) {
			var projectId = projectIdObj.val();
			if (projectId != "0" && projectId != "") {
				if (projectOverspendObj.text() != "否") {
					alert('提示：因项目现已超支，暂不允许提交报销单（但可以保存），请联系项目经理，尽快变更项目预算。');
					rtVal = false;
					return false;
				}
			}
		}
	}

	if(rtVal == true){
		//防止重复提交
		$("input[type='submit']").attr('disabled',true);
	}

	return rtVal;
}

//表单验证 - 工程项目费用报销
function checkEsm(){

	//费用期间表单验证
	if($("#CostDateBegin").val() == "" || $("#CostDateEnd").val() == "" || $("#days").val() == ""){
		alert('请填写完整的费用期间');
		return false;
	}

	//事由
	if(strTrim($("#Purpose").val()) == ""){
		alert('请填写报销事由');
		return false;
	}

	//如果是预算项目类的，还要验证费用归属部门
	var DetailType = $("input:radio[name='expense[DetailType]']:radio:checked").val();
	if(strTrim(DetailType) == "4"){
		if($("#CostBelongDeptNameS").combobox("getValue") == ""){
			alert('请选择费用归属部门！');
			return false;
		}
	}

	var rtVal = true;
	var costTypeId , costMoney , costType , detailMoney ,delTag ,isSubsidy;
	$("#invbody input[id^='costTypeId']").each(function(i,n){
		//类型id
		costTypeId = this.value*1;
		delTag = $("#isDelTag_"+ costTypeId).length;
		if(delTag == 0){
			//获取是否需要发票选项
			isSubsidy = $("#isSubsidy" + costTypeId).val();

			//金额缓存
			costMoney = $("#costMoney" + costTypeId).val();
			days = $("#days" + costTypeId).val();
			costMoney = accMul(costMoney,days,2);

			//名称缓存
			costType = $("#costType" + costTypeId).val();

			//如果不需要录入发票金额，则跳过验证
			if(isSubsidy == '0'){
				if(costMoney != 0){
					//重新初始化金额
					detailAll = 0;
					$("#table_"+ costTypeId +" select[id^='select_" + costTypeId + "_']").each(function(i,n){
						delTag = $("#feeTbl #isDelTag_"+ costTypeId + "_" + i).length;
						if(delTag == 0){
							//获取金额
							detailMoney = $("#invoiceMoney_"+ costTypeId + "_" + i).val();
							//金额 和类型验证
							if(this.value == "" && (detailMoney *1 != 0 || detailMoney != "")){
								alert( costType + ' 发票明细中存在无类型但有金额的发票明细项');
								rtVal = false;
								return false;
							}
							//计算发票金额
							detailAll = accAdd(detailAll,detailMoney,2);

							//发票数量必填验证
							detailNumber = $("#invoiceNumber_"+ costTypeId + "_" + i).val();
							if(detailNumber*1 == 0 || strTrim( detailNumber ) == ""){
								alert( costType + ' 发票明细中存在发票数量为0或空的项');
								rtVal = false;
							}
						}
					});
					if(rtVal == false){
						return false;
					}
					if(detailAll *1 != costMoney){
						alert( costType + ' 中费用金额' + costMoney + " 不等于发票合计金额 " + detailAll + ",请修改后再进行保存操作");
						rtVal = false;
						return false;
					}
				}
			}else{
				if(costMoney != 0){
					//重新初始化金额
					detailAll = 0;

					//计算发票金额
					detailMoney = $("#invoiceMoney_"+ costTypeId + "_0").val();
					detailAll = accAdd(detailAll,detailMoney,2);

					var k = 1;
					$("#table_"+ costTypeId +" select[id^='select_" + costTypeId + "_']").each(function(i,n){
						delTag = $("#feeTbl #isDelTag_"+ costTypeId + "_" + k).length;
						if(delTag == 0){
							//获取金额
							detailMoney = $("#invoiceMoney_"+ costTypeId + "_" + k).val();
							//金额 和类型验证
							if(this.value == "" && (detailMoney *1 != 0 || detailMoney != "")){
								alert( costType + ' 发票明细中存在无类型但有金额的发票明细项');
								rtVal = false;
								return false;
							}
							//计算发票金额
							detailAll = accAdd(detailAll,detailMoney,2);

							//发票数量必填验证
							detailNumber = $("#invoiceNumber_"+ costTypeId + "_" + k).val();
							alert(detailNumber);
							if(detailNumber*1 == 0 || strTrim( detailNumber ) == ""){
								alert( costType + ' 发票明细中存在发票数量为0或空的项');
								rtVal = false;
							}
						}
						k++;
					});

					if(rtVal == false){
						return false;
					}
					if(detailAll *1 != costMoney){
						alert( costType + ' 中费用金额' + costMoney + " 不等于发票合计金额 " + detailAll + ",请修改后再进行保存操作");
						rtVal = false;
						return false;
					}
				}
			}

			//备注
			if(costMoney*1 > 0 && strTrim($("#remark" + costTypeId).val()) == ""){
				alert('请填写 ' + costType + ' 的费用说明');
				rtVal = false;
				return false;
			}
		}
	});

	if(rtVal == true){
		//防止重复提交
		$("input[type='submit']").attr('disabled',true);
	}

	return rtVal;
}

//提交审批改变隐藏值
function audit(thisType){
	$("#thisAuditType").val(thisType);
	if(checkForm(thisType)){
		$("#form1").submit();
	}
}

//自定义费用选择功能
function selectCostType(){
	//自定义费用域
	var costAreaObj = $("#costArea");

	//获取当前有的费用类型
	var costTypeArr = $("#invbody input[id^='costTypeId']");
	//当前存在费用类型数组
	var costTypeIdArr = [];
	//当前存在费用类型字符串
	var costTypeIds = '';

	if(costTypeArr.length > 0){
		//缓存当前存在费用类型
		costTypeArr.each(function(i,n){
			//判断是否已删除
			if($("#isDelTag_" + this.value).length == 0){
				costTypeIdArr.push(this.value);
			}
		});
		//构建当前存在费用类型id串
		costTypeIds = costTypeIdArr.toString();
	}

	//费用类型隐藏赋值
	$("#costTypeIds").val(costTypeIds);

	//第一次加载
	var isFirst = false;

	if($("#costTypeInner").html() == ""){
		isFirst = true;
		$.ajax({
		    type: "POST",
		    url: "?model=finance_expense_costtype&action=getCostType",
		    async: false,
		    success: function(data){
		   		if(data != ""){
					$("#imgLoading2").hide();
					$("#costTypeInner").html(data)
					if(costTypeIds != ""){
						//设值
						for(var i = 0; i < costTypeIdArr.length;i++){
							$("#chk" + costTypeIdArr[i]).attr('checked',true);
							$("#view" + costTypeIdArr[i]).attr('class','blue');
						}
					}
		   	    }else{
					alert('没有找到自定义的费用类型');
		   	    }
			}
		});
	}

	//显示隐藏
	if(costAreaObj.is(":hidden")){
		costAreaObj.show();
		if(isFirst == true){
			initMasonry();
		}
	}else{
		costAreaObj.hide();
	}
}

//自定义费用选择功能 - 弹出选择
function selectCostType2(){

	//获取当前有的费用类型
	var costTypeArr = $("#invbody input[id^='costTypeId']");
	//当前存在费用类型数组
	var costTypeIdArr = [];
	//当前存在费用类型字符串
	var costTypeIds = '';

	if(costTypeArr.length > 0){
		//缓存当前存在费用类型
		costTypeArr.each(function(i,n){
			//判断是否已删除
			if($("#isDelTag_" + this.value).length == 0){
				costTypeIdArr.push(this.value);
			}
		});
		//构建当前存在费用类型id串
		costTypeIds = costTypeIdArr.toString();
	}

	//费用类型隐藏赋值
	$("#costTypeIds").val(costTypeIds);

	if($("#costTypeInner").html() == ""){
		$.ajax({
		    type: "POST",
		    url: "?model=finance_expense_costtype&action=getCostType",
		    async: false,
		    success: function(data){
		   		if(data != ""){
					$("#costTypeInner").html("<div id='costTypeInner2'>" + data + "</div>")
					if(costTypeIds != ""){
						//设值
						for(var i = 0; i < costTypeIdArr.length;i++){
							$("#chk" + costTypeIdArr[i]).attr('checked',true);
							$("#view" + costTypeIdArr[i]).attr('class','blue');
						}
					}
		   	    }else{
					alert('没有找到自定义的费用类型');
		   	    }

			}
		});
	}
	$("#costTypeInner").dialog({
		title : '新增费用类型',
		height : 600,
		width : 1000
	});
	//延时调用排序方法
	setTimeout(function(){
		initMasonry();
		if(checkExplorer() == 1){
			$("#costTypeInner2").height(560).css("overflow-y","scroll");
		}
	},200);
}

//判断浏览器
function checkExplorer(){
	var Sys = {};
    var ua = navigator.userAgent.toLowerCase();
    window.ActiveXObject ? Sys.ie = ua.match(/msie ([\d.]+)/)[1] :
    document.getBoxObjectFor ? Sys.firefox = ua.match(/firefox\/([\d.]+)/)[1] :
    window.MessageEvent && !document.getBoxObjectFor ? Sys.chrome = ua.match(/chrome\/([\d.]+)/)[1] :
    window.opera ? Sys.opera = ua.match(/opera.([\d.]+)/)[1] :
    window.openDatabase ? Sys.safari = ua.match(/version\/([\d.]+)/)[1] : 0;

    if(Sys.ie){
		return 1;
    }
}

//瀑布流排序
function initMasonry(){
	$('#costTypeInner2').masonry({
		itemSelector: '.box'
	});
}

//上下级渲染
function CostTypeShowAndHide(thisCostType){
	//缓存表格对象
	var tblObj = $("table .ct_"+thisCostType + "[isView='1']");
	//如果表格当前是隐藏状态，则显示
	if(tblObj.is(":hidden")){
		tblObj.show();
		$("#" + thisCostType).attr("src","images/menu/tree_minus.gif");
	}else{
		tblObj.hide();
		$("#" + thisCostType).attr("src","images/menu/tree_plus.gif");
	}
	initMasonry();
}

//三级费用项目查看
function CostType2View(thisCostType){
	//缓存表格对象
	var tblObj = $("table .ct_"+thisCostType);
	//如果表格当前是隐藏状态，则显示
	if(tblObj.is(":hidden")){
		tblObj.show();
		tblObj.attr('isView',1);
		$("#" + thisCostType).attr("src","images/menu/tree_minus.gif");
	}else{
		tblObj.hide();
		tblObj.attr('isView',0);
		$("#" + thisCostType).attr("src","images/menu/tree_plus.gif");
	}
	initMasonry();
}

//选择自定义费用类型
function setCustomCostType(thisCostType,thisObj){
	if($(thisObj).attr('checked') == true){
		$("#view" + thisCostType).attr('class','blue');
	}else{
		$("#view" + thisCostType).attr('class','');
	}
	//判断类型是否存在，存在则干掉，不存在新增
	var trObj = $("#tr" + thisCostType);
	if(trObj.length == 1 && $("#isDelTag_" + thisCostType).length == 0){
		//删除并重新计算金额
		deleteRow(thisCostType);
	}else{
		if(trObj.length > 0){
			//如果已经存在对象，反删除
			unDeleteRow(thisCostType);
		}else{
			//缓存选择项
			var chkObj = $("#chk" + thisCostType);
			var chkName = chkObj.attr('name');  //费用名称
			var chkParentName = chkObj.attr('parentName'); //费用父类型名称
			var chkParentId = chkObj.attr('parentId'); //费用父类型id
			var chkShowDays = chkObj.attr('showDays'); //是否显示天数
			var chkIsPeplace = chkObj.attr('isReplace');//可替票
			var chkIsEqu = chkObj.attr('isEqu');//录入设备信息
			var chkInvoiceType = chkObj.attr('invoiceType');//默认发票类型
			var chkInvoiceTypeName = chkObj.attr('invoiceTypeName');//默认发票名称
			var chkIsSubsidy = chkObj.attr('isSubsidy');//是否补贴
			//费用信息处理
			//缓存表格
			var invbodyObj = $("#invbody");
			//样式渲染
			var tr_class = invbodyObj.children().length % 2 == 0 ? 'tr_odd' : 'tr_even';

			var thisI = thisCostType + "_0";
			var str = '<tr class="'+tr_class+'" id="tr' + thisCostType + '">' +
				'<td valign="top">' +
				'<img style="cursor:pointer;" src="images/removeline.png" title="删除费用" onclick="deleteCostType('+ thisCostType +')"/>' +
				'</td>' +
				'<td valign="top" class="form_text_right">' +
				chkParentName +
				'<input type="hidden" name="expense[expensedetail]['+ thisCostType +'][MainType]" value="'+ chkParentName +'"/>' +
				'<input type="hidden" name="expense[expensedetail]['+ thisCostType +'][MainTypeId]" value="'+ chkParentId +'"/>' +
				'<input type="hidden" id="showDays'+ thisCostType +'" value="'+ chkShowDays +'"/>' +
				'<input type="hidden" id="defaultInvoice'+ thisCostType +'" value="'+ chkInvoiceType +'"/>' +
				'<input type="hidden" id="defaultInvoiceName'+ thisCostType +'" value="'+ chkInvoiceTypeName +'"/>' +
				'<input type="hidden" id="isReplace'+ thisCostType +'" value="'+ chkIsPeplace +'"/>' +
				'<input type="hidden" id="isEqu'+ thisCostType +'" value="'+ chkIsEqu +'"/>' +
				'<input type="hidden" id="isSubsidy'+ thisCostType +'" value="'+ chkIsSubsidy +'"/>' +
				'</td>' +
				'<td valign="top" class="form_text_right">' +
				chkName +
				'<input type="hidden" name="expense[expensedetail]['+ thisCostType +'][costType]" id="costType'+ thisCostType +'" value="'+ chkName + '"/>' +
				'<input type="hidden" name="expense[expensedetail]['+ thisCostType +'][CostTypeID]" id="costTypeId'+ thisCostType +'" value="' + thisCostType + '"/>' +
				'</td>' +
				'<td valign="top" class="form_text_right">';

			if(chkShowDays == 0){
				str +=
					'<input type="text" name="expense[expensedetail]['+ thisCostType +'][CostMoney]" id="costMoney'+ thisCostType +'" style="width:146px" class="txtmiddle formatMoney" onblur="detailSet('+ thisCostType +');countAll();setCostshareMoney('+ thisCostType +');"/>' +
					'<input type="hidden" name="expense[expensedetail]['+ thisCostType +'][days]" id="days'+ thisCostType +'" value="1"/>';
			}else{
				//获取期间天数
				var days = $("#days").val();
				str +=
					'<span>' +
					'<input type="text" name="expense[expensedetail]['+ thisCostType +'][CostMoney]" id="costMoney'+ thisCostType +'" style="width:60px" class="txtshort formatMoney" onblur="detailSet('+ thisCostType +');countAll();setCostshareMoney('+ thisCostType +');"/>' +
					' X ' +
					' 天数 ' +
					'<input type="text" name="expense[expensedetail]['+ thisCostType +'][days]" class="txtmin" id="days'+ thisCostType +'" value="'+ days +'" onblur="daysCheck(this);detailSet('+ thisCostType +');countAll();setCostshareMoney('+ thisCostType +');" onchange="setCostshareMoney('+ thisCostType +');"/>' +
					'</span>';
			}

			//如果是补贴，则禁用发票部分
			if(chkIsSubsidy == "1"){
				str +='</td>' +
					'<td valign="top" colspan="4" class="innerTd">' +
					'<table class="form_in_table" id="table_'+ thisCostType +'">' +
					'<tr id="tr_' + thisI + '">' +
					'<td width="30%">' +
					'<input type="text" id="select_' + thisI + '" style="width:90px" class="readOnlyTxtShort" readonly="readonly" value="'+ chkInvoiceTypeName +'"/>' +
					'<input type="hidden" name="expense[expensedetail]['+ thisCostType +'][expenseinv][0][BillTypeID]" value="'+ chkInvoiceType +'"/>' +
					'</td>' +
					'<td width="25%">' +
					'<input  type="text" id="invoiceMoney_' + thisI + '" name="expense[expensedetail]['+ thisCostType +'][expenseinv][0][Amount]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" style="color:gray" title="补贴类发票金额不计入到单据发票金额中,只用于打单显示" class="txtshort formatMoney" onblur="invMoneySet(\''+ thisI +'\');countAll();"/>' +
					'</td>' +
					'<td width="25%">' +
					'<input type="text" id="invoiceNumber_' + thisI + '" name="expense[expensedetail]['+ thisCostType +'][expenseinv][0][invoiceNumber]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="readOnlyTxtShort" style="color:gray" readonly="readonly"/>' +
					'<input type="hidden" id="invIsSubsidy_' + thisI + '" name="expense[expensedetail]['+ thisCostType +'][expenseinv][0][isSubsidy]" value="1"/>' +
					'</td>' +
					'<td width="20%">' +
					'<img style="cursor:pointer;" src="images/add_item.png" title="添加行" onclick="add_lnvoice('+ thisCostType +')"/>' +
					'</td>' +
					'</tr>' +
					'</table>' +
					'</td>' +
					'<td valign="top">' +
	            	'<input name="expense[expensedetail]['+ thisCostType +'][specialApplyNo]" id="specialApplyNo' + thisCostType + '" class="txtshort" title="引入特别申请" onclick="showSpecialApply(' + thisCostType + ')" readonly="readonly"/>' +
					'</td>' +
					'<td valign="top">' +
	            	'<textarea name="expense[expensedetail]['+ thisCostType +'][Remark]" id="remark' + thisCostType + '" class="txt"></textarea>' +
					'</td>' +
					'</tr>';
			}else{
				str +='</td>' +
					'<td valign="top" colspan="4" class="innerTd">' +
					'<table class="form_in_table" id="table_'+ thisCostType +'">' +
					'<tr id="tr_' + thisI + '">' +
					'<td width="30%">' +
					'<select id="select_' + thisI + '" name="expense[expensedetail]['+ thisCostType +'][expenseinv][0][BillTypeID]"><option value="">请选择发票</option></select>' +
					'</td>' +
					'<td width="25%">' +
					'<input  type="text" id="invoiceMoney_' + thisI + '" name="expense[expensedetail]['+ thisCostType +'][expenseinv][0][Amount]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="txtshort formatMoney" onblur="invMoneySet(\''+ thisI +'\');countInvoiceMoney();countAll();"/>' +
					'</td>' +
					'<td width="25%">' +
					'<input type="text" id="invoiceNumber_' + thisI + '" name="expense[expensedetail]['+ thisCostType +'][expenseinv][0][invoiceNumber]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="txtshort" onblur="countInvoiceNumber(this);"/>' +
					'<input type="hidden" id="invIsSubsidy_' + thisI + '" name="expense[expensedetail]['+ thisCostType +'][expenseinv][0][isSubsidy]" value="0"/>' +
					'</td>' +
					'<td width="20%">' +
					'<img style="cursor:pointer;" src="images/add_item.png" title="添加行" onclick="add_lnvoice('+ thisCostType +')"/>' +
					'</td>' +
					'</tr>' +
					'</table>' +
					'</td>' +
					'<td valign="top">' +
	            	'<input name="expense[expensedetail]['+ thisCostType +'][specialApplyNo]" id="specialApplyNo' + thisCostType + '" class="txtshort" title="引入特别申请" onclick="showSpecialApply(' + thisCostType + ')" readonly="readonly"/>' +
					'</td>' +
					'<td valign="top">' +
	            	'<textarea name="expense[expensedetail]['+ thisCostType +'][Remark]" id="remark' + thisCostType + '" class="txt"></textarea>' +
					'</td>' +
					'</tr>';
			}
			//载入行
			invbodyObj.append(str);
			//分摊信息处理
			//缓存表格
			var costshareObj = $("#costshare");
			//样式渲染
			var tr_class = costshareObj.children().length % 2 == 0 ? 'tr_odd' : 'tr_even';

			var thisI = thisCostType + "_0";
			//初始化所属板块
			var deptId = $("#costBelongDeptId").val();
			var module = "";
			if(deptId != undefined && deptId != ""){
			    $.ajax({
			        type: 'POST',
			        url: '?model=deptuser_dept_dept&action=getDept',
			        data: {
			            'deptId': deptId
			        },
			        async: false,
                    dataType: 'json',
			        success: function (data) {
			        	module = data.module;
			        }
			    });
			}
			moduleStr = rtModuleStr(moduleArr,module);

			// ----- 根据当前费用归属部门是否满足可编辑板块的条件来显示对应编辑框 ----- //
			// 费用归属部门
			var costBelongDeptId = $("#costBelongDeptId").val();
			// ------- 分摊板块可否修改权限控制 ------- //
			chkCanChangeModule(costBelongDeptId);
			// ------- ./分摊板块可否修改权限控制 ------- //
			var hideSelect = (canChangeModule)? '' : 'display:none';
			var hideInput = (canChangeModule)? 'display:none' : '';
			if(currentModule == ''){currentModule = ($("#moduleVal").val() == undefined)? '' : $("#moduleVal").val();}
			var currentModuleName = (currentModule == '' || currentModule == undefined)? '' : getDataByCode(currentModule);

			var moduleBoxStr =
				'<select id="selectCostshare_' + thisI + '" name="expense[expensecostshare]['+ thisCostType +'][expenseinv][0][module]" style="width:90px; '+hideSelect+'"><option value="">请选择板块</option>' + moduleStr + '</select>' +
				'<input id="inputCostshare_' + thisI + '" type="text" value="'+currentModuleName+'"  style="width:90px;text-align:center;background-color: #EEEEEE;border: 1px solid #C0C2CF;'+hideInput+'" readonly/>';
			// ----- ./根据当前费用归属部门是否满足可编辑板块的条件来显示对应编辑框 ----- //

			var str = '<tr class="'+tr_class+'" id="trCostshare' + thisCostType + '">' +
				'<td valign="top">' +
				'<img style="cursor:pointer;" src="images/removeline.png" title="删除费用" onclick="deleteCostType('+ thisCostType +')"/>' +
				'</td>' +
				'<td valign="top" class="form_text_right">' +
				chkParentName +
				'<input type="hidden" name="expense[expensecostshare]['+ thisCostType +'][MainType]" value="'+ chkParentName +'"/>' +
				'<input type="hidden" name="expense[expensecostshare]['+ thisCostType +'][MainTypeId]" value="'+ chkParentId +'"/>' +
				'</td>' +
				'<td valign="top" class="form_text_right">' +
				chkName +
				'<input type="hidden" name="expense[expensecostshare]['+ thisCostType +'][CostType]" id="costTypeCostshare'+ thisCostType +'" value="'+ chkName + '"/>' +
				'<input type="hidden" name="expense[expensecostshare]['+ thisCostType +'][CostTypeID]" id="costTypeIdCostshare'+ thisCostType +'" value="' + thisCostType + '"/>' +
				'</td>' +
				'<td valign="top" colspan="3" class="innerTd">' +
				'<table class="form_in_table" id="tableCostshare_'+ thisCostType +'">' +
				'<tr id="trCostshare_' + thisI + '">' +
				'<td width="49.5%"><input type="text" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" name="expense[expensecostshare]['+ thisCostType +'][expenseinv][0][CostMoney]" id="costMoneyCostshare_'+ thisI +'" style="width:146px" class="txtmiddle formatMoney" onblur="countAllCostshare();"/></td>' +
				'<td width="32.5%">' +
				moduleBoxStr+
				'</td>' +
				'<td width="18%">' +
				'<img style="cursor:pointer;" src="images/add_item.png" title="添加行" onclick="addModule('+ thisCostType +')"/>' +
				'</td>' +
				'</tr>' +
				'</table>' +
				'</td>' +
				'<td valign="top">' +
	        	'<textarea name="expense[expensecostshare]['+ thisCostType +'][Remark]" id="remarkCostshare' + thisCostType + '" class="txtlong"></textarea>' +
				'</td>' +
				'</tr>';
			//载入行
			costshareObj.append(str);
			
			formateMoney();
			//初始化发票类型TODO
			if(chkIsSubsidy == "0"){
				billTypeStr = rtBillTypeStr(billTypeArr,thisCostType);
				$("#select_" + thisI).append(billTypeStr);
			}
		}
	}
	countAll();
	//明细计算
    countInvoiceNumber();
	countInvoiceMoney();
	countAllCostshare();
}

//删除费用行
function deleteRow(thisCostType){
	//删除费用信息
	var trObj = $("#tr" + thisCostType);//行对象
	trObj.hide();
	trObj.append('<input type="hidden" name="expense[expensedetail]['+
			thisCostType +'][isDelTag]" id="isDelTag_'+ thisCostType +'" value="1"/>');
	
	//删除分摊信息
	var trObj = $("#trCostshare" + thisCostType);//行对象
	trObj.hide();
	trObj.append('<input type="hidden" name="expense[expensecostshare]['+
			thisCostType +'][isDelTag]" id="isDelTag_'+ thisCostType +'" value="1"/>');
}

//反删除费用行
function unDeleteRow(thisCostType){
	//费用信息
	var trObj = $("#tr" + thisCostType);//行对象
	trObj.show();
	$("#feeTbl #isDelTag_" + thisCostType).remove();
	
	//分摊信息
	var trObj = $("#trCostshare" + thisCostType);//行对象
	trObj.show();
	$("#costshare #isDelTag_" + thisCostType).remove();
}

//天数验证
function daysCheck(obj){
	var days = $("#days").val();
	if(strTrim(obj.value) == "" || isNaN(obj.value)){
		alert('天数输入值错误');
		obj.value = days;
		return false;
	}

	if(days*1 < obj.value*1){
		alert('报销明细中的天数不能大于费用期间的天数!');
		obj.value = days;
		return false;
	}
}

//删除费用
function deleteCostType(costType){
	if(confirm('确认删除该项费用吗')){
		//取消选择
		var chkObj = $("#chk" + costType);
		if(chkObj.length == 1){
			chkObj.attr('checked',false);
		}
		//删除
		deleteRow(costType);

		//重新计算金额的节奏
		countAll();
	    countInvoiceNumber();
		countInvoiceMoney();
		countAllCostshare();
	}
}

//重新加载自定义费用选中项
function resetCustomCostType(){
	//自定义费用域
	var costTypeInnerObj = $("#costTypeInner");
	if(costTypeInnerObj.html() != ""){
		//先取消选择
		$("#costTypeInner input[id^='chk']").attr('checked',false);
		//获取当前有的费用类型
		var costTypeArr = $("#invbody input[id^='costTypeId']");
		//当前存在费用类型数组
		var costTypeIdArr = [];
		//当前存在费用类型字符串
		var costTypeIds = '';

		if(costTypeArr.length > 0){
			//缓存当前存在费用类型
			costTypeArr.each(function(i,n){
				//判断是否已删除
				if($("#isDelTag_" + this.value).length == 0){
					costTypeIdArr.push(this.value);
					$("#chk" + this.value).attr('checked',true);
				}
			});
			//构建当前存在费用类型id串
			costTypeIds = costTypeIdArr.toString();
		}

		//费用类型隐藏赋值
		$("#costTypeIds").val(costTypeIds);
	}
}

//获取附件文档
function getFile(){
	if($("#fileId").attr("href") == '#'){
		alert('没有上传报销说明文档');
		return false;
	}
}

//打开保存界面
function openSavePage(){
	//获取当前有的费用类型
	var costTypeArr = $("#invbody input[id^='costTypeId']");
	var contentIdArr = [];
	//缓存当前存在费用类型
	costTypeArr.each(function(i,n){
		//判断是否已删除
		if($("#isDelTag_" + this.value).length == 0){
			contentIdArr.push(this.value);
		}
	});
	if(contentIdArr.length == "0"){
		alert('请至少选择一项费用类型');
	}else{
		$("#templateName").val($("#modelTypeName").val());
		$('#templateInfo').dialog({
		    title: '保存模板',
		    width: 400,
		    height: 200,
   			modal: false,
   			closable : true
		}).dialog('open');
	}
}

//保存模板
function saveTemplate(){
	//获取当前有的费用类型
	var costTypeArr = $("#invbody input[id^='costTypeId']");
	var contentArr = [];
	var contentIdArr = [];
	//缓存当前存在费用类型
	costTypeArr.each(function(i,n){
		//判断是否已删除
		if($("#isDelTag_" + this.value).length == 0){
			contentArr.push($("#costType" + this.value).val());
			contentIdArr.push(this.value);
		}
	});

	if(contentIdArr.length == 0){
		alert('没有任务选中值，请至少选择一项费用类型');
	}else{
		var templateName= $("#templateName").val();
		var content = contentArr.toString();
		var contentId = contentIdArr.toString();
	    if(templateName){
			$.ajax({
			    type: "POST",
			    url: "?model=finance_expense_customtemplate&action=ajaxSave",
			    data : {"templateName" : templateName , "content" : content , "contentId" : contentId },
			    async: false,
			    success: function(data){
			   		if(data != ""){
			   			alert('模板保存成功');
						$("#modelTypeName").val(templateName).yxcombogrid_expensemodel('reload');
						$("#modelType").val(data);
						$('#templateInfo').dialog('close');
			   	    }else{
						alert('模板保存失败');
						$('#templateInfo').dialog('close');
			   	    }
				}
			});
	    }else{
	    	if(strTrim(templateName) == ""){
				alert('请输入报销模板名称');
	    	}
	    }
	}
}

//根据用户id获取用户信息
function getUserInfo(userId){
	var dataArr;
	$.ajax({
	    type: "POST",
	    url: "?model=deptuser_user_user&action=ajaxGetUserInfo",
	    data : {"userId" : userId },
	    async: false,
	    success: function(data){
	   		if(data != ""){
				dataArr = eval("(" + data + ")");
	   	    }else{
				alert('没有找到对应的用户');
	   	    }
		}
	});
	return dataArr;
}

//判断对象的combobox是否已存在
function isCombobox(objCode){
	if($("#" + objCode).attr("comboname")){
		return 1;
	}else{
		return 0;
	}
}

//初始化提交按钮
function initSubButton(){
	if($("#needExpenseCheck").val() == "1"){
		$("#btnCheck").show();
	}else{
		$("#btnAudit").show();
	}
}

//数组构建
function deptArrBuild(deptId1,deptName1,deptId2,deptName2){
	var deptArr = [];
	deptArr.push({"text" : deptName1,"value" : deptId1 });
	if(deptId2 != undefined && deptId2 != '' && deptId1 != deptId2){
		deptArr.push({"text" : deptName2,"value" : deptId2 });
	}
	return deptArr;
}

//数组构建 - 多选
function deptArrBuildMul(deptArr,deptId,deptName){
	var newDeptArr = [{"text" : deptName,"value" : deptId }];
	var tempArr = [];
	tempArr.push(deptId);

	//如果原数组长度大于0，判断重复以及插入部门
	if(deptArr.length > 0){
		for(var i = 0 ; i < deptArr.length ; i++ ){
			//如果没有过缓存，则载入
			if(tempArr.indexOf(deptArr[i].deptId) == -1){
				//载入返回数组
				newDeptArr.push({"text" : deptArr[i].deptName,"value" : deptArr[i].deptId });
				//载入缓存
				tempArr.push(deptArr[i].deptId);
			}
		}
	}
	return newDeptArr;
}

//AJAX打回单据
function ajaxBack(){
	if(confirm('确认打回单据吗？')){
		$.ajax({
			type : "POST",
			url : "?model=finance_expense_expense&action=ajaxBack",
			data : {
				id : $("#id").val()
			},
			success : function(msg) {
				if (msg == '1') {
					alert('打回成功！');
					window.close();
					opener.show_page(1);
				}else{
					alert("打回失败! ");
				}
			}
		});
	}
}

//选择
function checkSpecialApply(){
	//当前费用类型
	var costType = $("#hiddenCostType").val();
	//选中单据
	var formNoArr = $("input[name='spa']:checked");
	var formNoValArr = [];
	formNoArr.each(function(){
		formNoValArr.push(this.value);
	});
	$("#specialApplyNo" + costType).val(formNoValArr.toString());
	$("#specialApplyWindow").window('close');
}

//取消选择
function cancelSpecialApply(){
	var costType = $("#hiddenCostType").val();
	$("#specialApplyNo" + costType).val('');
	$("#specialApplyWindow").window('close');
}

//重载选项
function reloadCheckbox(costType){
	var specialApplyNoVal = $("#specialApplyNo" + costType).val();
	var formNoValArr = specialApplyNoVal.split(",");
	$("input[name='spa']").each(function(){
		if($.inArray(this.value,formNoValArr) != -1){
			$(this).attr('checked',true);
		}else{
			$(this).attr('checked',false);
		}
	});
	$("#hiddenCostType").val(costType);
}
//获取特别事项申请
function showSpecialApply(costType){
	//事由
	var DetailType = $("input:radio[name='expense[DetailType]']:radio:checked").val();
	if(strTrim(DetailType) == "undefined"){
		alert('请先选择一种费用类型！');
		return false;
	}
	var CostMan = $("#CostMan").val();//报销人
	var winObj = $("#specialApplyWindow");
    if(CostMan != ""){
    	$(".costTypeSpeCls").hide();//隐藏全部的特别事项申请
    	//窗口打开
		winObj.window({
			title : '引入特别事项申请',
			height : 400,
			width : 800
		});
		//特别事项申请的对象
		var costSpeWin = $("#costTypeSpeTb" + costType);
		//特别事项申请绑定
    	// if(costSpeWin.length == 0){
    		winObj.html('');
    		winObj.append("<div id='costTypeSpeTb"+ costType +"' class='costTypeSpeCls'></div>");
    		costSpeWin = $("#costTypeSpeTb" + costType);
    		costSpeWin.yxeditgrid({
				url : '?model=general_special_specialapply&action=listJsonForSelect',
				param : {
					'charger' : CostMan ,
					'isCanUse' : 1 ,
					'nSpecialApplyNos' : $("#specialApplyNos").val(),
					'ExaStatus' : '完成',
					'needValidDate' : '1',
					'sdetailType' : DetailType,
					'dcostTypeId' : $("#costTypeId" + costType).val()
				},
				tableClass : 'form_in_table',
				height : 400,
				isAddAndDel : false,
				title : ' ',
				colModel : [{
					display : '',
					name : 'id',
					type : 'statictext',
					width : 30,
					process : function(v,row){
						return "<input type='radio' name='spa' value='"+row.formNo+"'/>";
					}
				}, {
					display : '费用类型',
					name : 'detailType',
					type : 'statictext',
					process : function(v){
						switch(v){
							case '1' : return '部门费用';break;
							case '2' : return '合同项目费用';break;
							case '3' : return '研发费用';break;
							case '4' : return '售前费用';break;
							case '5' : return '售后费用';break;
						}
					},
					width : 70
				}, {
					display : '申请单号',
					name : 'formNo',
					type : 'statictext'
				}, {
					display : '申请日期',
					name : 'applyDate',
					type : 'statictext',
					width : 70
				}, {
					display : '有效截止日期',
					name : 'validDate',
					type : 'statictext',
					width : 70
				}, {
					display : '适用人员',
					name : 'useRangeName',
					type : 'statictext'
				}, {
					display : '费用类型',
					name : 'costType',
					type : 'statictext',
					align : 'left'
				}, {
					display : '申请原因',
					name : 'applyReson',
					type : 'statictext',
					align : 'left'
				}],
				event : {
					'reloadData' : function(){
						costSpeWin.attr("style", "overflow-x:auto;overflow-y:auto;height:350px;");
						costSpeWin.find('thead tr').eq(0).children().eq(0).append(
							'<button class="txt_btn_a" onclick="checkSpecialApply();">确认选择</button> '+
							'<button class="txt_btn_a" onclick="cancelSpecialApply();">取消选择</button>'+
							'<input type="hidden" id="hiddenCostType"/>'
						);
						//重载选项
						reloadCheckbox(costType);
					}
				}
    		});
    	// }else{
    	// 	//显示选择框
    	// 	costSpeWin.show();
    	// 	//重载选项
    	// 	reloadCheckbox(costType);
    	// }
    }else{
		alert('请选择报销人员');
    }
}

/* 设置所属板块
 * @param deptId 
 * 1.通常不传这个值，默认都是取费用归属部门id
 * 2.如果有传值，则用传的值
 */
function setModule(){
	$('.txtshort').attr('value','');
    // 部门费用的时候再进行处理
    if ($("input[name='expense[DetailType]']").val() == "1") {
        // 费用归属部门
        var costBelongDeptId = $("#costBelongDeptId").val();
		// ------- 分摊板块可否修改权限控制 ------- //
		chkCanChangeModule(costBelongDeptId);
		// ------- ./分摊板块可否修改权限控制 ------- //

        // 如果是费用归属部门是综合管理，那么按报销人所属部门来匹配板块信息
        $.ajax({
            type: 'POST',
            url: '?model=deptuser_dept_dept&action=getDept',
            data: {
                'deptId': costBelongDeptId
            },
            async: false,
            dataType: 'json',
            success: function (data) {
                // 板块所属部门
                var CostDepartID = $("#CostDepartID").val();

                if (data.comCode == 'zh' && CostDepartID != costBelongDeptId) {
                    $.ajax({
                        type: 'POST',
                        url: '?model=deptuser_dept_dept&action=getDept',
                        data: {
                            'deptId': CostDepartID
                        },
                        async: false,
                        dataType: 'json',
                        success: function (data) {
							currentModule = data.module;
							var moduleName = (data.module == undefined || data.module == '')? '' : getDataByCode(data.module);
                            $("#costsharebody select[id^='selectCostshare_'][id$='0']").val(data.module);
							$("#costsharebody input[id^='inputCostshare_']").val(moduleName);
                        }
                    });
                } else {
					currentModule = data.module;
					var moduleName = (data.module == undefined || data.module == '')? '' : getDataByCode(data.module);
                    $("#costsharebody select[id^='selectCostshare_'][id$='0']").val(data.module);
					$("#costsharebody input[id^='inputCostshare_']").val(moduleName);
                }
            }
        });
    } else {
		currentModule = '';
        $("#costsharebody select[id^='selectCostshare_'][id$='0']").val("");
		$("#costsharebody input[id^='inputCostshare_']").val("");
    }

    // 根据当前费用归属部门是否满足可编辑板块条件显示和隐藏对应的编辑框
	$.each($("#costsharebody select[id^='selectCostshare_']"),function(i,item){
		$(item).val(currentModule);
		if(canChangeModule){
			$(item).show();
		}else{
			$(item).hide();
		}
	});
	$.each($("#costsharebody input[id^='inputCostshare_']"),function(i,item){
		if(canChangeModule){
			$(item).hide();
		}else{
			$(item).show();
		}
	});
}

function chkCanChangeModule(deptId){
	var responseText = $.ajax({
		url : 'index1.php?model=system_configurator_configurator&action=checkDeptInConfig',
		data: {'deptId':deptId},
		type : "POST",
		async : false
	}).responseText;
	if(responseText == '1'){
		canChangeModule = true;
	}else{
		canChangeModule = false;
	}
}

//填写费用金额时自动带出分摊金额
function setCostshareMoney(thisNum){
	//费用金额
	var costMoney = $("#costMoney" + thisNum).val();
	if(costMoney != ""){
		isSubsidy = $("#isSubsidy" + thisNum).val();//isSubsidy为1,则需要乘以天数
		if(isSubsidy == '1'){
			var days = strTrim($("#days" + thisNum).val());
			if(days != ""){
				costMoney = accMul(costMoney,days);
				setMoney("costMoneyCostshare_" + thisNum + "_0",costMoney);
			}
		}else{
			setMoney("costMoneyCostshare_" + thisNum + "_0",costMoney);
		}
	}
	//计算分摊合计
	countAllCostshare();
}