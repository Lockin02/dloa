var $thisCustomerId = ""; // ���ݿͻ�
var $thisCustomerName = ""; // ���ݿͻ�
var defaultCurrency = '�����'; // Ĭ�ϻ���

$(function(){
	// ��ѡ�ͻ�
	$("#contractUnitName").yxcombogrid_customer({
		hiddenId : 'contractUnitId',
		isShowButton : false,
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
                    //��ȡ����ӱ�����
                    var objGrid = $("#allotTable"); // ����ӱ����
                    var objArr = objGrid.yxeditgrid('getCmpByCol','objType');
                    objArr.each(function(){
                        var rowNum = $(this).data('rowNum');
                        objGrid.yxeditgrid('setRowColValue',rowNum,'rObjCode','');
                        objGrid.yxeditgrid('setRowColValue',rowNum,'objId','');
                        objGrid.yxeditgrid('setRowColValue',rowNum,'objCode','');
                        reloadCombo(this.value,rowNum);
                    });
				}
			}
		}
	});

    //ѡ��ͻ�
    if($("#contractUnitId").val() != ""){
        $thisCustomerId = $("#contractUnitId").val();
    }else{
        $thisCustomerId = $("#incomeUnitId").val();
    }

    //ѡ��ͻ�
    if($("#contractUnitName").val() != ""){
        $thisCustomerName = $("#contractUnitName").val();
    }else{
        $thisCustomerName = $("#incomeUnitName").val();
    }

    // ��ȡ����
    var currency = $("#currency").val();

    // �������
    initAllot(currency);

	// �ؿ����
	initIncomeCheck();

    // ������ʾ
    if(currency != defaultCurrency){
        $("#currencyInfo").show();
        $("#currencyC").show();
    }
});

// ���������ϸ
function initAllot(currency){
    var objTypeOptions = getObjType('KPRK');
    $("#allotTable").yxeditgrid({
        url : '?model=finance_income_incomeAllot&action=listJson',
        objName : 'income[incomeAllots]',
        title : '�������',
        param : { 'incomeId' : $("#id").val()},
        tableClass : 'form_in_table',
        event : {
            reloadData : function(e, g, data){
                if(data && data.length == 0){
					g.addRow(0);
					g.setRowColValue(0,'moneyCurrency',$("#incomeCurrency").val(),true);
                    g.setRowColValue(0,'money',$("#incomeMoney").val(),true);
                }
                // ͳһ����һ��
                countAll();
            },
            removeRow : function(t, rowNum, rowData) {
                countAll();
            }
        },
        colModel : [{
            display : 'id',
            name : 'id',
            type : 'hidden'
        }, {
            display : 'Դ������',
            name : 'objType',
            type : 'select',
            options : objTypeOptions,
            event : {
                change : function(){
                    reloadCombo(this.value,$(this).data('rowNum'));
                }
            }
        }, {
            display : 'Դ��id',
            name : 'objId',
            type : 'hidden'
        }, {
            display : 'Դ�����',
            name : 'objCode',
            tclass : 'txt',
            readonly : true,
			process : function($input) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
                var thisType = g.getCmpByRowAndCol(rowNum,'objType').val();
                reloadComboClear(thisType,rowNum);
			}
        }, {
			display : '��������',
			name : 'areaName',
			readonly : true,
			width: 100
		}, {
            display : '������',
            name : 'money',
            type : 'money',
            readonly : currency == defaultCurrency ? false : true,
            tclass : currency == defaultCurrency ? 'txtmiddle' : 'readOnlyTxt',
            event : {
                blur : function(){
                    if(currency == defaultCurrency){
                        countRow($(this).data('rowNum'),currency);
                        countAll();
                    }
                }
            }
        }, {
            display : '������('+ currency +')',
            name : 'moneyCurrency',
            type : currency == defaultCurrency ? 'hidden' : 'money',
            event : {
                blur : function(){
                    if(currency != defaultCurrency){
                        countRow($(this).data('rowNum'),currency);
                        countAll();
                    }
                }
            }
        }, {
            display : 'ҵ����',
            name : 'rObjCode',
            tclass : 'readOnlyTxt',
            readonly : true
        }, {
            display : '��������',
            name : 'allotDate',
            tclass : 'Wdate',
            type : 'date',
			width: 90,
            value : $("#thisDate").val()
        }]
    });
}

// ��Ⱦ��������ӱ�
function initIncomeCheck(){
	var objGrid = $("#checkTable");
	objGrid.yxeditgrid({
		url : '?model=finance_income_incomecheck&action=listJson',
		objName : 'income[incomeCheck]',
		title : '�ؿ����',
		param : { 'incomeId' : $("#id").val(),'incomeType' : '0'},
		tableClass : 'form_in_table',
		isAdd : false,
		event : {
			'reloadData' : function(e, g, data){
				if(!data){
//					objGrid.hide();
				}
			}
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '��ͬid',
			name : 'contractId',
			type : 'hidden'
		}, {
			display : '��ͬ����',
			name : 'contractName',
			type : 'hidden'
		}, {
			display : '��ͬ���',
			name : 'contractCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '��������id',
			name : 'payConId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'payConName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '���κ������',
			name : 'checkMoney',
			tclass : 'txtmiddle',
			type : 'money'
		}, {
			display : '��������',
			name : 'checkDate',
			tclass : 'txtmiddle Wdate',
			type : 'date'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	});

	//���ظ�������ѡ��ť
	objGrid.find("tr:first td").append("<input type='button' value='ѡ�񸶿�����' class='txt_btn_a' style='margin-left:10px;' onclick='selectPayCon();'/>");
}

// �д���
function countRow(rowNum,currency){
    var objGrid = $("#allotTable"); // ����ӱ����
    var rate = $("#rate").val();
    if(currency == defaultCurrency){
        var money = objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'money',true).val();
        var moneyCurrency = accDiv(money,rate,2);
        objGrid.yxeditgrid("setRowColValue",rowNum,"moneyCurrency",moneyCurrency,true);
    }else{
        var moneyCurrency = objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'moneyCurrency',true).val();
        var money = accMul(moneyCurrency,rate,2);
        objGrid.yxeditgrid("setRowColValue",rowNum,"money",money,true);
    }
}

// ���ϼ�
function countAll(){
    //��ȡ����ӱ�����
    var objGrid = $("#allotTable"); // ����ӱ����
    var objArr = objGrid.yxeditgrid('getCmpByCol','objType');
    var allAmount = 0; // ������
    var allCurrency = 0; // ������(��Ӧ�ұ�)
    objArr.each(function(){
        var money = objGrid.yxeditgrid('getCmpByRowAndCol',$(this).data('rowNum'),'money',true).val();
        if(!isNaN(money)){
            allAmount = accAdd(money,allAmount,2);
            var moneyCurrency = objGrid.yxeditgrid('getCmpByRowAndCol',$(this).data('rowNum'),'moneyCurrency',true).val();
            allCurrency = accAdd(allCurrency,moneyCurrency,2);
        }
    });
    var allotAble = accSub($('#incomeMoney').val(),allAmount,2);
    $('#allotAble').val(allotAble);
    $('#remain').html(moneyFormat2(allotAble));
    var allotCurrency = accSub($('#incomeCurrency').val(),allCurrency);
    $('#allotCurrency').val(allotCurrency);
    $('#remainC').html(moneyFormat2(allotCurrency));
}

//ѡ���ͬ��������
function selectPayCon(){
    var objIdArray = [];//�����ͬid
    //��ȡ����ӱ�����
    var tblObj = $("#allotTable"); // ����ӱ����
    var objArr = tblObj.yxeditgrid('getCmpByCol','objType');
    if(objArr.length > 0){
        objArr.each(function(){
            if(this.value == 'KPRK-12'){
                var rowNum = $(this).data('rowNum');
                //id��֤
                var objId = tblObj.yxeditgrid('getCmpByRowAndCol',rowNum,'objId').val();
                if(objId != ''){
                    objIdArray.push(objId);
                }
            }
        });
    }
	if(objIdArray.length > 0){
		showOpenWin("?model=contract_contract_receiptplan"
				+ "&action=selectPayCon"
				+ "&contractId="
				+ objIdArray.toString() ,1,500,1000,$("#id").val());
	}else{
		alert('����ѡ����Ҫ�������ĺ�ͬ');
	}
}

/**
 * ���ø�������
 * @return {Boolean}
 */
function setDatas(rows){
	var objGrid = $("#checkTable");
	for(var i = 0; i < rows.length ; i++){
		//�жϸ��������Ƿ��Ѵ���
		var payConIdArr = objGrid.yxeditgrid("getCmpByCol","payConId");
		var isExist = false;
		if(payConIdArr.length > 0){
			payConIdArr.each(function(){
				if(this.value == rows[i].id){
					isExist = true;
					alert('��ͬ��'+ rows[i].contractCode + "���ĸ���������"+ rows[i].paymentterm +"("+ rows[i].payDT +" "+ moneyFormat2(rows[i].money) +")���Ѿ������ڻؿ�����б���,�����ظ�ѡ��" );
					return false;
				}
			});
		}
		//����Ѿ��ظ��ˣ��Ͳ��ܼ���ѡ��
		if(isExist){
			return false;
		}
		//���»�ȡ����
		var tbRowNum = objGrid.yxeditgrid("getAllAddRowNum");
		//������
		objGrid.yxeditgrid("addRow",tbRowNum);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"contractId",rows[i].contractId);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"contractCode",rows[i].contractCode);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"contractName",rows[i].contractName);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"payConId",rows[i].id);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"payConName",rows[i].paymentterm);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"checkDate",$("#thisDate").val());
	}
}

/**
 * ���������뵽�������
 */
function toSubmit() {
	var isEmpty = false;//�жϴӱ�Դ���Ƿ�Ϊ��
	var isZero = false;//�ж��Ƿ���ڽ��Ϊ0�ķ�����
	var objIdArray = [];//�����ͬid
	//��ȡ����ӱ�����
    var tblObj = $("#allotTable"); // ����ӱ����
    var objArr = tblObj.yxeditgrid('getCmpByCol','objType');
	if(objArr.length > 0){
		objArr.each(function(){
			if(this.value == 'KPRK-12'){
				var rowNum = $(this).data('rowNum');
				//id��֤
				var objId = tblObj.yxeditgrid('getCmpByRowAndCol',rowNum,'objId').val();
				if(objId != '0' && objId != ''){
					objIdArray.push(objId);
				}else{
					isEmpty = true;
					return false;
				}

				//id��֤
				var objMoney = tblObj.yxeditgrid('getCmpByRowAndCol',rowNum,'money',true).val();;
				if(objMoney == '' || objMoney == 0 || objMoney < 0){
					isZero = true;
					return false;
				}
			}
		});
	}

	if(isEmpty == true){
		alert('��ͨ�����������ȷѡ�񵽿����Դ��');
		return false;
	}
	if(isZero == true){
		alert('���ڽ��Ϊ0���߿յķ������������б���');
		return false;
	}

	//�жϷ������Ƿ�С�ڵ�����
	var remain = $('#allotAble').val();
	if ( remain *1 >= 0 ) {
		return true;
	}else{
		alert("������Ͳ��ܴ��ڵ����");
		return false;
	}
}
