// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".contactGrid").yxgrid("reload");
};
$(function() {
	$(".contactGrid").yxgrid({
		model : 'engineering_personnel_personnel',
		title : "Ա����Ϣ",
			/**
			 * ��Ĭ�Ͽ��
			 */
			formWidth : 900,
			/**
			 * ��Ĭ�Ͽ��
			 */
			formHeight : 550,
			/**
			 * �Ƿ���ʾ�����鿴��ť/�˵�
			 *
			 * @type Boolean
			 */
			isBatchAction : true,
			isAddAction: false,
			/**
			 * �Ƿ���ʾ�鿴��ť/�˵�
			 *
			 * @type Boolean
			 */
			isViewAction : false,
			/**
			 * �Ƿ���ʾ�޸İ�ť/�˵�
			 *
			 * @type Boolean
			 */
			isEditAction : false,
			comboEx: [{
			text: "����",
			key: 'officeId',
			data : [{
				text : ' �������´� ',
				value : '46'
				}, {
				text : ' �ɶ����´� ',
				value : '45'
				}, {
				text : ' ��ɳ���´� ',
				value : '44'
				}, {
				text : ' �Ͼ����´� ',
				value : '43'
				}, {
				text : ' �������´� ',
				value : '42'
				}, {
				text : ' ���ݰ��´� ',
				value : '41'
				}
			]
		},{
			text: "�Ƿ�����Ŀ",
			key: 'isProj',
			data : [{
				text : '����Ŀ',
				value : '2'
				}, {
				text : '��Ŀ��',
				value : '1'
				}
			]
		}],
		//��չ��ť
		buttonsEx : [{
			name : 'Batch',
			text : '��������',
			icon : 'add',
			action : function(row,rows,grid) {
				   showThickboxWin("?model=engineering_personnel_personnel&action=batch" +
				   		"&placeValuesBefore&TB_iframe=true&modal=false" +
				   		"&height=400&width=800");
			}
		}],


		menusEx : [{
				name : 'edit',
				text : "�༭",
				icon : 'edit',
				action : function(row,rows,grid) {
							showThickboxWin("?model=engineering_personnel_personnel&action=init&id="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
				}
			}, {
				name : 'view',
				text : "�鿴",
				icon : 'view',
				action : function(row,rows,grid) {
							showOpenWin("?model=engineering_personnel_personnel&action=viewTab&id="
							+ row.id
							+ "&userCode="
							+ row.userCode
							+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}],


		// ����Ϣ
		colModel : [
				{
					display : 'id',
					name : 'id',
					hide : true
				},{
					display : 'userCode',
					name : 'userCode',
					hide : true
				}, {
					display : '����',
					name : 'userName',
					sortable : true
					// ���⴦���ֶκ���
				}, {
					display : '�Ա�',
					name : 'sex',
					sortable : true,
					width : '35',
					align : 'center',
					process : function(v,row){
						if(row.sex == 0){
							return "��";
						}else{
							return "Ů";
						}
					}
				}
				, {
					display : '�ȼ�',
					name : 'userLevel',
					sortable : true,
					align : 'center',
					width : '35'
				}, {
					display : '��ǰ��Ŀ',
					name : 'currentProName',
					sortable : true,
					process : function(v) {
							if(v==""){
								return "��";
							}else{
								return v;
						}
					},
					width : '200'
				}, {
					display : '��Ŀ��Ԥ�ƣ�����ʱ��',
					name : 'proEndDate',
					sortable : true,
					process : function(v) {
							if(v=="0000-00-00" || v==""){
								return "��";
							}else{
								return v;
						}
					},
					width : '130'
				}, {
					display : '����',
					name : 'officeName',
					sortable : true,
					width : '80'
				}, {
					display : '���ڵ�',
					name : 'locationName',
					sortable : true,
					width : '60'
				}, {
					display : '����',
					name : 'originPlace',
					sortable : true,
					width : '60'
				}, {
					display : '����״̬',
					name : 'attendStatus',
					sortable : true,
					datacode : 'KQZT',
					width : '60'
				}, {
					display : '��������',
					name : 'aptitudeNum',
					sortable : true,
					width : '60'
//				}, {
//					display : '��ְ����',
//					name : 'checkDate',
//					sortable : true
				}, {
					display : '��ͬ��',
					name : 'conYear',
					sortable : true,
					width : '60'
				}, {
					display : 'ֱ���ϼ�',
					name : 'leaderName',
					sortable : true
				}, {
					display : '�ǽ�����',
					name : 'ncityName',
					sortable : true,
					width: 60
				}],
				// ��������
				searchitems : [{
					display : '����',
					name : 'userName'
				}, {
					display : '�������´�',
					name : 'officeName'
				}, {
					display : 'ְλ',
					name : 'positionCode'
				}],
				// Ĭ������˳��
				sortorder : "ASC"

			});
});