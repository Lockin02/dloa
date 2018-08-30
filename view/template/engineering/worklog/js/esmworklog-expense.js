//初始化费用
function initFee(){
	var invbodyObj = $("#invbody");
	//如果没有实例化过费用，则加载模板
	if(invbodyObj.html() == ""){
		$.ajax({
		    type: "POST",
		    url: "?model=finance_expense_customtemplate&action=initTemplate",
		    data : {"isEsm" : 1},
		    async: false,
		    success: function(data){
		   		if(data != ""){
					var dataObj = eval("(" + data + ")");
					$("#templateName").val(dataObj.templateName);
					$("#templateId").val(dataObj.id);
					invbodyObj.html(dataObj.templateStr);
					formateMoney();
		   	    }else{
					alert('没有查询到报销模板');
		   	    }
			}
		});

		//实例化下拉选择
		initSelect();
	}else{
		invbodyObj.empty();
	}
}

//实例化下拉选择
function initSelect(){
	//模板选择渲染
	$("#templateName").yxcombogrid_expensemodel('remove').yxcombogrid_expensemodel({
		hiddenId :  'templateId',
		isFocusoutCheck : false,
		height : 300,
		isShowButton : false,
		isClear : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data){
					initTemplate(data.modelType);
				}
			}
		}
	});
}

//表单类型数组
var billTypeArr = [];
var billTypeStr;

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

//设置选择字符串 TODO 可优化
function rtBillTypeStr(data, costTypeId) {
	var defaultVal = $("#defaultInvoice" + costTypeId).val();
	var isReplace = $("#isReplace"+ costTypeId).val();
	if(isReplace == 1){
        var title =  '此费用允许替票';
	}else{
        var title =  '此费用不允许替票';
	}
	var str ;
	for (var i = 0, l = data.length; i < l; i++) {
		if(defaultVal == data[i].id){
    		str +='<option value="'+ data[i].id +'" selected="selected" title="'+title+'">'+ data[i].name+'</option>';
        }else{
        	if(isReplace == '1'){
           		str +='<option value="'+ data[i].id +'" title="'+title+'">'+ data[i].name+'</option>';
        	}
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
			setMoney("invoiceMoney_"+ thisNum + "_0",0);

			//初始化一个发票数量
			$("#invoiceNumber_"+ thisNum + "_0").val(0);
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
	detailAll = 0;
	$("select[id^='select_" + id + "_']").each(function(i,n){
		delTag = $("#isDelTag_"+ id + "_" + i).length;
		if(delTag == 0){
			detailMoney = $("#invoiceMoney_"+ id + "_" + i).val();
			detailAll = accAdd(detailAll,detailMoney,2);
		}
	});
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
			'<td width="30%"><select id="select_' + countStr + '" name="esmworklog[esmcostdetail]['+ id + '][invoiceDetail][' + tableTrLength + '][invoiceTypeId]" style="width:90px"><option value="">请选择发票</option>' + billTypeStr +'</select></td>' +
			'<td width="25%"><input id="invoiceMoney_' + countStr + '" costTypeId="'+ id +'" rowCount="'+ countStr +'" name="esmworklog[esmcostdetail]['+ id + '][invoiceDetail][' + tableTrLength + '][invoiceMoney]" type="text" class="txtshort" value="'+lastMoney+'" onblur="invMoneySet(\''+ countStr +'\');countInvoiceMoney();"/></td>' +
			'<td width="25%"><input id="invoiceNumber_' + countStr + '" costTypeId="'+ id +'" rowCount="'+ countStr +'"  name="esmworklog[esmcostdetail]['+ id + '][invoiceDetail][' + tableTrLength + '][invoiceNumber]" type="text" class="txtshort" value="'+ invoiceNumber +'" onblur="countInvoiceNumber(this);"/></td>' +
            '<td width="20%"><img style="cursor:pointer;" src="images/removeline.png" title="删除本行发票" onclick="delete_lnvoice(' + id + ',this)"/></td>' +
		'</tr>';
	tableObj.append(str);
	//格式化金额
    createFormatOnClick('invoiceMoney_'+countStr);

    //备注高度调整
    var remarkObj = $("#remark" + id);
    remarkObj.animate({height:"+=33"},100);

	//明细计算
    countInvoiceMoney();
    countInvoiceNumber();
}

//删除发票信息
function delete_lnvoice(id,obj){
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="esmworklog[esmcostdetail]['+
				id +'][invoiceDetail][' +
				rowNo + '][isDelTag]" id="isDelTag_'+ id +'_'+rowNo +'" value="1"/>');

	    //备注高度调整
	    var remarkObj = $("#remark" + id);
	    remarkObj.animate({height:"-=33"},100);


		//明细计算
	    countInvoiceNumber();
		countInvoiceMoney();
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
	var costTypeId , costMoney , countAll , days ,thisCostMoney , isSubsidy;
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
				//计算表单的补贴费用
				feeSubsidy = accAdd(feeSubsidy,thisCostMoney,2);
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
	var countAll , rowCount , mark ,costTypeId , isCount;
	$("#invbody input[id^='invoiceMoney_']").each(function(i,n){
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

		rowCount = $(this).attr('rowCount');
		//判断是否有值
		if(this.value != "" && rowCount && isCount){
			if($("#isDelTag_" + rowCount ).length == 0){
				countAll = accAdd(countAll,this.value);
			}
		}
	});
	if(countAll*1 == 0 || !countAll){
		countAll = "";
	}
	setMoney('invoiceMoney', countAll );
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
	var countAll , rowCount , mark ,costTypeId , isCount;
	$("#invbody input[id^='invoiceNumber_']").each(function(i,n){
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

		//判断是否有值 并且当前值计入统计中
		if(this.value != "" && isCount ){
			rowCount = $(this).attr('rowCount');
			if($("#isDelTag_" + rowCount ).length == 0){
				countAll = accAdd(countAll,this.value);
			}
		}
	});
	if(countAll*1 == 0 || !countAll){
		countAll = "";
	}
	$("#invoiceNumber").val(countAll);
}

//提交审批改变隐藏值
function audit(thisType){
	$("#thisAuditType").val(thisType);
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

	//第一次加载
	var isFirst = false;

	if($("#costTypeInner").html() == ""){
		isFirst = true;
		$.ajax({
		    type: "POST",
		    url: "?model=finance_expense_costtype&action=getCostType",
		    data : {"isEsm" : 1},
		    async: false,
		    success: function(data){
		   		if(data != ""){
					$("#costTypeInner").html("<div id='costTypeInner2'>" + data + "</div>")
					if(costTypeIds != ""){
						//设值
						for(var i = 0; i < costTypeIdArr.length;i++){
							$("#view" + costTypeIdArr[i]).attr('class','blue');

							var chkObj = $("#chk" + costTypeIdArr[i]);
							chkObj.attr('checked',true);
							if($("#remark" + costTypeIdArr[i]).length == 0){
								chkObj.attr('disabled',true);
							}
						}
					}
					//延时调用排序方法
					setTimeout(function(){
						initMasonry();
					},200);
		   	    }else{
					alert('没有找到自定义的费用类型');
		   	    }
			}
		});
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
			//缓存表格
			var invbodyObj = $("#invbody");
			//样式渲染
			var tr_class = $("#invbody").children().length % 2 == 0 ? 'tr_odd' : 'tr_even';

			var thisI = thisCostType + "_0";
			var str = '<tr class="'+tr_class+'" id="tr' + thisCostType + '">' +
				'<td valign="top">' +
				'<img style="cursor:pointer;" src="images/removeline.png" title="删除费用" onclick="deleteCostType('+ thisCostType +')"/>' +
				'</td>' +
				'<td valign="top" class="form_text_right">' +
				chkParentName +
				'<input type="hidden" name="esmworklog[esmcostdetail]['+ thisCostType +'][parentCostType]" value="'+ chkParentName +'"/>' +
				'<input type="hidden" name="esmworklog[esmcostdetail]['+ thisCostType +'][parentCostTypeId]" value="'+ chkParentId +'"/>' +
				'<input type="hidden" id="showDays'+ thisCostType +'" value="'+ chkShowDays +'"/>' +
				'<input type="hidden" id="defaultInvoice'+ thisCostType +'" value="'+ chkInvoiceType +'"/>' +
				'<input type="hidden" id="defaultInvoiceName'+ thisCostType +'" value="'+ chkInvoiceTypeName +'"/>' +
				'<input type="hidden" id="isReplace'+ thisCostType +'" value="'+ chkIsPeplace +'"/>' +
				'<input type="hidden" id="isEqu'+ thisCostType +'" value="'+ chkIsEqu +'"/>' +
				'<input type="hidden" id="isSubsidy'+ thisCostType +'" value="'+ chkIsSubsidy +'"/>' +
				'</td>' +
				'<td valign="top" class="form_text_right">' +
				chkName +
				'<input type="hidden" name="esmworklog[esmcostdetail]['+ thisCostType +'][costType]" id="costType'+ thisCostType +'" value="'+ chkName + '"/>' +
				'<input type="hidden" name="esmworklog[esmcostdetail]['+ thisCostType +'][costTypeId]" id="costTypeId'+ thisCostType +'" value="' + thisCostType + '"/>' +
				'</td>' +
				'<td valign="top" class="form_text_right">';
			if(chkShowDays == 0){
				str +=
					'<input type="text" name="esmworklog[esmcostdetail]['+ thisCostType +'][costMoney]" id="costMoney'+ thisCostType +'" style="width:146px" class="txtmiddle formatMoney" onblur="detailSet('+ thisCostType +');countAll();"/>' +
					'<input type="hidden" name="esmworklog[esmcostdetail]['+ thisCostType +'][days]" id="days'+ thisCostType +'" value="1"/>';
			}else{
				str +=
					'<span>' +
					'<input type="text" name="esmworklog[esmcostdetail]['+ thisCostType +'][costMoney]" id="costMoney'+ thisCostType +'" style="width:60px" class="txtshort formatMoney" onblur="detailSet('+ thisCostType +');countAll();"/>' +
					' X ' +
					' 天数 ' +
					'<input type="text" name="esmworklog[esmcostdetail]['+ thisCostType +'][days]" class="readOnlyTxtMin" id="days'+ thisCostType +'" value="1" readonly="readonly"/>' +
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
					'<input type="hidden" name="esmworklog[esmcostdetail]['+ thisCostType +'][invoiceDetail][0][invoiceTypeId]" value="'+ chkInvoiceType +'"/>' +
					'</td>' +
					'<td width="25%">' +
					'<input  type="text" id="invoiceMoney_' + thisI + '" name="esmworklog[esmcostdetail]['+ thisCostType +'][invoiceDetail][0][invoiceMoney]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="readOnlyTxtShort formatMoney" readonly="readonly"/>' +
					'</td>' +
					'<td width="25%">' +
					'<input type="text" id="invoiceNumber_' + thisI + '" name="esmworklog[esmcostdetail]['+ thisCostType +'][invoiceDetail][0][invoiceNumber]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="readOnlyTxtShort" readonly="readonly"/>' +
					'</td>' +
					'<td width="20%">' +
					'<img style="cursor:pointer;" src="images/add_item.png" title="该类型不需要录入发票，不能进行新增操作" onclick="alert(\'该类型不需要录入发票，不能进行新增操\');"/>' +
					'</td>' +
					'</tr>' +
					'</table>' +
					'</td>' +
					'<td valign="top">' +
	            	'<textarea name="esmworklog[esmcostdetail]['+ thisCostType +'][remark]" id="remark' + thisCostType + '" class="txtlong"></textarea>' +
					'</td>' +
					'</tr>';
			}else{
				str +='</td>' +
					'<td valign="top" colspan="4" class="innerTd">' +
					'<table class="form_in_table" id="table_'+ thisCostType +'">' +
					'<tr id="tr_' + thisI + '">' +
					'<td width="30%">' +
					'<select id="select_' + thisI + '" name="esmworklog[esmcostdetail]['+ thisCostType +'][invoiceDetail][0][invoiceTypeId]"><option value="">请选择发票</option></select>' +
					'</td>' +
					'<td width="25%">' +
					'<input  type="text" id="invoiceMoney_' + thisI + '" name="esmworklog[esmcostdetail]['+ thisCostType +'][invoiceDetail][0][invoiceMoney]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="txtshort formatMoney" onblur="invMoneySet(\''+ thisI +'\');countInvoiceMoney()"/>' +
					'</td>' +
					'<td width="25%">' +
					'<input type="text" id="invoiceNumber_' + thisI + '" name="esmworklog[esmcostdetail]['+ thisCostType +'][invoiceDetail][0][invoiceNumber]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="txtshort" onblur="countInvoiceNumber(this)"/>' +
					'</td>' +
					'<td width="20%">' +
					'<img style="cursor:pointer;" src="images/add_item.png" title="添加行" onclick="add_lnvoice('+ thisCostType +')"/>' +
					'</td>' +
					'</tr>' +
					'</table>' +
					'</td>' +
					'<td valign="top">' +
	            	'<textarea name="esmworklog[esmcostdetail]['+ thisCostType +'][remark]" id="remark' + thisCostType + '" class="txtlong"></textarea>' +
					'</td>' +
					'</tr>';
			}
			//载入行
			invbodyObj.append(str);
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
}

//删除费用行
function deleteRow(thisCostType){
	//行对象
	var trObj = $("#tr" + thisCostType);
	trObj.hide();
	trObj.append('<input type="hidden" name="esmworklog[esmcostdetail]['+
			thisCostType +'][isDelTag]" id="isDelTag_'+ thisCostType +'" value="1"/>');
}

//反删除费用行
function unDeleteRow(thisCostType){
	//行对象
	var trObj = $("#tr" + thisCostType);
	trObj.show();
	$("#isDelTag_" + thisCostType).remove();
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
		var　chkObj = $("#chk" + costType);
		if(chkObj.length == 1){
			chkObj.attr('checked',false);
		}
		//删除
		deleteRow(costType);

		//重新计算金额的节奏
		countAll();
	    countInvoiceNumber();
		countInvoiceMoney();
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
		var templateName = $("#templateName").val();
		var templateName= prompt("系统会将当前费用类型保存到模板中，请输入需要保存的模板名称",templateName);
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
						$("#templateName").val(templateName).yxcombogrid_expensemodel('reload');
						$("#templateId").val(data);
			   	    }else{
						alert('模板保存失败');
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

//初始渲染模板 - 新增时用
function initTemplate(modelType){
	//后台整合模板页面
	$.ajax({
	    type: "POST",
	    url: "?model=engineering_cost_esmcostdetail&action=initTempAdd",
	    data: {"modelType" : modelType},
	    async: false,
	    success: function(data){
			$("#invbody").html(data);
			// 金额 千分位处理
			formateMoney();
			resetCustomCostType();

			//这边要禁用天数
			$("input[id^='days']").each(function(i,n){
				if($(this).attr('class') == 'txtmin'){
					$(this).attr('class','readOnlyTxtMin').attr('readonly',true);
				}
			});
		}
	});
}

//表单验证
function checkForm(){
	var isTrue = true;
	var idLength = $("#id").length;
	var activityId = $("#activityId").val();
	var executionDate = $("#executionDate").val();
	//判断日志日期是否存在请休假记录
	if($('#workStatus').val() == 'GXRYZT-01' || $('#workStatus').val() == 'GXRYZT-02'){
		var isInHols = false;
		$.ajax({
			type : "POST",
			url : "?model=engineering_worklog_esmworklog&action=isInHols",
			data : {
				"executionDate" : executionDate
			},
			async: false,
			success : function(msg) {
				if(msg == '1'){
					alert("【" + executionDate + "】存在请休假记录，工作状态不能为工作或待命");
					isInHols = true;
				}
			}
		});
		if(isInHols){
			return false;
		}
	}
	//投入工作比例填写
	var inWorkRateObj = $("#inWorkRate");
	var maxInWorkRate = $("#maxInWorkRate").val();
	if(inWorkRateObj.val() *1 > maxInWorkRate*1){
		alert('投入工作比例【' + inWorkRateObj.val() + '】已超过最大可投入比例【' + maxInWorkRate + '】');
		return false;
	}

	//如果选择了项目，则必须要填写任务相关信息
	if($("#projectId").val() != "" && $("#activityId").val() == ""){
		alert('请选择一个任务');
		return false;
	}

	//如果填写了工作量或者工作量单位
	if((($("#workloadDay").val() != "") && $("#workloadUnit").val() == "") || (($("#workloadDay").val() == "") && $("#workloadUnit").val() != "")){
		alert('工作量和工作量单位必须同时填写');
		return false;
	}

	//判断日期是否周报是否可用
	if(idLength == 0){
		//任务判断 -- 如果任务当日已经填写过日志，不允许继续填写
		$.ajax({
			type : "POST",
			url : "?model=engineering_worklog_esmweeklog&action=checkLogCanWrite",
			data : {
				"executionDate" : executionDate
			},
		    async: false,
			success : function(msg) {
				if(msg == "0"){
					alert( executionDate + '对应的周报已进行确认，不能继续录入');
					isTrue = false;
				}
			}
		});
		if(isTrue == false){
			return false;
		}
	}

	if(activityId != '0' && activityId != ""){

		if(idLength == 0){
			//任务判断 -- 如果任务当日已经填写过日志，不允许继续填写
			$.ajax({
				type : "POST",
				url : "?model=engineering_worklog_esmworklog&action=checkActivityLog",
				data : {
					"executionDate" : executionDate,
					"activityId" : activityId
				},
			    async: false,
				success : function(msg) {
					if(msg != "0"){
						if(msg *1 == msg){
							if(confirm('此任务于'+ executionDate + '的日志已经填写，是否进入进行修改?')){
								isTrue = false;
								location = "?model=engineering_worklog_esmworklog&action=toEdit&id=" + msg;
							}else{
								isTrue = false;
							}
						}else{
							alert(msg);
							isTrue = false;
						}
					}
				}
			});
			if(isTrue == false){
				return false;
			}
		}

		if($("#workloadDay").val() == ""){
			alert('针对任务的日志，请录入对应的工作量');
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

			//如果不需要录入发票金额，则跳过验证
			if(isSubsidy == '0'){
				//金额缓存
				costMoney = $("#costMoney" + costTypeId).val();
				days = $("#days" + costTypeId).val();
				costMoney = accMul(costMoney,days,2);
				if(costMoney != 0){
					//名称缓存
					costType = $("#costType" + costTypeId).val();
					//重新初始化金额
					detailAll = 0;
					$("#table_"+ costTypeId +" select[id^='select_" + costTypeId + "_']").each(function(i,n){
						delTag = $("#isDelTag_"+ costTypeId + "_" + i).length;
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
			}
		}
	});

	//防止重复提交
	$("submit").attr('disable',true);

	return rtVal;
}