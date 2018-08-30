
//初始化一些字段
var objName = 'message';
var initId = 'feeTbl_c';
var myUrl = '?model=flights_message_message&action=ajaxGet';
var actionType = 'edit';
var isCompanyReadonly = true; //公司是否只读

$(function() {
	//初始化机票类型
	setTicketCheck();
	changeType($("#ticketTypeHidden").val());

	// 单选订票机构
	$("#organization").yxcombogrid_ticket( {
		hiddenId : 'organizationId',
		gridOptions : {
				param : {"findTicketVal" : "票务"}
		}
	});

	//选中当天最低
	var isLow = $("#isLowHidden").val();
	if(isLow == '0'){
		$("#reson").show();
		$("#resons").show();
		$("input[name='message[isLow]']").each(function(){
			if($(this).val()*1 == isLow*1){
				$(this).attr('checked',true);
			}
		});
	}else{
		$("#reson").hide();
		$("#resons").hide();
		$("input[name='message[isLow]']").each(function(){
			if($(this).val()*1 == isLow*1){
				$(this).attr('checked',true);
			}
		});
	}

    validate({
        "organization": {
            required: true
        },
        "fullFare_v": {
            required: true
        },
        "constructionCost_v": {
            required: true
        },
        "fuelCcharge_v": {
            required: true
        },
        "auditDate": {
            required: true
        }
    });

	var itemTableObj = $("#itemTable");
	itemTableObj.yxeditgrid( {
		objName : 'message[items]',
		url : '?model=flights_message_messageitem&action=listJson',
		title : '改签/退票信息',
		isAddAndDel : false,
        event: {
        	'reloadData': function(e) {
				var professionArr   = itemTableObj.yxeditgrid("getCmpByCol", "changeNum");
				if (professionArr.length == 0) {
					itemTableObj.hide();
				}else{
					professionArr.each(function(){
						var rowNum = $(this).data("rowNum");
						var professionObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"profession");
						var changeNumObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"changeNum");
						var arriveDateObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"arriveDate");
						var ticketSumObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"ticketSum");
						if(professionObj.val() != "1"){
							changeNumObj.removeClass("txtmiddle").removeClass("validate[required]").addClass('readOnlyTxtNormal').attr("readonly",true);
							arriveDateObj.removeClass("txtmiddle").removeClass("validate[required]").addClass('readOnlyTxtNormal').attr("readonly",true).attr("disabled",true);
						}else{
							ticketSumObj.removeClass("txtmiddle").removeClass("validate[required]").addClass('readOnlyTxtNormal').attr("readonly",true);
						}
					});
				}
            }
        },
		param : {
			mainId : $("#id").val()
		},
		colModel : [{
			name : 'id',
			display : 'id',
			type : "hidden"
		}, {
			name : 'profession',
			display : '类型',
			type : 'statictext',
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
			width : 120,
            validation: {
                required: true
            }
		}, {
			name : 'startDate',
			display : '乘机/退票日期',
			type : 'date',
			width : 110,
            validation: {
                required: true
            }
		}, {
			name : 'arriveDate',
			display : '到达日期',
			type : 'date',
			width : 100,
            validation: {
                required: true
            }
		}, {
			name : 'changeCost',
			display : '改签/退票手续费',
			width : 120,
			type : 'money',
            validation: {
                required: true
            },
            event: {
                blur: function() {
                    var rowNum = $(this).data("rowNum");
					var professionObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"profession");
					if(professionObj.val() != "1"){
						var ticketSumObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"ticketSum");
						var actualCost = $("#actualCost").val();
						ticketSumObj.val(moneyFormat2(accSub(actualCost,$(this).val(),2)));
					}
                }
            }
		}, {
			name : 'ticketSum',
			display : '退票金额',
			width : 80,
			type : 'money',
            validation: {
                required: true
            },
            event: {
                blur: function() {
                    var rowNum = $(this).data("rowNum");
					var professionObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"profession");
					if(professionObj.val() != "1"){
						var changeCostObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"changeCost");
						var actualCost = $("#actualCost").val();
						changeCostObj.val(moneyFormat2(accSub(actualCost,$(this).val(),2)));
					}
                }
            }
		},{
			name : 'changeReason',
			display : '改签/退票原由',
			width : 210,
            validation: {
                required: true
            }
		}]
	});
});

//计算实际订票价格
function calActCost() {
	var fullFare = $("#fullFare").val();
	var constructionCost = $("#constructionCost").val();
	var serviceCharge = $("#serviceCharge").val();
	var fuelCcharge = $("#fuelCcharge").val();
	var all = accAdd(accAdd(fullFare, constructionCost, 2), accAdd(serviceCharge, fuelCcharge, 2), 2);
	setMoney("actualCost",all);
	var costPayObj = $("#costPay");
	if(costPayObj.length > 0){
		costPayObj.val(all);
	}

	//更新从表
	var itemTableObj = $("#itemTable");
	var professionArr = itemTableObj.yxeditgrid("getCmpByCol", "changeNum");
	if(professionArr.length > 0){
		professionArr.each(function(){
			//行号
			var rowNum = $(this).data("rowNum");
			var professionObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"profession");
			if(professionObj.val() != "1"){
				var ticketSumObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"ticketSum");
				var changeCostObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"changeCost");
				ticketSumObj.val(moneyFormat2(accSub(all,changeCostObj.val(),2)));
			}
		});
	}
}
