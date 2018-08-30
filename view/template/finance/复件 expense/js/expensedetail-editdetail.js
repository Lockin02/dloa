//表单类型数组
var billTypeArr = [];

$(function(){
	//获取发票类型
	billTypeArr = getBillType();
	//特殊处理下
	billTypeArr = arrChange(billTypeArr);

	//渲染发票明细
	$("#innerTable").yxeditgrid({
		objName : 'expensedetail[expenseinv]',
		title : '发票明细',
		tableClass : 'form_in_table',
		url : '?model=finance_expense_expenseinv&action=listJson',
		param : { 'BillDetailID' : $("#ID").val() },
		event : {
			removeRow : function(t, rowNum, rowData) {

			}
		},
		colModel : [{
			display : 'ID',
			name : 'ID',
			type : 'hidden'
		}, {
			display : '发票类型',
			name : 'BillTypeID',
			validation : {
				required : true
			},
			tclass : 'select',
			type : 'select',
			options : billTypeArr
		}, {
			display : '发票金额',
			name : 'Amount',
			validation : {
				required : true
			},
			type : 'money',
			tclass : 'txt'
		}, {
			display : '发票数量',
			name : 'invoiceNumber',
			tclass : 'txt',
			value : 1
		}]
	})
});

//转换一下数据
function arrChange(billTypeArr){
	var newArr = [];
	var innerArr;
	for(var i = 0; i < billTypeArr.length ; i++){
		innerArr = {"name" : billTypeArr[i].name , "value" : billTypeArr[i].id};
		newArr.push(innerArr);
	}
	return newArr;
}

//判断所选类型是否可行
function checkCanSel(thisObj){
	var newCostObj = $("#newCostTypeID");
	var childrenObjs = newCostObj.find("option[parentId='"+ thisObj.value +"']");
	if(childrenObjs.length > 0){
		alert('该项存在子类型，不能进行选择');
		var costTypeId = $("#CostTypeID").val();
		newCostObj.val(costTypeId);
		return false;
	}else{
		//父类行赋值
		var newMainTypeId = newCostObj.find("option:selected").attr("parentId");
		var newMainType = newCostObj.find("option:selected").attr("parentName");
		$("#mainType").val(newMainTypeId);
		$("#mainTypeName").val(newMainType);
	}
	return true;
}


//保存发票类型变更
function checkform(){
	//金额验证
	var costMoney = $("#costMoney").val();
	if(costMoney*1 == 0 || costMoney == ""){
		alert('费用金额不能为0或者空');
		return false;
	}

	//改动前的类型
	var costTypeId = $("#CostTypeID").val();
	var mainType = $("#mainType").val();
	var mainTypeName = $("#mainTypeName").val();
	//新类型
	var newCostObj = $("#newCostTypeID");
	var newCostTypeId = newCostObj.val();
	var newCostType = newCostObj.find("option:selected").text();
	var newMainTypeId = newCostObj.find("option:selected").attr("parentId");
	var newMainType = newCostObj.find("option:selected").attr("parentName");

	//发票号码
	var BillNo = $("#BillNo").val();

	//当前存在费用类型
	var orgCostTypes = $("#orgCostTypes").val();
	var orgCostTypesArr = orgCostTypes.split(',');

	//判断新值是否已存在
//	if(jQuery.inArray(newCostTypeId,orgCostTypesArr) != '-1' && newCostTypeId != costTypeId){
//		alert('费用类型中已存在【'+newCostType+'】,不能修改成该项信息');
//		return false;
//	}

	//当前存在父费用类型
	var mainTypes = $("#mainTypes").val();
	var mainTypesArr = mainTypes.split(',');

	//从表对象
	var thisGrid = $("#innerTable");
	var cmps = thisGrid.yxeditgrid("getCmpByCol", "Amount");
	cmps.each(function(i,n) {
		//计算当前单价
		costMoney = accSub(costMoney,this.value,2);
	});
	//如果金额不为0，则单据金额不一致
	if(costMoney != 0){
		alert('费用金额与发票金额不一致');
		return false;
	}

	if(confirm('确认修改吗？')){
		return true;
	}else{
		return false;
	}
}