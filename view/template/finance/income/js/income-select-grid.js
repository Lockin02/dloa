/** �տ��б�* */

var show_page = function(page) {
	$("#incomeGrid").yxgrid("reload");
}

$(function() {
	$("#incomeGrid").yxgrid({
		model : 'finance_income_income',
		action : 'selectPageJson',
		param : {"formType" : "YFLX-DKD" ,'objIdArr' : $("#objId").val() },
		title : 'ѡ�񵽿�',
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		customCode : "incomeGrid",
		isOpButton : false,
		// ��������
		comboEx : [{
			text : '״̬',
			key : 'status',
			datacode : 'DKZT',
			value : 'DKZT-YFP'
		}],
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '���˵���',
			name : 'inFormNum',
			sortable : true,
			width : 110,
			hide : true
		}, {
			display : 'ϵͳ���ݺ�',
			name : 'incomeNo',
			sortable : true,
			width : 120,
			process : function(v,row){
				if(row.id == 'noId') return v;
				return "<a href='#' onclick='showOpenWin(\"?model=finance_income_income&action=toAllot&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
			}
		}, {
			display : '���λid',
			name : 'incomeUnitId',
			sortable : true,
			hide : true
		}, {
			display : '���λ',
			name : 'incomeUnitName',
			sortable : true,
			width : 130
		}, {
			display : '���λ����',
			name : 'incomeUnitType',
			sortable : true,
			datacode : 'KHLX',
			hide : true
		}, {
			display : '��ͬ��λid',
			name : 'contractUnitId',
			sortable : true,
			hide : true
		}, {
			display : '��ͬ��λ',
			name : 'contractUnitName',
			sortable : true,
			width : 130,
			hide : true
		}, {
			display : '��������',
			name : 'incomeDate',
			sortable : true,
			width : 80
		}, {
			display : '������',
			name : 'incomeMoney',
			sortable : true,
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 90
		}, {
			display : '¼����',
			name : 'createName',
			sortable : true,
			width : 80
		}, {
			display : '״̬',
			name : 'status',
			datacode : 'DKZT',
			sortable : true,
			width : 80
		}, {
			display : '¼��ʱ��',
			name : 'createTime',
			sortable : true,
			width : 120,
			hide : true
		}, {
			display : '��ע',
			name : 'remark',
			width : 120
		}],
        buttonsEx : [{
			name : 'Add',
			text : "ȷ��ѡ��",
			icon : 'add',
			action: function(row,rows,idArr ) {
				if(row){
					if(window.opener){
						window.opener.setIncomeObj(row);
					}
					//�رմ���
					window.close();
				}else{
					alert('��ѡ��һ������');
				}
			}
        }],
		searchitems : [{
			display : '�ͻ�����',
			name : 'incomeUnitName'
		},{
			display : '�ͻ�ʡ��',
			name : 'province'
		},{
			display : 'ϵͳ���ݺ�',
			name : 'incomeNo'
		},{
			display : '������',
			name : 'incomeMoney'
		},{
			display : '���˵���',
			name : 'inFormNum'
		},{
			display : '��������',
			name : 'incomeDateSearch'
		}],
		sortname : 'updateTime'
	});
});