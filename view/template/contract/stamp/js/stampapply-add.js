$(document).ready(function(){
    // �ͻ�����
    customerTypeArr = getData('GZLB');
    addDataToSelect(customerTypeArr, 'categoryId');

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
		formCode : 'stampapply'
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

	// ѡ���������ʱ�������Ӧ��ʹ������ѡ��
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

	//ҵ�񾭰�����Ⱦ
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

// ѡ��ʹ�������ʱ��,��ʾ��ѡ��������
function useMattersAlert(){
	$("#useMatters").click(function(e){
		if($("#categoryId").val() == ''){
			alert('����ѡ��������');
		}
	});
}

function checkForm(){
//	if((strTrim($("#uploadfileList").html()) =="" || $("#uploadfileList").html() =="�����κθ���")  && $("#remark").val() == ""){
//		alert('���û���ϴ�����������˵����ע���ļ�������ÿ��ҳ��');
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
//���� - �ύ����
function audit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=contract_stamp_stampapply&action=add&act=audit";
	}else{
		document.getElementById('form1').action="?model=contract_stamp_stampapply&action=add";
	}
}

//�ʼ�����
function checkEmailTA(obj){
    var addressdiv=document.getElementById("maildiv");
	if(obj.value=="y"){
	 	addressdiv.style.display="";
	}else{
		 addressdiv.style.display="none";
	}
}

