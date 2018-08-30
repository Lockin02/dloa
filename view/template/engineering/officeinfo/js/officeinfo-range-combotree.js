//选择事件
var rangeArr = [];//省份名称
var rangeIdArr = [];//省份id
var rangeCodeArr = [];//省份编码
var managerNameArr = [];//省份经理
var managerIdArr = [];//省份经理id

function onCheck(e, treeId, treeNode) {
	var zTree = $.fn.zTree.getZTreeObj("treeDemo");
	nodes = zTree.getCheckedNodes(true);

	rangeArr = [];//省份名称
	rangeIdArr = [];//省份id
	rangeCodeArr = [];//省份编码
	managerNameArr = [];//省份经理
	managerIdArr = [];//省份经理id

	var innerManagerIdArr = [];//循环内的经理数组
	var innerManagerNameArr = [];
	var tempManagerId;
	var tempManagerName;
	for (var i=0, l=nodes.length; i<l; i++) {
		rangeArr.push(nodes[i].provinceName);
		rangeIdArr.push(nodes[i].id);
		rangeCodeArr.push(nodes[i].code);

		//如果服务经理是空值，则直接跳过此次循环
		if(!nodes[i].managerId || nodes[i].managerId == ""){
			continue;
		}

		tempManagerId = nodes[i].managerId;
		innerManagerIdArr = tempManagerId.split(',');

		if(innerManagerIdArr.length > 1 ){//如果省份的服务经理由一个人以上构成

			tempManagerName = nodes[i].managerName;
			innerManagerNameArr = tempManagerName.split(',');

			for (var j=0, k=innerManagerIdArr.length; j<k; j++) {
				//如果已经存在服务经理数组中，则忽略
				if(jQuery.inArray(innerManagerIdArr[j],managerIdArr) == -1){
					managerNameArr.push(innerManagerNameArr[j]);
					managerIdArr.push(innerManagerIdArr[j]);
				}
			}
		}else if(innerManagerIdArr.length == 1){
			//如果已经存在服务经理数组中，则忽略
			if(jQuery.inArray(nodes[i].managerId,managerIdArr) == -1){
				managerNameArr.push(nodes[i].managerName);
				managerIdArr.push(nodes[i].managerId);
			}
		}
	}
	if(rangeArr.length > 0){
		$("#range").val(rangeArr.toString());
		$("#rangeId").val(rangeIdArr.toString());
		$("#rangeCode").val(rangeCodeArr.toString());
		$("#managerName").val(managerNameArr.toString());
		$("#managerCode").val(managerIdArr.toString());
	}else{
		$("#range").val("");
		$("#rangeId").val("");
		$("#rangeCode").val("");
		$("#managerName").val("");
		$("#managerCode").val("");
	}
}

//显示选择部分
function showMenu() {
	var cityObj = $("#range");
	var cityOffset = $("#range").offset();
	$("#menuContent").css({left:cityOffset.left + "px", top:cityOffset.top + cityObj.outerHeight() + "px"}).slideDown("fast");
	$("body").bind("mousedown", onBodyDown);
}

//隐藏选择部分
function hideMenu() {
	$("#menuContent").fadeOut("fast");
	$("body").unbind("mousedown", onBodyDown);
}
//未知
function onBodyDown(event) {
	if (!(event.target.id == "menuBtn" || event.target.id == "menuContent" || $(event.target).parents("#menuContent").length>0)) {
		hideMenu();
	}
}

//树的公共属性
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
		onCheck: onCheck
	},
	check: {
		enable: true
	}
};

//初始化树结构
function initTree(){
	var zNodes =[];
	//获取相关数据
	$.ajax({
	    type: "POST",
		url: "?model=system_procity_province&action=getProvinceAndManager",
		data : {'businessBelong' : $("#businessBelong").val(),'productLine' : $("#productLine").val()},
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
	var rangeId = $("#rangeId").val();
	var rangeIdArr = rangeId.split(',');
	for (var i=0, l=zNodes.length; i<l; i++) {
		if(jQuery.inArray(zNodes[i].id,rangeIdArr) > -1){
			zNodes[i].checked = true;
		}
	}

	$.fn.zTree.init($("#treeDemo"), setting, zNodes);
}

//重新根据省份和公司获取服务经理
function reloadManager(){
	//获取相关数据
	$.ajax({
	    type: "POST",
		url: "?model=engineering_officeinfo_manager&action=getManager",
		data : {'businessBelong' : $("#businessBelong").val(),'provinceIdArr' : $("#rangeId").val(),'productLine' : $("#productLine").val()},
	    async: false,
	    success: function(data){
	    	data = eval("(" + data + ")");
	    	var managerId = data.managerId;
	    	var managerName = data.managerName;
			$("#managerId").val(managerId);
			$("#managerName").val(managerName);
			managerNameArr = managerName.split(',');//省份经理
			managerIdArr = managerId.split(',');//省份经理id
		}
	});
}