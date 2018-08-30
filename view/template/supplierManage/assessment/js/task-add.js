$(document).ready(function() {
	$("#assesManName").yxselect_user({
		hiddenId : 'assesManId',
		mode:"single",
		formCode:'assessTask'
			});


	/**
	 * 验证信息
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
	$("#assessType").bind('change',function(){//根据不同的考核类型，隐藏或者显示“供应商来源”文本框
		$("#suppId").val("");
		$("#suppName").val("");
		if($(this).val()==""){//如果没有选择评估类型，则清空评估方案
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

  //验证是否选择了评估类型
  function checkSelectSupp(){
	var assessType=$("#assessType").val();
	if(assessType==""){
		alert("请先选择评估类型");
	}
  }

  //验证是否选择了评估类型
  function checkAssesType(){
	var assessType=$("#assessType").val();
	if(assessType==""){
		alert("请先选择评估类型");
	}
  }