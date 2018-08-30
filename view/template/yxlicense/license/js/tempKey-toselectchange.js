
//初始化license选单
$(function(){
	var licenseVal  = $('#licenseId').val();

	if(licenseVal != ""){
		$.post("?model=yxlicense_license_tempKey&action=getRecord",
			{ "id" : licenseVal },
			function(data){
				if(data != 0){
					data = eval("(" + data +")");
					$("#objType").val(data.licenseType );
					$("#licenseType").val(data.licenseType );
					if(data.licenseType == "PN"){
						$("#licenseDiv").html("");
						$("#thisVal").val(data.thisVal );
						initPN();
					}else{
						if( data.licenseStr == undefined ){
							//模板部分渲染
							$("#licenseTemplate").empty();
							$("#templateId").val(data.templateId);
							initTemplate(data.licenseType,data.templateId);

							if(data.thisVal != "" || data.extVal != "" ){

								//先选择表单
								$("#licenseDiv").append(data.modalStr );
								//选择渲染
								if(data.thisVal != ""){
									idArr = data.thisVal.split(",");
									for(var i = 0;i<idArr.length ;i++){
										dis(idArr[i] );
									}
								}
								//文本输入渲染
								if(data.extVal != ""){
									dataStr = eval('('+ data.extVal +')');

									initInput(dataStr)
								}
							}
						}else{
							$("#licenseDiv").append(data.licenseStr );
							$("#thisVal").val(data.thisVal );
							$("#fileName").val(data.fileName );
						}
					}
					$("#objType").attr("disabled",false);
				}else{
					alert('初始化失败');
					$("#objType").attr("disabled",false);
				}
			}
		)
	}else{
		$("#objType").attr("disabled",false);
	}
});

//保存LICENSE
function saveTemplate(){
	var licenseType = $("#objType").val();
	var thisVal =  $("#thisVal").val();
	var templateId =  $("#templateId").val();

	if(thisVal == "" && licenseType != ""){
		alert("没有选择任何加密配置！");
		return false;
	}

//	$.showDump(dataStr);

	if(licenseType == "" ){//取消license
		alert('不能保存空类型！');
		location.reload();
		return false;
	}else{//保存license
		$.post("?model=yxlicense_license_tempKey&action=addRecord",
			{"licenseType" : licenseType ,"thisVal" : thisVal , "extVal" : $.obj2json(dataStr)  , "templateId" : templateId},
			function(data){
				if(data != 0 ){
					alert('保存成功');
					parent.window.returnValue = strTrim(data);
					closeFun();
				}else{
					alert('保存失败');
					closeFun();
				}
			}
		);
	}
}