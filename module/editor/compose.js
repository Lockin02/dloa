//Constants.
SEP_PADDING = 5			//工具栏上的分隔条与按钮之间的间距
HANDLE_PADDING = 7		//工具栏把柄与按钮之间的间距


var yToolbars = new Array();  // Array of all toolbars.

// Initialize everything when the document is ready
var YInitialized = false;
// 文档已经下栽并初始化完毕，将触发onreadystatechange事件,更保险的要判断// event.readyState是否为true
function document.onreadystatechange() {
  if (YInitialized) return;
  YInitialized = true;

  var i, s, curr;

  // Find all the toolbars and initialize them.
  for (i=0; i<document.body.all.length; i++) {
    curr=document.body.all[i];
if (curr.className == "yToolbar") {
	//找到是工具栏的对象
if (! InitTB(curr)) {
	//初始化工具栏，这里将会重新布局按钮，并给每个按钮对象设置事件处
//理函数
        alert("Toolbar: " + curr.id + " failed to initialize. Status: false");
}
//送到数组中
      yToolbars[yToolbars.length] = curr;
    }
  }

  //Lay out the page, set handler.
  DoLayout();	//对工具栏和工具栏进行布局，记住工
//具栏的position为relative, 工具栏移动时，上面的
//按钮不需要重新布局
  window.onresize = DoLayout;

  Composition.document.open();	//给Iframe中写一些最基本的外壳代码。
  Composition.document.write("<BODY MONOSPACE STYLE=\"font:11pt 宋体\"></body>");
  Composition.document.close();
  Composition.document.designMode="On";    //IE支持的独有的编辑模式，可以看
//到输入焦点
  setTimeout("Composition.focus()",0);

}

// Initialize a toolbar button
function InitBtn(btn) {
  btn.onmouseover = BtnMouseOver;
  btn.onmouseout = BtnMouseOut;
  btn.onmousedown = BtnMouseDown;
  btn.onmouseup = BtnMouseUp;
  btn.ondragstart = YCancelEvent;		//禁止对按钮进行拖放操作
  btn.onselectstart = YCancelEvent;		//禁止对按钮进行选中操作
  btn.onselect = YCancelEvent;
  btn.YUSERONCLICK = btn.onclick;	//将onclick的操作转移给一个自定义
//的方法，以便更好的控制操作。
  btn.onclick = YCancelEvent;		//取消原有的onclick操作。
  btn.YINITIALIZED = true;
  return true;
}

//Initialize a toolbar.
function InitTB(y) {
  // Set initial size of toolbar to that of the handle
  y.TBWidth = 0;

  // Populate the toolbar with its contents
  if (! PopulateTB(y)) return false;

  // Set the toolbar width and put in the handle
  y.style.posWidth = y.TBWidth;

  return true;
}


// Hander that simply cancels an event
function YCancelEvent() {
  event.returnValue=false;
  event.cancelBubble=true;
  return false;
}

// Toolbar button onmouseover handler
function BtnMouseOver() {
  if (event.srcElement.tagName != "IMG") return false;
  var image = event.srcElement;
  var element = image.parentElement;

  // Change button look based on current state of image.
  if (image.className == "Ico") element.className = "BtnMouseOverUp";
  else if (image.className == "IcoDown") element.className = "BtnMouseOverDown";

  event.cancelBubble = true;
}

// Toolbar button onmouseout handler
function BtnMouseOut() {
  if (event.srcElement.tagName != "IMG") {
    event.cancelBubble = true;
    return false;
  }

  var image = event.srcElement;
  var element = image.parentElement;
  yRaisedElement = null;

  element.className = "Btn";
  image.className = "Ico";

  event.cancelBubble = true;
}

// Toolbar button onmousedown handler
function BtnMouseDown() {
  if (event.srcElement.tagName != "IMG") {
    event.cancelBubble = true;
    event.returnValue=false;
    return false;
  }

  var image = event.srcElement;
  var element = image.parentElement;

  element.className = "BtnMouseOverDown";
  image.className = "IcoDown";

  event.cancelBubble = true;
  event.returnValue=false;
  return false;
}

// Toolbar button onmouseup handler
function BtnMouseUp() {
  if (event.srcElement.tagName != "IMG") {
    event.cancelBubble = true;
    return false;
  }

  var image = event.srcElement;
  var element = image.parentElement;

//如果按下的是加粗按钮，下面eval函数中的
//element.YUSERONCLICK+"anonymous()"将会产生如下结
//果
//function anonymous()
//{
//format('bold');
//}anonymous()
  if (element.YUSERONCLICK) eval(element.YUSERONCLICK + "anonymous()");

  element.className = "BtnMouseOverUp";
  image.className = "Ico";

  event.cancelBubble = true;
  return false;
}

// Populate a toolbar with the elements within it
function PopulateTB(y) {
  var i, elements, element;

  // Iterate through all the top-level elements in the toolbar
  elements = y.children;
  for (i=0; i<elements.length; i++) {
element = elements[i];
//跳过注释和脚本
    if (element.tagName == "SCRIPT" || element.tagName == "!") continue;

    switch (element.className) {
    case "Btn":
      if (element.YINITIALIZED == null) {
	if (! InitBtn(element)) {
	  alert("Problem initializing:" + element.id);
	  return false;
	}
      }
      //定位按钮的左位移
      element.style.posLeft = y.TBWidth;
      y.TBWidth += element.offsetWidth + 1;
      break;

    case "TBGen":
      element.style.posLeft = y.TBWidth;
      y.TBWidth += element.offsetWidth + 1;
      break;

    case "TBSep":
      element.style.posLeft = y.TBWidth + 2;
      y.TBWidth += SEP_PADDING;
      break;

    case "TBHandle":
      element.style.posLeft = 2;
      y.TBWidth += element.offsetWidth + HANDLE_PADDING;
      break;

    default:
      alert("Invalid class: " + element.className + " on Element: " + element.id + " <" + element.tagName + ">");
      return false;
    }
  }

  y.TBWidth += 1;
  return true;
}

function DebugObject(obj) {
  var msg = "";
  for (var i in TB) {
    ans=prompt(i+"="+TB[i]+"\n");
    if (! ans) break;
  }
}

// Lay out the docked toolbars 对工具栏进行布局
function LayoutTBs() {
  NumTBs = yToolbars.length;

  // If no toolbars we're outta here
  if (NumTBs == 0) return;

  //Get the total size of a TBline.
  var i;
  var ScrWid = (document.body.offsetWidth) - 6;
  var TotalLen = ScrWid;
  for (i = 0 ; i < NumTBs ; i++) {
    TB = yToolbars[i];
    if (TB.TBWidth > TotalLen) TotalLen = TB.TBWidth;
  }

  var PrevTB;
  var LastStart = 0;
  var RelTop = 0;
  var LastWid, CurrWid;

  //Set up the first toolbar.
  var TB = yToolbars[0];
  TB.style.posTop = 0;
  TB.style.posLeft = 0;

  //Lay out the other toolbars.
  var Start = TB.TBWidth;
  for (i = 1 ; i < yToolbars.length ; i++) {
    PrevTB = TB;
    TB = yToolbars[i];
    CurrWid = TB.TBWidth;

    if ((Start + CurrWid) > ScrWid) {
      //TB needs to go on next line.
      Start = 0;
      LastWid = TotalLen - LastStart;
    }
    else {
      //Ok on this line.
      LastWid = PrevTB.TBWidth;
      //RelTop -= TB.style.posHeight;
      RelTop -= TB.offsetHeight;
    }

    //Set TB position and LastTB width.
    TB.style.posTop = RelTop;
    TB.style.posLeft = Start;
    PrevTB.style.width = LastWid;

    //Increment counters.
    LastStart = Start;
    Start += CurrWid;
  }

  //Set width of last toolbar.
  TB.style.width = TotalLen - LastStart;

  //Move everything after the toolbars up the appropriate amount.
  i--;
  TB = yToolbars[i];
  var TBInd = TB.sourceIndex;
  var A = TB.document.all;
  var item;
  for (i in A) {
    item = A.item(i);
    if (! item) continue;
    if (! item.style) continue;
    if (item.sourceIndex <= TBInd) continue;
    if (item.style.position == "absolute") continue;
    item.style.posTop = RelTop;
  }
}

//Lays out the page.
function DoLayout() {
  LayoutTBs();
}

// Check if toolbar is being used when in text mode
function validateMode() {
  if (! bTextMode) return true;
  alert("要使用工具条，请不要选择 \"编辑HTML源文件\"。");
  Composition.focus();
  return false;
}

//Formats text in composition.
function format(what,opt) {
  if (!validateMode()) return;

  if (opt=="removeFormat") {
    what=opt;
    opt=null;
  }
//让Html编辑器执行相应的命令
  if (opt==null) Composition.document.execCommand(what);
  else Composition.document.execCommand(what,"",opt);

  pureText = false;
  Composition.focus();
}

//Switches between text and html mode.
function setMode(newMode) {
  bTextMode = newMode;
  var cont;
  if (bTextMode) {
    cleanHtml();
    cleanHtml();

    cont=Composition.document.body.innerHTML;
    Composition.document.body.innerText=cont;
  } else {
    cont=Composition.document.body.innerText;
    Composition.document.body.innerHTML=cont;
  }

  Composition.focus();
}

//Finds and returns an element.
function getEl(sTag,start) {
  while ((start!=null) && (start.tagName!=sTag)) start = start.parentElement;
  return start;
}

function createLink() {
  if (!validateMode()) return;

  var isA = getEl("A",Composition.document.selection.createRange().parentElement());
  var str=prompt("输入网页链接 (例如 http://www.sohu.com):", isA ? isA.href : "http:\/\/");

  if ((str!=null) && (str!="http://")) {
    if (Composition.document.selection.type=="None") {
      var sel=Composition.document.selection.createRange();
      sel.pasteHTML("<A HREF=\""+str+"\">"+str+"</A> ");
      sel.select();
    }
    else format("CreateLink",str);
  }
  else Composition.focus();
}

function createImage() {
  if (!validateMode()) return;

  var isA = getEl("A",Composition.document.selection.createRange().parentElement());
  var str=prompt("Enter image location (e.g. http://www.ehottest.com/gif/camera.gif):", isA ? isA.href : "http:\/\/");

  if ((str!=null) && (str!="http://")) {
    if (Composition.document.selection.type=="None") {
      var sel=Composition.document.selection.createRange();
      sel.pasteHTML("<img src=\""+str+"\">");
      sel.select();
    }
    else format("CreateImage",str);
  }
  else Composition.focus();
}

//Sets the text color.
function foreColor() {
  if (! validateMode()) return;
  var arr = showModalDialog("/ym/ColorSelect?3", "", "font-family:Verdana; font-size:12; dialogWidth:30em; dialogHeight:35em");
  if (arr != null) format('forecolor', arr);
  else Composition.focus();
}

//Sets the background color.
function backColor() {
  if (!validateMode()) return;
  var arr = showModalDialog("/ym/ColorSelect?3", "", "font-family:Verdana; font-size:12; dialogWidth:30em; dialogHeight:35em");
  if (arr != null) format('backcolor', arr);
  else Composition.focus()
}

function cleanHtml() {
  var fonts = Composition.document.body.all.tags("FONT");
  var curr;
  for (var i = fonts.length - 1; i >= 0; i--) {
    curr = fonts[i];
    if (curr.style.backgroundColor == "#ffffff") curr.outerHTML = curr.innerHTML;
  }
}

function getPureHtml() {
  var str = "";
  var paras = Composition.document.body.all.tags("P");
  if (paras.length > 0) {
    for (var i=paras.length-1; i >= 0; i--) str = paras[i].innerHTML + "\n" + str;
  } else {
    str = Composition.document.body.innerHTML;
  }
  return str;
}
