<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title></title>
   <script type="text/javascript" src="../../jquery.js"></script>
    <script type="text/javascript" src="tabs.js"></script>
    <script type="text/javascript" src="toolbox.history.js"></script>
     <LINK rel="stylesheet" href="css/tabs.css" type="text/css">
    <script type="text/javascript">



$(function() {
	//初始化tabsURL
 	var tabArr=[
    			"?model=rdproject_task_rdtask&action=toTab1",
    			"?model=rdproject_task_rdtask&action=toTab2",
    			"?model=rdproject_task_rdtask&action=toTab3"
    			];
	//绑定事件(待抽出来)
    $("#tabs1").tabs(".panes > iframe",{ history: true,onClick:function(i,e) {
   		 var actTabId="#iframe" + (parseInt(e) + 1);
            $(actTabId).attr("src",tabArr[e]);
    }});


});

    </script>

</head>
<body>

<div>
    <!-- the tabs -->
    <ul class="tabs" id="tabs1">
        <li><a href="#tab1" >Tab 1</a></li>
        <li><a href="#tab2" >Tab 2</a></li>
        <li><a href="#tab3">Tab 3</a></li>
    </ul>

    <!-- tab "panes" -->
    <div class="panes">
            <iframe class="pane" id="iframe1"  src= "about:blank" frameborder= "0" >
            </iframe>
            <iframe class="pane"  id="iframe2"   src= "about:blank"  frameborder= "0">
            </iframe>
            <iframe class="pane"  id="iframe3"  src= "about:blank"  frameborder= "0">
            </iframe>
    </div>

</div>


</body>
</html>