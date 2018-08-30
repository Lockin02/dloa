$(document).ready(function() {
	//邮件渲染
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check',
		formCode : 'requirement'
	});
	//验证信息
	validate({
		"applyName" : {
			required : true
		},
		"applyDeptName" : {
			required : true
		},
		"userName" : {
			required : true
		},
		"userPhone" : {
			required : true
		},
		"userDeptName" : {
			required : true
		},
		"userCompanyName" : {
			required : true
		},
		"beginDate" : {
			required : true
		},
		"returnDate" : {
			required : true
		},
		"requireType" : {
			required : true
		}
	});
	//邮件接收人及id
	var sendName = $('#TO_NAME').val();
	var sendId = $('#TO_ID').val();
	//使用人
	$("#userName").yxselect_user({
		hiddenId : 'userId',
		isGetDept : [true, "userDeptId", "userDeptName"],
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					$('#userCompanyCode').val(returnValue.companyCode);
					$('#userCompanyName').val(returnValue.companyName);
					$('#TO_NAME').val(sendName+','+$('#userName').val());
					$('#TO_ID').val(sendId+','+$('#userId').val());
				}
			}
		}
	});
	//使用部门
	$("#userDeptName").yxselect_dept({
		hiddenId : 'userDeptId',
		event : {
			'selectReturn' : function(e, data) {
				$("#userName").yxselect_user("remove").val('');
				$("#userName").yxselect_user({
					hiddenId : 'userId',
					deptIds : data.val
				});
			},
			'clearReturn' : function(e){
				$("#userName").yxselect_user("remove");
				$("#userName").yxselect_user({
					hiddenId : 'userId'
				});
			}
		}
	});
	//根据长短期判断是否需要填写归还时间
	$('#requireType').change(function(){
		if( $(this).val() == 1 ){//使用类型为长期,去掉必填
			$('#returnDate').val('');
			$('#returnDate').removeClass("validate[required]").parent('td').prev('td').find('span').css('color','black');
		}else{
			$('#returnDate').addClass("validate[required]").parent('td').prev('td').find('span').css('color','blue');
		}
	});
	$('#useCode').change(function(){
		$('#useName').val($('#useCode').get(0).options[$('#useCode').get(0).selectedIndex].innerText);
	});
})

//项目编号必填判断
function removeProject() {
	$("#projectCode").yxcombogrid_rdprojectfordl("remove");
	$("#projectCode").yxcombogrid_esmproject("remove");
	$("#projectCode").val("");
	$("#projectId").val("");
	projectSelect();
}

// 初始化项目选择
function projectSelect() {
	$val = $("#useCode").find("option:selected").val();
	if ($val == 'ZCYT-YFL') {
		// 研发项目渲染啊
		$("#projectCode").removeClass('readOnlyTxtNormal')
				.addClass('txt validate[required]');
		$("#projectCode").yxcombogrid_rdprojectfordl({
			hiddenId : 'projectId',
			nameCol : 'projectCode',
			isShowButton : false,
			height : 250,
			isFocusoutCheck : false,
			gridOptions : {
				param : {
					'is_delete' : 0
				},
				isTitle : true,
				showcheckbox : false,
				event : {
					'row_dblclick' : function(e, row, data) {
						$("#projectName").val(data.projectName);
					}
				}
			}
		});
		$(".myspan").show();
	} else if ($val == 'ZCYT-GCFW') {
		// 工程项目渲染
		$("#projectCode").removeClass('readOnlyTxtNormal')
				.addClass('txt validate[required]');
		$("#projectCode").yxcombogrid_esmproject({
			hiddenId : 'projectId',
			nameCol : 'projectCode',
			isShowButton : false,
			height : 250,
			gridOptions : {
				isTitle : true,
				showcheckbox : false,
				event : {
					'row_dblclick' : function(e, row, data) {
						$("#projectName").val(data.projectName);
						$("#projectType").val(data.category);
					}
				}
			}
		});
		$(".myspan").show();
	} else {
		$("#projectCode").removeClass('txt').addClass('readOnlyTxtNormal');
		$("#projectCode").removeClass('validate[required]');
		$(".myspan").hide();
	}
}

//开始时间与结束时间差验证
function timeCheck($t){
	if($t.value!=''){
		var s = plusDateInfo('beginDate','returnDate');
		if(s < 0) {
			alert("开始时间不能比归还时间晚！");
			$t.value = "";
			return false;
		}
	}
}

// 根据从表的残值动态计算应付总金额
function countAmount() {
	// 获取当前的行数即卡片的资产数
	var curRowNum = $("#itemTable").yxeditgrid("getCurRowNum")
	var rowAmountVa = 0;
	var cmps = $("#itemTable").yxeditgrid("getCmpByCol", "expectAmount");
	cmps.each(function() {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	$("#expectAmount").val(rowAmountVa);
	$("#expectAmount_v").val(moneyFormat2(rowAmountVa));
	return true;
}
