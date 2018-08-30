//ѡ���¼�
var rangeArr = [];//ʡ������
var rangeIdArr = [];//ʡ��id
var rangeCodeArr = [];//ʡ�ݱ���
var managerNameArr = [];//ʡ�ݾ���
var managerIdArr = [];//ʡ�ݾ���id

function onCheck(e, treeId, treeNode) {
	var zTree = $.fn.zTree.getZTreeObj("treeDemo");
	nodes = zTree.getCheckedNodes(true);

	rangeArr = [];//ʡ������
	rangeIdArr = [];//ʡ��id
	rangeCodeArr = [];//ʡ�ݱ���
	managerNameArr = [];//ʡ�ݾ���
	managerIdArr = [];//ʡ�ݾ���id

	var innerManagerIdArr = [];//ѭ���ڵľ�������
	var innerManagerNameArr = [];
	var tempManagerId;
	var tempManagerName;
	for (var i=0, l=nodes.length; i<l; i++) {
		rangeArr.push(nodes[i].provinceName);
		rangeIdArr.push(nodes[i].id);
		rangeCodeArr.push(nodes[i].code);

		//����������ǿ�ֵ����ֱ�������˴�ѭ��
		if(!nodes[i].managerId || nodes[i].managerId == ""){
			continue;
		}

		tempManagerId = nodes[i].managerId;
		innerManagerIdArr = tempManagerId.split(',');

		if(innerManagerIdArr.length > 1 ){//���ʡ�ݵķ�������һ�������Ϲ���

			tempManagerName = nodes[i].managerName;
			innerManagerNameArr = tempManagerName.split(',');

			for (var j=0, k=innerManagerIdArr.length; j<k; j++) {
				//����Ѿ����ڷ����������У������
				if(jQuery.inArray(innerManagerIdArr[j],managerIdArr) == -1){
					managerNameArr.push(innerManagerNameArr[j]);
					managerIdArr.push(innerManagerIdArr[j]);
				}
			}
		}else if(innerManagerIdArr.length == 1){
			//����Ѿ����ڷ����������У������
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

//��ʾѡ�񲿷�
function showMenu() {
	var cityObj = $("#range");
	var cityOffset = $("#range").offset();
	$("#menuContent").css({left:cityOffset.left + "px", top:cityOffset.top + cityObj.outerHeight() + "px"}).slideDown("fast");
	$("body").bind("mousedown", onBodyDown);
}

//����ѡ�񲿷�
function hideMenu() {
	$("#menuContent").fadeOut("fast");
	$("body").unbind("mousedown", onBodyDown);
}
//δ֪
function onBodyDown(event) {
	if (!(event.target.id == "menuBtn" || event.target.id == "menuContent" || $(event.target).parents("#menuContent").length>0)) {
		hideMenu();
	}
}

//���Ĺ�������
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

//��ʼ�����ṹ
function initTree(){
	var zNodes =[];
	//��ȡ�������
	$.ajax({
	    type: "POST",
		url: "?model=system_procity_province&action=getProvinceAndManager",
		data : {'businessBelong' : $("#businessBelong").val(),'productLine' : $("#productLine").val()},
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
	var rangeId = $("#rangeId").val();
	var rangeIdArr = rangeId.split(',');
	for (var i=0, l=zNodes.length; i<l; i++) {
		if(jQuery.inArray(zNodes[i].id,rangeIdArr) > -1){
			zNodes[i].checked = true;
		}
	}

	$.fn.zTree.init($("#treeDemo"), setting, zNodes);
}

//���¸���ʡ�ݺ͹�˾��ȡ������
function reloadManager(){
	//��ȡ�������
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
			managerNameArr = managerName.split(',');//ʡ�ݾ���
			managerIdArr = managerId.split(',');//ʡ�ݾ���id
		}
	});
}