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
    saveHtm();
    $("#objType").val($("#showType").val());
    toselect($("#showType").val());
});

function initInput(objectArr){
    for(var t in objectArr){
        $("#"+ t).val(objectArr[t]);
        $("#"+ t + "_v").html(objectArr[t]);
    }
}

//ѡ�� ��Ⱦlicense�б�
function toselect(showType){
    $("#licenseDiv").html("");
    $("#showType").val(showType);
    switch(showType){
        case 'GSM' : initLicense(showType); break;
        default : initLicense(showType); break;
    }
}

//��Ⱦlicense ͨ��
function initLicense(showType){
    var id = $("#id").val();
    var name = $("#name").val();
    var showType = $("#showType").val();
    $.post("?model=yxlicense_license_baseinfo&action=returnToHtml",{ 'id' : id,'name' : name},function(data){
        $("#licenseDiv").append(data);

        //��ʼ��ѡ��ֵ
        var thisVal = $("#thisVal").val();
        var extVal = $("#extVal").val();
        if(thisVal != "" || extVal != "" ){
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
        if(!dataStr){
            dataStr = {};
        }
        dataStr[focusId]=tempVal;
    }
    $("#extVal").val($.obj2json(dataStr));
    //dataStr=tempStr;
}

//��ȡ��ǰҳ���URL
function saveHtm(){
    var id = $("#id").val();
    var name = $("#name").val();
    $.ajax({// �������к�
        type : "get",
        async : false,
        url : "?model=yxlicense_license_baseinfo&action=saveHtm",
        data : {
            "id" : id,
            "name" : name
        }
    })
}
//������з���
function addNew(){
    $(".clickTime").val(parseInt($(".clickTime").val())+1);
    var clickTime = parseInt($(".clickTime").val());
    var lineFeeds = $(".lineFeeds").val();         //ѭ������
    lineFeeds++;
    var v = clickTime * ($(".tempLine").val());       //Ϊ��ֹ�ظ�,��ǰ�������ı���id,Ϊ���ж�Ӧ�ı���id * �������
    var button2 = "button2_" + v;
    var str="<td class='clickBtn'><img id='"+button2+"' onclick='deleLine("+button2+");' src='images/removeline.png' ></img></td>";
    for(var i=0;i < lineFeeds; i++){
        v= v+i;
        var GMS = "GMS-"+v;
        var GMS_v = "GMS-"+v+"_v";
        str+=
            "<td ondblclick=\"disAndfocus('"+GMS+"')\"><span id="+GMS_v+"></span>"
                +"<input type=\"text\" class=\"txtmiddle\" id="+GMS+" onblur=\"changeInput('"+GMS+"')\" style=\"display:none\" /></td>";
    }
    //alert(GMS);
    $("#tableHead").append("<tr class=\"tr_even\" id=row_"+clickTime+" value="+clickTime+">"+str+"</tr>");
}

//ɾ����ǰ��
function deleLine(button2){
    //alert($(button2).val());
    $(button2).parent().parent().remove();
}