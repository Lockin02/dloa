var show_page = function(page) {
	$("#assetbyproGrid").yxgrid("reload");
};
function isRelated( assetId ){
	var equNum = 1;
	 $.ajax({
		type : 'POST',
		url : '?model=asset_assetcard_assetcard&action=isRelated',
		data : {
			id : assetId
		},
	    async: false,
		success : function(data) {
			equNum = data;
			return false;
		}
	})
	return equNum;
}
$(function() {
	$("#assetbyproGrid").yxgrid({
		model : 'asset_assetcard_assetcard',
		param : { 'currentId' :  $('#userId').val() },
		title : '�ҵ��ʲ��б�',
		customCode : 'myAssetcardGrid',
		showcheckbox : false,
		isEditAction : false,
		isViewAction : false,
		isAddAction : false,
		isDelAction : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'assetTypeName',
			display : '�ʲ����',
			sortable : true
		}, {
			name : 'assetCode',
			display : '��Ƭ���',
			sortable : true
		}, {
			display : '�ʲ���������id',
			name : 'requireId',
			sortable : true,
			hide : true
		}, {
			display : '�ʲ�����������',
			name : 'requireCode',
			width : '150',
			sortable : true,
			 process : function(v,row){
				 if(v != ""){
					 return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=asset_require_requirement&action=toViewTab&requireId=" + row.requireId + '&requireCode=' + v +"\")'>" + v + "</a>";
				 }
			 }
		}, {
			name : 'machineCode',
			display : '������',
			width : '150',
			sortable : true
		}, {
			name : 'spec',
			display : '����ͺ�',
			sortable : true
		}, {
			name : 'assetName',
			display : '�ʲ�����',
			sortable : true,
			process : function(v,row){
				if(v == "�ֻ�" && (row.mobileBand != "" || row.mobileNetwork != "")){
					return v+" <img src='images/icon/msg.png' style='width:14px;height:14px;' title='�ֻ�Ƶ��: " +
					row.mobileBand+"���ֻ�����:"+row.mobileNetwork+"'/>";
				}
				return v;
			}
		}, {
			name : 'mobileBand',
			display : '�ֻ�Ƶ��',
			sortable : true,
			hide : true
		}, {
			name : 'mobileNetwork',
			display : '�ֻ�����',
			sortable : true,
			hide : true
		}, {
			name : 'unit',
			display : '������λ',
			hide : true,
			sortable : true
		}, {
			name : 'buyDate',
			display : '��������',
			sortable : true
		}, {
			name : 'userName',
			display : 'ʹ����',
			sortable : true
		}, {
			name : 'useStatusName',
			display : 'ʹ��״̬',
			sortable : true
		}, {
			name : 'changeTypeName',
			display : '�䶯��ʽ',
			sortable : true
		}, {
			name : 'useOrgName',
			display : 'ʹ�ò�������',
			sortable : true
		}, {
			name : 'orgName',
			display : '������������',
			sortable : true
//			name : 'useProName',
//			display : 'ʹ����Ŀ',
//			sortable : true
//		}, {
		}],
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				window.open('?model=asset_assetcard_assetcard&action=init&perm=view&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			name : 'edit',
			text : '�黹�ʲ�',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.useStatusCode == 'SYZT-SYZ') {
					return true;
				} else
					return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					//��֤��Ƭ�Ƿ����ύ���黹,��ֹ�ظ��ύ����
					var isReturning = false;
					$.ajax({
						type : 'POST',
						url : '?model=asset_assetcard_assetcard&action=isReturning',
						data : {
							id : row.id
						},
					    async: false,
						success : function(data) {
							if(data == 1){
								alert("���ʲ���Ƭ���ύ���黹����,�����ظ��ύ");
								isReturning = true;
							}
						}
					})
					if(isReturning){
						return false;
					}
					showThickboxWin("?model=asset_daily_return&action=toReturnAsset&assetId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&&width=900");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '��Ƭ���',
			name : 'assetCode'
		}, {
			display : '������',
			name : 'machineCodeSer'
		}, {
			display : '�ʲ�����',
			name : 'assetName'
		}]
	});
});