var show_page = function(page) {
	$("#basicinfoGrid").yxgrid("reload");
};
$(function() {
	$("#basicinfoGrid").yxgrid({
		model : 'outsourcing_supplier_basicinfo',
		title : '外包黑名单',
		isAddAction:false,
		isEditAction:false,
		isDelAction:false,
		showcheckbox:false,
		param:{'suppGrade':'4'},
		bodyAlign:'center',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'suppCode',
			display : '供应商编号',
			width:70,
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showModalWin(\"?model=outsourcing_supplier_basicinfo&action=toTabView&id=" + row.id +"\",1)'>" + v + "</a>";
			}
		}, {
			name : 'suppName',
			display : '供应商名称',
			width:150,
			sortable : true
		},  {
			name : 'suppTypeName',
			display : '供应商类型',
			width:60,
			sortable : true
		},  {
			name : 'blackListReason',
			display : '列入黑名单原因',
			width:470,
			align:'left',
			sortable : true
		}],
		toViewConfig : {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_supplier_basicinfo&action=toTabView&id=" + get[p.keyField],1);
				}
			}
		},
		searchitems : [{
						display : "供应商编号",
						name : 'suppCode'
					},{
						display : "供应商名称",
						name : 'suppName'
					},{
						display : "区域",
						name : 'officeName'
					},{
						display : "省份",
						name : 'province'
					},{
						display : "供应商类型",
						name : 'suppTypeName'
					},{
						display : "成立时间",
						name : 'registeredDate'
					},{
						display : "法人代表",
						name : 'legalRepre'
					},{
						display : "主营业务",
						name : 'mainBusiness'
					},{
						display : "擅长网络类型",
						name : 'adeptNetType'
					},{
						display : "擅长厂家设备",
						name : 'adeptDevice'
					}],

				sortname : 'suppGrade',
				sortorder : 'ASC'
	});
});