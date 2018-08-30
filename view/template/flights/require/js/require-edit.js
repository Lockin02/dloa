//初始化一些字段
var objName = 'require';
var initId = 'feeTbl_c';
var myUrl = '?model=flights_require_require&action=ajaxGet';
var actionType = 'edit';
var isCompanyReadonly = true; //公司是否只读

$(document).ready(function() {
    //初始化机票类型
    setTicketCheck();
    changeType($("#ticketTypeHidden").val());

    //初始化明细
    initDetail();

	validate({
		"startPlace" : {
			required : true
		},
		"endPlace" : {
			required : true
		},
		"startDate" : {
			required : true
		},
		"outReason" : {
			required : true
		}
	});
	
	//期望日期时间段，自动间隔3小时 
	$("#flyStartTime").bind("blur",function(){
		if($("#flyStartTime").val()<=21){
			var sum = accAdd($("#flyStartTime").val(),3);
			$("#flyEndTime").val(sum);
		}else if($("#flyStartTime").val()==22){
			$("#flyEndTime").val(1);
		}else if($("#flyStartTime").val()==23){
			$("#flyEndTime").val(2);
		}else if($("#flyStartTime").val()==24){
			$("#flyEndTime").val(3);
		}else{
			alert('输入值有误！')
			$("#flyStartTime").val('');
			$("#flyEndTime").val('');
		}
	});
});

//初始化列表
function initDetail() {
    var itemTableObj = $("#itemTable");
    itemTableObj.yxeditgrid({
        url: "?model=flights_require_requiresuite&action=listJson",
        param: {
            "mainId": $("#id").val()
        },
        objName: 'require[items]',
        event: {
        	'reloadData': function(e) {
				var cardTypeArr   = itemTableObj.yxeditgrid("getCmpByCol", "cardType");
				if (cardTypeArr.length > 0) {
					cardTypeArr.each(function(){
               			var rowNum = $(this).data("rowNum");
	            		//获取相关列
						var validDateObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"validDate");
						var birthDateObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"birthDate");
						var nationObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"nation");
						var cardNoObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"cardNo");
						var airNameObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"airName");
						var employeeTypeObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"employeeType");

						//类型处理
						if($(this).val() != 'JPZJLX-01'){
							validDateObj.removeClass('readOnlyTxtNormal').addClass('txt').attr('readonly',false).attr('disabled',false).bind('focus',WdatePicker);
							birthDateObj.removeClass('readOnlyTxtNormal').addClass('txt').attr('readonly',false).attr('disabled',false).bind('focus',WdatePicker);
							nationObj.removeClass('readOnlyTxtNormal').addClass('txt').attr('readonly',false);
							cardNoObj.removeClass('readOnlyTxtNormal').addClass('txt').attr('readonly',false);

							//启用非身份证类验证
							openDetailCheck(rowNum);
						}
						//外部员工类型处理
						if(employeeTypeObj.val() == "YGLX-02"){
							airNameObj.removeClass('readOnlyTxtNormal').addClass('txt').attr('readonly',false).width(114);
							cardNoObj.removeClass('readOnlyTxtNormal').addClass('txt').attr('readonly',false);
						}
					});
				}
            }
        },
        colModel: [{
            name: 'id',
            display: 'id',
            width: 80,
            type: "hidden"
        },{
            name: 'employeeType',
            display: '员工类型',
            width: 80,
            type: 'select',
			datacode : 'YGLX',
			event: {
            	change: function(e) {
               		var rowNum = $(this).data("rowNum");
            		//获取相关列
					var cardNoObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"cardNo");
					var airNameObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"airName");
					var airPhoneObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"airPhone");

					//数据清空处理
					cardNoObj.val('');
					airNameObj.val('');
					airPhoneObj.val('');

					//类型处理
					if($(this).val() == 'YGLX-01'){
						$(airNameObj).yxselect_user();
						airNameObj.removeClass('txt').addClass('readOnlyTxtNormal').attr('readonly',true);
						cardNoObj.removeClass('txt').addClass('readOnlyTxtNormal').attr('readonly',true);
					}else{
						$(airNameObj).yxselect_user('remove');
						airNameObj.removeClass('readOnlyTxtNormal').addClass('txt').attr('readonly',false).width(114);
						cardNoObj.removeClass('readOnlyTxtNormal').addClass('txt').attr('readonly',false);
						}
                }
            }
		},{
            name: 'airName',
            display: '姓名',
            width: 80,
            readonly: true,
			validation : {
				required : true
			},
            process: function($input, rowData) {
                var rowNum = $input.data("rowNum");
                var g = $input.data("grid");
                var employeeTypeObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"employeeType");
                var cardNoObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"cardNo");
                if(employeeTypeObj.val() == 'YGLX-01'){
                    $input.yxselect_user({
                        hiddenId: 'itemTable_cmp_airId' + rowNum,
                        formCode: 'flirequire',
                        event: {
                            select: function(e, obj) {
                            	if(!obj) return ;
    							//获取人事档案内的内容
    							var personInfo = getPersonInfo(obj.val);
    							if (personInfo) {
    								itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"airPhone").val(personInfo.mobile); //手机号码
    								//本行数据显示
                	 				var cardType = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"cardType").val();
    								var cardNoObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"cardNo");
    								var cardNoHiddenObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"cardNoHidden");
                	 				if(cardType == 'JPZJLX-01'){
    				                	if($("#requireId").val() != obj.val){
    				                   		cardNoObj.val(formatIdCard(personInfo.identityCard));
    				                	}else{
    				                   		cardNoObj.val(personInfo.identityCard);
    				                	}
    			                   		cardNoHiddenObj.val(personInfo.identityCard);
                	 				}else{
    									cardNoObj.val('');
    									itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"birthDate").val('');
                	 				}
    							}

    							//启用表单验证
    							openCheck(rowNum);
    						},
    						clearReturn : function(){
            	 				itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum, "cardNo").val('');
    							itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"airPhone").val('');
    							itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"validDate").val('');
    							itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"birthDate").val('');
    							itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"nation").val('');
    							//关闭表单验证
    							closeCheck(rowNum);
    						}
                        }
                    });
                }
            }
        },
        {
        	name : 'sex',
			display : '乘机人性别',
			width : 80,
			type : 'select',
			options : [{
				'name' : '男',
				'value' : '男'
			}, {
				'name' : '女',
				'value' : '女'
			}],
			validation : {
				required : true
			}
        },
        {
            name: 'airId',
            display: '人员编号',
            width: 80,
            type: "hidden",
            readonly: true
        },
        {
			name : 'airPhone',
			display : '手机号码',
			width : 80,
			validation : {
				required : true
			}
		}, {
            name: 'cardType',
            display: '证件类型',
            width: 80,
            type: 'select',
			datacode : 'JPZJLX',
            event: {
            	change: function(e) {
               		var rowNum = $(this).data("rowNum");
            		//先清空卡号
					var cardNoObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"cardNo");
					var validDateObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"validDate");
					var birthDateObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"birthDate");
					var nationObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"nation");
					var employeeTypeObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"employeeType");

					//数据清空处理
					cardNoObj.val('');
					validDateObj.val('');
					birthDateObj.val('');

					//类型处理
					if($(this).val() == 'JPZJLX-01'){
						validDateObj.removeClass('txt').addClass('readOnlyTxtNormal').attr('readonly',true).attr('disabled',true);
						birthDateObj.removeClass('txt').addClass('readOnlyTxtNormal').attr('readonly',true).attr('disabled',true);
						nationObj.removeClass('txt').addClass('readOnlyTxtNormal').attr('readonly',true);
						if(employeeTypeObj.val()=='YGLX-01'){
							cardNoObj.removeClass('txt').addClass('readOnlyTxtNormal').attr('readonly',true);
						}

						//关闭非身份证类验证
						closeDetailCheck(rowNum);
					}else{
						validDateObj.removeClass('readOnlyTxtNormal').addClass('txt validate[required]').attr('readonly',false).attr('disabled',false).bind('focus',WdatePicker);
						birthDateObj.removeClass('readOnlyTxtNormal').addClass('txt validate[required]').attr('readonly',false).attr('disabled',false).bind('focus',WdatePicker);
						nationObj.removeClass('readOnlyTxtNormal').addClass('txt validate[required]').attr('readonly',false);
						cardNoObj.removeClass('readOnlyTxtNormal').addClass('txt').attr('readonly',false);

						//当从表有人时才启用验证
						if(itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"airName").val()!=""){
							//启用非身份证类验证
							openDetailCheck(rowNum);
						}
					}
                }
            }
        },{
			name : 'cardNo',
			display : '证件号码',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 120,
			validation : {
				required : true
			}
		}, {
			name : 'cardNoHidden',
			display : '证件号码',
            type: "hidden"
		}, {
			name : 'validDate',
			readonly : true,
			display : '证件有效期',
			tclass : 'readOnlyTxtNormal',
			width : 80
		}, {
			name : 'birthDate',
			readonly : true,
			display : '出生日期',
			tclass : 'readOnlyTxtNormal',
			width : 80
		}, {
			name : 'nation',
			display : '国籍',
			readonly : true,
			tclass : 'readOnlyTxtNormal',
			width : 80
		}, {
			name : 'tourAgency',
			display : '常旅客机构',
			width : 80,
			type : 'select',
			datacode : 'CLKJG',
			emptyOption : true
		}, {
			name : 'tourCardNo',
			display : '常旅客卡号',
			width : 80
		}]
    });
}