var show_page = function(page) {
	$("#stampconfigGrid").yxgrid("reload");
};
$(function() {
	$("#stampconfigGrid").yxgrid({
		model : 'system_stamp_stampconfig',
		title : '盖章配置表',
		isOpButton : false,
		param : { 'sort' : 'stampSort' , 'dir' : 'ASC'},
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'stampName',
				display : '章名称',
				sortable : true,
				width : 150
			}, {
				name : 'principalName',
				display : '盖章负责人',
				sortable : true,
				width : 200
			}, {
				name : 'principalId',
				display : '盖章负责人id',
				sortable : true,
				hide : true
			}, {
                name : 'businessBelongName',
                display : '公司名',
                sortable : true
            }, {
                name : 'businessBelongId',
                display : '公司ID',
                sortable : true,
                hide : true
            }, {
                name : 'typeId',
                display : '印章类别ID',
                sortable : true,
                hide : true
            }, {
                name : 'typeName',
                display : '印章类别',
                sortable : true
            },{
				name : 'legalPersonUsername',
				display : '公司法人用户名',
				sortable : true,
				hide : true
			},{
				name : 'legalPersonName',
				display : '公司法人姓名',
				sortable : true
			}, {
				name : 'stampSort',
				display : '序号',
				sortable : true
			}, {
				name : 'status',
				display : '状态',
				sortable : true,
				width : 70,
				process : function(v){
					if(v == 1){
						return '开启';
					}else{
						return '关闭';
					}
				}
			}, {
				name : 'remark',
				display : '备注',
				sortable : true,
				width : 250
			}, {
				name : 'createId',
				display : '创建人Id',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '创建人名称',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '创建时间',
				sortable : true,
				hide : true
			}, {
				name : 'updateId',
				display : '修改人Id',
				sortable : true,
				hide : true
			}, {
				name : 'updateName',
				display : '修改人名称',
				sortable : true,
				hide : true
			}, {
//            date.format("yyyy年MM月dd日")
				name : 'updateTime',
				display : '修改时间',
				sortable : true,
				width : 130,
				hide : true
			}
		],
        // 扩展右键菜单
        menusEx : [{
            name : 'viewHistory',
            text : '历史记录',
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
			display : '章名称',
			name : 'stampNameSearch'
		}, {
			display : '印章类别',
			name : 'typeNameSer'
		},{
			display : '负责人',
			name : 'principalNameSearch2'
		},{
			display : '公司法人姓名',
			name : 'legalPersonNameSearch'
		},{
			display : '公司名',
			name : 'businessBelongNameSer'
		}]
	});
});