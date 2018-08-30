//��ʼ��һЩ�ֶ�
var objName = 'message';
var initId = 'feeTbl_c';
var myUrl = '?model=flights_require_require&action=ajaxGet';
var actionType = 'edit';
var isCompanyReadonly = true; //��˾�Ƿ�ֻ��

//���涩Ʊ����˻���
var airOptions = [{'name' : '','value' : ''}];
var airArr;

$(document).ready(function() {
	$("#TO_NAME").yxselect_user({
		mode : 'check',
		hiddenId : 'defaultUserId',
		formCode : 'defaultUserName'
	});

	$("#ADDNAMES").yxselect_user({
		mode : 'check',
		hiddenId : 'ccUserId',
		formCode : 'ccUserName'
	});
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

	//��ȡֱ��¼�붩Ʊ��ϢID
	var requireId = $("#id").val();
    //��ȡ��Ʊ����˻�����Ϣ������
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

	//ʵ�����ӱ�
	initRequireDetail(requireId);

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

//��ʼ������Ʊ��
function initRequireDetail(requireId){
	var itemTableObj = $("#itemTable");

	itemTableObj.yxeditgrid("remove").yxeditgrid( {
		objName : 'message[items]',
//		url : 'index1.php?model=flights_require_require&action=requireQuerylistJson',
//        param : {
//            id : requireId
//        },
        data : airArr,
        title : '��Ʊ��Ϣ����',
        event: {
            'reloadData': function() {
            	var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "airName");
            	if (itemArr.length > 0) {
                    //ѭ��
            		var airIdArr = [];
            		var airNameArr = [];
                    itemArr.each(function() {
                    	airIdArr.push(itemTableObj.yxeditgrid("getCmpByRowAndCol",$(this).data('rowNum'),"airId").val());
                    	airNameArr.push(itemTableObj.yxeditgrid("getCmpByRowAndCol",$(this).data('rowNum'),"airName").val());
                    });
                    $("#TO_ID").val(airIdArr.toString());
                	$("#TO_NAME").val(airNameArr.toString());
                }
            }
        },
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
				display : 'ǰ��2Сʱ���',
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