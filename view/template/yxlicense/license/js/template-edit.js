/**
 * ��Ⱦǰ��̨����
 */
var idArr = new Array();
var idStr = "";
var index = 1;
var i = 0;

var rowStr = [];
var dataStr = {};
var tempStr = {};
//��ʼ��licenseѡ��
$(function(){
	$("#objType").val($("#licenseType").val());
	toselect($("#licenseType").val());
});

function initInput(objectArr){
	for(var t in objectArr){
		$("#"+ t).val(objectArr[t]);
		$("#"+ t + "_v").html(objectArr[t]);
	}
}

//����Ⱦ����
function initRow(rowVal,extVal){
    if(rowVal != ""){
        var rowArr = rowVal.split(',');
        var extArr = eval("(" + extVal + ")");
        for(var i = 0; i < rowArr.length ; i++){
            var key = rowArr[i];
            var str="<td class='clickBtn'><img id='" + i + "' onclick='deleLine("+ key +");' src='images/removeline.png' /></td>"; // ������
            $(".tempLine").each(function(){
                var id = 'GMS' + $(this).val() + '-' + key;
                var idV = id + '_v';
                var val = extArr[id] ? extArr[id] : '';
                str += "<td ondblclick=\"disAndfocus('" + id +"')\"><span id='"+ idV + "'>"+val+"</span>"
                    +"<input type=\"text\" class=\"txtmiddle\" id='"+ id +"' onblur=\"changeInput('"+ id +"')\" style=\"display:none\" value='"+val+"'/></td>";
            });
            $("#tableHead").append("<tr class=\"tr_even\" id=row_" + key + " value="+ key +">"+ str +"</tr>");
        }
    }
}

//ѡ�� ��Ⱦlicense�б�
function toselect(licenseType){
	$("#licenseDiv").html("");
	$("#licenseType").val(licenseType);
	switch(licenseType){
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
}

//��Ⱦlicense ͨ��
function initLicense(licenseType){
	$.post("?model=yxlicense_license_tempKey&action=returnHtml",{ 'licenseType' : licenseType},function(data){
		$("#licenseDiv").append(data);
		//��ʼ��ѡ��ֵ
		var thisVal = $("#thisVal").val();
		var extVal = $("#extVal").val();
		var rowVal = $("#rowVal").val();
		if(thisVal != "" || extVal != "" || rowVal != ""){
			//����Ⱦ
			if(rowVal != ""){
				initRow(rowVal,extVal);
			}
			//ѡ����Ⱦ
			if(thisVal != ""){
				idArr = thisVal.split(",");
				for(var i = 0;i<idArr.length ;i++){
					disInit(idArr[i] );
				}
			}
			//�ı�������Ⱦ
			if(extVal != ""){
				dataStr = eval('('+ extVal +')');
				initInput(dataStr)
			}
		}
	});
}

//��ʾ/���ض���
function disInit(name){
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

//��ʾ/���ض���
function dis(name){
	var obj = document.getElementById(name);
    var a = obj.getElementsByTagName("div");
    if(a.length>0){
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
	$("#extVal").val($.obj2json(dataStr));
	//dataStr=tempStr;
}

//������з���(�༭)
function addNew(){
    var rowValObj = $("#rowVal"); // �����ݶ���
    var rowVal = ''; // ���� + 1 Ϊ����ʵ������
    var rowArr = []; // ��idֵ����
    var colArr = $(".tempLine"); // ����Ϣ
    var rowObj = $("tr[id^='row_']");
    rowObj.each(function(){
        rowVal = $(this).attr('value')*1;
        rowArr.push(rowVal);
    });
    var rowVal = rowVal + 1; // ���� + 1 Ϊ����ʵ������
    rowArr.push(rowVal);
    var str="<td class='clickBtn'><img id='" + rowVal + "' onclick='deleLine("+ rowVal +");' src='images/removeline.png' /></td>"; // ������
    colArr.each(function(){
        str += "<td ondblclick=\"disAndfocus('GMS" + $(this).val() + '-' + rowVal +"')\"><span id='GMS"+ $(this).val() + '-' + rowVal + "_v'></span>"
            +"<input type=\"text\" class=\"txtmiddle\" id='GMS"+ $(this).val() + '-' + rowVal +"' onblur=\"changeInput('GMS"+$(this).val() + '-' + rowVal +"')\" style=\"display:none\"/></td>";
    });
    $("#tableHead").append("<tr class=\"tr_even\" id=row_" + rowVal + " value="+ rowVal +">"+ str +"</tr>");
    rowValObj.val(rowArr.toString());
}

//ɾ����ǰ��
function deleLine(rowVal){
    var rowObj = $("tr[id^='row_']");
    var rowArr = []; // ��idֵ����
    $("#row_" + rowVal).remove();
    rowObj.each(function(){
        if(rowVal*1 != $(this).attr('value')*1) rowArr.push($(this).attr('value')*1);
    });
    $("#rowVal").val(rowArr.toString());
}