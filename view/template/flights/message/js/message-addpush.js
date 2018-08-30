//初始化一些字段
var objName = 'message';
var initId = 'feeTbl_c';
var myUrl = '?model=flights_require_require&action=ajaxGet';
var actionType = 'edit';
var isCompanyReadonly = true; //公司是否只读

//缓存订票需求乘机人
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

	//获取直接录入订票信息ID
	var requireId = $("#id").val();
    //获取订票需求乘机人信息并缓存
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

	//实例化从表
	initRequireDetail(requireId);

    validate({
        "organization": {
            required: true
        }
    });

	//绑定一个checkform
    $("form").submit(function(){
		var itemTableObj = $("#itemTable");
		var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "airName");
		if(itemArr.length == 0){
			alert('订票信息详情不能为空');
			return false;
		}
    });
});

//初始化需求订票人
function initRequireDetail(requireId){
	var itemTableObj = $("#itemTable");

	itemTableObj.yxeditgrid("remove").yxeditgrid( {
		objName : 'message[items]',
//		url : 'index1.php?model=flights_require_require&action=requireQuerylistJson',
//        param : {
//            id : requireId
//        },
        data : airArr,
        title : '订票信息详情',
        event: {
            'reloadData': function() {
            	var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "airName");
            	if (itemArr.length > 0) {
                    //循环
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
				display : '核算日期',
				width : 'txtmiddle',
				width : 80,
				type : 'date',
				validation : {
					required : true
				}
			},{
				name : 'airName',
				display : '乘机人名称',
				type : 'hidden'
			},
			{
				name : 'airId',
				readonly : true,
				display : '乘机人',
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
				display : '航空公司',
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
							param : {"findVal" : "航空"}
						}
					});
				}
			},
			{
				name : 'airlineId',
				tclass : 'txt',
				display : '航空公司ID',
				type : 'hidden'
			},
			{
				name : 'flightNumber',
				tclass : 'txt',
				display : '车次/航班号',
				width : 120,
				validation : {
					required : true
				}
			},
			{
				name : 'flightTime',
				display : '乘机时间',
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
				display : '到达时间',
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
				display : '出发地点',
				width : 100,
				validation : {
					required : true
				}
			},
			{
				name : 'arrivalPlace',
				display : '到达地点',
				width : 100,
				validation : {
					required : true
				}
			},
			{
				name : 'isLow',
				type : 'checkbox',
				display : '前后2小时最低',
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
				display : '票面价格',
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
				display : '机场建设费',
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
				display : '服务费',
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
				display : '燃油附加费',
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
				display : '实际订票金额',
				type : 'money',
				width : 80,
				validation : {
					required : true
				}
			},
			{
				name : 'lowremark',
				tclass : 'txt',
				display : '未采用最低价原因',
				validation : {
					required : true
				},
				width : 150
			},
			{
				name : 'remark',
				tclass : 'txt',
				display : '备注',
				width : 120
			}
		]
	});
}

//去除从表最低价验证
function cancelReason(rowNum){
    var itemTableObj = $("#itemTable");
	itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "lowremark").removeClass('validate[required]');
}

//启用从表最低价验证
function setReason(rowNum){
    var itemTableObj = $("#itemTable");
	itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "lowremark").addClass('validate[required]');
}

//从表金额计算方法
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