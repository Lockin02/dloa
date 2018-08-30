//��̯���ͻ�������
var shareObjArr = [];
//�������ͻ�������
var feeTypeArr = [];

/**
 * ��ȡʡ������
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
 * ���ʡ��������ӵ�������
 */
function addDataToFeeType(data, selectId) {
	for (var i = 0, l = data.length; i < l; i++) {
		$("#" + selectId).append("<option value='" + data[i].feeType + "'>" + data[i].feeType + "</option>");
	}
}

//��ʼ����������
function initFeeType(){
	//����
	var detailNo = $("#detailNo").val();

	for(var i = 1;i <= detailNo ; i++){
		addDataToFeeType(feeTypeArr, "feeType" + i);
	}
}

//��ʼ����̯���󷽷�
function initShare(){
	//����
	var detailNo = $("#detailNo").val();
	//����ķ�̯����
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

//��ʼ����Ա
function initPerson(i){
	$("#shareObjName" + i).yxselect_user({
		hiddenId : 'shareObjCode' + i,
		formCode : 'payablescost'
	});
}

//��ʼ����Ա
function initDept(i){
	$("#shareObjName" + i).yxselect_dept({
		hiddenId : 'shareObjId' + i
	});
}

//��ʼ��������Ŀ
function initEsmproject(i){
	//������Ŀ��Ⱦ
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

//��ʼ���з���Ŀ
function initRdproject(i){
	//�з���Ŀ��Ⱦ��
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

//�������÷�̯����
function changeShareType(i,thisVal){
	//�������
	$("#shareObjName" + i).val("");
	$("#shareObjCode" + i).val("");
	$("#shareObjId" + i).val("");
	//ȡ����Ⱦ
	$("#shareObjName" + i).yxselect_user('remove');
	$("#shareObjName" + i).yxselect_dept('remove');
	$("#shareObjName" + i).yxcombogrid_esmproject('remove');
	$("#shareObjName" + i).yxcombogrid_rdprojectfordl('remove');

	//������Ⱦ
	switch(thisVal){
		case 'CWFYFT-01' : initPerson(i);break;
		case 'CWFYFT-02' : initDept(i);break;
		case 'CWFYFT-03' : initEsmproject(i);break;
		case 'CWFYFT-04' : initRdproject(i);break;
	}
}

//��Ʊ���
function reCalMoney(i){
	var shareMoneyCount = 0;
	//����
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


/***************** �ӱ���Ӳ��� ******************/
//�ӱ����
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
    oTL5.innerHTML="<img src='images/closeDiv.gif' onclick='mydel(this,\""+tablelist.id+"\")' id='deteleRow" + mycount + "' title='ɾ����'/>";

    document.getElementById(countNumP).value = document.getElementById(countNumP).value*1 + 1 ;

    //��̯���ʹ���
    addDataToSelect(shareObjArr, 'shareType'+mycount);
	//��ȡ��һ������ֵ��Ȼ���赽������Ⱦ��
    var lastShareType = $("select[id^='shareType']").eq(-2).val();
    if(lastShareType){
		$("#shareType" + mycount).val(lastShareType);
		changeShareType(mycount,lastShareType);
    }else{
		changeShareType(mycount,$('#shareType'+mycount).val());
    }

	//����
	var lastMoney = accSub($("#payapplyMoney").val(),$("#shareMoneyCount").val(),2);
	$('#shareMoney'+mycount).val(lastMoney);

    createFormatOnClick('shareMoney'+mycount);
    $("#shareMoney" + mycount + "_v").bind("blur",function(){
		reCalMoney()
    });
    reCalMoney();

    //�������ʹ���
    addDataToFeeType(feeTypeArr, "feeType" + mycount);
    var lastFeeType = $("select[id^='feeType']").eq(-2).val();
    if(lastFeeType){
		$("#feeType" + mycount).val(lastFeeType);
    }
	//���������
	resetRowNo('invbody');
}

/**********************ɾ����̬��*************************/
function mydel(obj,mytable)
{
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 1;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="payablescost[detail]['
				+ rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo
				+ '" />');
		//���������
		resetRowNo(mytable);
		//���¼�����
		reCalMoney();
	}
}

//����ų�ֵ
function resetRowNo(mytable){
	//���ñ��������
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

//����֤
function checkform(){
	//���ñ��������
	var tableLength = $("#invbody tr" ).length;
	var isDelTagObj;
	var shareMoney;
	var j = 0;

	for(var i = 1; i < tableLength ;i++){
		isDelTagObj = $("#isDelTag" + i);
		if(isDelTagObj.length == 0){
			j ++;
			//��̯������֤
			if($("#shareObjName" + i).val() == ""){
				alert('��'+ j +'�еķ�̯������Ϊ��');
				return false;
			}
			//��������
			if($("#feeType" + i).val() == ""){
				alert('��'+ j +'�еķ������Ͳ���Ϊ��');
				return false;
			}
			//��̯���
			shareMoney = $("#shareMoney" + i).val();
			if(shareMoney == "" || shareMoney == 0){
				alert('��'+ j +'�еķ�̯����Ϊ�ջ���0');
				return false;
			}
		}
	}

	//��̯���ܴ��ڸ���������
	var payapplyMoney =  $("#payapplyMoney").val()*1;
	var shareMoneyCount = $("#shareMoneyCount").val()*1;
	if(payapplyMoney < shareMoneyCount){
		alert('��̯���( '+ shareMoneyCount +' )���ܴ��ڸ���������( '+ payapplyMoney +' )');
		return false;
	}
}
