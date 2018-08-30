/**
 * ��д�ӱ��ͷ
 */
function tableHead(){
	var trHTML =  '';
	var detailRows = ['���޿�ʼ����','���޽�������','�����������ɱ�','����۸�','�������Խ��','������Ա'];
	var detailArr = ['��������','�۸�Ա�','�����������'];
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

//����������
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
 * ��Ŀ��ű����ж�
 */
function changeSelect(){
	$("#projectCode").yxcombogrid_rdprojectfordl("remove").yxcombogrid_esmproject("remove").val("");
	$("#projectName").val("");
	$("#projectType").val("");
	$("#projectId").val("");

	changeSelectClear();
}

//��ʼ����Ŀѡ��
function changeSelectClear(){
	$e = $("#outsourceType").find("option:selected").attr("e1");
	$val = $("#outsourceType").find("option:selected").val();
	if($e==1){
		if($val == 'HTWB02'){
			//�з���Ŀ��Ⱦ��
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
			//������Ŀ��Ⱦ
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


//ѡ�����д��ۺ󴥷��¼�
function entrustFun(thisVal, quiet){
	if(thisVal == '1'){
        if (quiet) {
            $("#bank").val('�Ѹ���');
            $("#bank").attr('class','readOnlyTxtNormal');
            $("#bank").attr('readonly',true);
            $("#account").val('�Ѹ���');
            $("#account").attr('class','readOnlyTxtNormal');
            $("#account").attr('readonly',true);
        } else {
            if(confirm('ѡ���Ѹ���󣬲����ɳ��ɽ��п���֧����ȷ��ѡ����')){
                $("#bank").val('�Ѹ���');
                $("#bank").attr('class','readOnlyTxtNormal');
                $("#bank").attr('readonly',true);
                $("#account").val('�Ѹ���');
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
 * �Ƿ���±����ж�
 */
function changeRadio(){
	//����������֤
//	if($("#uploadfileList").html() == "" || $("#uploadfileList").html() == "�����κθ���"){
//		alert('�������ǰ��Ҫ�ϴ���ͬ����!');
//		$("#isNeedStampNo").attr("checked",true);
//		return false;
//	}

	//��ʾ������
	if($("#isNeedStampYes").attr("checked")){
		$("#radioSpan").show();
		//��ֹ�ظ������������
		if($("#stampType").yxcombogrid_stampconfig('getIsRender') == true) return false;

		//����������Ⱦ
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

		//����������Ⱦ
		var stampTypeObj = $("#stampType");
		stampTypeObj.yxcombogrid_stampconfig('remove');
		stampTypeObj.val('');
	}
}

/**
 * ���¸���ѡ��
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


//��ʼ��
$(function(){
	changeSelectClear();

	//��ʼ�����㷽ʽ
	changePayTypeFun();
});

//���㷽ʽ
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


//��ʾ����������Ϣ
function showPayapplyInfo(thisObj){
	if(thisObj.checked == true){
		thisObj.value = 1;
		$(".payapplyInfo").show();

		//���ù�������
		$("#feeDeptName").yxselect_dept({
			hiddenId : 'feeDeptId',
			unDeptFilter: ($('#unDeptFilter').val() != undefined)? $('#unDeptFilter').val() : ''
		});

		//���ֳ�ʼ��
		var currencyCodeObj = $("#currencyCode");
		if(currencyCodeObj.length > 0){
			// ���ұ�
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
		//���ù�������
		$("#feeDeptName").yxselect_dept('remove');
		//���ֳ�ʼ��
		$("#currency").yxcombogrid_currency('remove');
	}
}


//����֤���� - ��Ϊ��Ŀ���ʱ,��Ҫ��д��Ŀ���
function checkForm(){
	if($("#outsourceType").find("option:selected").attr("e1") == 1){
		if($("#projectCode").val() == ""){
			alert('��Ŀ��ű�����д');
			return false;
		}
	}

	//��������
	var isNeedPayapplyObj = $("#isNeedPayapply");
	if( isNeedPayapplyObj.length == 1 && isNeedPayapplyObj.attr("checked") == true){

		//������
		var applyMoney = $("#applyMoney").val()*1;
		if(applyMoney == 0 || applyMoney == ""){
			alert('�����������Ϊ0���');
			return false;
		}

		//��������
		var formDate = $("#formDate").val();
		if(formDate == ""){
			alert('�����������ڲ���Ϊ��');
			return false;
		}

		//���ù�������
		var feeDeptName = $("#feeDeptName").val();
		if(feeDeptName == ""){
			alert('���ù������Ų���Ϊ��');
			return false;
		}

		innerPayType = $("#payType").find("option:selected").attr("e1");
		if(innerPayType == 1){

			//�տ�����
			var bank = $("#bank").val();
			if(bank == ""){
				alert('�տ����в���Ϊ��');
				return false;
			}

			//�տ��˺�
			var account = $("#account").val();
			if(account == ""){
				alert('�տ��˺Ų���Ϊ��');
				return false;
			}
		}

		//����
		if($("#currency").val() == ""){
			alert('����д�������');
			return false;
		}

		//����ص�
		if($("#place").val() == ""){
			alert('����д����ص�(ʡ/��)');
			return false;
		}

		if($("#payDesc").val() == ""){
			alert('����д����˵��');
			return false;
		}

		//������;
		var remark = strTrim($("#remark").val());
		if(remark == ""){
			alert('������;����Ϊ��');
			return false;
		}else{
			if(remark.length > 10){
				alert('�뽫������;������Ϣ������10���ֻ�10��������,��ǰ����Ϊ'+ remark.length +" ����");
				return false;
			}
		}
	}

	if($("#isNeedStampYes").attr('checked') == true){
		//��������
		if($("#stampType").val() == ""){
			alert('��ѡ��һ�ָ�������');
			return false;
		}

		var upList = strTrim($("#uploadfileList").html());
		//����������֤
		if(upList == "" || upList == "�����κθ���"){
			alert('�������ǰ��Ҫ�ϴ���ͬ����!');
			return false;
		}
	}

	//��ֹ�ظ��ύ��֤
//	$("input[type='submit']").attr('disabled',true);

	return true;
}

//�������֤���� - ��Ϊ��Ŀ���ʱ,��Ҫ��д��Ŀ���
function checkFormChange(){
	//��֤�����Ƿ����ı�
	var rs = checkWithoutIgnore('��ͬ��Ҫ��Ϣδ�����ı�');
	if(rs == false){
		return false;
	}

	if($("#outsourceType").find("option:selected").attr("e1") == 1){
		if($("#projectCode").val() == ""){
			alert('��Ŀ��ű�����д');
			return false;
		}
	}

	if($("#changeReason").val() == ""){
		alert('���ԭ�������д');
		return false;
	}

	if($("#isNeedStampYes").attr('checked') == true){
		//��������
		if($("#stampType").val() == ""){
			alert('��ѡ��һ�ָ�������');
			return false;
		}

		var upList = strTrim($("#uploadfileList").html());
		//����������֤
		if(upList == "" || upList == "�����κθ���"){
			alert('�������ǰ��Ҫ�ϴ���ͬ����!');
			return false;
		}
	}

	//��ֹ�ظ��ύ��֤
//	$("input[type='submit']").attr('disabled',true);

	return true;
}


//�жϿ�ʼʱ�䲻С�ڽ���ʱ��
function checkTime(beginDate,endDate){
	var beginDate = $("#beginDate").val();
	var endDate = $("#endDate").val();
	var s = DateDiff(beginDate,endDate); //���㿪ʼʱ�䵽����ʱ�������
	if(s < 0 ){
		alert('��ͬ�Ľ������ڲ������ڿ�ʼ���ڣ�������');
		$("#endDate").val("");
		$("#endDate").focus();
	}		
}

function checkTime_begin(beginDate,endDate){
	var beginDate = $("#beginDate").val();
	var endDate = $("#endDate").val();
	var s = DateDiff(beginDate,endDate); //���㿪ʼʱ�䵽����ʱ�������
	if(s < 0 ){
		alert('��ͬ�Ľ������ڲ������ڿ�ʼ���ڣ�������');
		$("#beginDate").val("");
		$("#beginDate").focus();
	}		
}


//����ʱ�ύ���� -- ��������
function auditDept(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=contract_outsourcing_outsourcing&action=addDept&act=audit";
	}else{
		document.getElementById('form1').action="?model=contract_outsourcing_outsourcing&action=addDept";
	}
}

//�ı��ͬ����,�����������ں�ͬ�����������
function checkApplyMoney(){
	var orderMoneyObj = $("#orderMoney");
	var applyMoneyObj = $("#applyMoney");

	if(orderMoneyObj.val()*1 < applyMoneyObj.val()*1){
		alert('��ͬ����С�ڸ���������');
		$("#applyMoney").val("");
		$("#applyMoney_v").val("");
	}
}

//��������ģ��
function saveTemplate(){
	var costTypeArr = $("#projectRentalTbody tr");
	if(costTypeArr.length == 0)
		alert('û����ϸ��');
	else{
		var temArr = [];
		costTypeArr.each(function(i,n){
			var rowNum = $(this).attr('rowNum');//�к�

			//����parentֵ
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
					alert('����ɹ�');
				}else{
					alert('����ʧ��');
				}
			}
		});
	}
}