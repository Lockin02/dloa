//树的一些设置
var setting = {
	view: {
		dblClickExpand: false
	},
	data: {
		simpleData: {
			enable: true
		}
	},
	callback: {
		beforeCheck: beforeCheck,
		onCheck: onCheck
	},
	check: {
		enable: true
	}
};

//指标信息获取
var zNodes =[];

//初始化
$(document).ready(function(){
	//获取相关数据
	$.ajax({
	    type: "POST",
		url: "?model=engineering_assess_esmassindex&action=listJson",
	    async: false,
	    success: function(data){
	   		if(data != ""){
				zNodes = eval("(" + data + ")");
	   	    }else{
				alert('没有相关联的单据');
	   	    }
		}
	});
	//初始化处理默认选择节点
	var indexIds = $("#indexIds").val();
	var rangeIdArr = indexIds.split(',');
	for (var i=0, l=zNodes.length; i<l; i++) {
		if(jQuery.inArray(zNodes[i].id,rangeIdArr) > -1){
			zNodes[i].checked = true;
		}
	}
	$.fn.zTree.init($("#treeDemo"), setting, zNodes);
});

//check之前的事件
function beforeCheck(treeId, treeNode) {
	var check = (treeNode && !treeNode.isParent);
	if (!check) alert("只能选择城市...");
	return check;
}

//check事件
function onCheck(e, treeId, treeNode) {
	var zTree = $.fn.zTree.getZTreeObj("treeDemo");
	nodes = zTree.getCheckedNodes(true);

	var indexNamsArr = [];//指标名称
	var indexIdsArr = [];//指标id

	for (var i=0, l=nodes.length; i<l; i++) {
		indexNamsArr.push(nodes[i].name);
		indexIdsArr.push(nodes[i].id);
	}

	if(indexNamsArr.length > 0){
		$("#indexNames").val(indexNamsArr.toString());
		$("#indexIds").val(indexIdsArr.toString());
	}else{
		$("#indexNames").val("");
		$("#indexIds").val("");
	}

	//先缓存个指标内容表吧
	var indexInfoObj = $("#indexInfo");
	var indexTrObj = $("#indexTr");
	var str;
	var scoreObj = $("#score");
	var score = scoreObj.val();
//	$.showDump(treeNode)

	if(treeNode.checked == true){
		str = "<tr id='tr" + treeNode.id + "'><td>"+ treeNode.name +"</td>"
			+ "<td>"+ treeNode.upperLimit +"</td>"
			+ "<td><input type='checkbox' id='chk" + treeNode.id + "' checked='checked' onclick='checkNeeds();' score='"+treeNode.upperLimit+"' indexName='"+treeNode.name+"' indexId='"+treeNode.id+"'/></td>"
			+ "</tr>";
		indexInfoObj.append(str);

		//分值累加
		scoreObj.val(accAdd(score,treeNode.upperLimit,0));
	}else{
		var trObj = $("#tr" + treeNode.id);
		if(trObj.length > 0){
			trObj.remove();
		}

		//分值累加
		scoreObj.val(accSub(score,treeNode.upperLimit,0));
	}

	if(indexInfoObj.children().length == 0){
		indexTrObj.hide();
	}else{
		indexTrObj.show();
	}
	//设置必选值
	checkNeeds();
}

//显示菜单
function showMenu() {
	var cityObj = $("#indexNames");
	var cityOffset = $("#indexNames").offset();
	$("#menuContent").css({left:cityOffset.left + "px", top:cityOffset.top + cityObj.outerHeight() + "px"}).slideDown("fast");

	$("body").bind("mousedown", onBodyDown);
}

//隐藏菜单
function hideMenu() {
	$("#menuContent").fadeOut("fast");
	$("body").unbind("mousedown", onBodyDown);
}

//配置树失焦
function onBodyDown(event) {
	if (!(event.target.id == "menuBtn" || event.target.id == "menuContent" || $(event.target).parents("#menuContent").length>0)) {
		hideMenu();
	}
}

//选择必选项
function checkNeeds(){
	var needIndexNamesArr = [];//必选指标名称
	var needIndexIdsArr = [];//必选指标id
	var needScoreObj = $("#needScore");
	var needScore = 0;
	$("input[id^='chk']").each(function(i,n){
		if($(this).attr('checked') == true){
			needIndexNamesArr.push($(this).attr('indexName'));
			needIndexIdsArr.push($(this).attr('indexId'));
			needScore = accAdd(needScore,$(this).attr('score'),0);
		}
	});

	if(needIndexIdsArr.length > 0){
		$("#needIndexNames").val(needIndexNamesArr.toString());
		$("#needIndexIds").val(needIndexIdsArr.toString());
	}else{
		$("#needIndexNames").val("");
		$("#needIndexIds").val("");
	}
	needScoreObj.val(needScore);
}

//表单验证
function checkform(){
	//名称
	var name = $("#name").val();
	if(strTrim(name) == ""){
		alert('模板名称必填');
		return false;
	}

	//必须分值
	var needScore = $("#needScore").val()*1;
	if(needScore == "" || needScore <= 0){
		alert('必须分值必须大于0切不能为空');
		return false;
	}

	//必须分值
	var indexNames = $("#indexNames").val();
	if(indexNames == ""){
		alert('考核指标不能为空');
		return false;
	}

	return true;
}