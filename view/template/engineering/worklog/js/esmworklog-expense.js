//��ʼ������
function initFee(){
	var invbodyObj = $("#invbody");
	//���û��ʵ���������ã������ģ��
	if(invbodyObj.html() == ""){
		$.ajax({
		    type: "POST",
		    url: "?model=finance_expense_customtemplate&action=initTemplate",
		    data : {"isEsm" : 1},
		    async: false,
		    success: function(data){
		   		if(data != ""){
					var dataObj = eval("(" + data + ")");
					$("#templateName").val(dataObj.templateName);
					$("#templateId").val(dataObj.id);
					invbodyObj.html(dataObj.templateStr);
					formateMoney();
		   	    }else{
					alert('û�в�ѯ������ģ��');
		   	    }
			}
		});

		//ʵ��������ѡ��
		initSelect();
	}else{
		invbodyObj.empty();
	}
}

//ʵ��������ѡ��
function initSelect(){
	//ģ��ѡ����Ⱦ
	$("#templateName").yxcombogrid_expensemodel('remove').yxcombogrid_expensemodel({
		hiddenId :  'templateId',
		isFocusoutCheck : false,
		height : 300,
		isShowButton : false,
		isClear : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data){
					initTemplate(data.modelType);
				}
			}
		}
	});
}

//����������
var billTypeArr = [];
var billTypeStr;

//��ȡ������
function getBillType() {
	var responseText = $.ajax({
		url : 'index1.php?model=common_otherdatas&action=getBillType',
		type : "POST",
		async : false
	}).responseText;
	var o = eval("(" + responseText + ")");
	return o;
}

//����ѡ���ַ��� TODO ���Ż�
function rtBillTypeStr(data, costTypeId) {
	var defaultVal = $("#defaultInvoice" + costTypeId).val();
	var isReplace = $("#isReplace"+ costTypeId).val();
	if(isReplace == 1){
        var title =  '�˷���������Ʊ';
	}else{
        var title =  '�˷��ò�������Ʊ';
	}
	var str ;
	for (var i = 0, l = data.length; i < l; i++) {
		if(defaultVal == data[i].id){
    		str +='<option value="'+ data[i].id +'" selected="selected" title="'+title+'">'+ data[i].name+'</option>';
        }else{
        	if(isReplace == '1'){
           		str +='<option value="'+ data[i].id +'" title="'+title+'">'+ data[i].name+'</option>';
        	}
        }
	}
	return str;
}

//��ͨ�����������
function detailSet(thisNum){
	//ȡ��ʾֵ��Ȼ�����+�ż���
	var costMoney = $("#costMoney" + thisNum + "_v").val();

	if(costMoney){
		costMoney = autoAdd(costMoney);
		setMoney("costMoney" + thisNum,costMoney);

		//��ȡѡ�����
		var isSubsidy = $("#isSubsidy" + thisNum);
		if(isSubsidy.val() == "1"){
			setMoney("invoiceMoney_"+ thisNum + "_0",0);

			//��ʼ��һ����Ʊ����
			$("#invoiceNumber_"+ thisNum + "_0").val(0);
		}else{
			var detailTable = $("#table_"+ thisNum +" select[id^='select_" + thisNum + "_']");
			if(detailTable.length == 1){
				var days = $("#days" + thisNum).val();
				costMoney = accMul(costMoney,days,2);
				setMoney("invoiceMoney_"+ thisNum + "_0",costMoney);

				//��ʼ��һ����Ʊ����
				var invoiceNumberObj = $("#invoiceNumber_"+ thisNum + "_0");
				if(invoiceNumberObj.val() == ""){
					invoiceNumberObj.val(1);
				}
			}
		}

		//��ϸ����
	    countInvoiceMoney();
	    countInvoiceNumber();
	}
}

//��Ʊ������������
function invMoneySet(thisNum){
	//ȡ��ʾֵ��Ȼ�����+�ż���
	var invoiceMoney = $("#invoiceMoney_" + thisNum + "_v").val();
	if(invoiceMoney){
		invoiceMoney = autoAdd(invoiceMoney);
		setMoney("invoiceMoney_" + thisNum,invoiceMoney);
	}
}

//�ӷ�����
function autoAdd(thisVal){
	var thisValArr = thisVal.split("+");
	var rtMoney = 0;
	for(var i = 0;i< thisValArr.length;i++){
		rtMoney = accAdd(rtMoney,thisValArr[i],2);
	}
	return rtMoney;
}

//���ӷ�Ʊ��Ϣ
function add_lnvoice(id){
	//ʵ��������
	var costMoney , costType , detailMoney ,delTag ,lastMoney,days;
	//����
	costMoney = $("#costMoney" + id).val();
	days = $("#days" + id).val();
	costMoney = accMul(costMoney,days,2);
	//���ƻ���
	costType = $("#costType" + id).val();
	//���³�ʼ�����
	detailAll = 0;
	$("select[id^='select_" + id + "_']").each(function(i,n){
		delTag = $("#isDelTag_"+ id + "_" + i).length;
		if(delTag == 0){
			detailMoney = $("#invoiceMoney_"+ id + "_" + i).val();
			detailAll = accAdd(detailAll,detailMoney,2);
		}
	});
	lastMoney = accSub(costMoney,detailAll,2);
	var invoiceNumber = 1;
	if(lastMoney*1 <= 0){
		lastMoney = "";
		invoiceNumber = "";
	}

	//��ʼ����Ʊ����
	billTypeStr = rtBillTypeStr(billTypeArr,id);

	//���ôӱ�
	var tableObj = $("#table_" + id);
	//�ӱ��ж���
	var tableTrObj = $("#table_" + id + " tr");
	//�ӱ�����
	var tableTrLength = tableTrObj.length;
	//����Id��׺
	var countStr = id + "_" + tableTrLength;
	var str = '<tr id="tr_' + countStr + '">' +
			'<td width="30%"><select id="select_' + countStr + '" name="esmworklog[esmcostdetail]['+ id + '][invoiceDetail][' + tableTrLength + '][invoiceTypeId]" style="width:90px"><option value="">��ѡ��Ʊ</option>' + billTypeStr +'</select></td>' +
			'<td width="25%"><input id="invoiceMoney_' + countStr + '" costTypeId="'+ id +'" rowCount="'+ countStr +'" name="esmworklog[esmcostdetail]['+ id + '][invoiceDetail][' + tableTrLength + '][invoiceMoney]" type="text" class="txtshort" value="'+lastMoney+'" onblur="invMoneySet(\''+ countStr +'\');countInvoiceMoney();"/></td>' +
			'<td width="25%"><input id="invoiceNumber_' + countStr + '" costTypeId="'+ id +'" rowCount="'+ countStr +'"  name="esmworklog[esmcostdetail]['+ id + '][invoiceDetail][' + tableTrLength + '][invoiceNumber]" type="text" class="txtshort" value="'+ invoiceNumber +'" onblur="countInvoiceNumber(this);"/></td>' +
            '<td width="20%"><img style="cursor:pointer;" src="images/removeline.png" title="ɾ�����з�Ʊ" onclick="delete_lnvoice(' + id + ',this)"/></td>' +
		'</tr>';
	tableObj.append(str);
	//��ʽ�����
    createFormatOnClick('invoiceMoney_'+countStr);

    //��ע�߶ȵ���
    var remarkObj = $("#remark" + id);
    remarkObj.animate({height:"+=33"},100);

	//��ϸ����
    countInvoiceMoney();
    countInvoiceNumber();
}

//ɾ����Ʊ��Ϣ
function delete_lnvoice(id,obj){
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="esmworklog[esmcostdetail]['+
				id +'][invoiceDetail][' +
				rowNo + '][isDelTag]" id="isDelTag_'+ id +'_'+rowNo +'" value="1"/>');

	    //��ע�߶ȵ���
	    var remarkObj = $("#remark" + id);
	    remarkObj.animate({height:"-=33"},100);


		//��ϸ����
	    countInvoiceNumber();
		countInvoiceMoney();
	}
}

//���÷��ý���title
function initAmountTitle(feeRegular,feeSubsidy){
	$("#feeRegularView").html(moneyFormat2(feeRegular));
	$("#feeSubsidyView").html(moneyFormat2(feeSubsidy));
}

//�����ݺϼ�
function countAll(){
	//�ӱ��ܽ��
	var tableTrObj = $("#invbody input[id^='costTypeId']");
	var costTypeId , costMoney , countAll , days ,thisCostMoney , isSubsidy;
	var feeRegular = feeSubsidy  = 0;
	$.each(tableTrObj,function(i,n){
		costTypeId = this.value*1;
		delTag = $("#isDelTag_"+ costTypeId).length;

		if(delTag == 0){
			costMoney = $("#costMoney" + costTypeId).val();
			days = $("#days" + costTypeId).val();
			thisCostMoney = accMul(costMoney,days,2);

			//��ȡ�Ƿ���Ҫ��Ʊѡ��
			isSubsidy = $("#isSubsidy" + costTypeId).val();
			if(isSubsidy == 1){
				//������Ĳ�������
				feeSubsidy = accAdd(feeSubsidy,thisCostMoney,2);
			}else{
				//������ĳ������
				feeRegular = accAdd(feeRegular,thisCostMoney,2);
			}
			//��������ܽ��
			countAll = accAdd(countAll,thisCostMoney,2);
		}
	});
	if(countAll*1 == 0 || countAll == undefined){
		countAll = "";
	}
	setMoney('countMoney', countAll );
	//����title
	initAmountTitle(feeRegular,feeSubsidy);
	//�������
	$("#feeRegular").val(feeRegular);
	$("#feeSubsidy").val(feeSubsidy);
}

//����Ʊ���ϼ�
function countInvoiceMoney(){
	//�ӱ��ܽ��
	var countAll , rowCount , mark ,costTypeId , isCount;
	$("#invbody input[id^='invoiceMoney_']").each(function(i,n){
		//��������id
		costTypeId = $(this).attr('costTypeId');
		if(mark != costTypeId){
			mark = costTypeId;
			if($("#isDelTag_" + costTypeId ).length > 0){
				isCount = false;
			}else{
				isCount = true;
			}
		}

		rowCount = $(this).attr('rowCount');
		//�ж��Ƿ���ֵ
		if(this.value != "" && rowCount && isCount){
			if($("#isDelTag_" + rowCount ).length == 0){
				countAll = accAdd(countAll,this.value);
			}
		}
	});
	if(countAll*1 == 0 || !countAll){
		countAll = "";
	}
	setMoney('invoiceMoney', countAll );
}

//����Ʊ�����ϼ�
function countInvoiceNumber(thisObj){
	//������ڴ������������֤�Ƿ�����
	if(thisObj){
		var re = /^[1-9]d*|0$/;
		if (!re.test(thisObj.value)) {
			if (isNaN(thisObj.value)) {
				alert("������Ǹ�����!");
				thisObj.value = "";
				thisObj.focus();
				return false;
			}
		}
	}
	//�ӱ��ܽ��
	var countAll , rowCount , mark ,costTypeId , isCount;
	$("#invbody input[id^='invoiceNumber_']").each(function(i,n){
		//��������id
		costTypeId = $(this).attr('costTypeId');
		if(mark != costTypeId){
			mark = costTypeId;
			if($("#isDelTag_" + costTypeId ).length > 0){
				isCount = false;
			}else{
				isCount = true;
			}
		}

		//�ж��Ƿ���ֵ ���ҵ�ǰֵ����ͳ����
		if(this.value != "" && isCount ){
			rowCount = $(this).attr('rowCount');
			if($("#isDelTag_" + rowCount ).length == 0){
				countAll = accAdd(countAll,this.value);
			}
		}
	});
	if(countAll*1 == 0 || !countAll){
		countAll = "";
	}
	$("#invoiceNumber").val(countAll);
}

//�ύ�����ı�����ֵ
function audit(thisType){
	$("#thisAuditType").val(thisType);
}

//�Զ������ѡ���� - ����ѡ��
function selectCostType2(){

	//��ȡ��ǰ�еķ�������
	var costTypeArr = $("#invbody input[id^='costTypeId']");
	//��ǰ���ڷ�����������
	var costTypeIdArr = [];
	//��ǰ���ڷ��������ַ���
	var costTypeIds = '';

	if(costTypeArr.length > 0){
		//���浱ǰ���ڷ�������
		costTypeArr.each(function(i,n){
			//�ж��Ƿ���ɾ��
			if($("#isDelTag_" + this.value).length == 0){
				costTypeIdArr.push(this.value);
			}
		});
		//������ǰ���ڷ�������id��
		costTypeIds = costTypeIdArr.toString();
	}

	//�����������ظ�ֵ
	$("#costTypeIds").val(costTypeIds);

	//��һ�μ���
	var isFirst = false;

	if($("#costTypeInner").html() == ""){
		isFirst = true;
		$.ajax({
		    type: "POST",
		    url: "?model=finance_expense_costtype&action=getCostType",
		    data : {"isEsm" : 1},
		    async: false,
		    success: function(data){
		   		if(data != ""){
					$("#costTypeInner").html("<div id='costTypeInner2'>" + data + "</div>")
					if(costTypeIds != ""){
						//��ֵ
						for(var i = 0; i < costTypeIdArr.length;i++){
							$("#view" + costTypeIdArr[i]).attr('class','blue');

							var chkObj = $("#chk" + costTypeIdArr[i]);
							chkObj.attr('checked',true);
							if($("#remark" + costTypeIdArr[i]).length == 0){
								chkObj.attr('disabled',true);
							}
						}
					}
					//��ʱ�������򷽷�
					setTimeout(function(){
						initMasonry();
					},200);
		   	    }else{
					alert('û���ҵ��Զ���ķ�������');
		   	    }
			}
		});
	}
}

//�ٲ�������
function initMasonry(){
	$('#costTypeInner2').masonry({
		itemSelector: '.box'
	});
}

//���¼���Ⱦ
function CostTypeShowAndHide(thisCostType){
	//���������
	var tblObj = $("table .ct_"+thisCostType + "[isView='1']");
	//������ǰ������״̬������ʾ
	if(tblObj.is(":hidden")){
		tblObj.show();
		$("#" + thisCostType).attr("src","images/menu/tree_minus.gif");
	}else{
		tblObj.hide();
		$("#" + thisCostType).attr("src","images/menu/tree_plus.gif");
	}
	initMasonry();
}

//����������Ŀ�鿴
function CostType2View(thisCostType){
	//���������
	var tblObj = $("table .ct_"+thisCostType);
	//������ǰ������״̬������ʾ
	if(tblObj.is(":hidden")){
		tblObj.show();
		tblObj.attr('isView',1);
		$("#" + thisCostType).attr("src","images/menu/tree_minus.gif");
	}else{
		tblObj.hide();
		tblObj.attr('isView',0);
		$("#" + thisCostType).attr("src","images/menu/tree_plus.gif");
	}
	initMasonry();
}

//ѡ���Զ����������
function setCustomCostType(thisCostType,thisObj){
	if($(thisObj).attr('checked') == true){
		$("#view" + thisCostType).attr('class','blue');
	}else{
		$("#view" + thisCostType).attr('class','');
	}
	//�ж������Ƿ���ڣ�������ɵ�������������
	var trObj = $("#tr" + thisCostType);
	if(trObj.length == 1 && $("#isDelTag_" + thisCostType).length == 0){
		//ɾ�������¼�����
		deleteRow(thisCostType);
	}else{
		if(trObj.length > 0){
			//����Ѿ����ڶ��󣬷�ɾ��
			unDeleteRow(thisCostType);
		}else{
			//����ѡ����
			var chkObj = $("#chk" + thisCostType);
			var chkName = chkObj.attr('name');  //��������
			var chkParentName = chkObj.attr('parentName'); //���ø���������
			var chkParentId = chkObj.attr('parentId'); //���ø�����id
			var chkShowDays = chkObj.attr('showDays'); //�Ƿ���ʾ����
			var chkIsPeplace = chkObj.attr('isReplace');//����Ʊ
			var chkIsEqu = chkObj.attr('isEqu');//¼���豸��Ϣ
			var chkInvoiceType = chkObj.attr('invoiceType');//Ĭ�Ϸ�Ʊ����
			var chkInvoiceTypeName = chkObj.attr('invoiceTypeName');//Ĭ�Ϸ�Ʊ����
			var chkIsSubsidy = chkObj.attr('isSubsidy');//�Ƿ���
			//������
			var invbodyObj = $("#invbody");
			//��ʽ��Ⱦ
			var tr_class = $("#invbody").children().length % 2 == 0 ? 'tr_odd' : 'tr_even';

			var thisI = thisCostType + "_0";
			var str = '<tr class="'+tr_class+'" id="tr' + thisCostType + '">' +
				'<td valign="top">' +
				'<img style="cursor:pointer;" src="images/removeline.png" title="ɾ������" onclick="deleteCostType('+ thisCostType +')"/>' +
				'</td>' +
				'<td valign="top" class="form_text_right">' +
				chkParentName +
				'<input type="hidden" name="esmworklog[esmcostdetail]['+ thisCostType +'][parentCostType]" value="'+ chkParentName +'"/>' +
				'<input type="hidden" name="esmworklog[esmcostdetail]['+ thisCostType +'][parentCostTypeId]" value="'+ chkParentId +'"/>' +
				'<input type="hidden" id="showDays'+ thisCostType +'" value="'+ chkShowDays +'"/>' +
				'<input type="hidden" id="defaultInvoice'+ thisCostType +'" value="'+ chkInvoiceType +'"/>' +
				'<input type="hidden" id="defaultInvoiceName'+ thisCostType +'" value="'+ chkInvoiceTypeName +'"/>' +
				'<input type="hidden" id="isReplace'+ thisCostType +'" value="'+ chkIsPeplace +'"/>' +
				'<input type="hidden" id="isEqu'+ thisCostType +'" value="'+ chkIsEqu +'"/>' +
				'<input type="hidden" id="isSubsidy'+ thisCostType +'" value="'+ chkIsSubsidy +'"/>' +
				'</td>' +
				'<td valign="top" class="form_text_right">' +
				chkName +
				'<input type="hidden" name="esmworklog[esmcostdetail]['+ thisCostType +'][costType]" id="costType'+ thisCostType +'" value="'+ chkName + '"/>' +
				'<input type="hidden" name="esmworklog[esmcostdetail]['+ thisCostType +'][costTypeId]" id="costTypeId'+ thisCostType +'" value="' + thisCostType + '"/>' +
				'</td>' +
				'<td valign="top" class="form_text_right">';
			if(chkShowDays == 0){
				str +=
					'<input type="text" name="esmworklog[esmcostdetail]['+ thisCostType +'][costMoney]" id="costMoney'+ thisCostType +'" style="width:146px" class="txtmiddle formatMoney" onblur="detailSet('+ thisCostType +');countAll();"/>' +
					'<input type="hidden" name="esmworklog[esmcostdetail]['+ thisCostType +'][days]" id="days'+ thisCostType +'" value="1"/>';
			}else{
				str +=
					'<span>' +
					'<input type="text" name="esmworklog[esmcostdetail]['+ thisCostType +'][costMoney]" id="costMoney'+ thisCostType +'" style="width:60px" class="txtshort formatMoney" onblur="detailSet('+ thisCostType +');countAll();"/>' +
					' X ' +
					' ���� ' +
					'<input type="text" name="esmworklog[esmcostdetail]['+ thisCostType +'][days]" class="readOnlyTxtMin" id="days'+ thisCostType +'" value="1" readonly="readonly"/>' +
					'</span>';
			}

			//����ǲ���������÷�Ʊ����
			if(chkIsSubsidy == "1"){
				str +='</td>' +
					'<td valign="top" colspan="4" class="innerTd">' +
					'<table class="form_in_table" id="table_'+ thisCostType +'">' +
					'<tr id="tr_' + thisI + '">' +
					'<td width="30%">' +
					'<input type="text" id="select_' + thisI + '" style="width:90px" class="readOnlyTxtShort" readonly="readonly" value="'+ chkInvoiceTypeName +'"/>' +
					'<input type="hidden" name="esmworklog[esmcostdetail]['+ thisCostType +'][invoiceDetail][0][invoiceTypeId]" value="'+ chkInvoiceType +'"/>' +
					'</td>' +
					'<td width="25%">' +
					'<input  type="text" id="invoiceMoney_' + thisI + '" name="esmworklog[esmcostdetail]['+ thisCostType +'][invoiceDetail][0][invoiceMoney]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="readOnlyTxtShort formatMoney" readonly="readonly"/>' +
					'</td>' +
					'<td width="25%">' +
					'<input type="text" id="invoiceNumber_' + thisI + '" name="esmworklog[esmcostdetail]['+ thisCostType +'][invoiceDetail][0][invoiceNumber]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="readOnlyTxtShort" readonly="readonly"/>' +
					'</td>' +
					'<td width="20%">' +
					'<img style="cursor:pointer;" src="images/add_item.png" title="�����Ͳ���Ҫ¼�뷢Ʊ�����ܽ�����������" onclick="alert(\'�����Ͳ���Ҫ¼�뷢Ʊ�����ܽ���������\');"/>' +
					'</td>' +
					'</tr>' +
					'</table>' +
					'</td>' +
					'<td valign="top">' +
	            	'<textarea name="esmworklog[esmcostdetail]['+ thisCostType +'][remark]" id="remark' + thisCostType + '" class="txtlong"></textarea>' +
					'</td>' +
					'</tr>';
			}else{
				str +='</td>' +
					'<td valign="top" colspan="4" class="innerTd">' +
					'<table class="form_in_table" id="table_'+ thisCostType +'">' +
					'<tr id="tr_' + thisI + '">' +
					'<td width="30%">' +
					'<select id="select_' + thisI + '" name="esmworklog[esmcostdetail]['+ thisCostType +'][invoiceDetail][0][invoiceTypeId]"><option value="">��ѡ��Ʊ</option></select>' +
					'</td>' +
					'<td width="25%">' +
					'<input  type="text" id="invoiceMoney_' + thisI + '" name="esmworklog[esmcostdetail]['+ thisCostType +'][invoiceDetail][0][invoiceMoney]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="txtshort formatMoney" onblur="invMoneySet(\''+ thisI +'\');countInvoiceMoney()"/>' +
					'</td>' +
					'<td width="25%">' +
					'<input type="text" id="invoiceNumber_' + thisI + '" name="esmworklog[esmcostdetail]['+ thisCostType +'][invoiceDetail][0][invoiceNumber]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="txtshort" onblur="countInvoiceNumber(this)"/>' +
					'</td>' +
					'<td width="20%">' +
					'<img style="cursor:pointer;" src="images/add_item.png" title="�����" onclick="add_lnvoice('+ thisCostType +')"/>' +
					'</td>' +
					'</tr>' +
					'</table>' +
					'</td>' +
					'<td valign="top">' +
	            	'<textarea name="esmworklog[esmcostdetail]['+ thisCostType +'][remark]" id="remark' + thisCostType + '" class="txtlong"></textarea>' +
					'</td>' +
					'</tr>';
			}
			//������
			invbodyObj.append(str);
			formateMoney();
			//��ʼ����Ʊ����TODO
			if(chkIsSubsidy == "0"){
				billTypeStr = rtBillTypeStr(billTypeArr,thisCostType);
				$("#select_" + thisI).append(billTypeStr);
			}
		}
	}
	countAll();
	//��ϸ����
    countInvoiceNumber();
	countInvoiceMoney();
}

//ɾ��������
function deleteRow(thisCostType){
	//�ж���
	var trObj = $("#tr" + thisCostType);
	trObj.hide();
	trObj.append('<input type="hidden" name="esmworklog[esmcostdetail]['+
			thisCostType +'][isDelTag]" id="isDelTag_'+ thisCostType +'" value="1"/>');
}

//��ɾ��������
function unDeleteRow(thisCostType){
	//�ж���
	var trObj = $("#tr" + thisCostType);
	trObj.show();
	$("#isDelTag_" + thisCostType).remove();
}

//������֤
function daysCheck(obj){
	var days = $("#days").val();
	if(strTrim(obj.value) == "" || isNaN(obj.value)){
		alert('��������ֵ����');
		obj.value = days;
		return false;
	}

	if(days*1 < obj.value*1){
		alert('������ϸ�е��������ܴ��ڷ����ڼ������!');
		obj.value = days;
		return false;
	}
}

//ɾ������
function deleteCostType(costType){
	if(confirm('ȷ��ɾ�����������')){
		//ȡ��ѡ��
		var��chkObj = $("#chk" + costType);
		if(chkObj.length == 1){
			chkObj.attr('checked',false);
		}
		//ɾ��
		deleteRow(costType);

		//���¼�����Ľ���
		countAll();
	    countInvoiceNumber();
		countInvoiceMoney();
	}
}

//���¼����Զ������ѡ����
function resetCustomCostType(){
	//�Զ��������
	var costTypeInnerObj = $("#costTypeInner");
	if(costTypeInnerObj.html() != ""){
		//��ȡ��ѡ��
		$("#costTypeInner input[id^='chk']").attr('checked',false);
		//��ȡ��ǰ�еķ�������
		var costTypeArr = $("#invbody input[id^='costTypeId']");
		//��ǰ���ڷ�����������
		var costTypeIdArr = [];
		//��ǰ���ڷ��������ַ���
		var costTypeIds = '';

		if(costTypeArr.length > 0){
			//���浱ǰ���ڷ�������
			costTypeArr.each(function(i,n){
				//�ж��Ƿ���ɾ��
				if($("#isDelTag_" + this.value).length == 0){
					costTypeIdArr.push(this.value);
					$("#chk" + this.value).attr('checked',true);
				}
			});
			//������ǰ���ڷ�������id��
			costTypeIds = costTypeIdArr.toString();
		}

		//�����������ظ�ֵ
		$("#costTypeIds").val(costTypeIds);
	}
}

//����ģ��
function saveTemplate(){
	//��ȡ��ǰ�еķ�������
	var costTypeArr = $("#invbody input[id^='costTypeId']");
	var contentArr = [];
	var contentIdArr = [];
	//���浱ǰ���ڷ�������
	costTypeArr.each(function(i,n){
		//�ж��Ƿ���ɾ��
		if($("#isDelTag_" + this.value).length == 0){
			contentArr.push($("#costType" + this.value).val());
			contentIdArr.push(this.value);
		}
	});

	if(contentIdArr.length == 0){
		alert('û������ѡ��ֵ��������ѡ��һ���������');
	}else{
		var templateName = $("#templateName").val();
		var templateName= prompt("ϵͳ�Ὣ��ǰ�������ͱ��浽ģ���У���������Ҫ�����ģ������",templateName);
		var content = contentArr.toString();
		var contentId = contentIdArr.toString();
	    if(templateName){
			$.ajax({
			    type: "POST",
			    url: "?model=finance_expense_customtemplate&action=ajaxSave",
			    data : {"templateName" : templateName , "content" : content , "contentId" : contentId },
			    async: false,
			    success: function(data){
			   		if(data != ""){
			   			alert('ģ�屣��ɹ�');
						$("#templateName").val(templateName).yxcombogrid_expensemodel('reload');
						$("#templateId").val(data);
			   	    }else{
						alert('ģ�屣��ʧ��');
			   	    }
				}
			});
	    }else{
	    	if(strTrim(templateName) == ""){
				alert('�����뱨��ģ������');
	    	}
	    }
	}
}

//��ʼ��Ⱦģ�� - ����ʱ��
function initTemplate(modelType){
	//��̨����ģ��ҳ��
	$.ajax({
	    type: "POST",
	    url: "?model=engineering_cost_esmcostdetail&action=initTempAdd",
	    data: {"modelType" : modelType},
	    async: false,
	    success: function(data){
			$("#invbody").html(data);
			// ��� ǧ��λ����
			formateMoney();
			resetCustomCostType();

			//���Ҫ��������
			$("input[id^='days']").each(function(i,n){
				if($(this).attr('class') == 'txtmin'){
					$(this).attr('class','readOnlyTxtMin').attr('readonly',true);
				}
			});
		}
	});
}

//����֤
function checkForm(){
	var isTrue = true;
	var idLength = $("#id").length;
	var activityId = $("#activityId").val();
	var executionDate = $("#executionDate").val();
	//�ж���־�����Ƿ�������ݼټ�¼
	if($('#workStatus').val() == 'GXRYZT-01' || $('#workStatus').val() == 'GXRYZT-02'){
		var isInHols = false;
		$.ajax({
			type : "POST",
			url : "?model=engineering_worklog_esmworklog&action=isInHols",
			data : {
				"executionDate" : executionDate
			},
			async: false,
			success : function(msg) {
				if(msg == '1'){
					alert("��" + executionDate + "���������ݼټ�¼������״̬����Ϊ���������");
					isInHols = true;
				}
			}
		});
		if(isInHols){
			return false;
		}
	}
	//Ͷ�빤��������д
	var inWorkRateObj = $("#inWorkRate");
	var maxInWorkRate = $("#maxInWorkRate").val();
	if(inWorkRateObj.val() *1 > maxInWorkRate*1){
		alert('Ͷ�빤��������' + inWorkRateObj.val() + '���ѳ�������Ͷ�������' + maxInWorkRate + '��');
		return false;
	}

	//���ѡ������Ŀ�������Ҫ��д���������Ϣ
	if($("#projectId").val() != "" && $("#activityId").val() == ""){
		alert('��ѡ��һ������');
		return false;
	}

	//�����д�˹��������߹�������λ
	if((($("#workloadDay").val() != "") && $("#workloadUnit").val() == "") || (($("#workloadDay").val() == "") && $("#workloadUnit").val() != "")){
		alert('�������͹�������λ����ͬʱ��д');
		return false;
	}

	//�ж������Ƿ��ܱ��Ƿ����
	if(idLength == 0){
		//�����ж� -- ����������Ѿ���д����־�������������д
		$.ajax({
			type : "POST",
			url : "?model=engineering_worklog_esmweeklog&action=checkLogCanWrite",
			data : {
				"executionDate" : executionDate
			},
		    async: false,
			success : function(msg) {
				if(msg == "0"){
					alert( executionDate + '��Ӧ���ܱ��ѽ���ȷ�ϣ����ܼ���¼��');
					isTrue = false;
				}
			}
		});
		if(isTrue == false){
			return false;
		}
	}

	if(activityId != '0' && activityId != ""){

		if(idLength == 0){
			//�����ж� -- ����������Ѿ���д����־�������������д
			$.ajax({
				type : "POST",
				url : "?model=engineering_worklog_esmworklog&action=checkActivityLog",
				data : {
					"executionDate" : executionDate,
					"activityId" : activityId
				},
			    async: false,
				success : function(msg) {
					if(msg != "0"){
						if(msg *1 == msg){
							if(confirm('��������'+ executionDate + '����־�Ѿ���д���Ƿ��������޸�?')){
								isTrue = false;
								location = "?model=engineering_worklog_esmworklog&action=toEdit&id=" + msg;
							}else{
								isTrue = false;
							}
						}else{
							alert(msg);
							isTrue = false;
						}
					}
				}
			});
			if(isTrue == false){
				return false;
			}
		}

		if($("#workloadDay").val() == ""){
			alert('����������־����¼���Ӧ�Ĺ�����');
			return false;
		}
	}

	var rtVal = true;
	var costTypeId , costMoney , costType , detailMoney ,delTag ,isSubsidy;
	$("#invbody input[id^='costTypeId']").each(function(i,n){
		//����id
		costTypeId = this.value*1;
		delTag = $("#isDelTag_"+ costTypeId).length;
		if(delTag == 0){
			//��ȡ�Ƿ���Ҫ��Ʊѡ��
			isSubsidy = $("#isSubsidy" + costTypeId).val();

			//�������Ҫ¼�뷢Ʊ����������֤
			if(isSubsidy == '0'){
				//����
				costMoney = $("#costMoney" + costTypeId).val();
				days = $("#days" + costTypeId).val();
				costMoney = accMul(costMoney,days,2);
				if(costMoney != 0){
					//���ƻ���
					costType = $("#costType" + costTypeId).val();
					//���³�ʼ�����
					detailAll = 0;
					$("#table_"+ costTypeId +" select[id^='select_" + costTypeId + "_']").each(function(i,n){
						delTag = $("#isDelTag_"+ costTypeId + "_" + i).length;
						if(delTag == 0){
							//��ȡ���
							detailMoney = $("#invoiceMoney_"+ costTypeId + "_" + i).val();
							//��� ��������֤
							if(this.value == "" && (detailMoney *1 != 0 || detailMoney != "")){
								alert( costType + ' ��Ʊ��ϸ�д��������͵��н��ķ�Ʊ��ϸ��');
								rtVal = false;
								return false;
							}
							//���㷢Ʊ���
							detailAll = accAdd(detailAll,detailMoney,2);
						}
					});
					if(rtVal == false){
						return false;
					}
					if(detailAll *1 != costMoney){
						alert( costType + ' �з��ý��' + costMoney + " �����ڷ�Ʊ�ϼƽ�� " + detailAll + ",���޸ĺ��ٽ��б������");
						rtVal = false;
						return false;
					}
				}
			}
		}
	});

	//��ֹ�ظ��ύ
	$("submit").attr('disable',true);

	return rtVal;
}