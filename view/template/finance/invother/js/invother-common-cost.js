//���÷�̯����
var shareTypeData=[
   {name:'���ŷ��� ',value:'���ŷ���'},
   {name:'��ͬ��Ŀ����',value:'��ͬ��Ŀ����'},
   {name:'�з�����',value:'�з�����'},
   {name:'��ǰ����',value:'��ǰ����'},
   {name:'�ۺ����',value:'�ۺ����'}
];

/**
 * ������������
 */
var tempObj=null;
var tempObjId=null;
function showFeeType($obj,$objId){
	tempObj=$obj;
	tempObjId=$objId;
	showThickboxWin('?model=finance_payablescost_payablescost&action=expense'
			+"&feeTypeId="+$objId.val()
			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000");
}
/**
 * �����������ͣ����ֵ
 */
function fillFeeType(id,$obj){
	tempObj.val($obj);
	tempObjId.val(id);
}

//�������÷�̯����
function changeShareType(shareType,$obj,$objCode,$objId){
	//�������
	$obj.val("");
	$objCode.val("");
	$objId.val("");
	//ȡ����Ⱦ
	$obj.yxcombogrid_projectall('remove');
	$obj.yxselect_dept('remove');
	$obj.yxcombogrid_rdprojectfordl('remove');
	$obj.yxcombogrid_rdprojectfordl('remove');
	changeTypeClear(shareType,$obj,$objCode,$objId);
}

//��ʼ����̯���󷽷�
/**
 * shareType ����
 * $obj   ��̯����
 * $objCode ��̯����code
 * $objId  ��̯����Id
 */
function initShare(shareType,$obj,$objCode,$objId){
	changeTypeClear(shareType,$obj,$objCode,$objId);
}

//�����ѡ������ TODO
function changeTypeClear(thisVal,$obj,$objCode,$objId){
	$obj.show();
	$obj.siblings().remove();
	$obj.attr('readonly','readonly');
	switch(thisVal){
		case '���ŷ���' :
			initDept($obj,$objId);break;
		case '��ͬ��Ŀ����' :
			//���ò���
			initEsmProject($obj,$objId,$objCode);break;
		case '�з�����' :
			//��ŷ��ò���
			initRdProject($obj,$objId,$objCode);break;
		case '��ǰ����' :
			//���۲���������Ⱦ
			initSale($obj,$objId,$objCode);break;
		case '�ۺ����' :
			//��Ⱦ��ͬ
			initContract($obj,$objId,$objCode);break;
		default : break;
	}
}

//��ʼ����Ա
function initPerson($obj,$objCode){
	$obj.yxselect_user({
		event:{
			select : function(e, row) {
				if(typeof(row) != 'undefined'){
					$objCode.val(row.val);
				}

			}
		}
	});
}

//��ʼ������
function initDept($obj,$objId){
	$obj.yxselect_dept({
		event:{
			selectReturn : function(e,row){
				$objId.val(row.dept.id);
			}
		}
	});
}
//��ʼ��������Ŀ
function initEsmProject($obj,$objId,$objCode){
	//������Ŀ��Ⱦ
	$obj.yxcombogrid_projectall({
		isDown : true,
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			param : {'contractType' : 'GCXMYD-01'},
			event : {
				'row_dblclick' : function(e,row,data) {
					$objId.val(data.id);
					$objCode.val(data.projectCode);
				}
			}
		}
	});
}

//�з���Ŀ��Ⱦ
function initRdProject($obj,$objId,$objCode){
	//�з���Ŀ��Ⱦ
	$obj.yxcombogrid_rdprojectfordl({
		isDown : true,
		isShowButton : false,
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			param : { 'is_delete' : 0 , 'project_typeNo' : '4'},
			event : {
				'row_dblclick' : function(e,row,data) {
					$objId.val(data.id);
					$objCode.val(data.projectCode)
				}
			}
		}
	});
}
//��ǰ����
function initSale($obj,$objId,$objCode){
	var data=$.ajax({
				url:'?model=finance_expense_expense&action=getSaleDept&detailType=4',
				type : 'get',
				async: false
			}).responseText;
	var dataArr=eval("("+data+")");
	var val=$obj.val();
	var name=$obj.attr('name');  //��ǰnameֵ
	var select=$("<select style='width:200px;' name='"+name+"' class='"+$obj.attr('id')+" select'></select>");
	var optionStr = "<option value=''></option>";
	for(i=0;i<dataArr.length;i++){
		if(val == dataArr[i].text){
			optionStr += "<option selected='selected' value='"+ dataArr[i].text +"'>" + dataArr[i].text +"</option>";
		}else{
			optionStr += "<option value='"+ dataArr[i].text +"'>" + dataArr[i].text +"</option>";
		}
	}
	select.append(optionStr);
	$obj.after(select);
	$obj.val(0);
	$obj.hide();

}
//�ۺ����
function initContract($obj,$objId,$objCode){
	//��Ⱦһ��ƥ�䰴ť
	var title = "���������Ķ�����ͬ�ţ�ϵͳ�Զ�ƥ�������Ϣ";
	var $button = $("<span class='search-trigger'  title='"+ title +"'>&nbsp;</span>");
	$button.click(function(){
		var check=getContractInfo($obj.val(),$objId,$objCode);
		if(!check)
			$obj.val('');
	})
	$obj.blur(function(){
		var check=getContractInfo($obj.val(),$objId,$objCode);
		if(!check)
			$obj.val('');
	});
	//�����հ�ť
	var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
	$button2.click(function(){
		$obj.val('');
	})
	$obj.attr('readonly','').after($button2).after($button).width($obj.width() - $button2.width()*2);
}

//�첽ƥ���ͬ��Ϣ
function getContractInfo(contractCode,$objId,$objCode){
	if(contractCode == ""){
		return false;
	}
	var data=$.ajax({
	    type: "POST",
	    url: "?model=contract_contract_contract&action=ajaxGetContract",
	    data: {"contractCode" : contractCode },
	    async: false
	}).responseText;
	if(data){
		var dataArr = eval("(" + data + ")");
		if(dataArr.thisLength*1 > 1){
			alert('����ϵͳ�д��ڡ�' + dataArr.thisLength + '��������Ϊ��' + contractName + '���ĺ�ͬ����ͨ����ͬ���ƥ���ͬ��Ϣ��');
			return false;
		}else if(dataArr.thisLength*1 == 1){
			$objId.val(dataArr.id);
			$objCode.val(dataArr.contractCode);
			alert('�ú�ͬ�ſ��ã�');
			return true;
		}
	    }else{
	    	alert('����û�в�ѯ����غ�ͬ��Ϣ');
	    	return false;
	    }
}

//�����̯��ϸ�ܽ��
function countDetailMoney(g,rowNum){
	var shareMoney=0;
	var shareMoneyDel=0;
	var shareMoneyArr = g.getCmpByCol('shareMoney');
	for(i=0;i<shareMoneyArr.length;i++){
		shareMoney+=($(shareMoneyArr[i]).val().replaceAll(',',''))*1;
	}

	return shareMoney;
}
//�ж�����һ�з�̯���Ŀɷ�̯�����
function applyMoney(g,rowNum){
	var applyMoney=$("#applyMoney").val();
	if(applyMoney==''||typeof(applyMoney)=='undefined'||isNaN(applyMoney)){
		applyMoney=0;
	}
	var money=Number(applyMoney);//��̯�ܽ��
	return money-countDetailMoney(g,rowNum);
}
//��̯��ϸ�ܼ�
function changeMoney(money){
	$("#payDetailMoney").val(Convert(money));  //��̯��ϸ�ܼ�
	$("#payDetailMoneyHidden").val(money);  //��̯��ϸ�ܼ�
}

//���ת��
function Convert(money) {
	if(money=='')money=0;
    var s = money; //��ȡС��������
    s += "";
    if (s.indexOf(".") == -1) s += ".0"; //���û��С���㣬�ں��油��С�����0
    if (/\.\d$/.test(s)) s += "0";   //�����ж�
    while (/\d{4}(\.|,)/.test(s))  //��������������滻
        s = s.replace(/(\d)(\d{3}(\.|,))/, "$1,$2"); //ÿ��3λ���һ��
    return s;
}

//����excel��������
function payImportExcel(){
	showThickboxWin('?model=contract_other_other&action=toPayImportExcel'
			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=250&width=450");
}

//��ʼ���ϼ���
function initInvcostCount(){
	$("#invcostTable tbody").after("<tr class='tr_count'><td colspan='3'>�ϼ�</td>"
		+ "<td colspan='2'></td>"
		+ "<td>"
		+"<input type='text' class='readOnlyTxtMiddleCount' style='width:200px;' id='payDetailMoney' readonly='readonly'/>"
		+"</td>"
		+ "</tr>");
}

//��ʼ����ذ�ť
function initButton(){
	//���밴ť
	$title=$("#invcostTable tr:first td");
	$title.html($title.html()+"<input type='button' value='���븶���̯����' class='txt_btn_a' style='margin-left:10px;' onclick='importPayCost();'/>");
}

//��̯�ϼƼ���
function countCost(){
	var invcostObj = $("#invcostTable");
	var trObj = invcostObj.yxeditgrid("getCmpByCol", "shareMoney");
	var countCost = 0;
	trObj.each(function(i,n){
		countCost = accAdd(countCost,this.value,2);
	});
	$("#payDetailMoney").val(moneyFormat2(countCost));
}