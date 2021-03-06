$(document).ready(function(){
    // 客户类型
    customerTypeArr = getData('GZLB');
    addDataToSelect(customerTypeArr, 'categoryId');

	// 验证信息
	validate({
       "fileName" : {
           required : true
       },
		"signCompanyName" : {
			required : true
		},
		"stampExecution" : {
			required : true
		},
		"stampType" : {
			required : true
		},
		"contractType" : {
			required : true
		},
		"useMatters" : {
			required : true
		},
		"categoryId" : {
			required : true
		},
        "printDoubleSide" : {
            required : true
        },
        "fileNum" : {
            required : true
        },
        "filePageNum" : {
            required : true
        },
		"attn" : {
			required : true
		}
	});

	//邮件接收人渲染
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check',
		formCode : 'stampapply'
	});

	//盖章类型渲染
	$("#stampType").yxcombogrid_stampconfig({
		hiddenId : 'stampIds',
		height : 250,
		gridOptions : {
			isTitle : true,
			showcheckbox : true,
			event : {
				//'row_dblclick' : function(e, row, data) {
					//$('#businessBelongId').val(data.businessBelongId);
					//$('#legalPersonUsername').val(data.legalPersonUsername);
					//$('#legalPersonName').val(data.legalPersonName);
				//},
				'row_check' : function(e, checkbox, row, rowData) {
					if (checkbox.attr('checked')) {
						if($('#businessBelongId').val() == ''){
							$('#businessBelongId').val(rowData.businessBelongId);
							$('#legalPersonUsername').val(rowData.legalPersonUsername);
							$('#legalPersonName').val(rowData.legalPersonName);
							return true;
						}else if($('#businessBelongId').val() != rowData.businessBelongId){
							alert('所选盖章类型归属公司必须一致。');
							checkbox.removeAttr('checked');
							row.removeClass('trSelected');
							return false;
						}else if($('#legalPersonUsername').val() != rowData.legalPersonUsername){
							alert('此印章公司法人信息有差异，请联系管理员检查盖章配置信息。');
							checkbox.removeAttr('checked');
							row.removeClass('trSelected');
							return false;
						}
					}else{
						if($('#stampType').val().split(',').length == 1 && $('#stampType').val() == rowData.stampType){
							$('#businessBelongId').val('');
							$('#legalPersonUsername').val('');
							$('#legalPersonName').val('');
						}
					}
				}
			}
		},
		event : {
			'clear': function () {
				$('#businessBelongId').val('');
				$('#legalPersonUsername').val('');
				$('#legalPersonName').val('');
			}
		}
	});

	// 选择盖章类别的时候带出相应的使用事项选项
	useMattersAlert();
	$("#categoryId").change(function(){
		$("#useMatters").yxcombogrid_usematters('clearValue');
		$("#useMatters").yxcombogrid_usematters('remove');
		var cid = $(this).val();
		if(cid == ''){
			useMattersAlert();
		}else{
			$("#useMatters").yxcombogrid_usematters({
				hiddenId : 'useMattersId',
				height : 250,
				gridOptions : {
					isTitle : true,
					param : { 'status' : 1, 'stamp_cId' : cid},
					// showcheckbox : true
				}
			});
		}
	});

	//业务经办人渲染
	$("#attn").yxselect_user({
		hiddenId : 'attnId',
		isGetDept : [true, "attnDeptId", "attnDept"],
		formCode : 'stampapply'
	});

    $("#fileNum").blur( function(){
        if( $(this).val() == "" || $("#fileNum").val() <= 0){
            $("#fileNumTip").show();
            $(this).val("");
        }else{
            $("#fileNumTip").hide();
        }
    });

    $("#filePageNum").blur( function(){
        if( $(this).val() == "" || $("#filePageNum").val() <= 0){
            $("#filePageNumTip").show();
            $(this).val("");
        }else{
            $("#filePageNumTip").hide();
        }
    });
});

// 选择使用事项的时候,提示先选择盖章类别
function useMattersAlert(){
	$("#useMatters").click(function(e){
		if($("#categoryId").val() == ''){
			alert('请先选择盖章类别。');
		}
	});
}

function checkForm(){
//	if((strTrim($("#uploadfileList").html()) =="" || $("#uploadfileList").html() =="暂无任何附件")  && $("#remark").val() == ""){
//		alert('如果没有上传附件，请在说明处注明文件份数及每份页数');
//		return false;
//	}
    var error = 0;
    if( $("#fileNum").val() == ""  || $("#fileNum").val() <= 0){
        $("#fileNumTip").show();
        $("#fileNum").val("");
        error += 1;
    }
    if( $("#filePageNum").val() == ""  || $("#filePageNum").val() <= 0){
        $("#filePageNumTip").show();
        $("#filePageNum").val("");
        error += 1;
    }
    if((strTrim($("#uploadfileList").html()) =="" || $("#uploadfileList").html() =="暂无任何附件")  && $("#fileName").val() == ""){
        alert('如果没有上传附件，请填写盖章文件名');
        error += 1;
    }else{
		// 将附件文件名填入盖章文件名中
		if($("#uploadfileList").children('div').children('a') != ""){
			var fileName = "";
			$("#uploadfileList").children('div').children('a').each(function(){
				fileName += $(this).text()+",";
			});
			fileName = removeLastStr(fileName);
			if(fileName != "" && $("#fileName").val() == ""){
				$("#fileName").val(fileName);
			}
		}
	}

	return (error > 0)? false : true;
}

function removeLastStr(str){
	var backStr = "";
	if(str.substr(str.length-1,1) == ","){
		backStr = str.substring(0,str.length-1);
	}
	return backStr;
}
//新增 - 提交审批
function audit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=contract_stamp_stampapply&action=add&act=audit";
	}else{
		document.getElementById('form1').action="?model=contract_stamp_stampapply&action=add";
	}
}

//邮件控制
function checkEmailTA(obj){
    var addressdiv=document.getElementById("maildiv");
	if(obj.value=="y"){
	 	addressdiv.style.display="";
	}else{
		 addressdiv.style.display="none";
	}
}

