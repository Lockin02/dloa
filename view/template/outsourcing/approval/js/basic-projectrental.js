//var pageAttr;//当前页面功能性质 add/edit/view
var datadictArr = [];//缓存数据字典
var parentDatadictArr = [];//缓存上级对象

//初始化整包界面
function initProjectRental(){
	//单选供应商
//	$("#suppName").yxcombogrid_outsupplier({
//		hiddenId : 'suppId',
//		gridOptions : {
//			showcheckbox : false,
//			event : {
//				'row_dblclick' : function(e,row,data) {
//                        $("#suppCode").val(data.suppCode);
//				}
//			}
//		}
//	});
	if(checkCanInit() == false){
		return false;
	}

	if(pageAttr == 'add'){//新增时
		initProjectRentalAdd();
	}else if(pageAttr == "edit"){
		initProjectRentalEdit();
	}else{
		initProjectRentalView();
	}
	//单选供应商
	$("#supplier2").yxcombogrid_outsupplier({
		hiddenId : 'supplierId2',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
                        $("#supplierCode2").val(data.suppCode);
                        $("#supp2").val(data.suppName);
                        checkSupp(2);
				}
			}
		}
	});
	//单选供应商
	$("#supplier3").yxcombogrid_outsupplier({
		hiddenId : 'supplierId3',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
                        $("#supplierCode3").val(data.suppCode);
                        $("#supp3").val(data.suppName);
                        checkSupp(3);
				}
			}
		}
	});
	//单选供应商
	$("#supplier4").yxcombogrid_outsupplier({
		hiddenId : 'supplierId4',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
                        $("#supplierCode4").val(data.suppCode);
                        $("#supp4").val(data.suppName);
                        checkSupp(4);
				}
			}
		}
	});
}

//验证是否可初始化
function checkCanInit(){
	//初始化时验证变量是否存在
	try{
		pageAttr;
	}catch(e){
		alert('无法初始化整包/分包信息，请先定义编码！');
		return false;
	}
	return true;
}

//新增
function initProjectRentalAdd(){
	var projectRentalTbObj = $("#projectRentalTb");
	if(projectRentalTbObj.children().length == 0){
		//ajax获取销售负责人
		var responseText = $.ajax({
			url:"?model=outsourcing_approval_projectRental&action=getAddPage",
			data : {projectId : $("#projectId").val()},
			type : "POST",
			async : false
		}).responseText;
		projectRentalTbObj.html(responseText);

		//千分位渲染
		formatProjectRentalMoney();
	}
}

//编辑
function initProjectRentalEdit(){
	var projectRentalTbObj = $("#projectRentalTb");
	if(projectRentalTbObj.children().length == 0){
		//ajax获取销售负责人
		var responseText = $.ajax({
			url:"?model=outsourcing_approval_projectRental&action=getEditPage",
			data : {mainId : $("#id").val()},
			type : "POST",
			async : false
		}).responseText;
		projectRentalTbObj.html(responseText);

		//千分位渲染
		formatProjectRentalMoney();
	}
}

//查看
function initProjectRentalView(){
	var projectRentalTbObj = $("#projectRentalTb");
	if(projectRentalTbObj.children().length == 0){
		//ajax获取销售负责人
		var responseText = $.ajax({
			url:"?model=outsourcing_approval_projectRental&action=getViewPage",
			data : {mainId : $("#id").val()},
			type : "POST",
			async : false
		}).responseText;
		projectRentalTbObj.html(responseText);

		//千分位渲染
		formatProjectRentalMoney();
	}
}

//删除 - 行
function delProjectRentalRow(rowNum){
	var supplierObj = $("#supplier4_id" + rowNum);
	if(supplierObj.length > 0){
		supplierObj.after('<input type="hidden" name="basic[projectRental]['+rowNum+'][isDelTag]" id="isDel'+rowNum+'" value="1"/>');
		$("#tr"+rowNum).hide();
	}else{
		$("#tr"+rowNum).remove();
	}
	countProjectCost(1);
	countProjectCost(2);
	countProjectCost(3);
	countProjectCost(4);
}

//计算 - 行
function countDetail(rowNum,supplierNum,isCopy){
	var priceObj = $("#supplier"+supplierNum+"_price" + rowNum);
	var numberObj = $("#supplier"+supplierNum+"_number" + rowNum);
	var periodObj = $("#supplier"+supplierNum+"_period" + rowNum);

	//是否从前面复制 数量和周期
	if(isCopy == 1){
		numberObj.val($("#supplier1_number" + rowNum).val());
		periodObj.val($("#supplier1_period" + rowNum).val());
	}

	if(priceObj.val()!= "" || numberObj.val()!= "" || periodObj.val()!= ""){//当三者存在一个不为空时,进行计算
		var price = number = period = 0;
		if(priceObj.val()*1 != "") price = priceObj.val()*1;
		if(numberObj.val()*1 != "") number = numberObj.val()*1;
		if(periodObj.val()*1 != "") period = periodObj.val()*1;

		var amount = accMul(accMul(price,number,2),period,2);
		setMoney("supplier"+supplierNum+"_amount" + rowNum,amount);
	}
	countProjectCost(supplierNum);//计算项目成本
}

//计算 - 表
function countProjectRental(){
	//暂未实现
}

//计算总成本
function countProjectCost(num){
	var projectRentalRowNum=$("#projectRentalRowNum").val()*1+1;
	var justCost=0;//项目纯成本
	for(i=0;i<projectRentalRowNum;i++){
		var supplierObj = $("#supplier4_id" + i);
		if(supplierObj.length > 0){
			if($('#isDel'+i).val()!=1&&$("#supplier"+num+"_amount"+i).length>0){
				justCost=accAdd(justCost,$("#supplier"+num+"_amount"+i).val()*1,2);
			}
		}else if($("#supplier"+num+"_amount"+i).length>0){
				justCost=accAdd(justCost,$("#supplier"+num+"_amount"+i).val()*1,2);
		}
	}
	//其他费用
	var otherfee=$("#otherfee"+num).val()*1;
	if(otherfee>0){
		justCost=accAdd(justCost,otherfee,2);
	}
	//管理费用
	var mangerfee=$("#mangerfee"+num).val()*1;
	if(mangerfee>0&&num==1){
		justCost=accAdd(justCost,mangerfee,2);
	}
	if(justCost>0||justCost==0){
		$("#isAllCost"+num).val(justCost);
		$("#isAllCost"+num+"_v").val(moneyFormat2(justCost));
	}
	//项目毛利率
	countProjectProfit(num);
	//项目净利率
	countNetProfit(num);
	//税费
	countDLTax(num);
	//选择供应商
     checkSupp(num);
}

//项目毛利率
function countProjectProfit(num){
	//项目总成本
	var isAllCost=$("#isAllCost"+num).val()*1;
	//合同金额
	var orderMoney=$("#orderMoney").val()*1;
	if(isAllCost>0&&orderMoney>0){
		var grossMargin=accDiv(orderMoney-isAllCost,orderMoney,4);
		$("#isProfit"+num).val(accMul(grossMargin,100,2) + '%');
	}
}

//项目净利率
function countNetProfit(num){
	//项目总成本
	var isAllCost=$("#isAllCost"+num).val()*1;
	//税费
	var isTax=$("#isTax"+num).val()*1;
	//合同金额
	var orderMoney=$("#orderMoney").val()*1;
	if(isAllCost>0&&orderMoney>0&&isTax>0){
		var grossMargin=accDiv(orderMoney-isAllCost-isTax,orderMoney,4);
		$("#isNetProfit"+num).val(accMul(grossMargin,100,2) + '%');
	}
}

//鼎利税费
function countDLTax(num){
	//合同金额
	var orderMoney=$("#orderMoney").val()*1;
	//项目总成本
	var allCost=$("#isAllCost"+num).val()*1;
	if(orderMoney>0){
		var tax=0;
		if(num==1){
			tax=accMul(orderMoney,0.06,2);
		}else if(num!=1&&$("#isPoint"+num).val()!=''&&allCost>0){
			dlTax=accMul(orderMoney,0.06,2);
			wbTax=accMul(allCost,$("#isPoint"+num).val()*0.01,2);
			tax=accSub(dlTax,wbTax,2);
		}
		if(allCost>0){
			$("#isTax"+num).val(tax);
			$("#isTax"+num+"_v").val(moneyFormat2(tax));
		}
	}
	//项目净利率
	countNetProfit(num);
	//选择增值税专用发票税点
	checkTaxPointCode(num);

}


//千分位渲染
function formatProjectRentalMoney(){
	// 渲染 千分位金额
	$.each($("#projectRentalTbody input.formatMoney"), function(i, n) {
		var idStr = "" + $(this).attr('id');
		if ($(this).get(0).tagName == 'INPUT'
				&& idStr.indexOf("_v") <= 1) {
			var strHidden = $("<input type='hidden' name='" + n.name
					+ "' id='" + n.id + "' value='" + n.value + "' />");
			$(this).attr('name', '');
			$(this).attr('id', n.id + '_v');
			$(this).val(moneyFormat2(n.value));
			$(this).bind("blur", function() {
				moneyFormat1(this, 2);
				if (n.onblur)
					n.onblur();
			});
			$(this).after(strHidden);
		} else {
			returnMoney = moneyFormat2($(this).text(), 2);
			if (returnMoney != "")
				$(this).text(returnMoney);
		}
	});

	//渲染千分位 页面表格
	$.each($("#projectRentalTbody td.formatMoney"),function(){
		returnMoney = moneyFormat2($(this).text(), 2);
		if (returnMoney != "")
			$(this).text(returnMoney);
	});
}

//获取数据字典
function getDatadictArr(code){
	if(!datadictArr[code]){//如果已存在该缓存,则直接返回缓存
		var dataArr = getData(code);
		datadictArr[code] = dataArr;
	}
	return datadictArr[code];
}

//获取数据字典对象
function getParentDatadictArr(code){
	if(parentDatadictArr.length == 0){
		parentArr = getDatadictArr('WBHTFYX');
		var num = parentArr.length;
		for(var i = 0;i<num;i++){
			parentDatadictArr[parentArr[i].dataCode] = parentArr[i];
		}
	}
	return parentDatadictArr[code];
}

//数据字典渲染成option
function getOptionStr(data){
	var str = "";
	var num = data.length;
	for(var i=0;i<num;i++){
		str +="<option value='"+ data[i].dataCode +"'>"+ data[i].dataName +"</option>";
	}
	return str;
}



//验证是否选择单选
function verification(){
	var outsourcing = $("#outsourcing").val();
	var rowNum = $("#projectRentalRowNum").val();
	var supplier2 = $('#supplier2').val();
	var supplier3 = $('#supplier3').val();
	var supplier4 = $('#supplier4').val();
	var checkVal = $('input[name="basic[projectRental][supplier][checkSupplier]"]:checked').val();
	if(outsourcing == "HTWBFS-01"||outsourcing == "HTWBFS-03"){
		$("#orderMoney").addClass('validate[required]');
		$("#beginDate").addClass('validate[required]');
		$("#endDate").addClass('validate[required]');
		$("#mangerfee1").addClass('validate[required]');
		$("#mangerfee1_v").addClass('validate[required]');
		$("#suppName").addClass('validate[required]');
		for(var i = 0;i<=rowNum;i++){
			if( $("#tr"+i).css("display") != "none" ){
				$("#supplier1_price"+i+"_v").addClass('validate[required]');
				$("#supplier1_number"+i).addClass('validate[required]');
				$("#supplier1_period"+i).addClass('validate[required]');
				$("#supplier1_amount"+i+"_v").addClass('validate[required]');
			}else{
				$("#supplier1_price"+i+"_v").removeClass('validate[required]');
				$("#supplier1_number"+i).removeClass('validate[required]');
				$("#supplier1_period"+i).removeClass('validate[required]');
				$("#supplier1_amount"+i+"_v").removeClass('validate[required]');
			}
		}
		if(supplier2 || supplier3 || supplier4.length>0){
			if(checkVal !=null){
				if($('#supplier'+checkVal).val()!=''){
					for(var i = 0;i<=rowNum;i++){
						if( $("#tr"+i).css("display") != "none" ){
							$("#supplier"+checkVal+"_price"+i+"_v").addClass('validate[required]');
							$("#supplier"+checkVal+"_number"+i).addClass('validate[required]');
							$("#supplier"+checkVal+"_period"+i).addClass('validate[required]');
							$("#supplier"+checkVal+"_amount"+i+"_v").addClass('validate[required]');
							$("#isPoint"+checkVal).addClass('validate[required]');
						}else{
							$("#supplier"+checkVal+"_price"+i+"_v").removeClass('validate[required]');
							$("#supplier"+checkVal+"_number"+i).removeClass('validate[required]');
							$("#supplier"+checkVal+"_period"+i).removeClass('validate[required]');
							$("#supplier"+checkVal+"_amount"+i+"_v").removeClass('validate[required]');
						}
					}

				}else{
					alert('请选择不为空的供应商');
				}

			}else{
				alert('请选择一个供应商');
			}
		}else{
				alert('请填写外包供应商信息');
			}

	}
	else{
		$("#orderMoney").removeClass('validate[required]');
		$("#beginDate").removeClass('validate[required]');
		$("#endDate").removeClass('validate[required]');
		$("#mangerfee1").removeClass('validate[required]');
		$("#mangerfee1_v").removeClass('validate[required]');
		$("#suppName").removeClass('validate[required]');
		for(var i = 0;i<rowNum;i++){
			$("#supplier1_price"+i+"_v").removeClass('validate[required]');
			$("#supplier1_number"+i).removeClass('validate[required]');
			$("#supplier1_period"+i).removeClass('validate[required]');
			$("#supplier1_amount"+i+"_v").removeClass('validate[required]');
		}
	}
}
//选择供应商
function checkSupp(num){
	var checkVal = $('input[name="basic[projectRental][supplier][checkSupplier]"]:checked').val();
	if(checkVal==4||checkVal==2||checkVal==3){
		var supplier = $('#supplier'+checkVal).val();
		if(supplier!=""){
			var supplierId = $('#supplierId'+checkVal).val();
			var supplierCode = $('#supplierCode'+checkVal).val();
			$('#suppCode').val(supplierCode);
			$('#suppId').val(supplierId);
			$('#suppName').val(supplier);
			//项目总成本
			var allCost=$("#isAllCost"+checkVal).val()*1;
			if(allCost>0){
				$("#outSuppMoney").val(allCost);
				$("#outSuppMoney_v").val(moneyFormat2(allCost));
			}
			var isProfit=$("#isProfit"+checkVal).val();
			if(isProfit!=''){
				$("#grossProfit").val(isProfit);
			}
			checkTaxPointCode(checkVal);
		}else{
			$('#suppCode').val("");
			$('#suppId').val("");
			$('#suppName').val("");
			$("#outSuppMoney").val("");
			$("#grossProfit").val("");
		}
	}

}

//增值税专用发票税点
function checkTaxPointCode(num){
	var checkVal = $('input[name="basic[projectRental][supplier][checkSupplier]"]:checked').val();
	if(checkVal==4||checkVal==2||checkVal==3){
		$("#taxPointCode").val($("#isPoint"+checkVal).val());
	}

}
