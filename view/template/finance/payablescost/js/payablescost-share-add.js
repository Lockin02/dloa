//初始化数据
$(document).ready(function() {
	/*//费用分摊对象
	shareObjArr = getData('CWFYFT');

	//加载第一行对象类型
	addDataToSelect(shareObjArr, 'shareType1');

	//加载分摊对象渲染
	initShare();

	//获取费用项目
	feeTypeArr = getFeeType();
	//初始化费用
	initFeeType();*/
	
	//付款信息
	$("#payDetail").payDetailGrid({
		objName:'payablescost[detail]'
			});
	initListCount();
});
/**
 * 
 * 提交前检验
 * @returns {Boolean}
 */
function checkform(){
	//分摊总金额
	var payDetailMoneyHidden=$("#payDetailMoneyHidden").val()*1;
	
	//分摊对象不能为空
	var shareObjNameArr= $("input[id^='payDetail_cmp_shareObjName']");
	for(i=0;i<shareObjNameArr.length;i++){
		if($(shareObjNameArr[i]).val()==''){
			alert('请填写分摊对象');
			return false;
		}
	}
//	费用类型
	var shareTypeArr=$("input[id^='payDetail_cmp_feeType']");
	for(i=0;i<shareTypeArr.length;i++){
		if($(shareTypeArr[i]).val()==''){
			alert('请填写费用类型');
			return false;
		}
		
	}	
	//申请金额
	var payMoney=$("#payMoney").val()*1;

	if(payDetailMoneyHidden==0){
		alert('请填写分摊明细列表或或分摊金额合计不能为0');
		return false;
	}
	if(payMoney<payDetailMoneyHidden){
		alert('错误！分摊合计金额不能大于申请金额');
		return false;
	}
	if(payMoney>payDetailMoneyHidden){
		alert('错误！请补充分摊金额');
		return false;
	}
	var shareMoneyArr=$("input[id^='payDetail_cmp_shareMoney']");
	for(i=0;i<shareMoneyArr.length;i++){
		if($(shareMoneyArr[i]).val()*1==0){
			alert('分摊金额不能为0');
			return false;
		}
		
	}

}
//初始化合计栏
function initListCount(){
	var $tbody = $("#payDetail").find('tbody');
	$tbody.after("<tr class='tr_count'><td colspan='2'>合计</td>"
		+ "<td>"
		+ "</td>"
		+ "<td>"
		+ "</td>"
		+ "<td>"
		+ "</td>"
		+ "<td>" 
		+'<span id="payDetailMoney">0.00</span>'
		+"</td>" 
		+ "</tr>");
}