var show_page = function(page) {
	$("#epersonGrid").yxgrid("reload");

	var nodes = treeObj.getSelectedNodes();
	treeObj.reAsyncChildNodes(nodes[0],'refresh');
};

//�����󻺴�
var treeObj;

$(function() {
	//����Ƿ���ڸ��ڵ㣬û��������
	$.ajax({
	    type: "POST",
	    url: "?model=engineering_baseinfo_eperson&action=checkParent",
	    data : "",
	    async: false
	});

	/******������������*********/

	//����������
	var setting = {
		async : {
			enable : true,
			url : "?model=engineering_baseinfo_eperson&action=getChildren",
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


	//��񲿷ִ���
	$("#epersonGrid").yxgrid({
		model : 'engineering_baseinfo_eperson',
		title : '����Ԥ����Ŀ',
		isDelAction : false,
		//����Ϣ
		colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        }, {
            name : 'personLevel',
            display : '��Ա�ȼ�',
            sortable : true,
            process : function(v,row){
                return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_baseinfo_eperson&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
            },
            width : 80
        }, {
            name : 'parentName',
            display : '�ϼ�����',
            sortable : true,
        hide : true
        }, {
            name : 'isLeaf',
            display : '�Ƿ�Ҷ�ӽڵ�',
            sortable : true,
            hide : true
        }, {
            name : 'price',
            display : '����',
            sortable : true,
            width : 80
        }, {
            name : 'number',
            display : '����',
            sortable : true,
            hide : true
        }, {
            name : 'money',
            display : '���',
            sortable : true,
            hide : true
        }, {
            name : 'unit',
            display : '��λ',
            sortable : true,
            width : 70
        }, {
            name : 'coefficient',
            display : '����ϵ��',
            sortable : true,
            width : 70
        }, {
            name : 'orderNum',
            display : '�����',
            sortable : true,
            width : 70
        }, {
            name : 'customPrice',
            display : '�Զ��嵥��',
            sortable : true,
            width : 70,
            process : function(v){
                return v == "1" ? "<span class='blue'>��</span>" : "��";
            }
        }, {
            name : 'status',
            display : '״̬',
            sortable : true,
            width : 70,
            process : function(v){
                 if (v == 0) {
                     return "����";
                 }else if(v == 1){
                     return "����";
                 }else{
                     return "���ݳ���";
                 }
            }
        }, {
            name : 'remark',
            display : '��ע',
            sortable : true,
            width : 150
        },{
            name : 'nonFamilyShort',
            display : '�Ǽ�ͥ�Ͱ��´����вμ���Ŀ(����)',
            sortable : true,
            width : 200
        },{
            name : 'nonFamilyLong',
            display : '�Ǽ�ͥ�Ͱ��´����вμ���Ŀ(����)',
            sortable : true,
            width : 200
        },{
            name : 'adminProject',
            display : '�����������Ļ���´����ڳ��в�����Ŀ',
            sortable : true,
            width : 300
        },{
            name : 'familyProject',
            display : '��ͥ���ڳ��в�����Ŀ',
            sortable : true,
            width : 150
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
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
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
						url : "?model=engineering_baseinfo_eperson&action=changeStatus",
						data : { "id" : row.id , "status" : 1 },
						success : function(msg) {
							if( msg == 1 ){
								alert('���óɹ���');
				                $("#epersonGrid").yxgrid("reload");
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
						url : "?model=engineering_baseinfo_eperson&action=changeStatus",
						data : { "id" : row.id , "status" : 0 },
						success : function(msg) {
							if( msg == 1 ){
								alert('���óɹ���');
				                $("#epersonGrid").yxgrid("reload");
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
						url : "?model=engineering_baseinfo_eperson&action=deleteCheck",
						data : { "rowData" : rowData },
						success : function(msg) {
									if( msg == 1 ){
										alert('�����Ѿ������ã�������ɾ����');
									}else{
										g.options.toDelConfig.toDelFn(g.options,g);
						                $("#epersonGrid").yxgrid("reload");
									}
								}
					});
			}
		}],
		searchitems : {
			display : "�����ֶ�",
			name : 'XXX'
		},
		sortorder : "ASC",
		sortname : "orderNum"
	});
});