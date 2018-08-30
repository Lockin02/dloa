var show_page = function(page) {
	// $("#proTypeTree").yxtree("reload");
	$("#assetGrid").yxgrid("reload");
};

$(function() {
	var titleVal = "<b>��Ƭ��ѡ  : �빴ѡ��Ҫѡ��Ŀ�Ƭ&nbsp;&nbsp;&nbsp;</b>";
	var showType = $("#showType").val();
	var agencyCode = $("#agencyCode").val();
	var deptId = $("#deptId").val();
	var param = '';
//��ͬҳ�棬���˲���param��ͬ
	if(showType=='borrow'||showType=='charge'){
		param = {
				'useStatusCode' : 'SYZT-XZ',
				'agencyCode' : agencyCode,
				'machineCodeSearch':'0',
				'belongTo' : '0',
				'isScrap':'0'
			};
	}else if(showType=='allocation'){
		param = {
				'useStatusCode' : 'SYZT-XZ',
				'belongTo' : '0',
				'machineCodeSearch':'0',
				'isScrap' : '0'
			};
		if(deptId){
			param.orgId=deptId;
		}
		if(agencyCode){
			param.agencyCode=agencyCode;
		}
	}else if(showType=='sell'){
		param = {
				'isSell' : '0'
			};
	}else if(showType=='scrap' || showType=='requireout'){
		param = {
			'useStatusCode' : 'SYZT-XZ',
			'isScrap' : '0'
			};
	}else{
		param = {
				'isScrap':'0'
			};
	}
	$("#assetGrid").yxgrid({
		model : 'asset_assetcard_assetcard',
		title : titleVal,
		isToolBar : true,
		isViewAction : false,
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		showcheckbox : true,
		param : param,
		colModel : [{
					name : 'property',
					display : '�ʲ�����',
					width : '70',
					process : function(v){
						if(v==0){
							return '�̶��ʲ�'
						}else if(v==1){
							return '��ֵ����Ʒ'
						}
					},
					sortable : true
				}, {
					name : 'assetTypeName',
					display : '�ʲ����',
					width : '70',
					sortable : true
				}, {
					name : 'id',
					display : '�ʲ�Id',
					hide : true,
					sortable : true
				}, {
					name : 'assetCode',
					display : '��Ƭ���',
					width : '160',
					sortable : true
				}, {
					name : 'machineCode',
					display : '������',
					width : '100',
					sortable : true
				}, {
					name : 'assetName',
					display : '�ʲ�����',
					width : '120',
					sortable : true
				}, {
					name : 'useStatusCode',
					display : 'ʹ��״̬����',
					hide : true,
					sortable : true
				}, {
					name : 'useStatusName',
					display : 'ʹ��״̬',
					width : '70',
					sortable : true
				}, {
					name : 'spec',
					display : '����ͺ�',
					sortable : true
				}, {
					name : 'unit',
					display : '������λ',
					hide : true,
					sortable : true
				}, {
					name : 'account',
					display : '����ԭֵ',
					hide : true,
					sortable : true
				}, {
					name : 'buyDate',
					display : '��������',
					sortable : true
				}, {
					name : 'orgId',
					display : '��������id',
					hide : true,
					sortable : true
				}, {
					name : 'orgName',
					display : '��������',
					sortable : true
				},{
					name : 'deploy',
					display : '����',
					sortable : true
				}],
		menusEx : [{
			name : 'view',
			text : "�鿴",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=asset_assetcard_assetcard&action=init&perm=view&id="
						+ row.id
						+ "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		}],
        buttonsEx : [{
			name : 'Add',
			text : "ȷ��ѡ��",
			icon : 'add',
			action: function(row,rows,idArr ) {
				if(row){
					if(window.opener){
						window.opener.setDatas(rows);
					}
					//�رմ���
					window.close();
				}else{
					alert('��ѡ��һ������');
				}
			}
        }],
		searchitems : [{
					display : '�ʲ�����',
					name : 'assetName'
				}, {
					display : '��Ƭ���',
					name : 'assetCode'
				}],
		sortorder : 'DESC'
	});
});