/**
 * ��֤�ı���
 * @param {} data
 */
function checkInput(arg1,arg2){
	$obj1 = $('#'+arg1);
	$obj2 = $('#'+arg2);
	if(isNaN($obj1.val())){
		$obj1.val($obj2.val());
	}else{
		if($obj2.val()< 0 ){
			if($obj1.val() >= 0){
				$obj1.val($obj2.val());
			}else if(Math.abs($obj1.val()) > Math.abs($obj2.val())){
				$obj1.val($obj2.val());
			}
		}else{
			if($obj1.val() <= 0){
				$obj1.val($obj2.val());
			}else if($obj1.val()*1 > $obj2.val()*1){
				$obj1.val($obj2.val());
			}
		}
	}
}

/**
 * ���������뵥
 * @param {} data
 */
function addStorage(data){
	var storageTable = $('#storageTable');
	var obj = eval("(" + data +")");
	var productsLength = 0;
	var str = '';
	var objStorage = $('#storageCount');
	var storageCount = $('#storageCount').val();
	if($('.storageList_' + obj.id).length > 0){
		$('.storageList_' + obj.id).remove();
	}else{
		productsLength = obj.products.length;
		for(var i = 1 ; i<= productsLength ; i++ ){
			storageCount++;
			objStorage.val(storageCount);
			$classCss = ((storageCount%2) == 0)?"tr_even":"tr_odd";
			j = i - 1;
			str +="<tr class='storageList_"+ obj.id +' ' + $classCss + "'>" +
					"<td>" +
						"<input type='hidden' name='storage["+storageCount+"][hookMainId]' value='" + obj.id + "'/>" +
						"<input type='hidden' name='storage["+storageCount+"][hookId]' value='" + obj.products[j].id + "'/>" +
						"<input type='text' name='storage["+storageCount+"][number]' value='"+ obj.products[j].unhookNumber +"' readonly='readonly'  size='12'/>" +
						"<input type='hidden' id='oldnumber"+storageCount+"' value='"+ obj.products[j].unhookNumber +"' />" +
						"<input type='hidden' name='storage["+storageCount+"][hookObjCode]' value='" + obj.storageAppCode + "'/>" +
						"<input type='hidden' name='storage["+storageCount+"][stockId]' id='storageId_"+storageCount+"' value='"+ obj.inStockId +"' />" +
						"<input type='hidden' name='storage["+storageCount+"][stockName]' value='"+ obj.inStockName +"' />" +
					"</td>";
			if(i != 1){
				str +="<td colspan='4'>" +
						"</td>"
			}else{
				str +="<td>" +
						formatDate(obj.createTime) +
					"</td>" +
					"<td>" +
						obj.storageAppCode +
						" <img src='images/closeDiv.gif' onclick='delStorage(" + obj.id + ")' title='ɾ����'/>" +
					"</td>" +
					"<td>" +
						obj.inStockName +
					"</td>" +
					"<td>" +
						obj.catchStatus +
					"</td>" ;
			}
			str +=
					"<td>" +
						obj.products[j].productName +
					"</td>" +
					"<td>" +
						obj.products[j].storageNum +
					"</td>" +
					"<td>" +
						obj.products[j].hookNumber +
					"</td>" +
					"<td>" +
						obj.products[j].hookAmount +
					"</td>" +
					"<td>" +
						obj.products[j].unhookNumber +
					"</td>" +
					"<td>" +
						obj.products[j].unhookAmount +
					"</td>" +
					"<td>" +
						obj.products[j].sequence +
						"<input type='hidden' name='storage["+storageCount+"][unHookNumber]' value='"+obj.products[j].unhookNumber +"'/>" +
						"<input type='hidden' name='storage["+storageCount+"][hookNumber]' value='"+obj.products[j].hookNumber +"'/>" +
						"<input type='hidden' name='storage["+storageCount+"][productName]' value='"+obj.products[j].productName +"'/>" +
						"<input type='hidden' name='storage["+storageCount+"][productNo]' value='"+obj.products[j].sequence +"'/>" +
						"<input type='hidden' name='storage["+storageCount+"][cost]' value='"+obj.products[j].cost +"'/>" +
						"<input type='hidden' name='storage["+storageCount+"][productId]' id='storagePN"+storageCount+"' value='"+obj.products[j].productId +"'/>" +
					"</td>" +
				"</tr>";
		}
		storageTable.append(str);
	}
}

/**
 * ����ѡ����ӷ�Ʊ
 * @param {} data
 */
function invAdd(data) {
	var invtable = $('#invTable');
	var obj = eval("(" + data +")");
	var str = '';
	var objInv = $('#invCount');
	var invCount = $('#invCount').val();
	if($('.invList_' + obj.id).length > 0){
		$('.invList_' + obj.id).remove();
	}else{
		productsLength = obj.products.length;
		for (var i = 1; i <= productsLength; i++) {
			invCount ++;
			objInv.val(invCount);
			j = i - 1;
			if(obj.products[j].hookNumber == 0){
				$unHookNumber = obj.products[j].number;
				$unHookAmount = obj.products[j].amount;
			}else{
				$unHookNumber = obj.products[j].unHookNumber;
				$unHookAmount = obj.products[j].unHookAmount;
			}
			if(obj.products[j].formType == "blue"){
				$price = obj.products[j].price;
			}else{
				$price = -obj.products[j].price;
			}
			$classCss = ((invCount%2) == 0)?"tr_even":"tr_odd";
			str += "<tr class='invList_"+ obj.id + " " + $classCss + "' title='"+obj.id+"'>" +
						"<td>" +
							"<input type='hidden' name='invpurdetail["+invCount+"][hookMainId]' value='" + obj.id + "'/>" +
							"<input type='hidden' name='invpurdetail["+invCount+"][hookId]' value='" + obj.products[j].id + "'/>" +
							"<input type='text' name='invpurdetail["+invCount+"][number]' id='number"+invCount+"' readonly='readonly' value='"+ $unHookNumber +"' size='12'/>" +
							"<input type='hidden' id='oldnumber"+invCount+"' value='"+ $unHookNumber +"' />" +
							"<input type='hidden' name='invpurdetail["+invCount+"][hookObjCode]' value='" + obj.objCode + "'/>" +
							"<input type='hidden' name='invpurdetail["+invCount+"][formType]' value='" + obj.formType + "'/>" +
						"</td>";
			if(i != 1){
				str +="<td colspan='3'>" +
						"</td>"
			}else{
				if(obj.formType == 'blue'){
					thisCode = obj.objCode;
				}else{
					thisCode = '<font color="red">'+ obj.objCode + '</font>';
				}
				str +="<td>" +
							formatDate(obj.createTime) +
						"</td>" +
						"<td align='left' width='12%'>" +
							thisCode +
							" <img src='images/closeDiv.gif' onclick='delInvPur(" + obj.id + ")' title='ɾ����'/>" +
						"</td>" +
						"<td width='10%'>" +
							obj.status +
						"</td>";
			}
			str += "<td>" +
						obj.products[j].productName +
						"<input type='hidden' name='invpurdetail["+invCount+"][productName]' value='"+obj.products[j].productName+"'/>" +
						"<input type='hidden' name='invpurdetail["+invCount+"][productId]' id='invpurPN"+invCount+"' value='"+obj.products[j].productId+"'/>" +
						"<input type='hidden' name='invpurdetail["+invCount+"][price]' id='price"+invCount+"' value='"+$price+"'/>" +
						"<input type='hidden' name='invpurdetail["+invCount+"][amount]' id='amount"+invCount+"' value='"+$unHookAmount+"'/>" +
					"</td>" +
					"<td>" +
						obj.products[j].number +
					"</td>" +
					"<td class='formatMoney'>" +
						moneyFormat2(obj.products[j].hookNumber) +
					"</td>" +
					"<td class='formatMoney'>" +
						moneyFormat2(obj.products[j].hookAmount) +
					"</td>" +
					"<td class='formatMoney'>" +
						moneyFormat2($unHookNumber) +
					"</td>" +
					"<td class='formatMoney'>" +
						moneyFormat2($unHookAmount) +
					"<td>" +
						obj.products[j].productNo +
						"<input type='hidden' name='invpurdetail["+invCount+"][hookNumber]' value='"+obj.products[j].hookNumber+"'/>" +
						"<input type='hidden' name='invpurdetail["+invCount+"][hookAmount]' value='"+obj.products[j].hookAmount+"'/>" +
						"<input type='hidden' name='invpurdetail["+invCount+"][unHookNumber]' value='"+$unHookNumber+"'/>" +
						"<input type='hidden' name='invpurdetail["+invCount+"][unHookAmount]' value='"+$unHookAmount+"'/>" +
						"<input type='hidden' name='invpurdetail["+invCount+"][productNo]' value='"+obj.products[j].productNo+"'/>" +
					"</td>" +
				"</tr>";
		}
		invtable.append(str);
	}
}

/**
 * ��ӷ��÷�Ʊ
 * @param {} data
 */
function invCostAdd(data){
	var costTable = $('#costTable');
	var obj = data.listStr;
	var str = '';
	var objCost = $('#invCostCount');
	var invCount = $('#invCostCount').val();
	if($('.costList_' + obj.id).length > 0){
		$('.costList_' + obj.id).remove();
	}else{
		invCount++;
		objCost.val(invCount);
		str += "<tr height='30px' class='costList_"+ obj.id + "'>" +
					"<td>" +
						obj.objCode +
						"<input type='hidden' name='invCost["+invCount+"][hookMainId]' value='" + obj.id + "'/>" +
						"<input type='hidden' name='invCost["+invCount+"][hookObjCode]' value='" + obj.objCode + "'/>" +
						"<input type='hidden' name='invCost["+invCount+"][amount]' id='amount"+invCount+"' value='"+obj.amount+"'/>" +
						"<input type='hidden' name='invCost["+invCount+"][hookAmount]' value='"+obj.amount+"'/>" +
						"<input type='hidden' name='invCost["+invCount+"][unHookAmount]' value='0'/>" +
					"</td>" +
					"<td>" +
						obj.supplierName +
					"</td>" +
					"<td>" +
						formatDate(obj.createTime) +
					"</td>" +
					"<td>" +
						moneyFormat2(obj.amount) +
					"</td>" +
					"<td></td>" +
				"</tr>";
		costTable.append(str);
	}
}

/**
 * ɾ������
 */
function delInvPur(id){
	if(confirm('ȷ��ɾ����')){
		$('.invList_' + id).remove();
	}
}

function delStorage(id){
	if(confirm('ȷ��ɾ����')){
		$('.storageList_' + id).remove();
	}
}

/**
 * ���ط�Ʊ����ѡ��
 */
$(function() {
	//��Ⱦtab
	$("ul.tabs").tabs("div.panes > div");

	//��Ⱦ���÷�Ʊ�������
	$("#invCost").yxcombogrid_invCost({
		gridOptions : {
			action : 'pageJsonGrid',
			showcheckbox : true,
			param : {
				"supplierId" : $('#supplierId').val(),
				"status" : "CGFPZT-WGJ"
			},colModel : [{
				display : 'id',
				name : 'id',
				hide : true
			}, {
				display : '��Ʊ���',
				name : 'objCode'
			}, {
				display : '��Ʊ����',
				name : 'objNo'
			},{
				display : '��Ӧ������',
				name : 'supplierName',
				width : 150
			}, {
				display : '����',
				name : 'createTime',
				process:function (v){
					return v.substr(0,10);
				},
				width : 80
			},{
				display : '���',
				name : 'amount',
				process :function(v){
					return moneyFormat2(v);
				},
				width : 80
			}, {
				display : '�б�����',
				name : 'listStr',
				hide : true
			}],
			event : {
				'row_click' : function(e, row, data) {
					invCostAdd(data);
				}
			}
		}
	});
})

//�ж��ַ����Ƿ���������
//�Ƿ���false
//�񷵻�true
function inArr(thisV,thisArr){
	var mark = true;
	for(var i=0;i<thisArr.length;i++){
		if(thisV == thisArr[i]){
			mark =  false;
		}
	}
	return mark;
}

/**
 * ����֤
 * @return {Boolean}
 */
function checkform(){
	//��֤�Ƿ���ڲɹ���Ʊ������嵥
	if($("input[id^='invpurPN']").length == 0){
		alert('��ѡ��ɹ���Ʊ');
		return false;
	}else if($("input[id^='storagePN']").length == 0){
		alert('��ѡ���⹺��ⵥ');
		return false;
	}

	var storageMark = "";
	//�ж��⹺��ⵥ�Ĳֿ��Ƿ�һֱ
	$.each($("input[id^='storageId_']"),function(i,n){
		if(i!= 0){
			if(storageMark != $(this).val()){
				alert('��ͬ�ֿ���⹺��ⲻ�ܹ���');
				return false;
			}
		}else{
			storageMark = $(this).val();
		}
	});

	//�жϲ�Ʒ�Ƿ��Ӧ  ԭ��  1^1 = 0
	/****************************************/
	var markKey = 0;
	var thisInvArr = new Array();

	$.each($("input[id^='invpurPN']"), function(i, n) {
		if(thisInvArr.length != 0  && inArr($(this).val(),thisInvArr)){
			thisInvArr[i] = $(this).val();
		}else if( inArr($(this).val(),thisInvArr)){
			thisInvArr[i] = $(this).val();
		}
	});

	for(var i = 0 ;i<thisInvArr.length ;i++){
		markKey^=thisInvArr[i] * 1;
	}
	var thisStoArr = new Array();
	$.each($("input[id^='storagePN']"), function(i, n) {
		if(thisStoArr.length != 0  && inArr($(this).val(),thisStoArr)){
			thisStoArr[i] = $(this).val();
		}else if( inArr($(this).val(),thisStoArr)){
			thisStoArr[i] = $(this).val();
		}
	});
	for(var i = 0 ;i<thisStoArr.length ;i++){
		markKey^=thisStoArr[i] * 1;
	}


	/*****************************************/
	if(markKey != 0){
		alert('δѡ�񵥾ݻ��߹�����Ʒ����ͬ��������ɹ���');
		return false;
	}
	return true;
}
