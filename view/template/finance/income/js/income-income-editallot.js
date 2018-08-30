var $thisCustomerId = ""; // 单据客户
var $thisCustomerName = ""; // 单据客户
var defaultCurrency = '人民币'; // 默认货币

$(function(){
	// 单选客户
	$("#contractUnitName").yxcombogrid_customer({
		hiddenId : 'contractUnitId',
		isShowButton : false,
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
                    //获取分配从表数据
                    var objGrid = $("#allotTable"); // 缓存从表对象
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

    //选择客户
    if($("#contractUnitId").val() != ""){
        $thisCustomerId = $("#contractUnitId").val();
    }else{
        $thisCustomerId = $("#incomeUnitId").val();
    }

    //选择客户
    if($("#contractUnitName").val() != ""){
        $thisCustomerName = $("#contractUnitName").val();
    }else{
        $thisCustomerName = $("#incomeUnitName").val();
    }

    // 获取货币
    var currency = $("#currency").val();

    // 到款分配
    initAllot(currency);

	// 回款核销
	initIncomeCheck();

    // 货币显示
    if(currency != defaultCurrency){
        $("#currencyInfo").show();
        $("#currencyC").show();
    }
});

// 到款分配明细
function initAllot(currency){
    var objTypeOptions = getObjType('KPRK');
    $("#allotTable").yxeditgrid({
        url : '?model=finance_income_incomeAllot&action=listJson',
        objName : 'income[incomeAllots]',
        title : '到款分配',
        param : { 'incomeId' : $("#id").val()},
        tableClass : 'form_in_table',
        event : {
            reloadData : function(e, g, data){
                if(data && data.length == 0){
					g.addRow(0);
					g.setRowColValue(0,'moneyCurrency',$("#incomeCurrency").val(),true);
                    g.setRowColValue(0,'money',$("#incomeMoney").val(),true);
                }
                // 统一计算一次
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
            display : '源单类型',
            name : 'objType',
            type : 'select',
            options : objTypeOptions,
            event : {
                change : function(){
                    reloadCombo(this.value,$(this).data('rowNum'));
                }
            }
        }, {
            display : '源单id',
            name : 'objId',
            type : 'hidden'
        }, {
            display : '源单编号',
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
			display : '销售区域',
			name : 'areaName',
			readonly : true,
			width: 100
		}, {
            display : '分配金额',
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
            display : '分配金额('+ currency +')',
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
            display : '业务编号',
            name : 'rObjCode',
            tclass : 'readOnlyTxt',
            readonly : true
        }, {
            display : '分配日期',
            name : 'allotDate',
            tclass : 'Wdate',
            type : 'date',
			width: 90,
            value : $("#thisDate").val()
        }]
    });
}

// 渲染到款核销从表
function initIncomeCheck(){
	var objGrid = $("#checkTable");
	objGrid.yxeditgrid({
		url : '?model=finance_income_incomecheck&action=listJson',
		objName : 'income[incomeCheck]',
		title : '回款核销',
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
			display : '合同id',
			name : 'contractId',
			type : 'hidden'
		}, {
			display : '合同名称',
			name : 'contractName',
			type : 'hidden'
		}, {
			display : '合同编号',
			name : 'contractCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '付款条件id',
			name : 'payConId',
			type : 'hidden'
		}, {
			display : '付款条件',
			name : 'payConName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '本次核销金额',
			name : 'checkMoney',
			tclass : 'txtmiddle',
			type : 'money'
		}, {
			display : '核销日期',
			name : 'checkDate',
			tclass : 'txtmiddle Wdate',
			type : 'date'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	});

	//加载付款条件选择按钮
	objGrid.find("tr:first td").append("<input type='button' value='选择付款条件' class='txt_btn_a' style='margin-left:10px;' onclick='selectPayCon();'/>");
}

// 行处理
function countRow(rowNum,currency){
    var objGrid = $("#allotTable"); // 缓存从表对象
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

// 金额合计
function countAll(){
    //获取分配从表数据
    var objGrid = $("#allotTable"); // 缓存从表对象
    var objArr = objGrid.yxeditgrid('getCmpByCol','objType');
    var allAmount = 0; // 分配金额
    var allCurrency = 0; // 分配金额(对应币别)
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

//选择合同付款条件
function selectPayCon(){
    var objIdArray = [];//缓存合同id
    //获取分配从表数据
    var tblObj = $("#allotTable"); // 缓存从表对象
    var objArr = tblObj.yxeditgrid('getCmpByCol','objType');
    if(objArr.length > 0){
        objArr.each(function(){
            if(this.value == 'KPRK-12'){
                var rowNum = $(this).data('rowNum');
                //id验证
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
		alert('请先选择需要到款分配的合同');
	}
}

/**
 * 设置付款内容
 * @return {Boolean}
 */
function setDatas(rows){
	var objGrid = $("#checkTable");
	for(var i = 0; i < rows.length ; i++){
		//判断付款条件是否已存在
		var payConIdArr = objGrid.yxeditgrid("getCmpByCol","payConId");
		var isExist = false;
		if(payConIdArr.length > 0){
			payConIdArr.each(function(){
				if(this.value == rows[i].id){
					isExist = true;
					alert('合同【'+ rows[i].contractCode + "】的付款条件【"+ rows[i].paymentterm +"("+ rows[i].payDT +" "+ moneyFormat2(rows[i].money) +")】已经存在于回款核销列表中,不能重复选择" );
					return false;
				}
			});
		}
		//如果已经重复了，就不能继续选择
		if(isExist){
			return false;
		}
		//重新获取行数
		var tbRowNum = objGrid.yxeditgrid("getAllAddRowNum");
		//新增行
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
 * 检查分配金额与到款金额规则
 */
function toSubmit() {
	var isEmpty = false;//判断从表源单是否为空
	var isZero = false;//判断是否存在金额为0的分配项
	var objIdArray = [];//缓存合同id
	//获取分配从表数据
    var tblObj = $("#allotTable"); // 缓存从表对象
    var objArr = tblObj.yxeditgrid('getCmpByCol','objType');
	if(objArr.length > 0){
		objArr.each(function(){
			if(this.value == 'KPRK-12'){
				var rowNum = $(this).data('rowNum');
				//id验证
				var objId = tblObj.yxeditgrid('getCmpByRowAndCol',rowNum,'objId').val();
				if(objId != '0' && objId != ''){
					objIdArray.push(objId);
				}else{
					isEmpty = true;
					return false;
				}

				//id验证
				var objMoney = tblObj.yxeditgrid('getCmpByRowAndCol',rowNum,'money',true).val();;
				if(objMoney == '' || objMoney == 0 || objMoney < 0){
					isZero = true;
					return false;
				}
			}
		});
	}

	if(isEmpty == true){
		alert('请通过下拉表格正确选择到款分配源单');
		return false;
	}
	if(isZero == true){
		alert('存在金额为0或者空的分配项，修正后进行保存');
		return false;
	}

	//判断分配金额是否小于到款金额
	var remain = $('#allotAble').val();
	if ( remain *1 >= 0 ) {
		return true;
	}else{
		alert("分配金额和不能大于到款金额！");
		return false;
	}
}
