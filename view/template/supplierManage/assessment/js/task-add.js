$(document).ready(function() {
	$("#assesManName").yxselect_user({
		hiddenId : 'assesManId',
		mode:"single",
		formCode:'assessTask'
			});


	/**
	 * ��֤��Ϣ
	 */
	validate({
//		"schemeCode" : {
//			required : true
//		},
		"suppName" : {
			required : true
		},
		"assesManName" : {
			required : true
		}
	});
	$("#assessType").bind('change',function(){//���ݲ�ͬ�Ŀ������ͣ����ػ�����ʾ����Ӧ����Դ���ı���
		$("#suppId").val("");
		$("#suppName").val("");
		if($(this).val()==""){//���û��ѡ���������ͣ��������������
			$("#suppName").yxcombogrid_supplier("remove");
		}
		if($(this).val()!=""){
			if($(this).val()=="xgyspg"){
				var suppGrad="E";
                $("#showId").hide();
			}else{
				var suppGrad="A,B,C";
                $("#showId").show();
                if ($(this).val() == "gysjd") {
                    $(".gysjd").show();
                    $("#assesQuarter").val($("#thisQuarter").val());
                }else{
                    $(".gysjd").hide();
                }
			}
			var supassType=$(this).val();
			$("#suppName").yxcombogrid_supplier("remove");
			$("#suppName").yxcombogrid_supplier({
					hiddenId : 'suppId',
					isShowButton : false,
					gridOptions : {
						showcheckbox : false,
						param:{suppGrade:suppGrad},
						event : {
							'row_dblclick' : function(e, row, data) {}
						}
					}
			});
			$("#suppName").yxcombogrid_supplier("showCombo");
		}
	});
  })

  //��֤�Ƿ�ѡ������������
  function checkSelectSupp(){
	var assessType=$("#assessType").val();
	if(assessType==""){
		alert("����ѡ����������");
	}
  }

  //��֤�Ƿ�ѡ������������
  function checkAssesType(){
	var assessType=$("#assessType").val();
	if(assessType==""){
		alert("����ѡ����������");
	}
  }