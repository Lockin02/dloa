
//��ʼ��һЩ�ֶ�
var objName = 'message';
var initId = 'feeTbl_c';
var myUrl = '?model=flights_message_message&action=ajaxGet';
var actionType = 'edit';
var isCompanyReadonly = true; //��˾�Ƿ�ֻ��

$(function() {
	//��ʼ����Ʊ����
	setTicketCheck();
	changeType($("#ticketTypeHidden").val());

	// ��ѡ��Ʊ����
	$("#organization").yxcombogrid_ticket( {
		hiddenId : 'organizationId',
		gridOptions : {
				param : {"findTicketVal" : "Ʊ��"}
		}
	});

	//ѡ�е������
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
		title : '��ǩ/��Ʊ��Ϣ',
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
			display : '����',
			type : 'statictext',
			process : function(v, row) {
				if (v == "1") {
					return "<span>��ǩ</span>";
				} else {
					return "<span style='color: red;' >��Ʊ</span>";
				}
			},
			width : 80
		}, {
			name : 'changeNum',
			display : '��ǩ�����',
			width : 120,
            validation: {
                required: true
            }
		}, {
			name : 'startDate',
			display : '�˻�/��Ʊ����',
			type : 'date',
			width : 110,
            validation: {
                required: true
            }
		}, {
			name : 'arriveDate',
			display : '��������',
			type : 'date',
			width : 100,
            validation: {
                required: true
            }
		}, {
			name : 'changeCost',
			display : '��ǩ/��Ʊ������',
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
			display : '��Ʊ���',
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
			display : '��ǩ/��Ʊԭ��',
			width : 210,
            validation: {
                required: true
            }
		}]
	});
});

//����ʵ�ʶ�Ʊ�۸�
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

	//���´ӱ�
	var itemTableObj = $("#itemTable");
	var professionArr = itemTableObj.yxeditgrid("getCmpByCol", "changeNum");
	if(professionArr.length > 0){
		professionArr.each(function(){
			//�к�
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
