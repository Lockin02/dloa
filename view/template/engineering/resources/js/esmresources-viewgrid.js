var show_page = function(page) {
	$("#esmresourcesGrid").yxgrid("reload");
};

$(function() {
	$("#esmresourcesGrid").yxgrid({
		model : 'engineering_resources_esmresources',
		action : 'viewListJson',
		title : '��Ŀ�豸Ԥ��',
		param : {
			"projectId" : $("#projectId").val()
		},
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		showcheckbox : false,
		noCheckIdValue : 'noId',
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
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_resources_esmresources&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900\")'>" + v + "</a>";
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
		toViewConfig : {
			showMenuFn : function(row) {
				if (row.id == "noId") {
					return false;
				}
				return true;
			},
			formWidth : 900,
			formHeight : 400
		},
		searchitems : [{
				display : "�豸����",
				name : 'resourceNameSearch'
			}
		],
		sortname : 'activityId',
		sortorder : 'ASC'
	});
});