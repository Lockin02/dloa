/**
 * 渲染前后台部分
 */
var idArr = new Array();
var idStr = "";
var index = 1;
var i = 0;


var dataStr = {};
var tempStr = {};

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
							if(data.thisVal != "" || data.extVal != "" ){
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

//初始化设置输入值
function initInput(objectArr){
	for(var t in objectArr){
		$("#"+ t).val(objectArr[t]);
		$("#"+ t + "_v").html(objectArr[t]);
	}
}

//清空输入值
function initInputClear(objectArr){
	for(var t in objectArr){
		$("#"+ t).val("");
		$("#"+ t + "_v").html("");
	}
}

//选择 渲染license列表
function toselect(licenseType){
	$("#licenseDiv").html("");
	$("#licenseTemplate").empty();
	switch(licenseType){
		case 'PN' : initPN(); break;
		case 'PIO' : initLicense(licenseType); break;
		case 'NAV' : initLicense(licenseType); break;
		case 'FL' : initLicense(licenseType); break;
		case 'FL2' : initLicense(licenseType); break;
		case 'SL' : initLicense(licenseType); break;
		case 'RCU' : initLicense(licenseType); break;
		case 'WT' : initLicense(licenseType); break;
		case 'WISER' : initLicense(licenseType); break;
		case 'Walktour Pack-Ipad' : initLicense(licenseType); break;
		default : initLicense(licenseType); break;
	}
	initTemplate(licenseType);
}

//初始化模板
function initTemplate(licenseType){
	$.post("?model=yxlicense_license_template&action=getTemplateByType",{ 'licenseType' : licenseType},function(data){
		addTempateToSelect(data,'licenseTemplate');
		$("#licenseTemplate").attr('disabled',false) ;
	});
}

//加载模板到下拉中
function addTempateToSelect(data, selectId) {
	$("#" + selectId).append("<option value=''>请选择</option>");
	dataRows = eval('('+ data +')');
	for (var i = 0, l = dataRows.length; i < l; i++) {
		$("#" + selectId).append("<option title='" + dataRows[i].remark + "' idTitle='" + dataRows[i].id
				+ "' innerTitle='" + dataRows[i].extVal
				+ "' value='" + dataRows[i].thisVal + "'>" + dataRows[i].name
				+ "</option>");
	}
}

//选择模板后处理页面值
function setTemplate(){
	clearTemplate($("#templateId").val());
	setTemplateClear();
}

//设置模板清空
function setTemplateClear(){
	//获取被选择的对象
	var selectedObj =  $("#licenseTemplate").find("option:selected");
	//获取选择值
	var thisValSel = selectedObj.attr("value");
	var extValSel = selectedObj.attr("innerTitle");
	if(thisValSel == "" && extValSel == ""){
		return false;
	}

	//选择渲染
	if(thisValSel != ""){
		idArr = thisValSel.split(",");
		for(var i = 0;i<idArr.length ;i++){
			dis(idArr[i] );
		}
	}
	//文本输入渲染
	if(extValSel != ""){
		dataStr = eval('('+ extValSel +')');
		initInput(dataStr)
	}
	$("#templateId").val(selectedObj.attr('idTitle'));
}

//清空方法
function clearTemplate(thisIdVal){
	if(thisIdVal == ""){
		return false;
	}
	var selectedObj =  $("#licenseTemplate").find("option[idTitle='"+ thisIdVal+"']");
	//获取选择值
	var extValSel = selectedObj.attr("innerTitle");

	$("#licenseDiv div").remove();


	//文本输入渲染
	if(extValSel != ""){
		dataStr = eval('('+ extValSel +')');
		initInputClear(dataStr)
	}
}

//重置模板
function resetTemplate(){
	$("#licenseDiv div").remove();
	$("#licenseDiv input").val("");
	$("#licenseDiv span").html("");
	if($("#templateId").val() != ""){
		setTemplateClear();
	}
}

//渲染树选项
function initCheck(){
	var thisVal = $("#thisVal").val();
	var valArr = [];
	if(thisVal != ""){
		valArr = thisVal.split(",");
	}
	for(var i = 0 ;i< valArr.length ; i ++){
		$("#baseinfoGrid_" + valArr[i] + "_check" ).click();
	}
}




function initPN() {
	$("#licenseDiv").append("<ul id='baseinfoGrid'/>");
    $("#baseinfoGrid").yxtree({
		checkable : true,
		expandSpeed : "",
		checkedObjId : "id",
		appendData : $("#thisVal").val(),
		url : '?model=yxlicense_license_baseinfo&action=getContent',
        nameCol : "describe",
        event : {
			"node_change" : function(event, treeId, treeNode) {
				//判断当前节点id是否在数组里面
				index = idArr.indexOf(treeNode.id);
				if(index != -1 ){
					//如果有,则删除数组中的该节点id
					idArr.splice(index, 1);
				}else{
					//如果没有,把id放入数组中
					if(treeNode.checked){
						idArr.push(treeNode.id);
					}
				}
				//如果当前节点有子节点,进行递归
				if(treeNode.nodes != undefined ){
					for(i = 0;i< treeNode.nodes.length ; i++){
						changeFn(treeNode.nodes[i]);
					}
				}
				//将数组转换成字符转
				var treeNodeStr = idArr.toString();
				$("#thisVal").val(treeNodeStr);
			}
		}
    });
}

//递归调用树节点函数
function changeFn(object){

	index = idArr.indexOf(object.id);
	if(index != -1){
		idArr.splice(index, 1);
	}else{
		if(object.checked){
			idArr.push(object.id);
		}
	}
	if(object.nodes != undefined ){
		for(var i = 0;i< object.nodes.length ; i++){
			changeFn(object.nodes[i]);
		}
	}
}

//渲染license 通用
function initLicense(licenseType){
	idArr = new Array();
	dataStr = {};
	$("#thisVal").val("");
	$.post("?model=yxlicense_license_tempKey&action=returnHtml",{ 'licenseType' : licenseType},function(data){
		$("#licenseDiv").append(data);
	});
}

//保存LICENSE
function saveTemplate(){
	var licenseType = $("#objType").val();
	var oldlicenseType = $("#licenseType").val();
	var licenseId = $("#licenseId" ).val();
	var thisVal =  $("#thisVal").val();
	var actType =  $("#actType").val();
	var fileName =  $("#fileName").val();

	if(thisVal == "" && licenseType != ""){
		alert("没有选择任何加密配置！");
		return false;
	}

	if(licenseType == "" ){//取消license
		if( licenseId  != "" ){
			if(confirm('当前操作会取消加密申请信息,要继续么?')){
				if( actType == 'edit'){//如果是编辑状态,去除设备表中的id
					self.parent.setLicenseId($('#focusId').val(),'');
					self.parent.tb_remove();
				}else{//否则删除license记录
					$.post("?model=yxlicense_license_tempKey&action=delRecord",
						{"id" : licenseId },
						function(data){
							if(data != 0 ){
								alert('保存成功');
								self.parent.setLicenseId($('#focusId').val(),'');
//								self.parent.tb_remove();
								closeFun();
							}else{
								alert('保存失败');
//								self.parent.tb_remove();
								closeFun();
							}
						}
					);
				}
			}
		}else{
			alert('请选择一种类型!');
			return false;
		}
	}else{//保存license
		if( licenseId  != "" ){
			if( oldlicenseType == licenseType ){
				if( actType == 'edit'){//如果是编辑状态,则直接新增
					if( fileName != ""){
						$.post("?model=yxlicense_license_tempKey&action=addRecord",
							{"licenseStr" : $("#licenseDiv").html(),"licenseType" : licenseType ,"thisVal" : thisVal },
							function(data){
								if(data != 0 ){
									alert('保存成功');
									self.parent.setLicenseId($('#focusId').val(),strTrim(data));
//									self.parent.tb_remove();
									closeFun();
								}else{
									alert('保存失败');
//									self.parent.tb_remove();
									closeFun();
								}
							}
						);
					}else{
						$.post("?model=yxlicense_license_tempKey&action=addRecord",
							{"licenseType" : licenseType ,"thisVal" : thisVal , "extVal" : $.obj2json(dataStr)},
							function(data){
								if(data != 0 ){
									alert('保存成功');
									self.parent.setLicenseId($('#focusId').val(),strTrim(data));
//									self.parent.tb_remove();
									closeFun();
								}else{
									alert('保存失败');
//									self.parent.tb_remove();
									closeFun();
								}
							}
						);
					}
				}else{//否则直接修改
					$.post("?model=yxlicense_license_tempKey&action=saveRecord",
						{"licenseType" : licenseType,"id" : licenseId ,"thisVal" : thisVal },
						function(data){
							if(data != 0 ){
								alert('保存成功');
								self.parent.setLicenseId($('#focusId').val(),strTrim(data));
//								self.parent.tb_remove();
								closeFun();
							}else{
								alert('保存失败');
//								self.parent.tb_remove();
								closeFun();
							}
						}
					);
				}
			}else{
				if(confirm('保存后原加密申请信息会丢失,要继续么?')){
					if( actType == 'edit'){//如果是编辑状态,则直接新增
						$.post("?model=yxlicense_license_tempKey&action=addRecord",
							{"licenseType" : licenseType ,"thisVal" : thisVal },
							function(data){
								if(data != 0 ){
									alert('保存成功');
									self.parent.setLicenseId($('#focusId').val(),strTrim(data));
//									self.parent.tb_remove();
									closeFun();
								}else{
									alert('保存失败');
//									self.parent.tb_remove();
									closeFun();
								}
							}
						);
					}else{
						$.post("?model=yxlicense_license_tempKey&action=saveRecord",
							{"licenseType" : licenseType,"id" : licenseId ,"thisVal" : thisVal},
							function(data){
								if(data != 0 ){
									alert('保存成功');
									self.parent.setLicenseId($('#focusId').val(),strTrim(data));
									self.parent.tb_remove();
								}else{
									alert('保存失败');
									self.parent.tb_remove();
								}
							}
						);
					}
				}else{
					return false;
				}
			}
		}else{//新增license
			$.post("?model=yxlicense_license_tempKey&action=addRecord",
				{"licenseType" : licenseType ,"thisVal" : thisVal , "extVal" : $.obj2json(dataStr) },
				function(data){
					if(data != 0 ){
						alert('保存成功');
						self.parent.setLicenseId($('#focusId').val(),strTrim(data));
						self.parent.tb_remove();
					}else{
						alert('保存失败');
						self.parent.tb_remove();
					}
				}
			);
		}
	}
}

//显示/隐藏对象
function dis(name){
	var fileName = $("#fileName").val();
	var obj = document.getElementById(name);
    var a = obj.getElementsByTagName("div");
    if(a.length>0){
    	if(fileName != ""){
      	  	$("#div"+name).remove();
    	}else{
	    	//判断当前节点id是否在数组里面
			index = idArr.indexOf(name);
			if(index != -1 ){
				//如果有,则删除数组中的该节点id
				idArr.splice(index, 1);
			}
			//将数组转换成字符转
			var idStr = idArr.toString();
			$("#thisVal").val(idStr);
      	  	$("#div"+name).remove();
    	}
    }else{
    	if(fileName != ""){
    		$("#"+name).append("<div id=div" + name + ">√</div>");
    	}else{
	    	//判断当前节点id是否在数组里面
			index = idArr.indexOf(name);
			if(index == -1 ){
				idArr.push(name);
			}
			//将数组转换成字符转
			var idStr = idArr.toString();
			$("#thisVal").val(idStr);
    		$("#"+name).append("<div id=div" + name + ">√</div>");
    	}
    }
}

var thisFocus = "";
//显示隐藏某对象 - 用于flee
function disAndfocus(name) {
	if( document.activeElement.id == ""){
		var temp = document.getElementById(name);
		temp.value = $("#" + name + "_v").html();
		if (temp.style.display == '')
			temp.style.display = "none";
		else if (temp.style.display == "none")
			temp.style.display = '';
			temp.focus();
	}
}
//input赋值
function changeInput(focusId){
	tempVal = $("#"+ focusId).val();
	$("#"+ focusId).val(tempVal);
	$("#"+ focusId).hide();
	$("#"+ focusId + "_v").html(tempVal);
	//var tempStr={};
	if(tempVal != ""){
		dataStr[focusId]=tempVal;
	}
	//dataStr=tempStr;
}