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
    initBtnClears();
}

//�鿴�������button
function initBtnClears(){
	$("#licenseDiv img").each(function(){
			$(this).remove();	
	});
	
	$("#licenseDiv td").each(function(){
		if($(this).attr('class') == 'clickBtn'){
			$(this).remove();
		};		
	});
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
		if(thisVal != "" || extVal != "" ){
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

function dis(){};
function disAndfocus() {};
function changeInput(){};
function deleLine(){};
function addNew(){};