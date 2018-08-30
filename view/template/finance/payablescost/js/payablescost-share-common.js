//分摊类型缓存数组
var shareObjArr = [];
//费用类型缓存数组
var feeTypeArr = [];

/**
 * 获取省份数组
 */
function getFeeType() {
	var responseText = $.ajax({
		url : 'index1.php?model=common_otherdatas&action=getFeeType',
		type : "POST",
		async : false
	}).responseText;
	var o = eval("(" + responseText + ")");
	return o;
}

/**
 * 添加省份数组添加到下拉框
 */
function addDataToFeeType(data, selectId) {
	for (var i = 0, l = data.length; i < l; i++) {
		$("#" + selectId).append("<option value='" + data[i].feeType + "'>" + data[i].feeType + "</option>");
	}
}

//初始化费用类型
function initFeeType(){
	//行数
	var detailNo = $("#detailNo").val();

	for(var i = 1;i <= detailNo ; i++){
		addDataToFeeType(feeTypeArr, "feeType" + i);
	}
}

//初始化分摊对象方法
function initShare(){
	//行数
	var detailNo = $("#detailNo").val();
	//缓存的分摊类型
	var shareType;

	for(var i = 1;i <= detailNo ; i++){
		shareType = $("#shareType" + i).val();

		switch(shareType){
			case 'CWFYFT-01' : initPerson(i);break;
			case 'CWFYFT-02' : initDept(i);break;
			case 'CWFYFT-03' : initEsmproject(i);break;
			case 'CWFYFT-04' : initRdproject(i);break;
		}
	}
}

//初始化人员
function initPerson(i){
	$("#shareObjName" + i).yxselect_user({
		hiddenId : 'shareObjCode' + i,
		formCode : 'payablescost'
	});
}

//初始化人员
function initDept(i){
	$("#shareObjName" + i).yxselect_dept({
		hiddenId : 'shareObjId' + i
	});
}

//初始化工程项目
function initEsmproject(i){
	//工程项目渲染
	$("#shareObjName" + i ).yxcombogrid_esmproject({
		hiddenId : 'shareObjId' + i,
		nameCol : 'projectName',
		isShowButton : false,
		isFocusoutCheck : false,
		height : 250,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(i){
					return function(e, row, data) {
						$("#shareObjCode" + i).val(data.projectCode);
					};
				}(i)
			}
		}
	});
}

//初始化研发项目
function initRdproject(i){
	//研发项目渲染啊
	$("#shareObjName" + i).yxcombogrid_rdprojectfordl({
		hiddenId : 'shareObjId' + i,
		nameCol : 'projectName',
		isShowButton : false,
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			param : { 'is_delete' : 0},
			isTitle : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(i){
					return function(e, row, data) {
						$("#shareObjCode" + i).val(data.projectCode);
					};
				}(i)
			}
		}
	});

}

//重新设置分摊对象
function changeShareType(i,thisVal){
	//数据清除
	$("#shareObjName" + i).val("");
	$("#shareObjCode" + i).val("");
	$("#shareObjId" + i).val("");
	//取消渲染
	$("#shareObjName" + i).yxselect_user('remove');
	$("#shareObjName" + i).yxselect_dept('remove');
	$("#shareObjName" + i).yxcombogrid_esmproject('remove');
	$("#shareObjName" + i).yxcombogrid_rdprojectfordl('remove');

	//重新渲染
	switch(thisVal){
		case 'CWFYFT-01' : initPerson(i);break;
		case 'CWFYFT-02' : initDept(i);break;
		case 'CWFYFT-03' : initEsmproject(i);break;
		case 'CWFYFT-04' : initRdproject(i);break;
	}
}

//发票金额
function reCalMoney(i){
	var shareMoneyCount = 0;
	//行数
	var detailNo = $("#detailNo").val();

	for(var i = 1;i <= detailNo ; i++){
		if($("#isDelTag" + i).val() != 1){
			shareMoney = $("#shareMoney" + i).val();
			shareMoneyCount = accAdd(shareMoneyCount,shareMoney,2);
		}
	}
//	alert(shareMoneyCount);

	setMoney('shareMoneyCount',shareMoneyCount,2);
}


/***************** 从表添加部分 ******************/
//从表添加
function detailAdd(tablelist,countNumP){
	mycount = document.getElementById(countNumP).value*1 + 1;
	var tablelist = document.getElementById(tablelist);
	i=tablelist.rows.length;
	oTR =tablelist.insertRow([i]);
	oTR.align="center";
	oTR.height="30px";
	oTL0=oTR.insertCell([0]);
	oTL0.innerHTML='';
	oTL1=oTR.insertCell([1]);
	oTL1.innerHTML="<select name='payablescost[detail]["+mycount+"][shareType]' id='shareType"+mycount+"' class='txtmiddle' onchange='changeShareType("+ mycount +",this.value)'></select>";
	oTL2=oTR.insertCell([2]);
	oTL2.innerHTML="<input type='text' class='txt' name='payablescost[detail]["+mycount+"][shareObjName]' id='shareObjName"+mycount+"' readonly='readonly'/>" +
		"<input type='text' class='txt' name='payablescost[detail]["+mycount+"][shareObjCode]' id='shareObjCode"+mycount+"' style='display:none' readonly='readonly'/>" +
		"<input type='hidden' name='payablescost[detail]["+mycount+"][shareObjId]' id='shareObjId"+mycount+"'/>";
    oTL3=oTR.insertCell([3]);
    oTL3.innerHTML="<select name='payablescost[detail]["+mycount+"][feeType]' id='feeType"+mycount+"' class='txtmiddle'><option></option></select>";
    oTL4=oTR.insertCell([4]);
    oTL4.innerHTML="<input type='text' name='payablescost[detail]["+mycount+"][shareMoney]' id='shareMoney"+mycount+"' class='txtmiddle'/>"
    	+"<input type='hidden' name='payablescost[detail]["+mycount+"][id]' id='id"+mycount+"'/>";
    oTL5=oTR.insertCell([5]);
    oTL5.innerHTML="<img src='images/closeDiv.gif' onclick='mydel(this,\""+tablelist.id+"\")' id='deteleRow" + mycount + "' title='删除行'/>";

    document.getElementById(countNumP).value = document.getElementById(countNumP).value*1 + 1 ;

    //分摊类型处理
    addDataToSelect(shareObjArr, 'shareType'+mycount);
	//获取上一个类型值，然后设到本次渲染中
    var lastShareType = $("select[id^='shareType']").eq(-2).val();
    if(lastShareType){
		$("#shareType" + mycount).val(lastShareType);
		changeShareType(mycount,lastShareType);
    }else{
		changeShareType(mycount,$('#shareType'+mycount).val());
    }

	//金额部分
	var lastMoney = accSub($("#payapplyMoney").val(),$("#shareMoneyCount").val(),2);
	$('#shareMoney'+mycount).val(lastMoney);

    createFormatOnClick('shareMoney'+mycount);
    $("#shareMoney" + mycount + "_v").bind("blur",function(){
		reCalMoney()
    });
    reCalMoney();

    //费用类型处理
    addDataToFeeType(feeTypeArr, "feeType" + mycount);
    var lastFeeType = $("select[id^='feeType']").eq(-2).val();
    if(lastFeeType){
		$("#feeType" + mycount).val(lastFeeType);
    }
	//重置行序号
	resetRowNo('invbody');
}

/**********************删除动态表单*************************/
function mydel(obj,mytable)
{
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 1;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="payablescost[detail]['
				+ rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo
				+ '" />');
		//重置行序号
		resetRowNo(mytable);
		//重新计算金额
		reCalMoney();
	}
}

//行序号充值
function resetRowNo(mytable){
	//重置表序号排列
	var tableLength = $("#" + mytable + " tr" ).length;
	var isDelTagObj;
	var j = 0;

	for(var i = 1; i < tableLength ;i++){
		isDelTagObj = $("#isDelTag" + i);
		if(isDelTagObj.length == 0){
			j ++;
			$("#shareType" + i).parent().parent().children().eq(0).html(j);
		}
	}
}

//表单验证
function checkform(){
	//重置表序号排列
	var tableLength = $("#invbody tr" ).length;
	var isDelTagObj;
	var shareMoney;
	var j = 0;

	for(var i = 1; i < tableLength ;i++){
		isDelTagObj = $("#isDelTag" + i);
		if(isDelTagObj.length == 0){
			j ++;
			//分摊对象验证
			if($("#shareObjName" + i).val() == ""){
				alert('第'+ j +'行的分摊对象不能为空');
				return false;
			}
			//费用类型
			if($("#feeType" + i).val() == ""){
				alert('第'+ j +'行的费用类型不能为空');
				return false;
			}
			//分摊金额
			shareMoney = $("#shareMoney" + i).val();
			if(shareMoney == "" || shareMoney == 0){
				alert('第'+ j +'行的分摊金额不能为空或者0');
				return false;
			}
		}
	}

	//分摊金额不能大于付款申请金额
	var payapplyMoney =  $("#payapplyMoney").val()*1;
	var shareMoneyCount = $("#shareMoneyCount").val()*1;
	if(payapplyMoney < shareMoneyCount){
		alert('分摊金额( '+ shareMoneyCount +' )不能大于付款申请金额( '+ payapplyMoney +' )');
		return false;
	}
}
