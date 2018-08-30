var expandState = 0;
var oldPosition = 0;
var basketOrgTop = 290; //比较栏与屏幕上方的距离
var basketUpTop = 200; //产品比较篮上方突出的距离, 默认是宽屏的数值
var ie = document.all ? 1 : 0;
var ns = document.layers ? 1 : 0;
var firstStep = 20;
var autoIndentTime = 10; //自动回缩的时间，单位:秒
var timeoutProcess;
var timeoutProcessCounter;

function init(w,l,v){
    var windowHeight = window.screen.height;
    basketUpTop = windowHeight > 1000 ? 120 : 200; //普通屏幕产品比较篮中出，宽屏出现位置提上避免看不全

       if(isRight()){
        while(w>0){
            w = (w-v) < 0 ? 0 : (w-v);
            l = (l+v)> (initLeft+initWidth)?(initLeft+initWidth):(l+v);
        }    
        document.getElementById("basket_show").style.width=w+"px";
        document.getElementById("basket_show").style.left=l+"px";
    }else{
        document.getElementById("basket_show").style.width="0px";
    }
    document.getElementById("basket_close").style.display = "";        
    document.getElementById("basket_show").style.overflow = "hidden";

    //设定初始垂直位置
    document.getElementById("basket").style.top       = (basketOrgTop - basketUpTop) + 'px';
    document.getElementById("basket_show").style.top  = (basketOrgTop - basketUpTop) + 'px';
    document.getElementById("basket_close").style.top = basketOrgTop + 'px';    
    window.setInterval("flow()",10);
}
function flow() {
        var scrollPos;
        var cHeight;
        if (typeof window.pageYOffset != 'undefined') {//ns专有属性
               scrollPos = window.pageYOffset;
               cHeight=document.documentElement.clientHeight;

        }
        else if (typeof document.compatMode != 'undefined' &&
            document.compatMode != 'BackCompat') {
               scrollPos = document.documentElement.scrollTop;
               cHeight=document.documentElement.clientHeight;
        }
        else if (typeof document.body != 'undefined') {
               scrollPos = document.body.scrollTop;//ie
               cHeight=document.body.clientHeight;
        }
        var a = scrollPos;
        if(a!=oldPosition){ //设定滚动后的垂直位置
            document.getElementById("basket").style.top       = (a + basketOrgTop - basketUpTop) + 'px';
            document.getElementById("basket_show").style.top  = (a + basketOrgTop - basketUpTop) + 'px';
            document.getElementById("basket_close").style.top = (a + basketOrgTop) + 'px';                        
            oldPosition = a;
        }
}
function isRight(){
    return initLeft > document.getElementById("basket").offsetWidth/2;
}
function showBoxManual() {
    if (expandState == 0) {
        showBox();
    }

    if (timeoutProcess != null) {
        clearTimeout(timeoutProcess);
    }

    timeoutProcessCounter = autoIndentTime;
    timeoutProcess = setTimeout("hideBoxInSeconds()", 1000);
}
function showBox() {
    if (expandState == 0) {
        if (ie) {
            if (isRight()) {
                rightIncrease(firstStep);
            } else {
                leftIncrease(firstStep);
            }
        }
        expandState = 1;
    }
}
function hideBox() {
    if (expandState == 1) {
        if (ie) {
            if (isRight()) {
                rightDecrease(firstStep);
            } else {
                leftDecrease(firstStep);
            }
        }
        expandState = 0;
    }
}
function hideBoxInSeconds() {
    timeoutProcessCounter--;

    if (timeoutProcessCounter >= 0) 
        timeoutProcess = setTimeout("hideBoxInSeconds()", 1000);
    else 
        hideBox();
}
function rightIncrease(v){
    var w = document.getElementById("basket_show").offsetWidth-2 ;
    var l = document.getElementById("basket_show").offsetLeft ;
    var step = (w+v) > initWidth ? initWidth : (w+v);
    var step2 = (l-v) < initLeft? initLeft : (l-v);
    document.getElementById("basket_show").style.width=step+"px";
    document.getElementById("basket_show").style.left=step2+"px";
    if(step>0){
        document.getElementById("basket_close").style.visibility = 'hidden';
        document.getElementById("basket_show").style.visibility  = 'visible';
    }
    if(step < initWidth){
        setTimeout('rightIncrease('+v+')',10);
    }
}

function leftIncrease(v){
    var w = document.getElementById("basket_show").offsetWidth-2 ;
    var step = (w+v) > initWidth ? initWidth : (w+v);
    document.getElementById("basket_show").style.width=step+"px";
    if(step>0){
        document.getElementById("basket_close").style.visibility = 'hidden';
        document.getElementById("basket_show").style.visibility  = 'visible';
    }
    if(step < initWidth){
        setTimeout('leftIncrease('+v+')',10);
    }
}
function rightDecrease(v){
    var w = document.getElementById("basket_show").offsetWidth-2 ;
    var l = document.getElementById("basket_show").offsetLeft ;
    var step = (w-v) < 0 ? 0 : (w-v);
    var step2 = (l+v)> (initLeft+initWidth)?(initLeft+initWidth):(l+v);
    document.getElementById("basket_show").style.width=step+"px";
    document.getElementById("basket_show").style.left=step2+"px";
    if(step > 0){
        setTimeout('rightDecrease('+v+')',10);
    } else {
        document.getElementById("basket_close").style.visibility = 'visible';
        document.getElementById("basket_show").style.visibility  = 'hidden';
    }
}

function leftDecrease(v){
    var w = document.getElementById("basket_show").offsetWidth-2 ;
    var step = (w-v) < 0 ? 0 : (w-v);
    document.getElementById("basket_show").style.width=step+"px";
    if(step > 0){
        setTimeout('leftDecrease('+v+')',10);
    } else {
        document.getElementById("basket_close").style.visibility = 'visible';
        document.getElementById("basket_show").style.visibility  = 'hidden';
    }
}
function selComp(proNo) {
    if (!ie) return;
    document.getElementById("basket").style.visibility = 'visible';
    var ckids="";
    var cks = document.getElementsByName("selCompBox");
    for(var i=0;i<cks.length;i++)
    {
        if(cks[i].checked)
            ckids += cks[i].value+",";
    }
    document.c.id.value = ckids;
    document.c.name.value = proNo;
    document.c.submit();

    if (expandState == 0) {
        showBox();
    }

    if (timeoutProcess != null) {
        clearTimeout(timeoutProcess);
    }

    timeoutProcessCounter = autoIndentTime;
    timeoutProcess = setTimeout("hideBoxInSeconds()", 1000);
}
function selCompExamine(proNo,billNo){
    if (!ie) return;
    document.getElementById("basket").style.visibility = 'visible';
    document.c.id.value = billNo;
    document.c.name.value = proNo;
    document.c.submit();

    if (timeoutProcess != null) {
        clearTimeout(timeoutProcess);
    }

    timeoutProcessCounter = autoIndentTime;
    timeoutProcess = setTimeout("hideBoxInSeconds()", 1000);
}
function submitComp(){
    var ckids="";
    var cks = document.frames("compareBox").document.getElementsByName("compareCheckbox");
    var x=0;
    for(var i=0;i<cks.length;i++)
    {
        if(cks[i].checked){
            ckids += cks[i].value+",";
            x++;
        }
    }
    if(x>=2&&ckids!="")
        window.open("cost_detail_compare_list.php?billNos="+ckids);
    else
        alert("请选择两个以上进行比较！");
}
//style="visibility:hidden"
var initWidth;
var initLeft;
if(ie){
    document.write('    <div id="basket" style="visibility:hidden">');
    document.write('        <div id="basket_close" onclick="showBoxManual()"><i>&gt;&gt;</i><strong>报销单比较</strong></div>');
    document.write('        <div id="basket_show" onclick="hideBox()">');
    document.write('            <div id="basket_top"><strong>报销单比较</strong><i>收回&lt;&lt;</i></div>');
    document.write('            <iframe name=compareBox width="180" height="388" src="cost_detail_compare.php" frameborder="0" scolling="no"></iframe>');
    document.write('            <div id="basket_buttom"><input type="button" value="比较" onclick="submitComp()" class="BigButton"></div>');
    document.write('        </div>');
    document.write('    </div>');
    document.write("<FORM name=c METHOD=POST ACTION='cost_detail_compare.php' target=compareBox>");
    document.write("<INPUT TYPE=hidden NAME=id >");
    document.write("<INPUT TYPE=hidden NAME=name >");
    document.write("<INPUT TYPE=hidden NAME=method VALUE='a'>");
    document.write("</FORM>");
    initWidth = document.getElementById("basket_show").offsetWidth-2 ;
    initLeft = document.getElementById("basket_show").offsetLeft ;
    init(initWidth,initLeft,firstStep);
}