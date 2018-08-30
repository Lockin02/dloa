//��ʼ��һЩ�ֶ�
var objName = 'message';
var initId = 'feeTbl_c';
var myUrl = '?model=flights_require_require&action=ajaxGet';
var actionType = 'add';
var isCompanyReadonly = true; //��˾�Ƿ�ֻ��

$(document).ready(function() {
	// ��ѡ��Ʊ����
	$("#organization").yxcombogrid_ticket( {
		hiddenId : 'organizationId',
		gridOptions : {
				param : {"findTicketVal" : "Ʊ��"}
		}
	});
	$("#requireNo").yxcombogrid_require({
		hiddenId : 'requireId',
		height : 300,
		gridOptions : {
			isTitle : true,
			param : {"ticketMsg" : "δ����"},//"ExaStatus" : "���",
			event : {
				row_dblclick : function(e,row,data){
					var airLineArr = $("input[id^='itemTable_cmp_airline']")
					if(airLineArr.length > 0){
						airLineArr.each(function(){
							var rowNum = $(this).data("rowNum");
							if($(this).attr("id") == "itemTable_cmp_airline" + rowNum){
								$(this).yxcombogrid_ticket("remove");
							}
						});
					}
					initRequireDetail(data.id);
					//��Ⱦ�༭
					initCostTypeEdit(data);
				}
			}
		}
	});

    //ʵ��������
    initCostType();

    var itemTableObj = $("#itemTable");
    itemTableObj.yxeditgrid({
        objName: 'message[items]',
        isAddOneRow: true,
        title : '��Ʊ��ϸ��Ϣ',
		colModel : [{
				name : 'auditDate',
				display : '��������',
				width : 'txtmiddle',
				width : 80,
				type : 'date',
				validation : {
					required : true
				},
				value : $("#thisDate").val()
			},{
				name : 'airId',
				display : '�˻���ID',
				type : 'hidden'
			},
	        {
	            name: 'airName',
	            tclass: 'txt',
//	            readonly: true,
	            display: '�˻���',
	            width: 100,
	            validation: {
	                required: true
	            },
	            process: function($input, rowData) {
	                var rowNum = $input.data("rowNum");
	                var g = $input.data("grid");
	                $input.yxselect_user({
	                    hiddenId: 'itemTable_cmp_airId' + rowNum,
	                    formCode : 'flights'
	                });
	            }
	        },
			{
				name : 'airline',
				tclass : 'txt',
				display : '���չ�˾',
				width : 120,
				validation : {
					required : true
				},
				process : function($input, rowData) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_ticket( {
						hiddenId : 'itemTable_cmp_airlineId' + rowNum,
						gridOptions : {
							param : {"findVal" : "����"}
						}
					});
				}
			},
			{
				name : 'airlineId',
				tclass : 'txt',
				display : '���չ�˾ID',
				type : 'hidden'
			},
			{
				name : 'flightNumber',
				tclass : 'txt',
				display : '����/�����',
				width : 120,
				validation : {
					required : true
				}
			},
			{
				name : 'flightTime',
				display : '�˻�ʱ��',
				width : 130,
				event : {
					click : function() {
						WdatePicker({
							dateFmt:"yyyy-MM-dd HH:mm:00"
						});
					}
				},
				validation : {
					required : true
				}
			},
			{
				name : 'arrivalTime',
				display : '����ʱ��',
				width : 130,
				event : {
					click : function() {
						WdatePicker({
							dateFmt:"yyyy-MM-dd HH:mm:00"
						});
					}
				},
				validation : {
					required : true
				}
			},
			{
				name : 'departPlace',
				display : '�����ص�',
				width : 100,
				validation : {
					required : true
				}
			},
			{
				name : 'arrivalPlace',
				display : '����ص�',
				width : 100,
				validation : {
					required : true
				}
			},
			{
				name : 'isLow',
				type : 'checkbox',
				display : 'ǰ��2Сʱ���',
				checked : false,
				checkVal : '1',
				width : 60,
				value : 0,
				event : {
					click : function() {
						var rowNum = $(this).data("rowNum");
						if ($(this).attr("checked") == true) {
							itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"lowremark").val("").addClass("readOnlyTxtNormal").removeClass("txt").attr("readonly",true);
							cancelReason(rowNum);
						} else {
							itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"lowremark").addClass("txt").removeClass("readOnlyTxtNormal").attr("readonly",false);
							setReason(rowNum)
						}
					}
				}
			},
			{
				name : 'fullFare',
				tclass : 'txt',
				display : 'Ʊ��۸�',
				width : 80,
				type : 'money',
				validation : {
					required : true
				},
				event : {
					blur : function() {
						var rowNum = $(this).data("rowNum");
						calDetail(rowNum);
					}
				}
			},
			{
				name : 'constructionCost',
				tclass : 'txt',
				display : '���������',
				width : 80,
				type : 'money',
				validation : {
					required : true
				},
				event : {
					blur : function() {
						var rowNum = $(this).data("rowNum");
						calDetail(rowNum);
					}
				}
			},
			{
				name : 'serviceCharge',
				tclass : 'txt',
				display : '�����',
				width : 80,
				type : 'money',
				event : {
					blur : function() {
						var rowNum = $(this).data("rowNum");
						calDetail(rowNum);
					}
				}
			},
			{
				name : 'fuelCcharge',
				tclass : 'txt',
				display : 'ȼ�͸��ӷ�',
				width : 80,
				type : 'money',
				validation : {
					required : true
				},
				event : {
					blur : function() {
						var rowNum = $(this).data("rowNum");
						calDetail(rowNum);
					}
				}
			},
			{
				name : 'actualCost',
				tclass : 'txt',
				readonly : true,
				tclass : 'readOnlyTxtNormal',
				display : 'ʵ�ʶ�Ʊ���',
				type : 'money',
				width : 80,
				validation : {
					required : true
				}
			},
			{
				name : 'lowremark',
				tclass : 'txt',
				display : 'δ������ͼ�ԭ��',
				validation : {
					required : true
				},
				width : 150
			},
			{
				name : 'remark',
				tclass : 'txt',
				display : '��ע',
				width : 120
			}
		]
    });

    validate({
        "organization": {
            required: true
        }
    });

	//��һ��checkform
    $("form").submit(function(){
		var itemTableObj = $("#itemTable");
		var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "airName");
		if(itemArr.length == 0){
			alert('��Ʊ��Ϣ���鲻��Ϊ��');
			return false;
		}
    });
});

//ȥ���ӱ���ͼ���֤
function cancelReason(rowNum){
    var itemTableObj = $("#itemTable");
	itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "lowremark").removeClass('validate[required]');
}

//���ôӱ���ͼ���֤
function setReason(rowNum){
    var itemTableObj = $("#itemTable");
	itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "lowremark").addClass('validate[required]');
}

//�ӱ�����㷽��
function calDetail(rowNum) {
    var itemTableObj = $("#itemTable");
    var fullFare = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "fullFare").val();
    if (!fullFare) {
        fullFare = 0;
    }
    var constructionCost = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "constructionCost").val();
    if (!constructionCost) {
        constructionCost = 0;
    }
    var serviceCharge = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "serviceCharge").val();
    if (!serviceCharge) {
        serviceCharge = 0;
    }
    var fuelCcharge = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "fuelCcharge").val();
    if (!fuelCcharge) {
        fuelCcharge = 0;
    }
    var all = accAdd(accAdd(fullFare, constructionCost, 2), accAdd(serviceCharge, fuelCcharge, 2), 2);
    setMoney("itemTable_cmp_actualCost" + rowNum,all);
}



//��ʼ������Ʊ��
function initRequireDetail(requireId){
    //��ȡ��Ʊ����˻�����Ϣ������
	var airArr;
	var airOptions = [{'name' : '','value' : ''}];
	$.ajax({
		type : 'POST',
		url : 'index1.php?model=flights_require_require&action=requireQuerylistJson',
		data : {
			id : requireId,
			dir : 'ASC'
		},
	    async: false,
		success : function(data) {
			airArr = eval("(" + data + ")");
			if(airArr.length > 0){
				for(var i = 0; i<airArr.length;i++){
					airOptions.push({'name' : airArr[i].airName,'value' : airArr[i].airId});
				}
			}
		}
	});

	var itemTableObj = $("#itemTable");
	itemTableObj.yxeditgrid("remove").yxeditgrid( {
		objName : 'message[items]',
//		url : 'index1.php?model=flights_require_require&action=requireQuerylistJson',
//        param : {
//                id : requireId
//            },
        data : airArr,
        title : '��Ʊ��ϸ��Ϣ',
		isAddOneRow : true,
		colModel : [{
				name : 'auditDate',
				display : '��������',
				width : 'txtmiddle',
				width : 80,
				type : 'date',
				validation : {
					required : true
				}
			},{
				name : 'airName',
				display : '�˻�������',
				type : 'hidden'
			},
			{
				name : 'airId',
				readonly : true,
				display : '�˻���',
				width : 110,
				type : 'select',
				options : airOptions,
				event : {
					blur : function() {
						var rowNum = $(this).data("rowNum");
						itemTableObj.yxeditgrid("setRowColValue",rowNum,"airName",$(this).find(":selected").text());
					}
				},
				validation : {
					required : true
				}
			},
			{
				name : 'airline',
				tclass : 'txt',
				display : '���չ�˾',
				width : 120,
				validation : {
					required : true
				},
				process : function($input, rowData) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_ticket( {
						hiddenId : 'itemTable_cmp_airlineId' + rowNum,
						gridOptions : {
							param : {"findVal" : "����"}
						}
					});
				}
			},
			{
				name : 'airlineId',
				tclass : 'txt',
				display : '���չ�˾ID',
				type : 'hidden'
			},
			{
				name : 'flightNumber',
				tclass : 'txt',
				display : '����/�����',
				width : 120,
				validation : {
					required : true
				}
			},
			{
				name : 'flightTime',
				display : '�˻�ʱ��',
				width : 130,
				event : {
					click : function() {
							WdatePicker({
								dateFmt:"yyyy-MM-dd HH:mm:00"
							});
					}
				},
				validation : {
					required : true
				}
			},
			{
				name : 'arrivalTime',
				display : '����ʱ��',
				width : 130,
				event : {
					click : function() {
							WdatePicker({
								dateFmt:"yyyy-MM-dd HH:mm:00"
							});
					}
				},
				validation : {
					required : true
				}
			},
			{
				name : 'departPlace',
				display : '�����ص�',
				width : 100,
				validation : {
					required : true
				}
			},
			{
				name : 'arrivalPlace',
				display : '����ص�',
				width : 100,
				validation : {
					required : true
				}
			},
			{
				name : 'isLow',
				type : 'checkbox',
				display : '�������',
				checked : false,
				checkVal : '1',
				width : 60,
				event : {
					click : function() {
						var rowNum = $(this).data("rowNum");
						if ($(this).attr("checked") == true) {
							itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"lowremark").val("").addClass("readOnlyTxtNormal").removeClass("txt").attr("readonly",true);
							cancelReason(rowNum);
						} else {
							itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"lowremark").addClass("txt").removeClass("readOnlyTxtNormal").attr("readonly",false);
							setReason(rowNum)
						}
					}
				}
			},
			{
				name : 'fullFare',
				tclass : 'txt',
				display : 'Ʊ��۸�',
				width : 80,
				type : 'money',
				validation : {
					required : true
				},
				event : {
					blur : function() {
						var rowNum = $(this).data("rowNum");
						calDetail(rowNum);
					}
				}
			},
			{
				name : 'constructionCost',
				tclass : 'txt',
				display : '���������',
				width : 80,
				type : 'money',
				validation : {
					required : true
				},
				event : {
					blur : function() {
						var rowNum = $(this).data("rowNum");
						calDetail(rowNum);
					}
				}
			},
			{
				name : 'serviceCharge',
				tclass : 'txt',
				display : '�����',
				width : 80,
				type : 'money',
				validation : {
					required : true
				},
				event : {
					blur : function() {
						var rowNum = $(this).data("rowNum");
						calDetail(rowNum);
					}
				}
			},
			{
				name : 'fuelCcharge',
				tclass : 'txt',
				display : 'ȼ�͸��ӷ�',
				width : 80,
				type : 'money',
				validation : {
					required : true
				},
				event : {
					blur : function() {
						var rowNum = $(this).data("rowNum");
						calDetail(rowNum);
					}
				}
			},
			{
				name : 'actualCost',
				tclass : 'txt',
				readonly : true,
				tclass : 'readOnlyTxtNormal',
				display : 'ʵ�ʶ�Ʊ���',
				type : 'money',
				width : 80,
				validation : {
					required : true
				}
			},
			{
				name : 'lowremark',
				tclass : 'txt',
				display : 'δ������ͼ�ԭ��',
				validation : {
					required : true
				},
				width : 150
			},
			{
				name : 'remark',
				tclass : 'txt',
				display : '��ע',
				width : 120
			}
		]
	});
}