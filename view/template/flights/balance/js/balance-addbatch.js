$(document).ready(function() {
    // 单选订票机构
    $("#organization").yxcombogrid_ticket({
        hiddenId: 'organizationId',
        gridOptions: {
            param: {
                "findTicketVal": "票务"
            }
        }
    });

    //缓存质检内容表
    var itemTableObj = $("#itemTable");
    itemTableObj.yxeditgrid({
        objName: 'balance[items]',
        url: "?model=flights_message_message&action=getConfirmedMsgJson",
        param: {
            "ids":  $("#msgId").val()
        },
        isAddAndDel: false,
        event: {
            'reloadData': function() {
                //获取表格上所有字段
                var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "costPay");

                var allCost = 0;
                if (itemArr.length > 0) {
                    //循环
                    itemArr.each(function() {
                        //accAdd加法
                        allCost = accAdd(allCost, $(this).val(), 2);
                    });
                }

                setMoney("balanceSum",allCost);
                setMoney("actualMoney",allCost);

                //大于够格长度时
                if(itemArr.length > 3){
					itemTableObj.attr("style", "overflow-x:auto;overflow-y:auto;height:500px;");
                }
            }
        },
        title: '订票信息',
        colModel: [{
            name: 'msgType',
            display: '结算类型',
            type: 'statictext',
            process : function(v){
				if(v == "0"){
					return '<span class="green">正常</span>';
				}else if(v == '1'){
					return '<span class="blue">改签</span>';
				}else{
					return '<span class="red">退票</span>';
				}
            }
        },{
            name: 'airName',
            display: '乘机人',
            readonly: true,
            tclass : 'readOnlyTxtMiddle'
        },{
            name: 'airId',
            display: 'airId',
            type : 'hidden'
        },{
            name: 'airline',
            display: '航空公司',
            readonly: true,
            width : 150,
            tclass : 'readOnlyTxtNormal'
        },{
            name: 'airlineId',
            display: 'airlineId',
            type : 'hidden'
        },{
            name: 'flightNumber',
            display: '车次/航班号',
            readonly: true,
            tclass : 'readOnlyTxtMiddle'
        },{
            name: 'flightTime',
            display: '乘机时间',
            readonly: true,
            width : 130,
            tclass : 'readOnlyTxtMiddle'
        },{
            name: 'arrivalTime',
            display: '到达时间',
            readonly: true,
            width : 130,
            tclass : 'readOnlyTxtMiddle'
        },{
            name: 'departPlace',
            display: '出发地点',
            readonly: true,
            tclass : 'readOnlyTxtShort'
        },{
            name: 'arrivalPlace',
            display: '到达地点',
            readonly: true,
            tclass : 'readOnlyTxtShort'
        },{
            name: 'costPay',
            display: '实际付款金额',
            process : function(v){
            	return moneyFormat2(v);
            },
            readonly: true,
            tclass : 'readOnlyTxtMiddle'
        },{
            name: 'msgType',
            display: 'msgType',
            type: 'hidden',
            readonly: true
        },{
            name: 'msgId',
            display: 'msgId',
            type: 'hidden',
            readonly: true
        }]
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

//新增
function sumCost(){
    var balanceSum = $("#balanceSum").val();
    var allCost = balanceSum;
   	allCost = accSub(allCost,$("#rebate").val(), 2);
    allCost = accSub(allCost,$("#exchangeMoney").val(), 2);
    setMoney("actualMoney",allCost);
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