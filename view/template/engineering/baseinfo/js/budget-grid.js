var show_page = function(page) {
	$("#budgetGrid").yxgrid("reload");

	var nodes = treeObj.getSelectedNodes();
	treeObj.reAsyncChildNodes(nodes[0],'refresh');
};

//�����󻺴�
var treeObj;

$(function() {

	$.ajax({
	    type: "POST",
	    url: "?model=engineering_baseinfo_budget&action=checkParent",
	    data : "",
	    async: false
	});

	/******������������*********/

	//����������
	var setting = {
		async : {
			enable : true,
			url : "?model=engineering_baseinfo_budget&action=getChildren",
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

		var budgetGrid = $("#budgetGrid").data('yxgrid');
		budgetGrid.options.param['parentId']=treeNode.id;

		budgetGrid.reload();
		$("#parentId").val(treeNode.id);
	}


    $("#budgetGrid").yxgrid({
        model : 'engineering_baseinfo_budget',
        title : 'Ԥ����Ŀ',
        //����Ϣ
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        }         ,{
            name : 'budgetCode',
            display : 'Ԥ�����',
            sortable : true
        }         ,{
            name : 'budgetName',
            display : 'Ԥ������',
            sortable : true
        }         ,{
            name : 'parentId',
            display : '���ڵ�id',
            sortable : true,
            hide:true
        }         ,{
            name : 'parentCode',
            display : '���ڵ����',
            sortable : true,
            hide:true
        }         ,{
            name : 'parentName',
            display : '�ϼ�����',
            sortable : true
        }         ,{
            name : 'currencyUnit',
            display : '���ҵ�λ',
            sortable : true
        }         ,{
            name : 'subjectName',
            display : '��Ŀ����',
            sortable : true
        }         ,{
            name : 'subjectCode',
            display : '��Ŀ����',
            sortable : true,
            hide:true
        }         ,{
            name : 'budgetType',
            display : '��������',
            datacode : 'FYLX',
            sortable : true
        }         ,{
            name : 'orderNum',
            display : '����˳���',
            sortable : true
        }         ,{
            name : 'isLeaf',
            display : '�Ƿ�Ҷ�ӽڵ�',
            sortable : true,
            hide:true
        }         ,{
            name : 'remark',
            display : '��ע',
			hide:true,
            sortable : true
        }         ,{
            name : 'status',
            display : '״̬',
            sortable : true,
            process : function(v){
				 if (v == 0) {
                     return "����";
                 }else if(v == 1){
                     return "����";
                 }else{
                     return "���ݳ���";
                 }
            }
        }],
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
						url : "?model=engineering_baseinfo_budget&action=changeStatus",
						data : { "id" : row.id , "status" : 1 },
						success : function(msg) {
							if( msg == 1 ){
								alert('���óɹ���');
				                $("#budgetGrid").yxgrid("reload");
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
						url : "?model=engineering_baseinfo_budget&action=changeStatus",
						data : { "id" : row.id , "status" : 0 },
						success : function(msg) {
							if( msg == 1 ){
								alert('���óɹ���');
				                $("#budgetGrid").yxgrid("reload");
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
						url : "?model=engineering_baseinfo_budget&action=deleteCheck",
						data : { "rowData" : rowData },
						success : function(msg) {
									if( msg == 1 ){
										alert('�����Ѿ������ã�������ɾ����');
									}else{
										g.options.toDelConfig.toDelFn(g.options,g);
						                $("#budgetGrid").yxgrid("reload");
									}
								}
					});
			}
		}],
		sortorder : "ASC",
		sortname : "budgetCode",
		isDelAction : false,
		searchitems : [{
				display : "Ԥ�����",
				name : 'budgetCode'
			}, {
				display : "Ԥ������",
				name : 'budgetName'
			}]
    });
});