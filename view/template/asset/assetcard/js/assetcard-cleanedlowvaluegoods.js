var show_page = function(page) {
	$("#assetcardGrid").yxgrid("reload");
};
$(function() {
	$("#assetcardGrid").yxgrid({
		model : 'asset_assetcard_assetcard',
		action : 'pageJsonOther',
		title : '�������ֵ����Ʒ�б�',
		param : {
			isDel : 1,
			useStatusCode : 'SYZT-YQL'
		},
		showcheckbox : false,
		isEditAction : false,
		isViewAction : false,
		isAddAction : false,
		isDelAction : false,
		isOpButton : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'assetCode',
			display : '��Ƭ���',
			width : '150',
			sortable : true,
			process : function(v,row){
				if(row.remark != ""){
					return v+" <img src='images/icon/msg.png' style='width:14px;height:14px;' title='��ע : "+row.remark+"'/>";
				}
				return v;
			}
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
			name : 'assetTypeId',
			display : '�ʲ����id',
			sortable : true,
			hide : true
		}, {
			name : 'assetTypeName',
			display : '�ʲ����',
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
			name : 'brand',
			display : 'Ʒ��',
			sortable : true
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
			name : 'englishName',
			display : 'Ӣ������',
			hide : true,
			sortable : true
		}, {
			name : 'assetSource',
			display : '�ʲ���Դ���',
			hide : true,
			sortable : true
		}, {
			name : 'assetSourceName',
			display : '�ʲ���Դ',
			sortable : true
		}, {
			name : 'unit',
			display : '��λ',
			hide : true,
			sortable : true
		}, {
			name : 'buyDate',
			display : '��������',
			sortable : true
		}, {
			name : 'wirteDate',
			display : '��������',
			sortable : true
		}, {
			name : 'deploy',
			display : '����',
			sortable : true
		}, {
			name : 'userId',
			display : 'ʹ����id',
			sortable : true,
			hide : true
		}, {
			name : 'userName',
			display : 'ʹ����',
			sortable : true
		}, {
			name : 'useStatusName',
			display : 'ʹ��״̬',
			sortable : true
		}, {
			name : 'changeTypeCode',
			display : '�䶯��ʽ����',
			sortable : true,
			hide : true
		}, {
			name : 'changeTypeName',
			display : '�䶯��ʽ',
			sortable : true
		}, {
			name : 'useOrgId',
			display : 'ʹ�ò���id',
			sortable : true,
			hide : true
		}, {
			name : 'useOrgName',
			display : 'ʹ�ò���',
			sortable : true
		}, {
			name : 'agencyName',
			display : '��������',
			sortable : true
		}, {
			name : 'orgId',
			display : '��������id',
			sortable : true,
			hide : true
		}, {
			name : 'orgName',
			display : '��������',
			sortable : true
		}, {
			name : 'belongManId',
			display : '������Id',
			sortable : true,
			hide : true
		}, {
			name : 'belongMan',
			display : '������',
			sortable : true
		}, {
			name : 'useProId',
			display : 'ʹ����ĿId',
			sortable : true,
			hide : true
		}, {
			name : 'useProName',
			display : 'ʹ����Ŀ',
			hide : true,
			sortable : true
		}, {
			name : 'spec',
			display : '����ͺ�',
			sortable : true,
			hide : true
		}, {
			name : 'deprName',
			display : '�۾ɷ�ʽ',
			sortable : true,
			hide : true
		}, {
			name : 'subName',
			display : '�̶��ʲ���Ŀ',
			sortable : true,
			hide : true
		}, {
			name : 'depSubName',
			display : '�ۼ��۾ɿ�Ŀ',
			sortable : true,
			hide : true
		}, {
			name : 'origina',
			display : '����ԭֵ',
			hide : true,
			sortable : true
		}, {
			name : 'buyDepr',
			display : '�����ۼ��۾�',
			hide : true,
			sortable : true
		}, {
			name : 'beginTime',
			display : '��ʼʹ������',
			sortable : true,
			hide : true
		}, {
			name : 'estimateDay',
			display : 'Ԥ��ʹ���ڼ���',
			sortable : true,
			hide : true
		}, {
			name : 'alreadyDay',
			display : '��ʹ���ڼ���',
			sortable : true,
			hide : true
		}, {
			name : 'depreciation',
			display : '�ۼ��۾�',
			hide : true,
			sortable : true
		}, {
			name : 'salvage',
			display : 'Ԥ�ƾ���ֵ',
			hide : true,
			sortable : true
		}, {
			name : 'netValue',
			display : '��ֵ',
			hide : true,
			sortable : true
		}, {
			name : 'version',
			display : '�汾��',
			hide : true,
			sortable : true
		}, {
			name : 'belongTo',
			display : '�����ʲ�',
			hide : true,
			sortable : true
		}, {
			name : 'belongToCode',
			display : '�����ʲ�����',
			width : '160',
			sortable : true
		}, {
			name : 'isBelong',
			display : '�Ƿ���',
			width : '60',
			hide : true,
			sortable : true
		}, {
		    name : 'isScrap',
			display : '����״̬',
			hide : true,
			sortable : true,
				process : function(val) {
						if (val == "0") {
							return "δ����";
						}
						if (val == "1") {
							return "�ѱ���";
						}
					}
		}, {
			name : 'remark',
			display : '��ע'
		}],
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				window.open('?model=asset_assetcard_assetcard&action=init&perm=view&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '��Ƭ���',
			name : 'assetCode'
		}, {
			display : '�ʲ�����',
			name : 'assetName'
		}, {
			display : '������',
			name : 'machineCodeSer'
		}, {
			display : 'ʹ����',
			name : 'userName'
		}, {
			display : 'ʹ�ò���',
			name : 'useOrgName'
		}, {
			display : '��������',
			name : 'agencyName'
		}, {
			display : '������',
			name : 'belongMan'
		}, {
			display : '��������',
			name : 'orgName'
		}, {
			display : '�ʲ���Դ',
			name : 'assetSourceNameSer'
		}]
	});
});