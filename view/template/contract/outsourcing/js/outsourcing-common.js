/**
 * 重写从表表头
 */
function tableHead(){
	var trHTML =  '';
	var detailRows = ['租赁开始日期','租赁结束日期','服务线人力成本','外包价格','技术面试结果','面试人员'];
	var detailArr = ['租赁周期','价格对比','技术面试情况'];
	var trObj = $("#itemTable tr:eq(0)");
	var tdArr = trObj.children();
	var mark = 1;
	var m = 0;
	tdArr.each(function(i,n){
		if($.inArray($(this).text(),detailRows) != -1){
			if(mark == 1){
				$(this).attr("colSpan",2).text(detailArr[m]);
				mark = 0;
				m++;
			}else{
				$(this).remove();
				mark = 1;
			}
		}else{
			$(this).attr("rowSpan",2);
		}
	});

	trHTML+='<tr class="main_tr_header">';
	for(m=0;m<detailRows.length;m++){
		trHTML+='<th><div class="divChangeLine" style="min-width:100px;">'+detailRows[m]+'</div></th>';
	}
	trHTML+='</tr>';
	trObj.after(trHTML);
}

//变更外包类型
function outsourType(){
	var outsourcing = $("#outsourcing").val();
	if(outsourcing == "HTWBFS-02"){
		$("#personrental").show();
		$("#projectrental").hide();
		itemDetail();
	}else{
		$("#personrental").hide();
		$("#projectrental").show();
		initProjectRental();
	}
}

/**
 * 项目编号必填判断
 */
function changeSelect(){
	$("#projectCode").yxcombogrid_rdprojectfordl("remove").yxcombogrid_esmproject("remove").val("");
	$("#projectName").val("");
	$("#projectType").val("");
	$("#projectId").val("");

	changeSelectClear();
}

//初始化项目选择
function changeSelectClear(){
	$e = $("#outsourceType").find("option:selected").attr("e1");
	$val = $("#outsourceType").find("option:selected").val();
	if($e==1){
		if($val == 'HTWB02'){
			//研发项目渲染啊
			$("#projectCode").yxcombogrid_rdprojectfordl({
				hiddenId : 'projectId',
				nameCol : 'projectCode',
				isShowButton : false,
				height : 250,
				isFocusoutCheck : false,
				gridOptions : {
					param : { 'is_delete' : 0},
					isTitle : true,
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e,row,data) {
							$("#projectName").val(data.projectName);
						}
					}
				}
			});
		}else{
			//工程项目渲染
			$("#projectCode").yxcombogrid_esmproject({
				hiddenId : 'projectId',
				nameCol : 'projectCode',
				isShowButton : false,
				height : 250,
				gridOptions : {
					isTitle : true,
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e,row,data) {
							$("#projectName").val(data.projectName);
							$("#projectType").val(data.category);
						}
					}
				}
			});
		}
		$("#myspan").show();
	}else{
		$("#myspan").hide();
	}
}


//选择银行代扣后触发事件
function entrustFun(thisVal, quiet){
	if(thisVal == '1'){
        if (quiet) {
            $("#bank").val('已付款');
            $("#bank").attr('class','readOnlyTxtNormal');
            $("#bank").attr('readonly',true);
            $("#account").val('已付款');
            $("#account").attr('class','readOnlyTxtNormal');
            $("#account").attr('readonly',true);
        } else {
            if(confirm('选择已付款后，不再由出纳进行款项支付，确认选择吗？')){
                $("#bank").val('已付款');
                $("#bank").attr('class','readOnlyTxtNormal');
                $("#bank").attr('readonly',true);
                $("#account").val('已付款');
                $("#account").attr('class','readOnlyTxtNormal');
                $("#account").attr('readonly',true);
            }else{
                $("#isEntrustNo").attr('checked',true);
                $("#bank").val('');
                $("#bank").attr('class','txt');
                $("#bank").attr('readonly',false);
                $("#account").val('');
                $("#account").attr('class','txt');
                $("#account").attr('readonly',false);
            }
        }
	}else{
		$("#bank").val('');
		$("#bank").attr('class','txt');
		$("#bank").attr('readonly',false);
		$("#account").val('');
		$("#account").attr('class','txt');
		$("#account").attr('readonly',false);
	}
}

/**
 * 是否盖章必填判断
 */
function changeRadio(){
	//附件盖章验证
//	if($("#uploadfileList").html() == "" || $("#uploadfileList").html() == "暂无任何附件"){
//		alert('申请盖章前需要上传合同附件!');
//		$("#isNeedStampNo").attr("checked",true);
//		return false;
//	}

	//显示必填项
	if($("#isNeedStampYes").attr("checked")){
		$("#radioSpan").show();
		//防止重复数理化下拉表格
		if($("#stampType").yxcombogrid_stampconfig('getIsRender') == true) return false;

		//盖章类型渲染
		$("#stampType").yxcombogrid_stampconfig({
			hiddenId : 'stampType',
			height : 250,
			gridOptions : {
				isTitle : true,
				showcheckbox : true
			}
		});
	}else{
		$("#radioSpan").hide();

		//盖章类型渲染
		var stampTypeObj = $("#stampType");
		stampTypeObj.yxcombogrid_stampconfig('remove');
		stampTypeObj.val('');
	}
}

/**
 * 从新盖章选择
 */
function restampRadio(thisVal){
	if(thisVal == 1){
		$(".restamp").show();
		$(".restampIn").attr('disabled',false);
	}else{
		$(".restamp").hide();
		$(".restampIn").attr('disabled',true);
	}
}


//初始化
$(function(){
	changeSelectClear();

	//初始化结算方式
	changePayTypeFun();
});

//结算方式
function changePayTypeFun(){
	innerPayType = $("#payType1").find("option:selected").attr("e1");
	if(innerPayType == 1){
		$("#bankNeed").show();
		$("#accountNeed").show();
	}else{
		$("#bankNeed").hide();
		$("#accountNeed").hide();
	}
}


//显示付款申请信息
function showPayapplyInfo(thisObj){
	if(thisObj.checked == true){
		thisObj.value = 1;
		$(".payapplyInfo").show();

		//费用归属部门
		$("#feeDeptName").yxselect_dept({
			hiddenId : 'feeDeptId',
			unDeptFilter: ($('#unDeptFilter').val() != undefined)? $('#unDeptFilter').val() : ''
		});

		//币种初始化
		var currencyCodeObj = $("#currencyCode");
		if(currencyCodeObj.length > 0){
			// 金额币别
			$("#currency").yxcombogrid_currency({
				hiddenId : 'currencyCode',
				valueCol : 'currencyCode',
				isFocusoutCheck : false,
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							$("#rate").val(data.rate);
						}
					}
				}
			});
		}
	}else{
		thisObj.value = 0;
		$(".payapplyInfo").hide();
		//费用归属部门
		$("#feeDeptName").yxselect_dept('remove');
		//币种初始化
		$("#currency").yxcombogrid_currency('remove');
	}
}


//表单验证方法 - 当为项目外包时,需要填写项目编号
function checkForm(){
	if($("#outsourceType").find("option:selected").attr("e1") == 1){
		if($("#projectCode").val() == ""){
			alert('项目编号必须填写');
			return false;
		}
	}

	//付款申请
	var isNeedPayapplyObj = $("#isNeedPayapply");
	if( isNeedPayapplyObj.length == 1 && isNeedPayapplyObj.attr("checked") == true){

		//申请金额
		var applyMoney = $("#applyMoney").val()*1;
		if(applyMoney == 0 || applyMoney == ""){
			alert('付款申请金额不能为0或空');
			return false;
		}

		//付款日期
		var formDate = $("#formDate").val();
		if(formDate == ""){
			alert('期望付款日期不能为空');
			return false;
		}

		//费用归属部门
		var feeDeptName = $("#feeDeptName").val();
		if(feeDeptName == ""){
			alert('费用归属部门不能为空');
			return false;
		}

		innerPayType = $("#payType").find("option:selected").attr("e1");
		if(innerPayType == 1){

			//收款银行
			var bank = $("#bank").val();
			if(bank == ""){
				alert('收款银行不能为空');
				return false;
			}

			//收款账号
			var account = $("#account").val();
			if(account == ""){
				alert('收款账号不能为空');
				return false;
			}
		}

		//币种
		if($("#currency").val() == ""){
			alert('请填写付款币种');
			return false;
		}

		//汇入地点
		if($("#place").val() == ""){
			alert('请填写汇入地点(省/市)');
			return false;
		}

		if($("#payDesc").val() == ""){
			alert('请填写款项说明');
			return false;
		}

		//款项用途
		var remark = strTrim($("#remark").val());
		if(remark == ""){
			alert('款项用途不能为空');
			return false;
		}else{
			if(remark.length > 10){
				alert('请将款项用途描述信息保持在10个字或10个字以内,当前长度为'+ remark.length +" 个字");
				return false;
			}
		}
	}

	if($("#isNeedStampYes").attr('checked') == true){
		//盖章类型
		if($("#stampType").val() == ""){
			alert('请选择一种盖章类型');
			return false;
		}

		var upList = strTrim($("#uploadfileList").html());
		//附件盖章验证
		if(upList == "" || upList == "暂无任何附件"){
			alert('申请盖章前需要上传合同附件!');
			return false;
		}
	}

	//防止重复提交验证
//	$("input[type='submit']").attr('disabled',true);

	return true;
}

//变更表单验证方法 - 当为项目外包时,需要填写项目编号
function checkFormChange(){
	//验证数据是否发生改变
	var rs = checkWithoutIgnore('合同主要信息未发生改变');
	if(rs == false){
		return false;
	}

	if($("#outsourceType").find("option:selected").attr("e1") == 1){
		if($("#projectCode").val() == ""){
			alert('项目编号必须填写');
			return false;
		}
	}

	if($("#changeReason").val() == ""){
		alert('变更原因必须填写');
		return false;
	}

	if($("#isNeedStampYes").attr('checked') == true){
		//盖章类型
		if($("#stampType").val() == ""){
			alert('请选择一种盖章类型');
			return false;
		}

		var upList = strTrim($("#uploadfileList").html());
		//附件盖章验证
		if(upList == "" || upList == "暂无任何附件"){
			alert('申请盖章前需要上传合同附件!');
			return false;
		}
	}

	//防止重复提交验证
//	$("input[type='submit']").attr('disabled',true);

	return true;
}


//判断开始时间不小于结束时间
function checkTime(beginDate,endDate){
	var beginDate = $("#beginDate").val();
	var endDate = $("#endDate").val();
	var s = DateDiff(beginDate,endDate); //计算开始时间到结束时间的天数
	if(s < 0 ){
		alert('合同的结束日期不能早于开始日期！请重填');
		$("#endDate").val("");
		$("#endDate").focus();
	}		
}

function checkTime_begin(beginDate,endDate){
	var beginDate = $("#beginDate").val();
	var endDate = $("#endDate").val();
	var s = DateDiff(beginDate,endDate); //计算开始时间到结束时间的天数
	if(s < 0 ){
		alert('合同的结束日期不能早于开始日期！请重填');
		$("#beginDate").val("");
		$("#beginDate").focus();
	}		
}


//新增时提交审批 -- 独立新增
function auditDept(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=contract_outsourcing_outsourcing&action=addDept&act=audit";
	}else{
		document.getElementById('form1').action="?model=contract_outsourcing_outsourcing&action=addDept";
	}
}

//改变合同金额后,如果申请金额大于合同金额，清除申请金额
function checkApplyMoney(){
	var orderMoneyObj = $("#orderMoney");
	var applyMoneyObj = $("#applyMoney");

	if(orderMoneyObj.val()*1 < applyMoneyObj.val()*1){
		alert('合同金额不能小于付款申请金额');
		$("#applyMoney").val("");
		$("#applyMoney_v").val("");
	}
}

//保存整包模板
function saveTemplate(){
	var costTypeArr = $("#projectRentalTbody tr");
	if(costTypeArr.length == 0)
		alert('没有明细项');
	else{
		var temArr = [];
		costTypeArr.each(function(i,n){
			var rowNum = $(this).attr('rowNum');//行号

			//存入parent值
			var parent = $("#parent"+rowNum).val();

			var costTypeObj = $("#costType"+rowNum);
			var costType = '';
			var costTypeName = '';
			if(costTypeObj.length > 0){
				costType = costTypeObj.val();
			}else{
				costTypeName = $("#costTypeName"+rowNum).val();
			}

			temArr[rowNum] = {parent : parent,costType:costType,costTypeName:costTypeName};
		});

		$.ajax({
		    type: "POST",
		    url: "?model=contract_outsourcing_outtemplate&action=saveTemplate",
		    data: {obj : temArr},
		    success: function(data){
				if(data == "1"){
					alert('保存成功');
				}else{
					alert('保存失败');
				}
			}
		});
	}
}