$(document).ready(function() {
    // 单选订票机构
    $("#airline").yxcombogrid_ticket({
        hiddenId: 'airlineId',
    	height:300,
        gridOptions: {
            param: {
                "findTicketVal": "航空"
            },
            isTitle : true,
            showcheckbox : true
        }
    });

    // 单选订票机构
    $("#organizationSel").yxcombogrid_ticket({
        hiddenId: 'organizationIdSel',
    	height:300,
        gridOptions: {
            param: {
                "findTicketVal": "票务"
            },
            isTitle : true,
			event : {
				'row_dblclick' : function(e, row, data){
					$("#organizationId").val(data.id);
					$("#organization").val(data.institutionName);
				}
			}
        },
        event : {
        	clear : function(){
				$("#organizationId").val('');
				$("#organization").val('');
        	}
        }
    });

    validate({
        "balanceDateB": {
            required: true
        },
        "balanceDateE": {
            required: true
        },
        "organization": {
            required: true
        },
        "rebate_v": {
            required: true
        },
        "exchange_v": {
            required: true
        },
        "exchangeMoney_v": {
            required: true
        }
    });
    $("form").submit(checkform);
});

//

//启用表单渲染
function initBalance(){
	if($("#isSearch").val() == "1"){
		reloadBalance();
	}else{
		createBalance();
	}
}

//创建
function createBalance(){
	$("#isSearch").val(1);

    $("#messageInfo").show();
    $("#balanceInfo").show();

    var paramObj = {
        "beginDateThen":  $("#balanceDateB").val(),
        "endDateThen":  $("#balanceDateE").val(),
        "auditState" : 1,
        "airlineId" : $("#airlineId").val(),
        "organizationId" : $("#organizationIdSel").val()
    };

	//实例化订票信息
    var itemTableObj = $("#itemTable");
    $.ajax({
    	type : "POST",
    	url : "?model=flights_message_message&action=listHtml&sort=auditDate&dir=ASC",
    	data : paramObj,
    	success : function(data) {
			if (data) {
				itemTableObj.html(data);
				getAllCost(paramObj);
			}
		}
    });
//    itemTableObj.yxeditgrid({
//        objName: 'balance[items]',
//        url: "?model=flights_message_message&action=listJson&sort=auditDate&dir=ASC",
//        param: paramObj,
//		tableClass : 'form_in_table',
//        isAdd: false,
//        event: {
//            'reloadData': function() {
//                //获取表格上所有字段
//                var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "costPay");
//
//                var allCost = 0;
//                if (itemArr.length > 0) {
//                    //循环
//                    itemArr.each(function() {
//                        //accAdd加法
//                        allCost = accAdd(allCost, $(this).val(), 2);
//                    });
//                }
//                setMoney("balanceSum",allCost);
//                setMoney("actualMoney",allCost);
//
//				itemTableObj.attr("style", "overflow-x:auto;overflow-y:auto;height:500px;");
//            },
//			'removeRow' : function(){
//				//计算结算单金额
//				recount();
//			}
//        },
//        colModel: [{
//            name: 'msgType',
//            display: '结算类型',
//            type: 'statictext',
//            process : function(v){
//				if(v == "0"){
//					return '<span class="green">正常</span>';
//				}else if(v == '1'){
//					return '<span class="blue">改签</span>';
//				}else{
//					return '<span class="red">退票</span>';
//				}
//            },
//            width : 70
//        },{
//            name: 'auditDate',
//            display: '核算日期',
//            readonly: true,
//            tclass : 'readOnlyTxtMiddle',
//            width : 80
//        },{
//            name: 'airName',
//            display: '乘机人',
//            readonly: true,
//            tclass : 'readOnlyTxtMiddle',
//            width : 90
//        },{
//            name: 'airId',
//            display: 'airId',
//            type : 'hidden'
//        },{
//            name: 'airline',
//            display: '航空公司',
//            readonly: true,
//            width : 140,
//            tclass : 'readOnlyTxtNormal'
//        },{
//            name: 'airlineId',
//            display: 'airlineId',
//            type : 'hidden'
//        },{
//            name: 'flightNumber',
//            display: '车次/航班号',
//            readonly: true,
//            tclass : 'readOnlyTxtMiddle'
//        },{
//            name: 'flightTime',
//            display: '乘机时间',
//            readonly: true,
//            width : 130,
//            tclass : 'readOnlyTxtMiddle'
//        },{
//            name: 'arrivalTime',
//            display: '到达时间',
//            readonly: true,
//            width : 130,
//            tclass : 'readOnlyTxtMiddle'
//        },{
//            name: 'departPlace',
//            display: '出发地点',
//            readonly: true,
//            tclass : 'readOnlyTxtShort'
//        },{
//            name: 'arrivalPlace',
//            display: '到达地点',
//            readonly: true,
//            tclass : 'readOnlyTxtShort'
//        },{
//            name: 'costPay',
//            display: '实际付款金额',
//            process : function(v){
//            	return moneyFormat2(v);
//            },
//            readonly: true,
//            tclass : 'readOnlyTxtMiddle'
//        },{
//            name: 'msgType',
//            display: 'msgType',
//            type: 'hidden',
//            readonly: true
//        },{
//            name: 'msgId',
//            display: 'msgId',
//            type: 'hidden',
//            readonly: true
//        }]
//    });

    // 单选订票机构
    $("#organization").yxcombogrid_ticket({
        hiddenId: 'organizationId',
    	height:300,
        gridOptions: {
            param: {
                "findTicketVal": "票务"
            },
            isTitle : true
        }
    });
}

//数据刷新
function reloadBalance(){
    var paramObj = {
        "beginDateThen":  $("#balanceDateB").val(),
        "endDateThen":  $("#balanceDateE").val(),
        "auditState" : 1,
        "airlineId" : $("#airlineId").val(),
        "organizationId" : $("#organizationIdSel").val()
    };

  //实例化订票信息
    var itemTableObj = $("#itemTable");
    $.ajax({
    	type : "POST",
    	url : "?model=flights_message_message&action=listHtml&sort=auditDate&dir=ASC",
    	data : paramObj,
    	success : function(data) {
			if (data) {
				itemTableObj.html(data);
				getAllCost(paramObj);
			}
		}
    });
	//实例化订票信息
//    $("#itemTable").yxeditgrid("setParam",paramObj).yxeditgrid("processData");
    
}

//新增
function sumCost(){
    var balanceSum = $("#balanceSum").val();
    var allCost = balanceSum;
   	allCost = accSub(allCost,$("#rebate").val(), 2);
    allCost = accSub(allCost,$("#exchangeMoney").val(), 2);
    setMoney("actualMoney",allCost);
}

//重新计算表单金额
function recount(){
	var itemTableObj = $("#itemTable");
    var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "costPay");
    var allMoney = 0;
    if(itemArr.length > 0){
		itemArr.each(function(){
			//过滤掉删除的行
			var rowNum = $(this).data('rowNum');
			if($("#balance[items]_" + rowNum +"_isDelTag").length == 0){
				allMoney = accAdd($(this).val() , allMoney);
			}
		});
    }
	setMoney("balanceSum",allMoney);
	sumCost();
}

//验证
function checkform(){
	if($("#actualMoney").val()*1 <= 0){
		alert('实际金额不能小于或者等于0');
		return false;
	}

	//缓存msgId用来判断是否重复结算
	var msgIdArr = [];
	var itemTableObj = $("#itemTable");
    var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "msgId");
    var isUnique = true; //是否包含重复数据
	itemArr.each(function(){
		//过滤掉删除的行
		var rowNum = $(this).data('rowNum');
		if($("#balance[items]_" + rowNum +"_isDelTag").length == 0){
			if($.inArray($(this).val(),msgIdArr) == -1){
				msgIdArr.push($(this).val());
			}else{
				var flightNo = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"flightNumber").val();
				alert('不能将同一条订票信息【航班号：'+ flightNo +'】做多次结算');
				isUnique = false;
				return false;
			}
		}
	});
	return isUnique;
}

//计算表单金额
function getAllCost(paramObj){
	$.ajax({
    	type : "POST",
    	url : "?model=flights_message_message&action=getAllCost",
    	data : paramObj,
    	success : function(data) {
    		setMoney("balanceSum",data);
			sumCost();
		}
    });
}

//删除行
function removeRow(rowNum){
	var balanceSum =  accSub($("#balanceSum").val(),$("#itemTable_cmp_costPay"+rowNum).val(),2);
	setMoney("balanceSum",balanceSum);
	sumCost();
	$("tr[rownum='"+rowNum+"']").remove();
}
