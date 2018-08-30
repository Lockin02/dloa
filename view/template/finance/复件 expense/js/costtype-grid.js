$(function() {
	var treeObj = $('#costTypeGrid');
	var thisHeight = document.documentElement.clientHeight - 40;

	treeObj.treegrid({
		title : ' �·�������',
		url : '?model=finance_expense_costtype&action=treeJson',
		width : '98%',
		height : thisHeight,
		nowrap : false,
		rownumbers : true,
		animate : false,
		collapsible : true,
		idField : 'id',
		treeField : 'CostTypeName',//��������
		fitColumns : false,//�����Ӧ
		pagination : false,//��ҳ
		showFooter : false,//��ʾͳ��
		columns : [[
			{
				title : '������������',
				field : 'CostTypeName',
				width : 280
			},{
				field : 'k3Code',
				title : 'K3���ͱ���',
				width : 80
			},{
				field : 'k3Name',
				title : 'K3��������',
				width : 100
			},{
				field : 'invoiceTypeName',
				title : 'Ĭ�Ϸ�Ʊ����',
				width : 80
			},{
				field : 'showDays',
				title : '��ʾ����',
				align : 'center',
				formatter:function(v,row){
					if(v == '1'){
                		return '��';
				 	}else{
				 		return '';
				 	}
				},
				width : 60
			},{
				field : 'isReplace',
				title : '������Ʊ',
				width : 60,
				align : 'center',
				formatter:function(v,row){
					if(v == '1'){
                		return '��';
				 	}else{
				 		return '';
				 	}
				}
			},{
				field : 'isEqu',
				title : '¼�������嵥',
				width : 80,
				align : 'center',
				formatter:function(v,row){
					if(v == '1'){
                		return '��';
				 	}else{
				 		return '';
				 	}
				}
			},{
				field : 'isSubsidy',
				title : '�Ƿ���',
				width : 60,
				align : 'center',
				formatter:function(v,row){
					if(v == '1'){
                		return '��';
				 	}else{
				 		return '';
				 	}
				}
			},{
				field : 'isClose',
				title : '�Ƿ�ر�',
				width : 60,
				align : 'center',
				formatter:function(v,row){
					if(v == '1'){
                		return '��';
				 	}else{
				 		return '';
				 	}
				}
			},{
				field : 'orderNum',
				title : '�����',
				width : 50
			},{
				field : 'esmCountName',
				title : '����ͳ�Ʊ��',
				width : 80
			},{
				field : 'Remark',
				title : '��ע��Ϣ',
				width : 120
			}
		]],
		onContextMenu : function(e, row) {
			e.preventDefault();
			$(this).treegrid('unselectAll');
			$(this).treegrid('select', row.id);
			$('#menuDiv').menu('show', {
				left : e.pageX,
				top : e.pageY
			});
		}
	});
});

//ԭҳ��ˢ�·���
function show_page(){
	reload();
}

//ˢ��
function reload(){
	var node = $('#costTypeGrid').treegrid('getSelected');
	if (node){
		$('#costTypeGrid').treegrid('reload', node._parentId);
	} else {
		$('#costTypeGrid').treegrid('reload');
	}
}

//�����ڵ�
function append(){
	if($("#isNewHidden").val() == "0"){
		alert('�ݲ�֧���ڴ��б���ӷ�������');
	}else{
		var node = $('#costTypeGrid').treegrid('getSelected');
		if(node){
			showThickboxWin("?model=finance_expense_costtype&action=toAdd&ParentCostTypeID=" + node.id + "&ParentCostType=" + node.CostTypeName + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
		}else{
			showThickboxWin("?model=finance_expense_costtype&action=toAdd&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
		}
	}
}

//�༭�ڵ�
function edit(){
	var node = $('#costTypeGrid').treegrid('getSelected');
	if (node){
		showThickboxWin("?model=finance_expense_costtype&action=toEdit&id=" + node.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
	}else{
		alert('��ѡ��һ���ڵ�');
	}
}


//ɾ���ڵ�
function remove(){
	var node = $('#costTypeGrid').treegrid('getSelected');
	if (node){
		if(node.isNew == "1"){
			if(confirm('ȷ��Ҫɾ����')){
				//�첽ɾ���ڵ�
				$.ajax({
				    type: "POST",
				    url: "?model=finance_expense_costtype&action=ajaxdeletes",
				    data: {
				    	id : node.id
				    },
				    async: false,
				    success: function(data){
				   		if(data == "1"){
							alert('ɾ���ɹ�');
							reload();
				   	    }else{
							alert('ɾ��ʧ��');
							return false;
				   	    }
					}
				});
			}
		}else{
			alert('�ݲ���ɾ���ɱ�������');
		}
	}else{
		alert('��ѡ��һ���ڵ�');
	}
}