
function myload(){
	//�Բɹ���ͬ�������豸����������֤������ܳ����ɹ�������豸��������������
	$(".amount").bind("change",function(){
		var thisVal = parseInt( $(this).val() );
		var nextVal = parseInt( $(this).next().val() );
		if(isNaN(this.value.replace(/,|\s/g,''))){
			alert('����������');
				$(this).attr("value",nextVal);
		}
		if(thisVal>nextVal){
			alert("�벻Ҫ���������������� "+nextVal);
			$(this).attr("value",nextVal);
		}else if(thisVal<1){
			alert("�벻Ҫ����0����");
				$(this).attr("value",thisVal);
			$(this).attr("value",nextVal);
		}
	});
	//�����뵥�۽�����֤
	$(".price").bind("change",function(){
		var thisVal = parseInt( $(this).val() );
		var nextVal = parseInt( $(this).next().val() );
		if(isNaN(this.value.replace(/,|\s/g,''))){
			alert('����������');
				$(this).attr("value",nextVal);
				$(this).focus();
		}
		 if(thisVal<0){
			alert("�벻Ҫ���븺��");
				$(this).attr("value",thisVal);
			$(this).attr("value",nextVal);
				$(this).focus();
		}
	});

}

//����ɹ������ܽ��
function sumAllMoney(){
	var tab = document.getElementById("invbody") ;
      //�������
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

//����ɹ���������
function sumPrice(i){
		var thisPrice=parseFloat($("#applyPrice"+i).val());//��˰����
		var taxRate=parseInt($("#taxRate"+i).val());//˰��
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

//����ɹ�������˰����
function sumApplyPrice(i){
		var price=parseFloat($("#price"+i).val());//��˰����
		var taxRate=parseInt($("#taxRate"+i).val());//˰��

		if(price!=undefined&&price!=""){
			applyPrice=accMul(price,taxRate*0.01+1,6);
		}
		$("#applyPrice"+i).val(applyPrice);
		sumAllMoney();
}

//�޸Ĳɹ�����ʱ����ɹ������ܽ��
function sumAllMoneyInEdit(obj){

	var tab = document.getElementById("invbody") ;
      //�������
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
	//���ݻ��ʼ��㱾��
	conversion();
}


//���ɹ���ֱͬ���ύ����
function toSubmit(){

	var tab = document.getElementById("invbody") ;
      //�������
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


//������ݲɹ��������ɹ�����ֱ���ύ����
function toSubmitByOrder(){

	var tab = document.getElementById("invbody") ;
      //�������
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

//�ɲɹ������´�ɹ�������ֱ���ύ����
function toSubmitByTask(){
	var orderType=$("#orderType").val();
	document.getElementById('form1').action = "index1.php?model=purchase_contract_purchasecontract&action=add&act=app&type=task&orderTypes="+orderType;
}

//���ʲ��ɹ������´�ɹ�������ֱ���ύ����
function toSubmitByAssetTask(){
	var orderType=$("#orderType").val();
	document.getElementById('form1').action = "index1.php?model=purchase_contract_purchasecontract&action=addAssetOrder&act=asset";
}




//������Ĳɹ���ͬ�ύ����
function commitToApproval(){
	document.getElementById('form1').action = "index1.php?model=purchase_change_contractchange&action=change&act=app";
}

//���޸ı����ͬ��ҳ�潫��ͬ�ύ����
function editToApproval(){
	document.getElementById('form1').action = "index1.php?model=purchase_change_contractchange&action=editchange&act=app";
}

//���޸�ҳ���ύ����
function submitAudit(){
	document.getElementById('form2').action="index1.php?model=purchase_contract_purchasecontract&action=editContract&act=audit";
}



// ��������
$(function() {
	$('#dateHope').bind('focusout', function() {

		var thisDate = $(this).val();
        dateHope = $('#dateHope').val();
		$.each($(':input[id^="equDateHope"]'), function(i, n) {
			$(this).val(thisDate);
		})
	});


});



// �����������ʺŵļ�������
$(function() {
	$('#suppAccount').yxcombogrid_suppAccount({
		isFocusoutCheck:false,
		gridOptions : {
			reload : true,
			showcheckbox : false,
			// ���ݹ�Ӧ��ID��ѡ�еĿ������У����˳���Ӧ�������ʺ�
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
					$("#suppBankName").val(data.depositbank_name);		//�����е������ֵ�ת�������ĺ��滻��Ӧ��ֵ������ҳ����ʾ
					$("#suppBank").val(data.depositbank);				//���е������ֵ���룬���������ڱ�������ʱд�����ݿ�
				}
			}
		}
	});

});


 /**
 *
 * @param {} mycount
 * ��Ⱦ�����ʺ������б�
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
/**********************ɾ����̬��*************************/
function mydel(obj, mytable) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
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

//���ύʱ��������Ϣ��У��
function checkAllData(){
	var booble=true;
	$("input.amount").each(function(){
		if ($(this).val()==""||$(this).val()==0) {
			alert("����������,����Ϊ�ջ���С��1");
			$(this).focus();
			booble=false;
			return false;
		}
	});
	$("input.productId").each(function(){
		if ($(this).val()=="") {
			alert("������Ϣ����������ѡ������");
			$(this).focus();
			booble=false;
			return false;
		}
	});
	if(!$("input.amount").length>0){   //�ж��Ƿ�ѡ��������
		alert("�ɹ������嵥Ϊ��,��ѡ�����ϡ�");
		booble=false;
	}

	//����������������
	if($("#isNeedStampYes").attr('checked') == true){
		if($("#stampType").val() == ""){
			alert('��ѡ���������');
			return false;
		}

		var upList = strTrim($("#uploadfileList").html());
		//����������֤
		if(upList == "" || upList == "�����κθ���"){
			alert('�������ǰ��Ҫ�ϴ���ͬ����!');
			return false;
		}
	}

	return booble;

}



/***************** ������������ ********************/
//����ѡ���ж�
function changeRadio(){
	//����������֤
	if($("#uploadfileList").html() == "" || $("#uploadfileList").html() == "�����κθ���"){
		alert('�������ǰ��Ҫ�ϴ���ͬ����!');
		$("#isNeedStampNo").attr("checked",true);
		return false;
	}

	//��ʾ������
	if($("#isNeedStampYes").attr("checked")){
		$("#radioSpan").show();
	}else{
		$("#radioSpan").hide();
	}
}

