$(document).ready(function() {
	//费用归属
	$("#costbelong").costbelong({
		objName : 'expense',
		url : '?model=finance_expense_expense&action=ajaxGet',
		data : {"id" : $("#id").val()},
		actionType : 'view'
	});

	//回到顶部
	$.scrolltotop({className: 'totop'});

	var chinseAmountObj = $("#chinseAmount");
	if(chinseAmountObj.length != 0){
		chinseAmountObj.html(toChinseMoney($("#Amount").val()*1));
	}

	//渲染延迟报销申请
	if($("#isLate").val() == "1"){
		$("#mainTitle").css('color','red').html('【费用延期报销】');
	}
})

//设置需要填写的内容
function changeTypeView(thisVal){
	$("#saleInfo").hide();
	$("#projectInfo").hide();
	$("#feeDeptInfo").hide();
	$("#contractInfo").hide();

	switch(thisVal){
		case '1' :
			$("#feeDeptInfo").show();

			//渲染需要检查的部门扩展信息
			initCheckDeptView();

			break;
		case '2' :
			$("#projectCodeView").html('合同项目编号');
			$("#projectNameView").html('合同项目名称');
			$("#projectInfo").show();
			break;
		case '3' :
			$("#projectCodeView").html('研发项目编号');
			$("#projectNameView").html('研发项目名称');
			$("#projectInfo").show();
			break;
		case '4' :
			$("#projectCodeView").html('试用项目编号');
			$("#projectNameView").html('试用项目名称');
			$("#projectInfo").show();
			$("#saleInfo").show();
			$("#procityInfo").show();
			$("#chanceDeptInfo").show();
			break;
		case '5' :
			$("#contractInfo").show();
			$("#procityInfo").show();
			$("#chanceDeptInfo").show();
			break;
		default : break;
	}
}

//页面渲染
function initCheckDeptView(){
	//如果需要部门检查
	var provinceObj = $('#provinceHidden');
	if(provinceObj.length == 1 && provinceObj.val() != ""){
		//插入省份
		var str="<td class='form_text_left_three'>所属省份</td><td class='form_text_right_three'>"+ provinceObj.val() +"</td>";
		//缩写部门格
		$("#feeDept").attr("colspan",1).attr("class","form_text_right_three").after(str);
	}
}

/****************** 修改事由 ********************/
//大概事由编辑部分
function openSavePurpose(){
	$('#purposeInfo').dialog({
    title: '保存模板',
		width: 400,
		height: 200,
		modal: true
	}).dialog('open');
}

function savePurpose(){
	var Purpose = $("#Purpose").val();
	if(strTrim(Purpose) == ""){
		alert('事由不能为空');
		return false;
	}
	//异步修改
	$.ajax({
	    type: "POST",
	    url: "?model=finance_expense_expense&action=ajaxUpdate",
	    data: {'id' : $("#id").val(),'myKey' : "Purpose" , "myValue" : Purpose},
	    async: false,
	    success: function(data){
	   		if(data == "1"){
	   			alert('更新成功');
				$("#PurposeShow").html(Purpose);
				$('#purposeInfo').dialog('close');
	   	    }
		}
	});
}

/****************** 费用部分 ********************/

//编辑修改费用类型
function changeDetail(costType,costTypeName,mainType,mainTypeName){
	//需要修改的单据号
	var BillNo = $("#BillNo").val();

	//是否存在相同类型判断
	var costTypeArr = [];
	$("span[id^='spanDetail']").each(function(i,n){
		costTypeArr.push($(this).attr("title"));
	});
	if(costTypeArr.length > 0){
		var orgCostTypes = costTypeArr.toString();
	}else{
		var orgCostTypes = "";
	}

	//父类型
	var MainTypeArr = [];
	$("input[id^='MainType']").each(function(i,n){
		MainTypeArr.push(this.value);
	});
	if(MainTypeArr.length > 0){
		var mainTypes = MainTypeArr.toString();
	}else{
		var mainTypes = "";
	}

	//修改费用类型
	showThickboxWin('?model=finance_expense_expensedetail&action=toEditDetail&CostTypeID=' + costType
		+ "&CostTypeName=" + costTypeName
		+ "&mainTypeName=" + mainTypeName
		+ "&mainType=" + mainType
		+ "&BillNo=" + BillNo
		+ "&orgCostTypes=" + orgCostTypes
		+ "&mainTypes=" + mainTypes
		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=800");
}

//页面刷新
function show_pageDetail(){
	var expensebodyObj = $("#expensebody");
	expensebodyObj.empty();
	var BillNo = $("#BillNo").val();
	//异步修改
	$.ajax({
	    type: "POST",
	    url: "?model=finance_expense_expensedetail&action=ajaxGetCostDetail",
	    data: {'BillNo' : BillNo},
	    async: false,
	    success: function(data){
	   		if(data){
				expensebodyObj.html(data);
	   	    }
		}
	});
}

/****************** 发票部分 ********************/

//编辑修改发票类型
function changeInv(invType,invTypeName){
	//需要修改的单据号
	var BillNo = $("#BillNo").val();

	//是否存在相同类型判断
	var billTypeArr = [];
	$("span[id^='spanInv']").each(function(i,n){
		billTypeArr.push($(this).attr("title"));
	});
	if(billTypeArr.length > 0){
		var orgBillTypes = billTypeArr.toString();
	}else{
		var orgBillTypes = "";
	}

	//修改发票类型
	showThickboxWin('?model=finance_expense_expenseinv&action=toEitBillTypeID&BillTypeID=' + invType
		+ "&BillTypeIDName=" + invTypeName
		+ "&BillNo=" + BillNo
		+ "&orgBillTypes=" + orgBillTypes
		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=250&width=400");
}

//页面刷新
function show_pageInv(billTypeId,newBillTypeId,newBillType,isMarge){
	if(isMarge == '0'){
		//插入新元素
		$("#imgInv" + billTypeId).after(
			'<img src="images/changeedit.gif" id="imgInv'+ newBillTypeId +'" onclick="changeInv(\''+ newBillTypeId +'\',\''+ newBillType +'\')" title="修改发票类型"/> ' +
			'<span id="spanInv'+ newBillTypeId +'">'+ newBillType +'</span>'
		);
		//干掉原来的信息
		$("#spanInv" + billTypeId).remove();
		$("#imgInv" + billTypeId).remove();
	}else{
		$("#invbody").empty();
		var BillNo = $("#BillNo").val();
		//异步修改
		$.ajax({
		    type: "POST",
		    url: "?model=finance_expense_expenseinv&action=ajaxGetBillDetail",
		    data: {'BillNo' : BillNo},
		    async: false,
		    success: function(data){
		   		if(data){
					$("#invbody").html(data);
		   	    }
			}
		});
	}

	//重新格式化金额
	formateMoney();
}



//发票数量重新赋值
function recountInvoiceNumber(){
	var invoiceNumber = 0;
	$.each($("td[id^='detailInvoiceNumber']"),function(i,n){
		invoiceNumber = accAdd(invoiceNumber,$(this).html());
	});
	$("#invoiceNumber").html(invoiceNumber);
}

//页面刷新 － 刷新费用和发票部分
function show_pageExpense(reloadType){
	if(reloadType == "1"){
		window.location.reload();
	}else{
		show_pageDetail();
		show_pageInv(1,1,1,1);
		recountInvoiceNumber();
	}
}

//查看特别事项申请
function viewSpecialApply(formNo){
	$.ajax({
	    type: "POST",
	    url: "?model=general_special_specialapply&action=getId",
	    data: {"formNo" : formNo},
	    async: false,
	    success: function(id){
	    	if(id != "" && id != "0"){
				showOpenWin("?model=general_special_specialapply&action=toView&id=" + id ,1,800,1100,id);
	    	}else{
	    		alert('没有查询到对应的特别事项申请');
	    	}
		}
	});
}