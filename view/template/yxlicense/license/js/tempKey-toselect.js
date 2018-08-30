/**
 * ��Ⱦǰ��̨����
 */
var idArr = new Array();
var idStr = "";
var index = 1;
var i = 0;


var dataStr = {};
var tempStr = {};

//��ʼ��licenseѡ��
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
								//ѡ����Ⱦ
								if(data.thisVal != ""){
									idArr = data.thisVal.split(",");
									for(var i = 0;i<idArr.length ;i++){
										dis(idArr[i] );
									}
								}
								//�ı�������Ⱦ
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
					alert('��ʼ��ʧ��');
					$("#objType").attr("disabled",false);
				}
			}
		)
	}else{
		$("#objType").attr("disabled",false);
	}
});

//��ʼ����������ֵ
function initInput(objectArr){
	for(var t in objectArr){
		$("#"+ t).val(objectArr[t]);
		$("#"+ t + "_v").html(objectArr[t]);
	}
}

//�������ֵ
function initInputClear(objectArr){
	for(var t in objectArr){
		$("#"+ t).val("");
		$("#"+ t + "_v").html("");
	}
}

//ѡ�� ��Ⱦlicense�б�
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

//��ʼ��ģ��
function initTemplate(licenseType){
	$.post("?model=yxlicense_license_template&action=getTemplateByType",{ 'licenseType' : licenseType},function(data){
		addTempateToSelect(data,'licenseTemplate');
		$("#licenseTemplate").attr('disabled',false) ;
	});
}

//����ģ�嵽������
function addTempateToSelect(data, selectId) {
	$("#" + selectId).append("<option value=''>��ѡ��</option>");
	dataRows = eval('('+ data +')');
	for (var i = 0, l = dataRows.length; i < l; i++) {
		$("#" + selectId).append("<option title='" + dataRows[i].remark + "' idTitle='" + dataRows[i].id
				+ "' innerTitle='" + dataRows[i].extVal
				+ "' value='" + dataRows[i].thisVal + "'>" + dataRows[i].name
				+ "</option>");
	}
}

//ѡ��ģ�����ҳ��ֵ
function setTemplate(){
	clearTemplate($("#templateId").val());
	setTemplateClear();
}

//����ģ�����
function setTemplateClear(){
	//��ȡ��ѡ��Ķ���
	var selectedObj =  $("#licenseTemplate").find("option:selected");
	//��ȡѡ��ֵ
	var thisValSel = selectedObj.attr("value");
	var extValSel = selectedObj.attr("innerTitle");
	if(thisValSel == "" && extValSel == ""){
		return false;
	}

	//ѡ����Ⱦ
	if(thisValSel != ""){
		idArr = thisValSel.split(",");
		for(var i = 0;i<idArr.length ;i++){
			dis(idArr[i] );
		}
	}
	//�ı�������Ⱦ
	if(extValSel != ""){
		dataStr = eval('('+ extValSel +')');
		initInput(dataStr)
	}
	$("#templateId").val(selectedObj.attr('idTitle'));
}

//��շ���
function clearTemplate(thisIdVal){
	if(thisIdVal == ""){
		return false;
	}
	var selectedObj =  $("#licenseTemplate").find("option[idTitle='"+ thisIdVal+"']");
	//��ȡѡ��ֵ
	var extValSel = selectedObj.attr("innerTitle");

	$("#licenseDiv div").remove();


	//�ı�������Ⱦ
	if(extValSel != ""){
		dataStr = eval('('+ extValSel +')');
		initInputClear(dataStr)
	}
}

//����ģ��
function resetTemplate(){
	$("#licenseDiv div").remove();
	$("#licenseDiv input").val("");
	$("#licenseDiv span").html("");
	if($("#templateId").val() != ""){
		setTemplateClear();
	}
}

//��Ⱦ��ѡ��
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
				//�жϵ�ǰ�ڵ�id�Ƿ�����������
				index = idArr.indexOf(treeNode.id);
				if(index != -1 ){
					//�����,��ɾ�������еĸýڵ�id
					idArr.splice(index, 1);
				}else{
					//���û��,��id����������
					if(treeNode.checked){
						idArr.push(treeNode.id);
					}
				}
				//�����ǰ�ڵ����ӽڵ�,���еݹ�
				if(treeNode.nodes != undefined ){
					for(i = 0;i< treeNode.nodes.length ; i++){
						changeFn(treeNode.nodes[i]);
					}
				}
				//������ת�����ַ�ת
				var treeNodeStr = idArr.toString();
				$("#thisVal").val(treeNodeStr);
			}
		}
    });
}

//�ݹ�������ڵ㺯��
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

//��Ⱦlicense ͨ��
function initLicense(licenseType){
	idArr = new Array();
	dataStr = {};
	$("#thisVal").val("");
	$.post("?model=yxlicense_license_tempKey&action=returnHtml",{ 'licenseType' : licenseType},function(data){
		$("#licenseDiv").append(data);
	});
}

//����LICENSE
function saveTemplate(){
	var licenseType = $("#objType").val();
	var oldlicenseType = $("#licenseType").val();
	var licenseId = $("#licenseId" ).val();
	var thisVal =  $("#thisVal").val();
	var actType =  $("#actType").val();
	var fileName =  $("#fileName").val();

	if(thisVal == "" && licenseType != ""){
		alert("û��ѡ���κμ������ã�");
		return false;
	}

	if(licenseType == "" ){//ȡ��license
		if( licenseId  != "" ){
			if(confirm('��ǰ������ȡ������������Ϣ,Ҫ����ô?')){
				if( actType == 'edit'){//����Ǳ༭״̬,ȥ���豸���е�id
					self.parent.setLicenseId($('#focusId').val(),'');
					self.parent.tb_remove();
				}else{//����ɾ��license��¼
					$.post("?model=yxlicense_license_tempKey&action=delRecord",
						{"id" : licenseId },
						function(data){
							if(data != 0 ){
								alert('����ɹ�');
								self.parent.setLicenseId($('#focusId').val(),'');
//								self.parent.tb_remove();
								closeFun();
							}else{
								alert('����ʧ��');
//								self.parent.tb_remove();
								closeFun();
							}
						}
					);
				}
			}
		}else{
			alert('��ѡ��һ������!');
			return false;
		}
	}else{//����license
		if( licenseId  != "" ){
			if( oldlicenseType == licenseType ){
				if( actType == 'edit'){//����Ǳ༭״̬,��ֱ������
					if( fileName != ""){
						$.post("?model=yxlicense_license_tempKey&action=addRecord",
							{"licenseStr" : $("#licenseDiv").html(),"licenseType" : licenseType ,"thisVal" : thisVal },
							function(data){
								if(data != 0 ){
									alert('����ɹ�');
									self.parent.setLicenseId($('#focusId').val(),strTrim(data));
//									self.parent.tb_remove();
									closeFun();
								}else{
									alert('����ʧ��');
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
									alert('����ɹ�');
									self.parent.setLicenseId($('#focusId').val(),strTrim(data));
//									self.parent.tb_remove();
									closeFun();
								}else{
									alert('����ʧ��');
//									self.parent.tb_remove();
									closeFun();
								}
							}
						);
					}
				}else{//����ֱ���޸�
					$.post("?model=yxlicense_license_tempKey&action=saveRecord",
						{"licenseType" : licenseType,"id" : licenseId ,"thisVal" : thisVal },
						function(data){
							if(data != 0 ){
								alert('����ɹ�');
								self.parent.setLicenseId($('#focusId').val(),strTrim(data));
//								self.parent.tb_remove();
								closeFun();
							}else{
								alert('����ʧ��');
//								self.parent.tb_remove();
								closeFun();
							}
						}
					);
				}
			}else{
				if(confirm('�����ԭ����������Ϣ�ᶪʧ,Ҫ����ô?')){
					if( actType == 'edit'){//����Ǳ༭״̬,��ֱ������
						$.post("?model=yxlicense_license_tempKey&action=addRecord",
							{"licenseType" : licenseType ,"thisVal" : thisVal },
							function(data){
								if(data != 0 ){
									alert('����ɹ�');
									self.parent.setLicenseId($('#focusId').val(),strTrim(data));
//									self.parent.tb_remove();
									closeFun();
								}else{
									alert('����ʧ��');
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
									alert('����ɹ�');
									self.parent.setLicenseId($('#focusId').val(),strTrim(data));
									self.parent.tb_remove();
								}else{
									alert('����ʧ��');
									self.parent.tb_remove();
								}
							}
						);
					}
				}else{
					return false;
				}
			}
		}else{//����license
			$.post("?model=yxlicense_license_tempKey&action=addRecord",
				{"licenseType" : licenseType ,"thisVal" : thisVal , "extVal" : $.obj2json(dataStr) },
				function(data){
					if(data != 0 ){
						alert('����ɹ�');
						self.parent.setLicenseId($('#focusId').val(),strTrim(data));
						self.parent.tb_remove();
					}else{
						alert('����ʧ��');
						self.parent.tb_remove();
					}
				}
			);
		}
	}
}

//��ʾ/���ض���
function dis(name){
	var fileName = $("#fileName").val();
	var obj = document.getElementById(name);
    var a = obj.getElementsByTagName("div");
    if(a.length>0){
    	if(fileName != ""){
      	  	$("#div"+name).remove();
    	}else{
	    	//�жϵ�ǰ�ڵ�id�Ƿ�����������
			index = idArr.indexOf(name);
			if(index != -1 ){
				//�����,��ɾ�������еĸýڵ�id
				idArr.splice(index, 1);
			}
			//������ת�����ַ�ת
			var idStr = idArr.toString();
			$("#thisVal").val(idStr);
      	  	$("#div"+name).remove();
    	}
    }else{
    	if(fileName != ""){
    		$("#"+name).append("<div id=div" + name + ">��</div>");
    	}else{
	    	//�жϵ�ǰ�ڵ�id�Ƿ�����������
			index = idArr.indexOf(name);
			if(index == -1 ){
				idArr.push(name);
			}
			//������ת�����ַ�ת
			var idStr = idArr.toString();
			$("#thisVal").val(idStr);
    		$("#"+name).append("<div id=div" + name + ">��</div>");
    	}
    }
}

var thisFocus = "";
//��ʾ����ĳ���� - ����flee
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
//input��ֵ
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