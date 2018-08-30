var show_page = function(page) {
	$("#stampconfigGrid").yxgrid("reload");
};
$(function() {
	$("#stampconfigGrid").yxgrid({
		model : 'system_stamp_stampconfig',
		title : '�������ñ�',
		isOpButton : false,
		param : { 'sort' : 'stampSort' , 'dir' : 'ASC'},
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'stampName',
				display : '������',
				sortable : true,
				width : 150
			}, {
				name : 'principalName',
				display : '���¸�����',
				sortable : true,
				width : 200
			}, {
				name : 'principalId',
				display : '���¸�����id',
				sortable : true,
				hide : true
			}, {
                name : 'businessBelongName',
                display : '��˾��',
                sortable : true
            }, {
                name : 'businessBelongId',
                display : '��˾ID',
                sortable : true,
                hide : true
            }, {
                name : 'typeId',
                display : 'ӡ�����ID',
                sortable : true,
                hide : true
            }, {
                name : 'typeName',
                display : 'ӡ�����',
                sortable : true
            },{
				name : 'legalPersonUsername',
				display : '��˾�����û���',
				sortable : true,
				hide : true
			},{
				name : 'legalPersonName',
				display : '��˾��������',
				sortable : true
			}, {
				name : 'stampSort',
				display : '���',
				sortable : true
			}, {
				name : 'status',
				display : '״̬',
				sortable : true,
				width : 70,
				process : function(v){
					if(v == 1){
						return '����';
					}else{
						return '�ر�';
					}
				}
			}, {
				name : 'remark',
				display : '��ע',
				sortable : true,
				width : 250
			}, {
				name : 'createId',
				display : '������Id',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '����������',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '����ʱ��',
				sortable : true,
				hide : true
			}, {
				name : 'updateId',
				display : '�޸���Id',
				sortable : true,
				hide : true
			}, {
				name : 'updateName',
				display : '�޸�������',
				sortable : true,
				hide : true
			}, {
//            date.format("yyyy��MM��dd��")
				name : 'updateTime',
				display : '�޸�ʱ��',
				sortable : true,
				width : 130,
				hide : true
			}
		],
        // ��չ�Ҽ��˵�
        menusEx : [{
            name : 'viewHistory',
            text : '��ʷ��¼',
            icon : 'view',
            action : function(row, rows, grid) {
                showThickboxWin('?model=system_stamp_stampconfig&action=toViewHistory'
                    + '&pid='+row.id
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750');
            }
        }
        ],
        toEditConfig : {
            action : 'toEdit'
        },
        toViewConfig : {
            action : 'toView'
        },
		searchitems : [{
			display : '������',
			name : 'stampNameSearch'
		}, {
			display : 'ӡ�����',
			name : 'typeNameSer'
		},{
			display : '������',
			name : 'principalNameSearch2'
		},{
			display : '��˾��������',
			name : 'legalPersonNameSearch'
		},{
			display : '��˾��',
			name : 'businessBelongNameSer'
		}]
	});
});