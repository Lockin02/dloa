var show_page = function(page) {
	$("#resourceGrid").yxgrid("reload");

	var nodes = treeObj.getSelectedNodes();
	treeObj.reAsyncChildNodes(nodes[0],'refresh');
};

var treeObj;

$(function() {
	$.ajax({
	    type: "POST",
	    url: "?model=engineering_baseinfo_resource&action=checkParent",
	    data : "",
	    async: false
	});

	/******������������*********/

	//����������
	var setting = {
		async : {
			enable : true,
			url : "?model=engineering_baseinfo_resource&action=getChildren",
			autoParam : ["id", "name=n"],
			otherParam : { 'rtParentType' : 1 }
		},
		callback : {
			onClick : clickFun,
			onAsyncSuccess : zTreeOnAsyncSuccess
		},
		view : {
			selectedMulti : false
		}
	};

	//������
	treeObj = $.fn.zTree.init($("#tree"), setting);

	//��һ�μ��ص�ʱ��ˢ�¸��ڵ�
	var firstAsy = true;
	// ���سɹ���ִ��
	function zTreeOnAsyncSuccess() {
		if (firstAsy) {
			var treeObj = $.fn.zTree.getZTreeObj("tree");
			var nodes = treeObj.getNodes();
			if (nodes.length > 0) {
				treeObj.reAsyncChildNodes(nodes[0], "refresh");
			}
		}
		firstAsy = false;
	}

	//����˫���¼�
	function clickFun(event, treeId, treeNode){

		var budgetGrid = $("#resourceGrid").data('yxgrid');
		budgetGrid.options.param['parentId']=treeNode.id;

		budgetGrid.reload();
		$("#parentId").val(treeNode.id);
	}

	$("#resourceGrid").yxgrid({
		model : 'engineering_baseinfo_resource',
		title : '��ԴĿ¼',
		//����Ϣ
		colModel : [
			{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'resourceCode',
				display : '��Դ����',
				sortable : true
			}, {
				name : 'resourceName',
				display : '��Դ����',
				sortable : true
			}, {
				name : 'parentId',
				display : '���ڵ�id',
				sortable : true,
				hide : true
			}, {
				name : 'parentCode',
				display : '���ڵ����',
				sortable : true,
				hide : true
			}, {
				name : 'parentName',
				display : '�ϼ�����',
				sortable : true
			}, {
				name : 'isLeaf',
				display : '�Ƿ�Ҷ�ӽڵ�',
				sortable : true,
				hide : true
			}, {
				name : 'price',
				display : '��Դ����',
				sortable : true,
				process : function(v){
					if(v != 0.00){
						return v;
					}
				},
				width : 80
			}, {
				name : 'currencyUnit',
				display : '���ҵ�λ',
				sortable : true,
				width : 80
			}, {
				name : 'units',
				display : '������λ',
				sortable : true,
				width : 80
			}, {
				name : 'budgetCode',
				display : 'Ԥ����Ŀ����',
				sortable : true,
				hide : true
			}, {
				name : 'budgetName',
				display : 'Ԥ����Ŀ',
				sortable : true
			}, {
				name : 'orderNum',
				display : '�������',
				sortable : true,
				width : 80
			}, {
				name : 'status',
				display : '״̬',
				sortable : true,
				process : function(v){
					if(v == 0){
						return '����';
					}else{
						return '����';
					}
				},
				width : 80
			}, {
				name : 'remark',
				display : '��ע',
				sortable : true,
				hide : true
			}
		],
		//����
	 	toAddConfig : {
			toAddFn : function(p ,treeNode , treeId) {
				var c = p.toAddConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				showThickboxWin("?model="
					+ p.model
					+ "&action="
					+ c.action
					+ c.plusUrl
					+ "&parentId=" +$("#parentId").val(treeNode.id)
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
					+ h + "&width=" + w);
			}
		},
		menusEx : [{
			text : '����',
			icon : 'edit',
			showMenuFn:function(row){
				if(row.status == 0){
					return true;
				}
				return false;
			},
			action: function(row){
				if (window.confirm(("ȷ��������"))) {
					$.ajax({
						type : "POST",
						url : "?model=engineering_baseinfo_resource&action=changeStatus",
						data : { "id" : row.id , "status" : 1 },
						success : function(msg) {
									if( msg == 1 ){
										alert('���óɹ���');
						                $("#resourceGrid").yxgrid("reload");
									}else{
										alert('����ʧ�ܣ�');
									}
								}
					});
				}
			}
		},
		{
			text : '����',
			icon : 'edit',
			showMenuFn:function(row){
				if(row.status != 0){
					return true;
				}
				return false;
			},
			action: function(row){
				if (window.confirm(("ȷ��������"))) {
					$.ajax({
						type : "POST",
						url : "?model=engineering_baseinfo_resource&action=changeStatus",
						data : { "id" : row.id , "status" : 0 },
						success : function(msg) {
									if( msg == 1 ){
										alert('���óɹ���');
						                $("#resourceGrid").yxgrid("reload");
									}else{
										alert('����ʧ�ܣ�');
									}
								}
					});
				}
			}
		},{
			text : 'ɾ��',
			icon : 'delete',
			action : function(rowData, rows, rowIds, g) {
				$.ajax({
						type : "POST",
						url : "?model=engineering_baseinfo_resource&action=deleteCheck",
						data : { "rowData" : rowData },
						success : function(msg) {
									if( msg == 1 ){
										alert('�����Ѿ������ã�������ɾ����');
									}else{
										g.options.toDelConfig.toDelFn(g.options,g);
						                $("#resourceGrid").yxgrid("reload");
									}
								}
					});
			}
		}],
		isDelAction : false,
		sortname : "resourceCode ASC,orderNum",
		sortorder : "ASC",
		searchitems : [{
				display : "��Դ����",
				name : 'resourceCode'
			}, {
				display : "��Դ����",
				name : 'resourceName'
			}]
	});
});