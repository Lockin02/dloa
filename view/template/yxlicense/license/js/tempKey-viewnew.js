//��ʼ��һ��ģ�建������
var templateStr = "";

//��ʼ��licenseѡ��
$(function(){
	var licenseVal  = $('#licenseId').val();
	if(licenseVal != ""){
		$.post("?model=yxlicense_license_tempKey&action=viewRecord",
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
						$("#outputTable").show();
					}else{
						if( data.licenseStr == undefined ){
							if(data.thisVal != "" || data.extVal != "" ){
								//��Ⱦ���ǰlicense��ֵ
								templateStr = $("#oldVal").val();

								$("#licenseDiv").append(data.modalStr );
								//ѡ����Ⱦ
								if(data.thisVal != ""){
									idArr = data.thisVal.split(",");
									for(var i = 0;i<idArr.length ;i++){
										setCheck(idArr[i] );
									}
								}
								//�ı�������Ⱦ
								if(data.extVal != ""){
									initInput( eval('('+ data.extVal +')') )
								}
							}
						}else{
							$("#licenseDiv").append(data.licenseStr );
						}
					}

					var diffVal = $("#diffVal").val();
					if(diffVal != ""){
						diffArr = diffVal.split(",");
						for(var i = 0;i<diffArr.length ;i++){
							setCheckDiff(diffArr[i] );
						}
					}

				}else{
					alert('��ʼ��ʧ��');
				}
			}
		)
	}
});

function initInput(objectArr){
	for(var t in objectArr){
		$("#"+ t).val(objectArr[t]);
		$("#"+ t + "_v").html(objectArr[t]);
	}
}

/*****************Navigator + Pioneer
 */
/**
 * ��Ⱦǰ��̨����
 */
var idArr = new Array();
var idStr = "";
var index = 1;
var i = 0;


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

//��ʾ/���ض���
function setCheck(name){
	//��������
	if(templateStr != undefined){
		//�жϵ�ǰ�ڵ�id�Ƿ�����������
		templateIndex = templateStr.indexOf(name);
		if( templateIndex == -1 ){
			$("#"+name).append("<div style='color:green;font-weight:bold' id=div" + name + ">[��]</div>");
		}else{
			$("#"+name).append("<div id=div" + name + ">��</div>");
		}
	}else{
		$("#"+name).append("<div style='color:green;font-weight:bold' id=div" + name + ">[��]</div>");
	}
}

//��ʾ/���ض���
function setCheckDiff(name){
	$("#"+name).append("<div style='color:red;font-weight:bold' id=div" + name + ">[��]</div>");
}

//�պ���,����JS����
function dis(){}
function disAndfocus() {}
function changeInput(){}
