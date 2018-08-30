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

//选择 渲染license列表
function toselect(showType){
    $("#licenseDiv").html("");
    $("#showType").val(showType);
    switch(showType){
        case 'GSM' : initLicense(showType); break;
        default : initLicense(showType); break;
    }
}

//渲染license 通用
function initLicense(showType){
    var id = $("#id").val();
    var name = $("#name").val();
    var showType = $("#showType").val();
    $.post("?model=yxlicense_license_baseinfo&action=returnToHtml",{ 'id' : id,'name' : name},function(data){
        $("#licenseDiv").append(data);

        //初始化选择值
        var thisVal = $("#thisVal").val();
        var extVal = $("#extVal").val();
        if(thisVal != "" || extVal != "" ){
            //选择渲染
            if(thisVal != ""){
                idArr = thisVal.split(",");
                for(var i = 0;i<idArr.length ;i++){
                    disInit(idArr[i] );
                }
            }
            //文本输入渲染
            if(extVal != ""){
                dataStr = eval('('+ extVal +')');
                initInput(dataStr)
            }
        }
    });
}

//显示/隐藏对象
function disInit(name){
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

//显示/隐藏对象
function dis(name){
    var obj = document.getElementById(name);
    var a = obj.getElementsByTagName("div");
    if(a.length>0){
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
        if(!dataStr){
            dataStr = {};
        }
        dataStr[focusId]=tempVal;
    }
    $("#extVal").val($.obj2json(dataStr));
    //dataStr=tempStr;
}

//获取当前页面的URL
function saveHtm(){
    var id = $("#id").val();
    var name = $("#name").val();
    $.ajax({// 缓存序列号
        type : "get",
        async : false,
        url : "?model=yxlicense_license_baseinfo&action=saveHtm",
        data : {
            "id" : id,
            "name" : name
        }
    })
}
//点击增行方法
function addNew(){
    $(".clickTime").val(parseInt($(".clickTime").val())+1);
    var clickTime = parseInt($(".clickTime").val());
    var lineFeeds = $(".lineFeeds").val();         //循环列数
    lineFeeds++;
    var v = clickTime * ($(".tempLine").val());       //为防止重复,当前新增行文本框id,为首行对应文本框id * 点击次数
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

//删除当前行
function deleLine(button2){
    //alert($(button2).val());
    $(button2).parent().parent().remove();
}