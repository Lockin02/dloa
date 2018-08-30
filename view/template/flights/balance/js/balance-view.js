$(document).ready(function() {
    //缓存质检内容表
    var itemTableObj = $("#itemTable");

    itemTableObj.yxeditgrid({
        objName: 'batch[items]',
        url: "?model=flights_balance_batchitem&action=listJson&sort=auditDate&dir=ASC",
        param: {
            "mainId": $("#id").val()
        },
        event: {
            'reloadData': function(e) {
                //获取表格上所有字段
                var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "costPay");

                //大于够格长度时
                if(itemArr.length > 15){
					itemTableObj.attr("style", "overflow-x:auto;overflow-y:auto;height:480px;");
                }
			}
        },
        type: 'view',
//        title: '订票信息',
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
            },
            width : 70
        },{
            name: 'auditDate',
            display: '核算日期'
        },{
            name: 'airName',
            display: '乘机人'
        },{
            name: 'airId',
            display: 'airId',
            type : 'hidden'
        },{
            name: 'airline',
            display: '航空公司',
            align :'left',
            process : function(v){
            	return '<span style="padding:0px 0px 0px 10px;">'+v+'</span>';
            }
        },{
            name: 'airlineId',
            display: 'airlineId',
            type : 'hidden'
        },{
            name: 'flightNumber',
            display: '车次/航班号',
            align :'left',
            process : function(v){
            	return '<span style="padding:0px 0px 0px 10px;">'+v+'</span>';
            }
        },{
            name: 'flightTime',
            display: '乘机时间'
        },{
            name: 'arrivalTime',
            display: '到达时间'
        },{
            name: 'departPlace',
            display: '出发地点'
        },{
            name: 'arrivalPlace',
            display: '到达地点'
        },{
            name: 'costPay',
            display: '实际付款金额',
            align :'right',
            process : function(v){
            	return '<span style="padding:0px 10px 0px 0px;">'+moneyFormat2(v)+'</span>';
            }
        }]
    });
});