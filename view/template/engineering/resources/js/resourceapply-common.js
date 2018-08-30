//计算设备金额
function calResource(){
	//获取数量
	var number = $("#number").val();
	//获取单价
	var price = $("#price").val();
	//获取天数
	var useDays = $("#useDays").val();
	if( number != "" && price != "" && useDays != "" ){
		//计算单天设备金额
		var amount = accMul(number,price,2);
		//计算多天设备金额
		var amount = accMul(amount,useDays,2);

		setMoney('amount',amount,2);
	}
}

//计算设备金额 - 批量新增 - 复制 功能中使用
function calResourceBatch(rowNum){
	//从表前置字符串
	var beforeStr = "importTable_cmp";
	//获取当前数量
	var number= $("#"+ beforeStr + "_number" + rowNum ).val();

	if($("#" + beforeStr + "_resourceName"  + rowNum ).val() != "" && number != ""){
		//获取单价
		var price = $("#" + beforeStr +  "_price" + rowNum + "_v").val();
		//获取天数
		var useDays = $("#" + beforeStr +  "_useDays" + rowNum ).val();
		//计算单天设备金额
		var amount = accMul(number,price,2);

		//计算多天设备金额
		var amount = accMul(useDays,amount,2);

		setMoney(beforeStr +  "_amount" +  rowNum,amount,2);
	}
}

/**
 * 预计借出和预计归还日期差验证，使用天数的计算
 * @param {} $t
 * @return {Boolean}
 */
function timeCheck($t){
	var startDate = $('#planBeginDate').val();
	var endDate = $('#planEndDate').val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate) + 1;
	if(s < 0) {
		alert("预计开始不能比预计结束时间晚！");
		$t.value = "";
		return false;
	}
	$("#useDays").val(s);
}

//提交审批改变隐藏值
function setAudit(thisType){
	$("#audit").val(thisType);
}

//提交确认改变隐藏值
function setConfirm(thisType){
	$("#confirmStatus").val(thisType);
}

//确认验证
function confirmCheck(){
	//验证联系电话
	var mobile = $("#mobile").val();
	if(mobile != ""){
		var regex1 = /(^0?[1][34578][0-9]{9}$)/;
		if(!regex1.test(mobile)){
			var regex2 = /^(0[0-9]{2,3}\-)?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/;
			if(!regex2.test(mobile)){
				alert("请输入有效的手机号码或电话号码。如果是电话号码，区号和电话号码之间请用-分割，如：010-29292929");
				return false;
			}
		}
	}
	var objGrid = $("#importTable");
	//日期验证
	var dateConfirm = 0;
	var resourceIdArr = objGrid.yxeditgrid('getCmpByCol','resourceId');
	if(resourceIdArr.length == 0){
		alert("设备信息不能为空");
		return false;
	}
	resourceIdArr.each(function(){
    	var rowNum = $(this).data("rowNum");//当前行数

		var beginDateObj = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"planBeginDate");
		var beginDate = beginDateObj.val();
		var endDateObj = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"planEndDate");
		if(DateDiff($("#applyDate").val(),beginDate) < 0){
			dateConfirm = 1;
			beginDateObj.focus();
			return false;
		}
		if(DateDiff(beginDate,endDateObj.val()) < 0){
			dateConfirm = 2;
			endDateObj.focus();
			return false;
		}
    });
    if(dateConfirm == 1){
    	alert("领用日期不能早于申请日期");
    	return false;
    }
    if(dateConfirm == 2){
    	alert("归还日期不能早于领用日期");
    	return false;
    }
    var confirmStatus = $("#confirmStatus").val();
    var confirmCheckType = $("#confirmCheckType").val();//区分是否为确认页面
	if(confirmStatus == 1 && confirmCheckType != 'confirm'){//新增或编辑页面验证
		//检查当前员工是否存在设备申请锁定记录
		var	msg = $.ajax({
			type: "POST",
			url: "?model=engineering_resources_lock&action=checkLock",
			dataType:'html',
			async:false
		}).responseText;
		if(msg == 1){
			alert('您的设备借用申请权限暂时被锁定，请对锁定设备进行【归还】或【续借】或【转借】操作，详情请联系设备管理员');
			return false;
		}
		if(confirm('确认提交单据吗')){
			return true;
		}else{
			return false;
		}
	}else if(confirmStatus == 2){//确认页面验证
        //是否勾选了物料
        var isChecked = false;
        //物料合法性验证
        var isAllConfirm = true;
        resourceIdArr.each(function(){
            if(objGrid.yxeditgrid("getCmpByRowAndCol",$(this).data("rowNum"),"isChecked").attr('checked')){//只验证勾选的物料
            	isChecked = true;
                if(this.value == "0"){
                    isAllConfirm = false;
                    return false;
                }
            }
        });
        if(isChecked == false){
            alert('请至少勾选一个设备进行确认');
            return false;
        }
        if(isAllConfirm == false){
            alert('含有未确认的物料,不能提交表单');
            return false;
        }
    }
}

//获取省份
function getProvince() {
	var responseText = $.ajax({
		url : 'index1.php?model=system_procity_province&action=listJsonSort',
		type : "POST",
		async : false
	}).responseText;
	return eval("(" + responseText + ")");
}

/**
 * 添加省份数组添加到下拉框
 */
function addDataToProvince(data,selectId) {
	var optionStr = "<option value=''></option>";
	if($("#" + selectId + "Hidden").length > 0){
		var defaultVal = $("#" + selectId + "Hidden").val();
	}else{
		var defaultVal = '';
	}
	for(var i = 0;i < data.length;i++){
		if(defaultVal == data[i].id){
			optionStr += "<option title='" + data[i].provinceName
				+ "' value='" + data[i].id + "' selected='selected'>" + data[i].provinceName
				+ "</option>";
		}else{
			optionStr += "<option title='" + data[i].provinceName
				+ "' value='" + data[i].id + "'>" + data[i].provinceName
				+ "</option>";
		}
	}
	$("#" + selectId).append(optionStr);
}

/**
* 省份改变时对中文部分赋值
*/
function setPlace(){
	$('#place').val($("#placeId").find("option:selected").attr("title"));
}

//计算预计成本的总和
function calAmount(){
	var num = $("[id^='importTable_cmp_resourceTypeName']").length;
	var amount= 0;
	for(var i=0;i<num;i++){
		if($('#importTable_cmp_amount'+i).length == 0){
			continue;
		}
		amount = accAdd(amount,$('#importTable_cmp_amount'+i).val());
	}
	$('#view_amount').val(moneyFormat2(amount));
}

//查看申请操作记录
function viewLog(id){
	showOpenWin("?model=engineering_baseinfo_resourceapplylog"
			+ "&applyId="+id
			,1,700,1350);
}