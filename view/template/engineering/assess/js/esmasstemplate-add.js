//����һЩ����
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

//ָ����Ϣ��ȡ
var zNodes =[];

//��ʼ��
$(document).ready(function(){
	//��ȡ�������
	$.ajax({
	    type: "POST",
		url: "?model=engineering_assess_esmassindex&action=listJson",
	    async: false,
	    success: function(data){
	   		if(data != ""){
				zNodes = eval("(" + data + ")");
	   	    }else{
				alert('û��������ĵ���');
	   	    }
		}
	});
	//��ʼ������Ĭ��ѡ��ڵ�
	var indexIds = $("#indexIds").val();
	var rangeIdArr = indexIds.split(',');
	for (var i=0, l=zNodes.length; i<l; i++) {
		if(jQuery.inArray(zNodes[i].id,rangeIdArr) > -1){
			zNodes[i].checked = true;
		}
	}
	$.fn.zTree.init($("#treeDemo"), setting, zNodes);
});

//check֮ǰ���¼�
function beforeCheck(treeId, treeNode) {
	var check = (treeNode && !treeNode.isParent);
	if (!check) alert("ֻ��ѡ�����...");
	return check;
}

//check�¼�
function onCheck(e, treeId, treeNode) {
	var zTree = $.fn.zTree.getZTreeObj("treeDemo");
	nodes = zTree.getCheckedNodes(true);

	var indexNamsArr = [];//ָ������
	var indexIdsArr = [];//ָ��id

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

	//�Ȼ����ָ�����ݱ��
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

		//��ֵ�ۼ�
		scoreObj.val(accAdd(score,treeNode.upperLimit,0));
	}else{
		var trObj = $("#tr" + treeNode.id);
		if(trObj.length > 0){
			trObj.remove();
		}

		//��ֵ�ۼ�
		scoreObj.val(accSub(score,treeNode.upperLimit,0));
	}

	if(indexInfoObj.children().length == 0){
		indexTrObj.hide();
	}else{
		indexTrObj.show();
	}
	//���ñ�ѡֵ
	checkNeeds();
}

//��ʾ�˵�
function showMenu() {
	var cityObj = $("#indexNames");
	var cityOffset = $("#indexNames").offset();
	$("#menuContent").css({left:cityOffset.left + "px", top:cityOffset.top + cityObj.outerHeight() + "px"}).slideDown("fast");

	$("body").bind("mousedown", onBodyDown);
}

//���ز˵�
function hideMenu() {
	$("#menuContent").fadeOut("fast");
	$("body").unbind("mousedown", onBodyDown);
}

//������ʧ��
function onBodyDown(event) {
	if (!(event.target.id == "menuBtn" || event.target.id == "menuContent" || $(event.target).parents("#menuContent").length>0)) {
		hideMenu();
	}
}

//ѡ���ѡ��
function checkNeeds(){
	var needIndexNamesArr = [];//��ѡָ������
	var needIndexIdsArr = [];//��ѡָ��id
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

//����֤
function checkform(){
	//����
	var name = $("#name").val();
	if(strTrim(name) == ""){
		alert('ģ�����Ʊ���');
		return false;
	}

	//�����ֵ
	var needScore = $("#needScore").val()*1;
	if(needScore == "" || needScore <= 0){
		alert('�����ֵ�������0�в���Ϊ��');
		return false;
	}

	//�����ֵ
	var indexNames = $("#indexNames").val();
	if(indexNames == ""){
		alert('����ָ�겻��Ϊ��');
		return false;
	}

	return true;
}