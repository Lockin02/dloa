var show_page = function(page) {
	$("#esmresourcesGrid").yxgrid("reload");

	//ˢ��tab
	reloadTab('��Ŀ�ſ�');
};

//����ˢ��tab
function reloadTab(thisVal){
	var tt = window.parent.$("#tt");
	var tb=tt.tabs('getTab',thisVal);
	if(tb!= null){
		tb.panel('options').headerCls = tb.panel('options').thisUrl;
	}
}

$(function() {
	//��ֵ����ĸ߶�
	var thisHeight = document.documentElement.clientHeight - 40;

	var projectId = $("#projectId").val();

	//ʵ�����б�
	$("#esmresourcesGrid").yxgrid({
		model : 'engineering_resources_esmresources',
		action : 'managePageJson',
		title : '��Ŀ�豸Ԥ��',
		param : {
			"projectId" : projectId
		},
		noCheckIdValue : 'noId',
		isDelAction : false,
		isAddAction : false,
		isOpButton : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'resourceTypeId',
				display : '�豸����id',
				sortable : true,
				hide : true
			}, {
				name : 'resourceTypeName',
				display : '�豸����',
				sortable : true
			},{
				name : 'resourceId',
				display : '�豸id',
				sortable : true,
				hide : true
			}, {
				name : 'resourceCode',
				display : '�豸����',
				sortable : true,
				hide : true
			}, {
				name : 'resourceName',
				display : '�豸����',
				sortable : true,
				process : function(v,row){
					if(row.id == 'noId') return v;
					if(row.thisType == "0"){
						return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_resources_esmresources&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900\")'>" + v + "</a>";
					}else{
						return "<a href='javascript:void(0)' style='color:red;' title='����е�Ԥ��' onclick='showThickboxWin(\"?model=engineering_resources_esmresources&action=toViewChange&id=" + row.uid + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900\")'>" + v + "</a>";
					}
				},
				width : 160
			}, {
				name : 'number',
				display : '����',
				sortable : true,
				width : 60
			}, {
				name : 'unit',
				display : '��λ',
				sortable : true,
				width : 60,
				hide : true
			}, {
				name : 'planBeginDate',
				display : '��������',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				width : 80
			}, {
				name : 'planEndDate',
				display : '�黹����',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				width : 80
			}, {
				name : 'beignTime',
				display : '��ʼʹ��ʱ��',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				hide : true,
				width : 80
			}, {
				name : 'endTime',
				display : '����ʹ��ʱ��',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				hide : true,
				width : 80
			}, {
				name : 'useDays',
				display : 'ʹ������',
				sortable : true,
				width : 70
			}, {
				name : 'price',
				display : '���豸�۾�',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'amount',
				display : '�豸�ɱ�',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'projectId',
				display : '��Ŀid',
				sortable : true,
				hide : true
			}, {
				name : 'projectCode',
				display : '��Ŀ���',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '��Ŀ����',
				sortable : true,
				hide : true
			}, {
				name : 'activityId',
				display : '����id',
				sortable : true,
				hide : true
			}, {
				name : 'activityName',
				display : '��������',
				sortable : true,
				hide : true
			}, {
				name : 'workContent',
				display : '��������',
				sortable : true,
            	width : 250,
				hide : true
			}, {
				name : 'remark',
				display : '��ע˵��',
				sortable : true,
				hide : true
			}, {
				name : 'applyNo',
				display : '���뵥��',
				sortable : true,
				width : 120
			}, {
				name : 'status',
				display : '����״̬',
				sortable : true,
				width : 75,
				process : function(v){
					switch(v){
						case '0' : return 'δ����';
						case '1' : return '������';
						case '2' : return '�Ѵ���';
						case '3' : return '�����';
						default : return v;
					}
				},
				hide : true
			}, {
				name : 'ExaStatus',
				display : '����״̬',
				sortable : true,
				width : 75
			}, {
				name : 'sendNum',
				display : '�ѷ�������',
				sortable : true,
				width : 80
			}, {
				name : 'receviceNum',
				display : 'ȷ�Ͻ�������',
				sortable : true,
				width : 80
			}
		],
		toAddConfig : {
			formWidth : 950,
			formHeight : 500,
			plusUrl : "&projectId=" + projectId,
			toAddFn : function(p, treeNode, treeId) {
				var canChange = true;
				//�ж���Ŀ�Ƿ���Խ��б��
				$.ajax({
				    type: "POST",
				    url: "?model=engineering_change_esmchange&action=hasChangeInfo",
				    data: {
				    	"projectId" : $("#projectId").val()
				    },
				    async: false,
				    success: function(data){
				   		if(data*1 == -1){
							canChange = false;
				   	    }
					}
				});

				//������ɱ��
				if(canChange == false){
					alert('��Ŀ��������У���ȴ�������ɺ��ٽ��б��������');
					return false;
				}

				var activityId = $("#activityId").val();
				var c = p.toAddConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				showThickboxWin("?model="
						+ p.model
						+ "&action="
						+ c.action
						+ c.plusUrl
						+ "&activityId="
						+ activityId
						+ "&lft="
						+ $("#lft").val()
						+ "&rgt="
						+ $("#rgt").val()
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ h + "&width=" + w);
			}
		},
		toEditConfig : {
			showMenuFn : function(row) {
				return true;
			},
			formWidth : 950,
			formHeight : 500,
			toEditFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				var canChange = true;
				//�ж���Ŀ�Ƿ���Խ��б��
				$.ajax({
				    type: "POST",
				    url: "?model=engineering_change_esmchange&action=hasChangeInfo",
				    data: {
				    	"projectId" : $("#projectId").val()
				    },
				    async: false,
				    success: function(data){
				   		if(data*1 == -1){
							canChange = false;
				   	    }
					}
				});

				//������ɱ��
				if(canChange == false){
					alert('��Ŀ��������У���ȴ�������ɺ��ٽ��б��������');
					return false;
				}
				if(row.thisType == "0"){
					return showThickboxWin("?model=engineering_resources_esmresources&action=toEdit&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=950");
				}else{
					return showThickboxWin("?model=engineering_resources_esmresources&action=toEditChange&id=" + row.uid + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=950");
				}
			}
		},
		toViewConfig : {
			showMenuFn : function(row) {
				return true;
			},
			formWidth : 900,
			formHeight : 400,
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				if(row.thisType == "0"){
					return showThickboxWin("?model=engineering_resources_esmresources&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=950");
				}else{
					return showThickboxWin("?model=engineering_resources_esmresources&action=toViewChange&id=" + row.uid + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=950");
				}
			}
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : 'ɾ��',
			icon : 'delete',
			action : function(row) {
				if (row) {
					var canChange = true;
					var projectId = $("#projectId").val();
					//�ж���Ŀ�Ƿ���Խ��б��
					$.ajax({
					    type: "POST",
					    url: "?model=engineering_change_esmchange&action=hasChangeInfo",
					    data: {
					    	"projectId" : projectId
					    },
					    async: false,
					    success: function(data){
					   		if(data*1 == -1){
								canChange = false;
					   	    }
						}
					});

					//������ɱ��
					if(canChange == false){
						alert('��Ŀ��������У���ȴ�������ɺ��ٽ��б��������');
						return false;
					}
					if(row.thisType == "0"){
						var id = row.id;
						var changeId = '';
					}else{
						var id = '';
						var changeId = row.uid;
					}
					if (window.confirm(("ȷ��Ҫɾ��?"))) {
						$.ajax({
							type : "POST",
							url : "?model=engineering_resources_esmresources&action=ajaxdeletes",
							data : {
								"id" : id,
								"changeId" : changeId,
				    			"projectId" : projectId
							},
							success : function(msg) {
								if (msg == 1) {
									alert('ɾ���ɹ���');
									show_page();
								} else {
									alert("ɾ��ʧ��! ");
								}
							}
						});
					}
				}
			}
		}],
		buttonsEx : [{
			name : 'Add',
			text : "��������",
			icon : 'add',
			action : function(row, rows, idArr) {
				var canChange = true;
				//�ж���Ŀ�Ƿ���Խ��б��
				$.ajax({
				    type: "POST",
				    url: "?model=engineering_change_esmchange&action=hasChangeInfo",
				    data: {
				    	"projectId" : $("#projectId").val()
				    },
				    async: false,
				    success: function(data){
				   		if(data*1 == -1){
							canChange = false;
				   	    }
					}
				});

				//������ɱ��
				if(canChange == false){
					alert('��Ŀ��������У���ȴ�������ɺ��ٽ��б��������');
					return false;
				}

				showThickboxWin("?model=engineering_resources_esmresources&action=toBatchAdd&projectId="
						+ projectId
						+ "&activityId="
						+ $("#activityId").val()
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=1000");
			}
		},{
			name : 'exportIn',
			text : "����",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=engineering_resources_esmresources&action=toEportExcelIn&projectId="
						+ $("#projectId").val()
						+ "&activityId="
						+ $("#activityId").val()
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		},{
			text : "ɾ��Ԥ��",
			icon : 'delete',
			name : 'batchAdd',
			action : function(row,rows,idArr ) {
				if(row){
					var canChange = true;
					//�ж���Ŀ�Ƿ���Խ��б��
					$.ajax({
					    type: "POST",
					    url: "?model=engineering_change_esmchange&action=hasChangeInfo",
					    data: {
					    	"projectId" : $("#projectId").val()
					    },
					    async: false,
					    success: function(data){
					   		if(data*1 == -1){
								canChange = false;
					   	    }
						}
					});

					//������ɱ��
					if(canChange == false){
						alert('��Ŀ��������У���ȴ�������ɺ��ٽ��б��������');
						return false;
					}
					if(confirm('ȷ��ɾ��ѡ�е�Ԥ����ô��')){
						var idArr = [];//����id
						var changeIdArr = []; //�����id
						for(var i = 0;i < rows.length ; i++){
							if(rows[i].thisType == "0"){
								idArr.push(rows[i].id);
							}else{
								changeIdArr.push(rows[i].uid);
							}
						}
						$.ajax({
							type : "POST",
							url : "?model=engineering_resources_esmresources&action=ajaxdeletes",
							data : {
								"id" : idArr.toString() ,
								"changeId" : changeIdArr.toString(),
								"projectId" : projectId
							},
							success : function(msg) {
								if (msg == 1) {
									alert('ɾ���ɹ���');
									show_page(1);
								}else{
									alert('ɾ��ʧ��!');
								}
							}
						});
					}
				}else{
					alert('����ѡ������һ����¼');
				}
			}
		}],
		searchitems : [{
				display : "�豸����",
				name : 'resourceNameSearch'
			}
		],
		sortorder : 'ASC'
	});
});