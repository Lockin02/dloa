	var zTree;
	var demoIframe;

	var setting = {

		url: "?model=system_procity_province&action=getChildren",

		callback:{
			beforeChange: zTreeBeforeChange,
			beforeRemove :zTreeBeforeRemove,
			click: function(a,b,c){
			$("#proId").val(c.id);
				//$(".procitylist").yxgrid("reload");
				$('#testIframe').attr("src","?model=system_procity_city&action=page");
			},
			change:zTreeChange,
			drop: zTreeOnDrop,
			rename:zTreeRename
		}
	};

//	zNodes = {myrows};
	zNodes = [];

	$(document).ready(function(){
		refresh();
	});

	function zTreeOnClick(event, treeId, treeNode) {
		$('#testIframe').attr("src","?model=system_procity_city&action=page&provinceId="+treeNode.id);
	}

	//删除
	function zTreeBeforeRemove(treeId,treeNode){
		if(treeNode.isParent){
			alert('此节点非叶子节点，不能删除');
			return false;
		}else{
			 $.post("?model=system_procity_province&action=ajaxDelete",{
				id : treeNode.id ,
				parentId : treeNode.parentId
			},function(data,textStatus){
				if(data == "1"){
					alert('删除成功');
					refreshE();
				}else{
					alert('删除失败');
					refreshE();
				}
			});
		}
	}

	function zTreeBeforeChange(treeId,treeNode){
		alert(treeNode.parentId)
	}

	function zTreeChange(treeId,treeNode){
//		alert(treeNode.parentId)
	}

	//展开&折叠
	function expandAll(expandSign) {
		zTree.expandAll(expandSign);
	}

	function expandNode(expandSign) {
		zTree.expandNode(zTree,expandSign,true);
	}

	//重命名
	function zTreeRename(event,treeId,treeNode){
		$.post("?model=system_procity_province&action=ajaxEdit",{
			id : treeNode.id ,
			name : treeNode.name
		},function(data,textStatus){
			if(data == "1"){
				refreshE();
			}else{
				alert('修改失败');
				refreshE();
			}
		});
	}

	//拖拽落下时
	function zTreeOnDrop(event, treeId, treeNode, targetNode) {
	    $.post("?model=system_procity_province&action=ajaxDrop",{
			id : treeNode.id ,
			newParentId : targetNode.id,
			newParentName : targetNode.name,
			oldParentId : treeNode.parentId
		},function(data,textStatus){
			if(data == "1"){
				refreshE();
			}else{
				alert('移动失败');
				refreshE();
			}
		});
	}

	//刷新 - 进入只读状态
	function refresh(){
		setting.editable = false;
		setting.edit_renameBtn = false;
		setting.edit_removeBtn = false;
		zTree = $("#tree").yxtree(setting);
	}

	//刷新 - 进入读写状态
	function refreshE(){
		setting.editable = true;
		setting.edit_renameBtn = true;
		setting.edit_removeBtn = true;
		zTree = $("#tree").yxtree(setting);
	}

	//导航 - 状态转换
	function editOrRead(obj){
		thisObj = $('#'+obj.id);
		if(thisObj.attr("title") == "进入编辑状态"){
			thisObj.attr('class','ico read');
			thisObj.attr('title','进入只读状态');
			refreshE();
		}else{
			thisObj.attr('class','ico edit');
			thisObj.attr('title','进入编辑状态');
			refresh();
		}
	}

	function ajaxAdd(selectedNode){

	}

	//添加节点方法 1
	function addNodeInTree(){
		if($('#statusButton').attr('title') == "进入编辑状态"){
			alert('只能在可编辑状态添加节点');
			return false;
		}

		var selectedNode = zTree.getSelectedNode();
		if(selectedNode == null){
			selectedNode = {id:'-1',name:'根节点'};
		}

		$.post("?model=stock_productinfo_province&action=ajaxAdd",{
			name : '新增节点',
			leaf : '1',
			parentId : selectedNode.id,
			parentName : selectedNode.name
		},function(data,textStatus){
			if(data != '0'){
				addNodesText(data,selectedNode);
			}else{
				alert('添加出错');
				return false;
			}
		});
	}

	//添加节点方法2
	function addNodesText(data,selectedNode){
		var newNodes = [{id:data,name:'新增节点',isParent:'0',parentId:selectedNode.id}];
		var nodes = zTree.addNodes(selectedNode, newNodes);
		refreshE();
	}