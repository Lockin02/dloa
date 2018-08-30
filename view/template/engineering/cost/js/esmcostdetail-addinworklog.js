//表单类型数组
var bliiTypeArr = [];
var billTypeStr;

$(document).ready(function() {
	bliiTypeArr = getBillType();

	//异常申请
	$("#excApplyCode").yxcombogrid_exceptionapply({
		hiddenId : 'excApplyId',
		height : 250,
		gridOptions : {
			showcheckbox : true,
			isTitle : true,
			param : {'ExaStatus' : '完成' , 'applyUserAndRange' : $("#createId").val()}
		}
	});

	//模板选择渲染
	$("#modelTypeName").yxcombogrid_expensemodel({
		hiddenId :  'modelType',
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data){
					initTemplate(data.modelType);
				}
			}
		}
	});

	//如果是编辑页面，则计算金额合计
	if($("#isEdit").length > 0){
		//计算
		countAll();
	    countInvoiceMoney();
	    countInvoiceNumber();
	}
})

//初始渲染模板 - 新增时用
function initTemplate(modelType){
	//后台整合模板页面
	$.ajax({
	    type: "POST",
	    url: "?model=finance_expense_expense&action=initTempAdd",
	    data: {"modelType" : modelType},
	    async: false,
	    success: function(data){
			$("#invbody").html(data);
			// 金额 千分位处理
			formateMoney();
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

//录入租车信息
function initCarRental(thisNum){
	var worklogId = $("#worklogId").val();
	var url = "?model=carrental_records_carrecordsdetail&action=toAddInWorklog"
		+ "&worklogId="
		+ worklogId
	;

	//为了解决GOOGLE 浏览器的BUG，所以要使用以下代码
	var prevReturnValue = window.returnValue; // Save the current returnValue
	window.returnValue = undefined;
	var dlgReturnValue = window.showModalDialog(url, '',"dialogWidth:950px;dialogHeight:500px;");
	if (dlgReturnValue == undefined) // We don't know here if undefined is the real result...
	{
	    // So we take no chance, in case this is the Google Chrome bug
	    dlgReturnValue = window.returnValue;
	}
	window.returnValue = prevReturnValue; // Restore the original returnValue

	//赋值
	if(dlgReturnValue){
		var MoneyArr = eval("(" + dlgReturnValue + ")");
//		$.showDump(MoneyArr);

		var detailTable;
		for (i in MoneyArr ){
//		    alert(MoneyArr[i]);
//			$("#costMoney" + i).val(MoneyArr[i]);
			setMoney('costMoney' + i,MoneyArr[i]);
			detailTable = $("select[id^='select_" + i + "_']");
			if(detailTable.length == 1){
				setMoney("invoiceMoney_" + i + "_0",MoneyArr[i]);
				$("#invoiceNumber_"+ i + "_0").val(1);
			}
		}

		//计算
		countAll();
	    countInvoiceMoney();
	    countInvoiceNumber();
//		$("#costMoney" + thisNum).val(dlgReturnValue);
	}
}

//录入测试卡信息
function initCardrecords(thisNum){
	var worklogId = $("#worklogId").val();
	var url = "?model=cardsys_cardrecords_cardrecords&action=toAddInWorklog"
		+ "&worklogId="
		+ worklogId
	;

	//为了解决GOOGLE 浏览器的BUG，所以要使用以下代码
	var prevReturnValue = window.returnValue; // Save the current returnValue
	window.returnValue = undefined;
	var dlgReturnValue = window.showModalDialog(url, '',"dialogWidth:950px;dialogHeight:500px;");
	if (dlgReturnValue == undefined) // We don't know here if undefined is the real result...
	{
	    // So we take no chance, in case this is the Google Chrome bug
	    dlgReturnValue = window.returnValue;
	}
	window.returnValue = prevReturnValue; // Restore the original returnValue

	//赋值
	if(dlgReturnValue){
//		$("#costMoney" + thisNum).val(dlgReturnValue);
		setMoney('costMoney' + thisNum,dlgReturnValue);
		var detailTable = $("select[id^='select_" + thisNum + "_']");
		if(detailTable.length == 1){
			setMoney("invoiceMoney_"+ thisNum + "_0",dlgReturnValue);
			$("#invoiceNumber_"+ thisNum + "_0").val(1);

			//计算
			countAll();
		    countInvoiceMoney();
		    countInvoiceNumber();
		}
	}
}

//录入临聘人员信息
function initTempPerson(thisNum){
	var worklogId = $("#worklogId").val();
	var url = "?model=engineering_tempperson_personrecords&action=toAddInWorklog"
		+ "&worklogId="
		+ worklogId
	;

	//为了解决GOOGLE 浏览器的BUG，所以要使用以下代码
	var prevReturnValue = window.returnValue; // Save the current returnValue
	window.returnValue = undefined;
	var dlgReturnValue = window.showModalDialog(url, '',"dialogWidth:800px;dialogHeight:400px;");
	if (dlgReturnValue == undefined) // We don't know here if undefined is the real result...
	{
	    // So we take no chance, in case this is the Google Chrome bug
	    dlgReturnValue = window.returnValue;
	}
	window.returnValue = prevReturnValue; // Restore the original returnValue

	//赋值
	if(dlgReturnValue){
//		$("#costMoney" + thisNum).val(dlgReturnValue);
		setMoney('costMoney' + thisNum,dlgReturnValue);
		var detailTable = $("select[id^='select_" + thisNum + "_']");
		if(detailTable.length == 1){
			setMoney("invoiceMoney_"+ thisNum + "_0",dlgReturnValue);
			$("#invoiceNumber_"+ thisNum + "_0").val(1);

			//计算
			countAll();
		    countInvoiceMoney();
		    countInvoiceNumber();
		}
	}
}

//普通输入框金额设置
function detailSet(thisNum){
	var costMobey = $("#costMoney" + thisNum).val()*1;
	if(costMobey){
		var detailTable = $("select[id^='select_" + thisNum + "_']");
		if(detailTable.length == 1){
			setMoney("invoiceMoney_"+ thisNum + "_0",costMobey);

			//初始化一个发票数量
			var invoiceNumberObj = $("#invoiceNumber_"+ thisNum + "_0");
			if(invoiceNumberObj.val() == ""){
				invoiceNumberObj.val(1);
			}
		}

		//明细计算
	    countInvoiceMoney();
	    countInvoiceNumber();
	}
}

//增加发票信息
function add_lnvoice(id){
	//实例化变量
	var costMoney , costType , detailMoney ,delTag ,lastMoney;
	//金额缓存
	costMoney = $("#costMoney" + id).val();
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
	billTypeStr = rtBillTypeStr(bliiTypeArr,id);

	//内置从表
	var tableObj = $("#table_" + id);
	//从表行对象
	var tableTrObj = $("#table_" + id + " tr");
	//从表行数
	var tableTrLength = tableTrObj.length;
	//内容Id后缀
	var countStr = id + "_" + tableTrLength;
	var str = '<tr id="tr_' + countStr + '">' +
			'<td width="30%"><select id="select_' + countStr + '" name="esmcostdetail['+ id + '][invoiceDetail][' + tableTrLength + '][invoiceTypeId]" style="width:90px"><option>请选择发票</option>' + billTypeStr +'</select></td>' +
			'<td width="25%"><input id="invoiceMoney_' + countStr + '" costTypeId="'+ id +'" rowCount="'+ countStr +'"  name="esmcostdetail['+ id + '][invoiceDetail][' + tableTrLength + '][invoiceMoney]" type="text" class="txtshort" value="'+lastMoney+'"/></td>' +
			'<td width="25%"><input id="invoiceNumber_' + countStr + '" costTypeId="'+ id +'" rowCount="'+ countStr +'"  name="esmcostdetail['+ id + '][invoiceDetail][' + tableTrLength + '][invoiceNumber]" type="text" class="txtshort" value="'+invoiceNumber+'"/></td>' +
            '<td width="20%"><img style="cursor:pointer;" src="images/removeline.png" title="添加行" onclick="delete_lnvoice(' + id + ',this)"/></td>' +
		'</tr>';
	tableObj.append(str);

    //备注高度调整
    var remarkObj = $("#remark" + id);
    remarkObj.animate({height:"+=33"},100);

	//格式化金额
    createFormatOnClick('invoiceMoney_'+countStr);

	//明细计算
    countInvoiceMoney();
    countInvoiceNumber();
}

//增加发票信息
function delete_lnvoice(id,obj){
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="esmcostdetail['+
				id +'][invoiceDetail][' +
				rowNo + '][isDelTag]" id="isDelTag_'+ id +'_'+rowNo +'" value="1"/>');

	    //备注高度调整
	    var remarkObj = $("#remark" + id);
	    remarkObj.animate({height:"-=33"},100);

		//明细计算
	    countInvoiceMoney();
	    countInvoiceNumber();
	}
}

//表单数据合计
function countAll(){
	//从表总金额
	var tableTrObj = $("#invbody input[id^='costTypeId']");
	var costTypeId , costMoney , countAll;
//	alert(tableTrObj.length)
	$.each(tableTrObj,function(i,n){
		costTypeId = this.value*1;
		delTag = $("#isDelTag_"+ costTypeId).length;

		if(delTag == 0){
			costMoney = $("#costMoney" + costTypeId).val();
			days = $("#days" + costTypeId).val();
			countAll = accAdd(countAll,accMul(costMoney,days),2);
		}
	});
	if(countAll*1 == 0){
		countAll = "";
	}
//	$("#countMoney").html(moneyFormat2(countAll));
	setMoney('countMoney', countAll );
}

//表单发票金额合计
function countInvoiceMoney(){
	//从表总金额
	var tableTrObj = $("#invbody input[id^='invoiceMoney_']");
	var countAll , delObj , rowCount , mark ,costTypeId , isCount;
//	alert(tableTrObj.length)
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
	var tableTrObj = $("#invbody input[id^='invoiceNumber_']");
	var countAll , delObj , rowCount , mark ,costTypeId , isCount;
//	alert(tableTrObj.length)
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

//表单验证
function checkform(){
	var rtVal = true;
	var tableTrObj = $("#invbody input[id^='costTypeId']");
	var costTypeId , costMoney , costType , detailMoney ,delTag;
	$.each(tableTrObj,function(i,n){
		//类型id
		costTypeId = this.value*1;
		//金额缓存
		costMoney = $("#costMoney" + costTypeId).val();
		if(costMoney != 0){
			//名称缓存
			costType = $("#costType" + costTypeId).val();
			//重新初始化金额
			detailAll = 0;
			$("select[id^='select_" + costTypeId + "_']").each(function(i,n){
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
	});
	return rtVal;
};