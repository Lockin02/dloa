$(document).ready(function(){
	// ��֤��Ϣ
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

	//�ʼ���������Ⱦ
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check',
		formCode : 'invoiceapply'
	});


	//����������Ⱦ
	$("#stampType").yxcombogrid_stampconfig({
		hiddenId : 'stampIds',
		height : 250,
		gridOptions : {
			isTitle : true,
			showcheckbox : true,
			event : {
				//'row_dblclick' : function(e, row, data) {
				//	$('#businessBelongId').val(data.businessBelongId);
				//	$('#legalPersonUsername').val(data.legalPersonUsername);
				//	$('#legalPersonName').val(data.legalPersonName);
				//}
				'row_check' : function(e, checkbox, row, rowData) {
					if (checkbox.attr('checked')) {
						if($('#businessBelongId').val() == ''){
							$('#businessBelongId').val(rowData.businessBelongId);
							$('#legalPersonUsername').val(rowData.legalPersonUsername);
							$('#legalPersonName').val(rowData.legalPersonName);
							return true;
						}else if($('#businessBelongId').val() != rowData.businessBelongId){
							alert('��ѡ�������͹�����˾����һ�¡�');
							checkbox.removeAttr('checked');
							row.removeClass('trSelected');
							return false;
						}else if($('#legalPersonUsername').val() != rowData.legalPersonUsername){
							alert('��ӡ�¹�˾������Ϣ�в��죬����ϵ����Ա������������Ϣ��');
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

	// ʹ��������Ⱦ
	var cid = $("#categoryId").val();
	$("#useMatters").yxcombogrid_usematters({
		hiddenId : 'useMattersId',
		height : 250,
		gridOptions : {
			isTitle : true,
			param : { 'status' : 1, 'stamp_cId' : cid},
			// showcheckbox : true
		}
	});

	// ѡ���������ʱ�������Ӧ��ʹ������ѡ��
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

	//����ļ�����Ϊ������ͬʱ������ʹ�����������ҵ�񾭰���Ϊֻ��
	if($("#contractType").val() == "HTGZYD-04"){
		$("#useMatters").removeClass("validate[required]");
		$("#useMatters").parent("td").hide();
		$("#useMatters").parent("td").prev("td").hide();
		$("#categoryId").parent("td").attr("colspan",3);
		$("#attn").removeClass("txt").addClass("readOnlyTxtNormal").attr("readonly",true);
		$("#attn").parent("td").prev("td").find(".blue").removeClass("blue");
	}else{
		//ҵ�񾭰�����Ⱦ
		$("#attn").yxselect_user({
			hiddenId : 'attnId',
			isGetDept : [true, "attnDeptId", "attnDept"],
			formCode : 'stampapply'
		});
	}

});

function checkForm(){
	// if((strTrim($("#uploadfileList").html()) =="" || $("#uploadfileList").html() =="�����κθ���") && $("#remark").val() == ""){
	// 	alert('δ�ϴ������ģ���Ҫע���ļ�������ÿ��ҳ��');
	// 	return false;
	// }
	// return true;

	var error = 0;
	if( $("#fileNum").val() == "" || $("#fileNum").val() <= 0){
		$("#fileNumTip").show();
		$("#fileNum").val("");
		error += 1;
	}
	if( $("#filePageNum").val() == "" || $("#filePageNum").val() <= 0){
		$("#filePageNumTip").show();
		$("#filePageNum").val("");
		error += 1;
	}
	if((strTrim($("#uploadfileList").html()) =="" || $("#uploadfileList").html() =="�����κθ���")  && $("#fileName").val() == ""){
		alert('���û���ϴ�����������д�����ļ���');
		error += 1;
	}else{
		// �������ļ�����������ļ�����
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
//�༭ҳ - �ύ����
function auditEdit(thisType){
	if(thisType == 'audit'){
        document.getElementById('form1').action="?model=contract_stamp_stampapply&action=edit&act=audit";
	}else{
		document.getElementById('form1').action="?model=contract_stamp_stampapply&action=edit";
	}
}

//�ʼ�����
function checkEmailTA(obj){
    var addressdiv=document.getElementById("maildiv");
    var addressdiv1=document.getElementById("maildiv1");
	if(obj.value=="y"){
	 	addressdiv.style.display="";
	 	addressdiv1.style.display="";
	}else{
		 addressdiv.style.display="none";
		 addressdiv1.style.display="none";
	}
}