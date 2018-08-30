//�����豸���
function calResource(){
	//��ȡ����
	var number = $("#number").val();
	//��ȡ����
	var price = $("#price").val();
	//��ȡ����
	var useDays = $("#useDays").val();
	if( number != "" && price != "" && useDays != "" ){
		//���㵥���豸���
		var amount = accMul(number,price,2);
		//��������豸���
		var amount = accMul(amount,useDays,2);

		setMoney('amount',amount,2);
	}
}

//�����豸��� - �������� - ���� ������ʹ��
function calResourceBatch(rowNum){
	//�ӱ�ǰ���ַ���
	var beforeStr = "importTable_cmp";
	//��ȡ��ǰ����
	var number= $("#"+ beforeStr + "_number" + rowNum ).val();

	if($("#" + beforeStr + "_resourceName"  + rowNum ).val() != "" && number != ""){
		//��ȡ����
		var price = $("#" + beforeStr +  "_price" + rowNum + "_v").val();
		//��ȡ����
		var useDays = $("#" + beforeStr +  "_useDays" + rowNum ).val();
		//���㵥���豸���
		var amount = accMul(number,price,2);

		//��������豸���
		var amount = accMul(useDays,amount,2);

		setMoney(beforeStr +  "_amount" +  rowNum,amount,2);
	}
}

/**
 * Ԥ�ƽ����Ԥ�ƹ黹���ڲ���֤��ʹ�������ļ���
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
		alert("Ԥ�ƿ�ʼ���ܱ�Ԥ�ƽ���ʱ����");
		$t.value = "";
		return false;
	}
	$("#useDays").val(s);
}

//�ύ�����ı�����ֵ
function setAudit(thisType){
	$("#audit").val(thisType);
}

//�ύȷ�ϸı�����ֵ
function setConfirm(thisType){
	$("#confirmStatus").val(thisType);
}

//ȷ����֤
function confirmCheck(){
	//��֤��ϵ�绰
	var mobile = $("#mobile").val();
	if(mobile != ""){
		var regex1 = /(^0?[1][34578][0-9]{9}$)/;
		if(!regex1.test(mobile)){
			var regex2 = /^(0[0-9]{2,3}\-)?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/;
			if(!regex2.test(mobile)){
				alert("��������Ч���ֻ������绰���롣����ǵ绰���룬���ź͵绰����֮������-�ָ�磺010-29292929");
				return false;
			}
		}
	}
	var objGrid = $("#importTable");
	//������֤
	var dateConfirm = 0;
	var resourceIdArr = objGrid.yxeditgrid('getCmpByCol','resourceId');
	if(resourceIdArr.length == 0){
		alert("�豸��Ϣ����Ϊ��");
		return false;
	}
	resourceIdArr.each(function(){
    	var rowNum = $(this).data("rowNum");//��ǰ����

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
    	alert("�������ڲ���������������");
    	return false;
    }
    if(dateConfirm == 2){
    	alert("�黹���ڲ���������������");
    	return false;
    }
    var confirmStatus = $("#confirmStatus").val();
    var confirmCheckType = $("#confirmCheckType").val();//�����Ƿ�Ϊȷ��ҳ��
	if(confirmStatus == 1 && confirmCheckType != 'confirm'){//������༭ҳ����֤
		//��鵱ǰԱ���Ƿ�����豸����������¼
		var	msg = $.ajax({
			type: "POST",
			url: "?model=engineering_resources_lock&action=checkLock",
			dataType:'html',
			async:false
		}).responseText;
		if(msg == 1){
			alert('�����豸��������Ȩ����ʱ����������������豸���С��黹�������衿��ת�衿��������������ϵ�豸����Ա');
			return false;
		}
		if(confirm('ȷ���ύ������')){
			return true;
		}else{
			return false;
		}
	}else if(confirmStatus == 2){//ȷ��ҳ����֤
        //�Ƿ�ѡ������
        var isChecked = false;
        //���ϺϷ�����֤
        var isAllConfirm = true;
        resourceIdArr.each(function(){
            if(objGrid.yxeditgrid("getCmpByRowAndCol",$(this).data("rowNum"),"isChecked").attr('checked')){//ֻ��֤��ѡ������
            	isChecked = true;
                if(this.value == "0"){
                    isAllConfirm = false;
                    return false;
                }
            }
        });
        if(isChecked == false){
            alert('�����ٹ�ѡһ���豸����ȷ��');
            return false;
        }
        if(isAllConfirm == false){
            alert('����δȷ�ϵ�����,�����ύ��');
            return false;
        }
    }
}

//��ȡʡ��
function getProvince() {
	var responseText = $.ajax({
		url : 'index1.php?model=system_procity_province&action=listJsonSort',
		type : "POST",
		async : false
	}).responseText;
	return eval("(" + responseText + ")");
}

/**
 * ���ʡ��������ӵ�������
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
* ʡ�ݸı�ʱ�����Ĳ��ָ�ֵ
*/
function setPlace(){
	$('#place').val($("#placeId").find("option:selected").attr("title"));
}

//����Ԥ�Ƴɱ����ܺ�
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

//�鿴���������¼
function viewLog(id){
	showOpenWin("?model=engineering_baseinfo_resourceapplylog"
			+ "&applyId="+id
			,1,700,1350);
}