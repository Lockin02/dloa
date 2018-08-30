	var zTree;
	var demoIframe;

	var setting = {

		url: "?model=system_datadict_datadict&action=getChildren",

		callback:{
			beforeChange: zTreeBeforeChange,
			beforeRemove :zTreeBeforeRemove,
			click: zTreeOnClick,
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
		$('#testIframe').attr("src","?model=system_datadict_datadict&action=page&id="+treeNode.id);
	}

	//ɾ��
	function zTreeBeforeRemove(treeId,treeNode){
		if(treeNode.isParent){
			alert('�˽ڵ��Ҷ�ӽڵ㣬����ɾ��');
			return false;
		}else{
			 $.post("?model=system_datadict_datadict&action=ajaxDelete",{
				id : treeNode.id ,
				parentId : treeNode.parentId
			},function(data,textStatus){
				if(data == "1"){
					alert('ɾ���ɹ�');
					refreshE();
				}else{
					alert('ɾ��ʧ��');
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

	//չ��&�۵�
	function expandAll(expandSign) {
		zTree.expandAll(expandSign);
	}

	function expandNode(expandSign) {
		zTree.expandNode(zTree,expandSign,true);
	}

	//������
	function zTreeRename(event,treeId,treeNode){
		$.post("?model=system_datadict_datadict&action=ajaxEdit",{
			id : treeNode.id ,
			name : treeNode.name
		},function(data,textStatus){
			if(data == "1"){
				refreshE();
			}else{
				alert('�޸�ʧ��');
				refreshE();
			}
		});
	}

	//��ק����ʱ
	function zTreeOnDrop(event, treeId, treeNode, targetNode) {
	    $.post("?model=system_datadict_datadict&action=ajaxDrop",{
			id : treeNode.id ,
			newParentId : targetNode.id,
			newParentName : targetNode.name,
			oldParentId : treeNode.parentId
		},function(data,textStatus){
			if(data == "1"){
				refreshE();
			}else{
				alert('�ƶ�ʧ��');
				refreshE();
			}
		});
	}

	//ˢ�� - ����ֻ��״̬
	function refresh(){
		setting.editable = false;
		setting.edit_renameBtn = false;
		setting.edit_removeBtn = false;
		zTree = $("#tree").yxtree(setting);
	}

	//ˢ�� - �����д״̬
	function refreshE(){
		setting.editable = true;
		setting.edit_renameBtn = true;
		setting.edit_removeBtn = true;
		zTree = $("#tree").yxtree(setting);
	}

	//���� - ״̬ת��
	function editOrRead(obj){
		thisObj = $('#'+obj.id);
		if(thisObj.attr("title") == "����༭״̬"){
			thisObj.attr('class','ico read');
			thisObj.attr('title','����ֻ��״̬');
			refreshE();
		}else{
			thisObj.attr('class','ico edit');
			thisObj.attr('title','����༭״̬');
			refresh();
		}
	}

	function ajaxAdd(selectedNode){

	}

	//��ӽڵ㷽�� 1
	function addNodeInTree(){
		if($('#statusButton').attr('title') == "����༭״̬"){
			alert('ֻ���ڿɱ༭״̬��ӽڵ�');
			return false;
		}

		var selectedNode = zTree.getSelectedNode();
		if(selectedNode == null){
			selectedNode = {id:'-1',name:'���ڵ�'};
		}

		$.post("?model=system_datadict_datadict&action=ajaxAdd",{
			name : '�����ڵ�',
			leaf : '1',
			parentId : selectedNode.id,
			parentName : selectedNode.name
		},function(data,textStatus){
			if(data != '0'){
				addNodesText(data,selectedNode);
			}else{
				alert('��ӳ���');
				return false;
			}
		});
	}

	//��ӽڵ㷽��2
	function addNodesText(data,selectedNode){
		var newNodes = [{id:data,name:'�����ڵ�',isParent:'0',parentId:selectedNode.id}];
		var nodes = zTree.addNodes(selectedNode, newNodes);
		refreshE();
	}
