//var pageAttr;//��ǰҳ�湦������ add/edit/view
var datadictArr = [];//���������ֵ�
var parentDatadictArr = [];//�����ϼ�����

//��ʼ����������
function initProjectRental(){
	if(checkCanInit() == false){
		return false;
	}

	if(pageAttr == 'add'){//����ʱ
		initProjectRentalAdd();
	}else if(pageAttr == "edit"){
		initProjectRentalEdit();
	}else{
		initProjectRentalView();
	}
}

//��֤�Ƿ�ɳ�ʼ��
function checkCanInit(){
	//��ʼ��ʱ��֤�����Ƿ����
	try{
		pageAttr;
	}catch(e){
		alert('�޷���ʼ������/�ְ���Ϣ�����ȶ�����룡');
		return false;
	}
	return true;
}

//����
function initProjectRentalAdd(){
	var projectRentalTbObj = $("#projectRentalTb");
	if(projectRentalTbObj.children().length == 0){
		//ajax��ȡ���۸�����
		var responseText = $.ajax({
			url:"?model=contract_outsourcing_projectrental&action=getAddPage",
			type : "POST",
			async : false
		}).responseText;
		projectRentalTbObj.html(responseText);

		//ǧ��λ��Ⱦ
		formatProjectRentalMoney();
	}
}

//�༭
function initProjectRentalEdit(){
	var projectRentalTbObj = $("#projectRentalTb");
	if(projectRentalTbObj.children().length == 0){
		//ajax��ȡ���۸�����
		var responseText = $.ajax({
			url:"?model=contract_outsourcing_projectrental&action=getEditPage",
			data : {mainId : $("#id").val()},
			type : "POST",
			async : false
		}).responseText;
		projectRentalTbObj.html(responseText);

		//ǧ��λ��Ⱦ
		formatProjectRentalMoney();
	}
}

//�鿴
function initProjectRentalView(){
	var projectRentalTbObj = $("#projectRentalTb");
	if(projectRentalTbObj.children().length == 0){
		//ajax��ȡ���۸�����
		var responseText = $.ajax({
			url:"?model=contract_outsourcing_projectrental&action=getViewPage",
			data : {mainId : $("#id").val()},
			type : "POST",
			async : false
		}).responseText;
		projectRentalTbObj.html(responseText);

		//ǧ��λ��Ⱦ
		formatProjectRentalMoney();
	}
}

//���� - ��
function addProjectRentalRow(){
	//����+1
	var projectRentalRowNumObj = $("#projectRentalRowNum");
	var newRowNum = projectRentalRowNumObj.val()*1 + 1;
	projectRentalRowNumObj.val(newRowNum);

	//��ȡ�����ֵ�
	var parentArr = getDatadictArr('WBHTFYX');
	var parentOptionStr = getOptionStr(parentArr);
    var firstOption = parentArr[0];//��һ��ѡ����,���ڼ����׸���ϸ����

    //���������ֵ���չ�ֶ�1�ж϶���ѡ����ѡ��ǿ���
    var costTypeStr = "";
	if(firstOption.expand1 == "1"){//ѡ��
        var costTypeArr = getDatadictArr(firstOption.dataCode);//��ȡ��ϸ����
        var costTypeOptionStr = getOptionStr(costTypeArr);//���ظ���ѡ��
        var costTypeStr =
        	'<select name="outsourcing[projectRental]['+newRowNum+'][costType]" id="costType'+newRowNum+'" style="width:65px;">'+costTypeOptionStr+'</select>' +
        	'<input type="hidden" name="outsourcing[projectRental]['+newRowNum+'][isCustom]" id="isCustom'+newRowNum+'" value="0"/>'
    	;
	}else{//����
        var costTypeStr =
        	'<input name="outsourcing[projectRental]['+newRowNum+'][costTypeName]" id="costTypeName'+newRowNum+'" class="rimless_textB" style="width:65px;"/>' +
        	'<input type="hidden" name="outsourcing[projectRental]['+newRowNum+'][isCustom]" id="isCustom'+newRowNum+'" value="1"/>'
        ;
	}

	//���������ֵ���չ�ֶ�2�жϽ��¼�뷽ʽ
	if(firstOption.expand2 == "1"){
		var moneyStr =
			'<td class="detailTd"><input name="outsourcing[projectRental]['+newRowNum+'][supplier1][price]" id="supplier1_price'+newRowNum+'" onblur="countDetail('+newRowNum+',1);" class="rimless_textB formatMoney" style="width:65px;"></td>' +
			'<td class="detailTd"><input name="outsourcing[projectRental]['+newRowNum+'][supplier1][number]" id="supplier1_number'+newRowNum+'" onblur="countDetail('+newRowNum+',1);" class="rimless_textB" style="width:35px;"></td>' +
			'<td class="detailTd"><input name="outsourcing[projectRental]['+newRowNum+'][supplier1][period]" id="supplier1_period'+newRowNum+'" onblur="countDetail('+newRowNum+',1);" class="rimless_textB" style="width:35px;"></td>' +
			'<td class="amountTd"><input name="outsourcing[projectRental]['+newRowNum+'][supplier1][amount]" id="supplier1_amount'+newRowNum+'" onblur="countDetail('+newRowNum+',1);" class="rimless_textB formatMoney" style="width:65px;"></td>' +
			'<td class="detailTd"><input name="outsourcing[projectRental]['+newRowNum+'][supplier2][price]" id="supplier2_price'+newRowNum+'" onblur="countDetail('+newRowNum+',2,1);" class="rimless_textB formatMoney" style="width:65px;"></td>' +
			'<td class="detailTd"><input name="outsourcing[projectRental]['+newRowNum+'][supplier2][number]" id="supplier2_number'+newRowNum+'" onblur="countDetail('+newRowNum+',2);" class="rimless_textB" style="width:35px;"></td>' +
            '<td class="detailTd"><input name="outsourcing[projectRental]['+newRowNum+'][supplier2][period]" id="supplier2_period'+newRowNum+'" onblur="countDetail('+newRowNum+',2);" class="rimless_textB" style="width:35px;"></td>' +
            '<td class="amountTd"><input name="outsourcing[projectRental]['+newRowNum+'][supplier2][amount]" id="supplier2_amount'+newRowNum+'" onblur="countDetail('+newRowNum+',2);" class="rimless_textB formatMoney" style="width:65px;"></td>' +
            '<td class="detailTd"><input name="outsourcing[projectRental]['+newRowNum+'][supplier3][price]" id="supplier3_price'+newRowNum+'" onblur="countDetail('+newRowNum+',3,1);" class="rimless_textB formatMoney" style="width:65px;"></td>' +
            '<td class="detailTd"><input name="outsourcing[projectRental]['+newRowNum+'][supplier3][number]" id="supplier3_number'+newRowNum+'" onblur="countDetail('+newRowNum+',3);" class="rimless_textB" style="width:35px;"></td>' +
            '<td class="detailTd"><input name="outsourcing[projectRental]['+newRowNum+'][supplier3][period]" id="supplier3_period'+newRowNum+'" onblur="countDetail('+newRowNum+',3);" class="rimless_textB" style="width:35px;"></td>' +
            '<td class="amountTd"><input name="outsourcing[projectRental]['+newRowNum+'][supplier3][amount]" id="supplier3_amount'+newRowNum+'" onblur="countDetail('+newRowNum+',3);" class="rimless_textB formatMoney" style="width:65px;"></td>' +
            '<td class="detailTd"><input name="outsourcing[projectRental]['+newRowNum+'][supplier4][price]" id="supplier4_price'+newRowNum+'" onblur="countDetail('+newRowNum+',4,1);" class="rimless_textB formatMoney" style="width:65px;"></td>' +
            '<td class="detailTd"><input name="outsourcing[projectRental]['+newRowNum+'][supplier4][number]" id="supplier4_number'+newRowNum+'" onblur="countDetail('+newRowNum+',4);" class="rimless_textB" style="width:35px;"></td>' +
            '<td class="detailTd"><input name="outsourcing[projectRental]['+newRowNum+'][supplier4][period]" id="supplier4_period'+newRowNum+'" onblur="countDetail('+newRowNum+',4);" class="rimless_textB" style="width:35px;"></td>' +
            '<td class="amountTd"><input name="outsourcing[projectRental]['+newRowNum+'][supplier4][amount]" id="supplier4_amount'+newRowNum+'" onblur="countDetail('+newRowNum+',4);" class="rimless_textB formatMoney" style="width:65px;"></td>' +
            '<td>' +
            	'<input name="outsourcing[projectRental]['+newRowNum+'][remark]" class="rimless_textB" style="width:80px;">' +
            	'<input type="hidden" name="outsourcing[projectRental]['+newRowNum+'][isDetail]" id="isDetail'+newRowNum+'" value="1"/>' +
			'</td>';
	}else{
		var moneyStr =
			'<td class="detailTd" style="display:none;"><input name="outsourcing[projectRental]['+newRowNum+'][supplier1][price]" id="supplier1_price'+newRowNum+'" onblur="countDetail('+newRowNum+',1);" class="rimless_textB formatMoney" style="width:65px;"></td>' +
			'<td class="detailTd" style="display:none;"><input name="outsourcing[projectRental]['+newRowNum+'][supplier1][number]" id="supplier1_number'+newRowNum+'" onblur="countDetail('+newRowNum+',1);" class="rimless_textB" style="width:35px;"></td>' +
			'<td class="detailTd" style="display:none;"><input name="outsourcing[projectRental]['+newRowNum+'][supplier1][period]" id="supplier1_period'+newRowNum+'" onblur="countDetail('+newRowNum+',1);" class="rimless_textB" style="width:35px;"></td>' +
			'<td class="amountTd" colspan="4"><input name="outsourcing[projectRental]['+newRowNum+'][supplier1][amount]" id="supplier1_amount'+newRowNum+'" onblur="countDetail('+newRowNum+',1);" class="rimless_textB formatMoney" style="width:230px;"></td>' +
			'<td class="detailTd" style="display:none;"><input name="outsourcing[projectRental]['+newRowNum+'][supplier2][price]" id="supplier2_price'+newRowNum+'" onblur="countDetail('+newRowNum+',2,1);" class="rimless_textB formatMoney" style="width:65px;"></td>' +
			'<td class="detailTd" style="display:none;"><input name="outsourcing[projectRental]['+newRowNum+'][supplier2][number]" id="supplier2_number'+newRowNum+'" onblur="countDetail('+newRowNum+',2);" class="rimless_textB" style="width:35px;"></td>' +
            '<td class="detailTd" style="display:none;"><input name="outsourcing[projectRental]['+newRowNum+'][supplier2][period]" id="supplier2_period'+newRowNum+'" onblur="countDetail('+newRowNum+',2);" class="rimless_textB" style="width:35px;"></td>' +
            '<td class="amountTd" colspan="4"><input name="outsourcing[projectRental]['+newRowNum+'][supplier2][amount]" id="supplier2_amount'+newRowNum+'" onblur="countDetail('+newRowNum+',2);" class="rimless_textB formatMoney" style="width:230px;"></td>' +
            '<td class="detailTd" style="display:none;"><input name="outsourcing[projectRental]['+newRowNum+'][supplier3][price]" id="supplier3_price'+newRowNum+'" onblur="countDetail('+newRowNum+',3,1);" class="rimless_textB formatMoney" style="width:65px;"></td>' +
            '<td class="detailTd" style="display:none;"><input name="outsourcing[projectRental]['+newRowNum+'][supplier3][number]" id="supplier3_number'+newRowNum+'" onblur="countDetail('+newRowNum+',3);" class="rimless_textB" style="width:35px;"></td>' +
            '<td class="detailTd" style="display:none;"><input name="outsourcing[projectRental]['+newRowNum+'][supplier3][period]" id="supplier3_period'+newRowNum+'" onblur="countDetail('+newRowNum+',3);" class="rimless_textB" style="width:35px;"></td>' +
            '<td class="amountTd" colspan="4"><input name="outsourcing[projectRental]['+newRowNum+'][supplier3][amount]" id="supplier3_amount'+newRowNum+'" onblur="countDetail('+newRowNum+',3);" class="rimless_textB formatMoney" style="width:230px;"></td>' +
            '<td class="detailTd" style="display:none;"><input name="outsourcing[projectRental]['+newRowNum+'][supplier4][price]" id="supplier4_price'+newRowNum+'" onblur="countDetail('+newRowNum+',4,1);" class="rimless_textB formatMoney" style="width:65px;"></td>' +
            '<td class="detailTd" style="display:none;"><input name="outsourcing[projectRental]['+newRowNum+'][supplier4][number]" id="supplier4_number'+newRowNum+'" onblur="countDetail('+newRowNum+',4);" class="rimless_textB" style="width:35px;"></td>' +
            '<td class="detailTd" style="display:none;"><input name="outsourcing[projectRental]['+newRowNum+'][supplier4][period]" id="supplier4_period'+newRowNum+'" onblur="countDetail('+newRowNum+',4);" class="rimless_textB" style="width:35px;"></td>' +
            '<td class="amountTd" colspan="4"><input name="outsourcing[projectRental]['+newRowNum+'][supplier4][amount]" id="supplier4_amount'+newRowNum+'" onblur="countDetail('+newRowNum+',4);" class="rimless_textB formatMoney" style="width:230px;"></td>' +
            '<td>' +
            	'<input name="outsourcing[projectRental]['+newRowNum+'][remark]" class="rimless_textB" style="width:80px;">' +
            	'<input type="hidden" name="outsourcing[projectRental]['+newRowNum+'][isDetail]" id="isDetail'+newRowNum+'" value="0"/>' +
			'</td>';
	}

	//������չ�ֶ�3�жϴ˷����Ƿ�Ϊ�������
	var isServerCost = firstOption.expand3 == "1" ? 1 : 0;
	var isServerCostStr ='<input type="hidden" name="outsourcing[projectRental]['+newRowNum+'][isServerCost]" id="isServerCost'+newRowNum+'" value="'+isServerCost+'"/>';

	//��
	var str = '<tr id="tr'+newRowNum+'" rowNum="'+newRowNum+'">' +
			'<td><img src="images/removeline.png" onclick="delProjectRentalRow('+newRowNum+');" title="ɾ����"/>'+isServerCostStr+'</td>' +
			'<td><select name="outsourcing[projectRental]['+newRowNum+'][parent]" id="parent'+newRowNum+'" onchange="changeParentSelect('+newRowNum+');" style="width:55px;">'+parentOptionStr+'</select></td>' +
			'<td>'+costTypeStr+'</td>' +
			moneyStr +
		'</tr>';
	$("#projectRentalTbody").append(str);
	//ǧ��λ��Ⱦ
	formatProjectRentalMoney();
}

//ɾ�� - ��
function delProjectRentalRow(rowNum){
	var supplierObj = $("#supplier4_id" + rowNum);
	if(supplierObj.length > 0){
		supplierObj.after('<input type="hidden" name="outsourcing[projectRental]['+rowNum+'][isDelTag]" id="isDel'+rowNum+'" value="1"/>');
		$("#tr"+rowNum).hide();
	}else{
		$("#tr"+rowNum).remove();
	}
}

//���� - ��
function countDetail(rowNum,supplierNum,isCopy){
	var priceObj = $("#supplier"+supplierNum+"_price" + rowNum);
	var numberObj = $("#supplier"+supplierNum+"_number" + rowNum);
	var periodObj = $("#supplier"+supplierNum+"_period" + rowNum);

	//�Ƿ��ǰ�渴�� ����������
	if(isCopy == 1){
		numberObj.val($("#supplier1_number" + rowNum).val());
		periodObj.val($("#supplier1_period" + rowNum).val());
	}

	if(priceObj.val()!= "" || numberObj.val()!= "" || periodObj.val()!= ""){//�����ߴ���һ����Ϊ��ʱ,���м���
		var price = number = period = 0;
		if(priceObj.val()*1 != "") price = priceObj.val()*1;
		if(numberObj.val()*1 != "") number = numberObj.val()*1;
		if(periodObj.val()*1 != "") period = periodObj.val()*1;

		var amount = accMul(accMul(price,number,2),period,2);
		setMoney("supplier"+supplierNum+"_amount" + rowNum,amount);
	}
}

//���� - ��
function countProjectRental(){
	//��δʵ��
}

//ǧ��λ��Ⱦ
function formatProjectRentalMoney(){
	// ��Ⱦ ǧ��λ���
	$.each($("#projectRentalTbody input.formatMoney"), function(i, n) {
		var idStr = "" + $(this).attr('id');
		if ($(this).get(0).tagName == 'INPUT'
				&& idStr.indexOf("_v") <= 1) {
			var strHidden = $("<input type='hidden' name='" + n.name
					+ "' id='" + n.id + "' value='" + n.value + "' />");
			$(this).attr('name', '');
			$(this).attr('id', n.id + '_v');
			$(this).val(moneyFormat2(n.value));
			$(this).bind("blur", function() {
				moneyFormat1(this, 2);
				if (n.onblur)
					n.onblur();
			});
			$(this).after(strHidden);
		} else {
			returnMoney = moneyFormat2($(this).text(), 2);
			if (returnMoney != "")
				$(this).text(returnMoney);
		}
	});

	//��Ⱦǧ��λ ҳ����
	$.each($("#projectRentalTbody td.formatMoney"),function(){
		returnMoney = moneyFormat2($(this).text(), 2);
		if (returnMoney != "")
			$(this).text(returnMoney);
	});
}

//��ȡ�����ֵ�
function getDatadictArr(code){
	if(!datadictArr[code]){//����Ѵ��ڸû���,��ֱ�ӷ��ػ���
		var dataArr = getData(code);
		datadictArr[code] = dataArr;
	}
	return datadictArr[code];
}

//��ȡ�����ֵ����
function getParentDatadictArr(code){
	if(parentDatadictArr.length == 0){
		parentArr = getDatadictArr('WBHTFYX');
		var num = parentArr.length;
		for(var i = 0;i<num;i++){
			parentDatadictArr[parentArr[i].dataCode] = parentArr[i];
		}
	}
	return parentDatadictArr[code];
}

//�����ֵ���Ⱦ��option
function getOptionStr(data){
	var str = "";
	var num = data.length;
	for(var i=0;i<num;i++){
		str +="<option value='"+ data[i].dataCode +"'>"+ data[i].dataName +"</option>";
	}
	return str;
}

//�������selectѡ�������
function changeParentSelect(rowNum){
	var parentSelectObj = $("#parent" + rowNum);
	var parentArr = getParentDatadictArr(parentSelectObj.val());

	//�䶯�������� ��չ�ֶ�1�ж��¼���������
	if(parentArr.expand1 == "1"){
		var costObj = $("#costTypeName" + rowNum);
		var costIdObj = $("#costType" + rowNum);
        var costTypeArr = getDatadictArr(parentSelectObj.val());//��ȡ��ϸ����
        var costTypeOptionStr = getOptionStr(costTypeArr);//���ظ���ѡ��
		if(costObj.length == 1) costObj.after('<select name="outsourcing[projectRental]['+rowNum+'][costType]" id="costType'+rowNum+'" style="width:65px;">'+costTypeOptionStr+'</select>').remove();
		$("#isCustom" + rowNum).val(0);
		costIdObj.empty().append(costTypeOptionStr);
	}else{
		var costObj = $("#costType" + rowNum);
		if(costObj.length == 1) costObj.after('<input name="outsourcing[projectRental]['+rowNum+'][costTypeName]" id="costTypeName'+rowNum+'" class="rimless_textB" style="width:65px;"/>').remove();
		$("#isCustom" + rowNum).val(1);
	}

	//�䶯�������� ��չ�ֶ�2�жϽ����������
	if(parentArr.expand2 == "1"){
		//��������ϸ����ʾ
		$("#projectRentalTbody tr[id='tr"+ rowNum +"'] td.amountTd").attr("colspan",1);
		$("#projectRentalTbody tr[id='tr"+ rowNum +"'] td.detailTd").show();
		$("#supplier1_amount"+rowNum+"_v").css("width","65px");
		$("#supplier2_amount"+rowNum+"_v").css("width","65px");
		$("#supplier3_amount"+rowNum+"_v").css("width","65px");
		$("#supplier4_amount"+rowNum+"_v").css("width","65px");
		$("#isDetail" + rowNum).val(1);
	}else{
		//��������ϸ����ʾ
		$("#projectRentalTbody tr[id='tr"+ rowNum +"'] td.amountTd").attr("colspan",4);
		$("#projectRentalTbody tr[id='tr"+ rowNum +"'] td.detailTd").hide();
		$("#supplier1_amount"+rowNum+"_v").css("width","230px");
		$("#supplier2_amount"+rowNum+"_v").css("width","230px");
		$("#supplier3_amount"+rowNum+"_v").css("width","230px");
		$("#supplier4_amount"+rowNum+"_v").css("width","230px");
		$("#isDetail" + rowNum).val(0);
	}

	//�䶯�������� ��չ�ֶ�3�жϽ���Ƿ����ɱ�
	if(parentArr.expand3 == "1"){
		$("#isServerCost"+rowNum).val(1);
	}else{
		$("#isServerCost"+rowNum).val(0);
	}
}

//��֤�Ƿ�ѡ��ѡ
function verification(){
	var outsourcing = $("#outsourcing").val();
	var rowNum = $("#projectRentalRowNum").val();
	var supplier2 = $('#supplier2').val();
	var supplier3 = $('#supplier3').val();
	var supplier4 = $('#supplier4').val();
	var checkVal = $('input[name="outsourcing[projectRental][supplier][checkSupplier]"]:checked').val();
	if(outsourcing == "HTWBFS-01"){
		if(supplier2 || supplier3 || supplier4.length>0){
			if(checkVal !=null){
				for(var i = 0;i<=rowNum;i++){
					if( $("#tr"+i).css("display") != "none" ){
						$("#supplier1_price"+i+"_v").addClass('validate[required]');
						$("#supplier1_number"+i).addClass('validate[required]');
						$("#supplier1_period"+i).addClass('validate[required]');
						$("#supplier1_amount"+i+"_v").addClass('validate[required]');
					}else{
						$("#supplier1_price"+i+"_v").removeClass('validate[required]');
						$("#supplier1_number"+i).removeClass('validate[required]');
						$("#supplier1_period"+i).removeClass('validate[required]');
						$("#supplier1_amount"+i+"_v").removeClass('validate[required]');
					}
				}
			}else{
				alert('��ѡ��һ����Ӧ��');
			}
		}
		
	}
	else{
		for(var i = 0;i<rowNum;i++){
			$("#supplier1_price"+i+"_v").removeClass('validate[required]');
			$("#supplier1_number"+i).removeClass('validate[required]');
			$("#supplier1_period"+i).removeClass('validate[required]');
			$("#supplier1_amount"+i+"_v").removeClass('validate[required]');
		}
	}
}