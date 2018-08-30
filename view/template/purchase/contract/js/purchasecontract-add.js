
function myload(){
	//对采购合同的申请设备数量进行验证，最大不能超过采购任务的设备的最大可申请数量
	$(".amount").bind("change",function(){
		var thisVal = parseInt( $(this).val() );
		var nextVal = parseInt( $(this).next().val() );
		if(isNaN(this.value.replace(/,|\s/g,''))){
			alert('请输入数字');
				$(this).attr("value",nextVal);
		}
		if(thisVal>nextVal){
			alert("请不要超过最大可申请数量 "+nextVal);
			$(this).attr("value",nextVal);
		}else if(thisVal<1){
			alert("请不要输入0或负数");
				$(this).attr("value",thisVal);
			$(this).attr("value",nextVal);
		}
	});
	//对申请单价进行验证
	$(".price").bind("change",function(){
		var thisVal = parseInt( $(this).val() );
		var nextVal = parseInt( $(this).next().val() );
		if(isNaN(this.value.replace(/,|\s/g,''))){
			alert('请输入数字');
				$(this).attr("value",nextVal);
				$(this).focus();
		}
		 if(thisVal<0){
			alert("请不要输入负数");
				$(this).attr("value",thisVal);
			$(this).attr("value",nextVal);
				$(this).focus();
		}
	});

}

//计算采购订单总金额
function sumAllMoney(){
	var tab = document.getElementById("invbody") ;
      //表格行数
    var rows = tab.rows.length ;
	var allMoney=0;
	for(var i=1;i<=rows;i++){
		/*var thisAmount=$("#amountAll"+i).val();
		var thisPrice=$("#applyPrice"+i).val();
		if(thisAmount!=undefined&&thisAmount!=""&thisPrice!=undefined&&thisPrice!=""){
			allMoney=accAdd(allMoney,accMul(thisAmount,thisPrice,6),6);
		}*/
		var moneyAll=$("#moneyAll"+i).val();
		if(moneyAll!=undefined&&moneyAll!=""){
			allMoney=accAdd(allMoney,moneyAll,6);
		}
	}
	$("#allMoney").val(allMoney);
	var quotes=moneyFormat2(allMoney);
	$("#allMoneyView").val(quotes);
}

//计算采购订单单价
function sumPrice(i){
		var thisPrice=parseFloat($("#applyPrice"+i).val());//含税单价
		var taxRate=parseInt($("#taxRate"+i).val());//税率
		var price=0;
		if($("#applyPrice"+i).val()!=undefined&&$("#applyPrice"+i).val()!=""&&$("#applyPrice"+i).val()!="NaN"){
			price=accDiv(thisPrice,taxRate*0.01+1,6);
		$("#price"+i).val(price);
		$("#price"+i+"_v").val(moneyFormat2(price,6));
		}
		if($("#applyPrice"+i).val()==""){
			$("#price"+i).val("");
			$("#price"+i+"_v").val("");
		}
//		sumAllMoney();
}

//计算采购订单含税单价
function sumApplyPrice(i){
		var price=parseFloat($("#price"+i).val());//含税单价
		var taxRate=parseInt($("#taxRate"+i).val());//税率

		if(price!=undefined&&price!=""){
			applyPrice=accMul(price,taxRate*0.01+1,6);
		}
		$("#applyPrice"+i).val(applyPrice);
		sumAllMoney();
}

//修改采购订单时计算采购订单总金额
function sumAllMoneyInEdit(obj){

	var tab = document.getElementById("invbody") ;
      //表格行数
    var rows = tab.rows.length ;
	var allMoney=0;
	for(var i=1;i<=rows;i++){
		/*var thisAmount=$("#amountAll"+i).val();
		var thisPrice=$("#applyPrice"+i).val();
		if(thisAmount!=undefined&&thisAmount!=""&thisPrice!=undefined&&thisPrice!=""){
			allMoney=accAdd(allMoney,accMul(thisAmount,thisPrice,6),6);
		}*/
		var moneyAll=$("#moneyAll"+i).val();
		if(moneyAll!=undefined&&moneyAll!=""){
			allMoney=accAdd(allMoney,moneyAll,6);
		}
	}
	$("#allMoney").val(allMoney);
	$("#allMoneyView").val(moneyFormat2(allMoney));
	$("#allMoneyView_v").val(moneyFormat2(allMoney));
	//根据汇率计算本币
	conversion();
}


//将采购合同直接提交审批
function toSubmit(){

	var tab = document.getElementById("invbody") ;
      //表格行数
    var rows = tab.rows.length ;
	var allMoney=0;
	for(var i=1;i<=rows;i++){
		var thisAmount=$("#amountAll"+i).val();
		var thisPrice=$("#applyPrice"+i).val();
		if(thisAmount!=undefined&&thisAmount!=""&thisPrice!=undefined&&thisPrice!=""){
			allMoney=accAdd(allMoney,accMul(thisAmount,thisPrice,4),4);
		}
	}
	$("#allMoney").val(allMoney);
	document.getElementById('form1').action = "index1.php?model=purchase_contract_purchasecontract&action=add&act=app&type=inquiry";
}


//单独起草采购订单将采购订单直接提交审批
function toSubmitByOrder(){

	var tab = document.getElementById("invbody") ;
      //表格行数
    var rows = tab.rows.length ;
	var allMoney=0;
	for(var i=1;i<=rows;i++){
		var thisAmount=$("#amountAll"+i).val();
		var thisPrice=$("#applyPrice"+i).val();
		if(thisAmount!=undefined&&thisAmount!=""&thisPrice!=undefined&&thisPrice!=""){
			allMoney=accAdd(allMoney,accMul(thisAmount,thisPrice,4),4);
		}
	}
	$("#allMoney").val(allMoney);
	var addType=$("#addType").val();
	document.getElementById('form1').action = "index1.php?model=purchase_contract_purchasecontract&action=add&act=app&type=order&appType="+addType;
}

//由采购任务下达采购订单并直接提交审批
function toSubmitByTask(){
	var orderType=$("#orderType").val();
	document.getElementById('form1').action = "index1.php?model=purchase_contract_purchasecontract&action=add&act=app&type=task&orderTypes="+orderType;
}

//由资产采购任务下达采购订单并直接提交审批
function toSubmitByAssetTask(){
	var orderType=$("#orderType").val();
	document.getElementById('form1').action = "index1.php?model=purchase_contract_purchasecontract&action=addAssetOrder&act=asset";
}




//将变更的采购合同提交审批
function commitToApproval(){
	document.getElementById('form1').action = "index1.php?model=purchase_change_contractchange&action=change&act=app";
}

//在修改变更合同的页面将合同提交审批
function editToApproval(){
	document.getElementById('form1').action = "index1.php?model=purchase_change_contractchange&action=editchange&act=app";
}

//在修改页面提交审批
function submitAudit(){
	document.getElementById('form2').action="index1.php?model=purchase_contract_purchasecontract&action=editContract&act=audit";
}



// 日期联动
$(function() {
	$('#dateHope').bind('focusout', function() {

		var thisDate = $(this).val();
        dateHope = $('#dateHope').val();
		$.each($(':input[id^="equDateHope"]'), function(i, n) {
			$(this).val(thisDate);
		})
	});


});



// 开户银行与帐号的级联过滤
$(function() {
	$('#suppAccount').yxcombogrid_suppAccount({
		isFocusoutCheck:false,
		gridOptions : {
			reload : true,
			showcheckbox : false,
			// 根据供应商ID及选中的开户银行，过滤出对应的银行帐号
			param : {
				suppId : $("#suppId").val()
			},
			event : {
				'row_dblclick' : function(e, row, data) {
					var getGrid = function() {
						return $("#suppAccount").yxcombogrid_suppAccount("getGrid");
					}
					var getGridOptions = function() {
						return $("#suppAccount").yxcombogrid_suppAccount("getGridOptions");
					}
					if (!$('#suppId').val()) {
					} else {
						if (getGrid().reload) {
							getGridOptions().param = {
								suppId : $('#suppId').val()
							};
							getGrid().reload();
						} else {
							getGridOptions().param = {
								suppId : $('#suppId').val()
							}
						}
					}
					$("#suppAccount").val(data.accountNum);
					$("#suppBankName").val(data.depositbank_name);		//将银行的数据字典转换成中文后替换相应的值，并在页面显示
					$("#suppBank").val(data.depositbank);				//银行的数据字典编码，隐藏域，用于保存数据时写入数据库
				}
			}
		}
	});

});


 /**
 *
 * @param {} mycount
 * 渲染开户帐号下拉列表
 *
 */
	function reloadSuppAccount( linkman ){
		var getGrid = function() {
			return $("#suppAccount").yxcombogrid_suppAccount("getGrid");
		}
		var getGridOptions = function() {
			return $("#suppAccount").yxcombogrid_suppAccount("getGridOptions");
		}
		if( !$('#suppId').val() ){
		}else{
			if (getGrid().reload) {
				getGridOptions().param = {
					suppId : $('#suppId').val()
				};
				getGrid().reload();
			} else {
				getGridOptions().param = {
					suppId : $('#suppId').val()
				}
			}
		}
	}
/**********************删除动态表单*************************/
function mydel(obj, mytable) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 2; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i-1;
		}
		sumAllMoney();
	}
}

//表单提交时，进行信息的校验
function checkAllData(){
	var booble=true;
	$("input.amount").each(function(){
		if ($(this).val()==""||$(this).val()==0) {
			alert("请输入数量,不能为空或者小于1");
			$(this).focus();
			booble=false;
			return false;
		}
	});
	$("input.productId").each(function(){
		if ($(this).val()=="") {
			alert("物料信息不完整，请选择物料");
			$(this).focus();
			booble=false;
			return false;
		}
	});
	if(!$("input.amount").length>0){   //判断是否选择了物料
		alert("采购物料清单为空,请选择物料。");
		booble=false;
	}

	//盖章申请新增部分
	if($("#isNeedStampYes").attr('checked') == true){
		if($("#stampType").val() == ""){
			alert('请选择盖章类型');
			return false;
		}

		var upList = strTrim($("#uploadfileList").html());
		//附件盖章验证
		if(upList == "" || upList == "暂无任何附件"){
			alert('申请盖章前需要上传合同附件!');
			return false;
		}
	}

	return booble;

}



/***************** 盖章新增部分 ********************/
//盖章选择判断
function changeRadio(){
	//附件盖章验证
	if($("#uploadfileList").html() == "" || $("#uploadfileList").html() == "暂无任何附件"){
		alert('申请盖章前需要上传合同附件!');
		$("#isNeedStampNo").attr("checked",true);
		return false;
	}

	//显示必填项
	if($("#isNeedStampYes").attr("checked")){
		$("#radioSpan").show();
	}else{
		$("#radioSpan").hide();
	}
}

