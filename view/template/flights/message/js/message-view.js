//初始化一些字段
var objName = 'message';
var initId = 'feeTbl_c';
var actionType = 'view';
var myUrl = '?model=flights_message_message&action=ajaxGet';

$(document).ready(function() {
	//界面显示
	changeType($("#ticketTypeHidden").val());
	
	var itemTableObj = $("#itemTable");
	itemTableObj.yxeditgrid( {
		objName : 'message[items]',
		url : '?model=flights_message_messageitem&action=listJson',
		type : 'view',
		title : '改签/退票信息',
        event: {
        	'reloadData': function(e) {
				var professionArr   = itemTableObj.yxeditgrid("getCmpByCol", "profession");
				if (professionArr.length == 0) {
					itemTableObj.hide();
				}
            }
        },
		param : {
			mainId : $("#id").val()
		},
		colModel : [ {
			name : 'profession',
			display : '类型',
			process : function(v, row) {
				if (v == "1") {
					return "<span>改签</span>";
				} else {
					return "<span style='color: red;' >退票</span>";
				}
			},
			width : 80
		}, {
			name : 'changeNum',
			display : '改签航班号',
			width : 120
		}, {
			name : 'startDate',
			display : '乘机/退票日期',
			width : 110
		}, {
			name : 'arriveDate',
			display : '到达日期',
			width : 100
		}, {
			name : 'changeCost',
			display : '改签/退票手续费',
			width : 110,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'ticketSum',
			display : '退票金额',
			width : 90,
			process : function(v){
				return moneyFormat2(v);
			}
		},{
			name : 'changeReason',
			display : '改签/退票原由',
			width : 220
		}]
	});
});
