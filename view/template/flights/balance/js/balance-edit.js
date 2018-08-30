$(document).ready(function() {

	//初始化明细
	initDetail();

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
});

function initDetail(){
    // 单选订票机构
    $("#organization").yxcombogrid_ticket({
        hiddenId: 'organizationId',
        gridOptions: {
            param: {
                "findTicketVal": "票务"
            }
        }
    });

	var itemTableObj = $("#itemTable");
	itemTableObj.yxeditgrid({
		url : "?model=flights_balance_batchitem&action=listJson",
		param : {"mainId" : $("#id").val()},
		objName : 'balance[items]',
//        title: '订票信息',
		event : {
			'reloadData' : function(e){
                //获取表格上所有字段
                var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "airName");

                //循环禁用原数据
                itemArr.each(function(){
                	$(this).removeClass('txtmiddle').addClass('readOnlyTxtMiddle');
                });

                //大于够格长度时
                if(itemArr.length > 15){
					itemTableObj.attr("style", "overflow-x:auto;overflow-y:auto;height:550px;");
                }
			},
			'removeRow' : function(){
                //获取表格上所有字段
                var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "costPay");
                //大于够格长度时
                if(itemArr.length > 3){
					itemTableObj.attr("style", "overflow-x:auto;overflow-y:auto;height:550px;");
                }else{
					itemTableObj.attr("style", "overflow-x:auto;overflow-y:hidden;");
                }

				//计算结算单金额
				recount();
			},
			'addRow' : function(e,rowNum,rowData){
				if(!rowData){
					var airNameObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"airName");
					var msgIdObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"msgId");
	                airNameObj.yxcombogrid_msginfo({
	                    hiddenId: msgIdObj.attr('id'),
                    	height : 230,
	                    gridOptions: {
	                    	isTitle : true,
	                        param: {
	                            "auditState": "1"
	                        },
	                        event : {
	                        	"row_dblclick" : function(rowNum){
	                        		return function(e, row, data){
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"msgTypeName").val(changeMsgType(data.msgType));
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"airId").val(data.airId);
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"auditDate").val(data.auditDate);
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"flightTime").val(data.flightTime);
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"arrivalTime").val(data.arrivalTime);
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"flightNumber").val(data.flightNumber);
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"airline").val(data.airline);
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"airlineId").val(data.airlineId);
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"msgType").val(data.msgType);
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"departPlace").val(data.departPlace);
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"arrivalPlace").val(data.arrivalPlace);
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"costPay").val(data.costPay);

										//计算结算单金额
										recount();
		                        	}
	                        	}(rowNum)
	                        }
	                    }
	                });
				}

                //获取表格上所有字段
                var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "airName");
                //大于够格长度时
                if(itemArr.length > 15){
					itemTableObj.attr("style", "overflow-x:auto;overflow-y:auto;height:550px;");
                }
			}
		},
        colModel: [{
            name: 'id',
            display: 'id',
            type : 'hidden'
        },{
            name: 'msgTypeName',
            display: '结算类型',
            readonly: true,
            tclass : 'readOnlyTxtMiddle',
            width : 70
        },{
            name: 'auditDate',
            display: '核算日期',
            readonly: true,
            tclass : 'readOnlyTxtMiddle',
            width : 80
        },{
            name: 'airName',
            display: '乘机人',
            readonly: true,
            tclass : 'txtmiddle',
			validation : {
				required : true
			}
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

    $("form").submit(checkform);
}

//类型转换
function changeMsgType(thisVal){
	if(thisVal == '0'){
		return '正常';
	}else if(thisVal == "1"){
		return '改签';
	}else{
		return '退票';
	}
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