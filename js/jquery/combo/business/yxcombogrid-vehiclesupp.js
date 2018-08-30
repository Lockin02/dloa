/**
 * 下拉车辆供应商组件（包括联系人、电话、地址）
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_vehiclesupp', {
		options: {
			hiddenId: 'id',
			nameCol: 'suppName',
			width: 600,
			isFocusoutCheck: false,
			gridOptions: {
				showcheckbox: false,
				model: 'outsourcing_outsourcessupp_vehiclesupp',
				param : {
					"suppLevelNeq" : '0'
				},
				//列信息
				colModel: [{
					display: 'id',
					name: 'id',
					sortable: true,
					hide: true
				},{
					name: 'suppName',
					display: '签约公司',
					width: 130,
					sortable: true
				},{
					name: 'province',
					display: '公司省份',
					width: 70,
					sortable: true
				},{
					name: 'city',
					display: '公司城市',
					width: 70,
					sortable: true
				},{
					name: 'linkmanName',
					display: '联系人',
					width: 80,
					sortable: true
				},{
					name: 'linkmanPhone',
					display: '联系电话',
					width: 80,
					sortable: true
				},{
					name: 'address',
					display: '地址',
					width: 150,
					sortable: true
				}],

				// 快速搜索
				searchitems: [{
					display: '签约公司',
					name: 'suppName'
				},{
					display: '公司省份',
					name: 'provinceSea'
				},{
					display: '公司城市',
					name: 'citySea'
				},{
					display: '联系人',
					name: 'linkmanName'
				}],

				// 默认搜索字段名
				sortname: "id",
				// 默认搜索顺序
				sortorder: "DESC"
			}
		}
	});
})(jQuery);