$(document).ready(function() {
$("#itemTable").yxeditgrid({		
		objName : 'order[orderequ]',
		url : "?model=outsourcing_workorder_orderequ&action=listJson",
		param : {
			parentId : $("#ID").val(),
			dir : 'ASC'
		},
		type : 'view',
		colModel : [{
			display : '施工人员',
			name : 'personName',
			tclass : 'txtshort'
		}, {
			display : '施工人员ID',
			name : 'personId',
			tclass : 'txt',
			type : 'hidden'
		}, {
			display : 'suppID',
			name : 'parentId',
			tclass : 'txt',
			type : 'hidden'
		}, {
			display : '身份证号码',
			name : 'IdCard',
			tclass : 'txt',
			type :　'readonly'
		}, {
			display : '手机',
			name : 'phone',
			tclass : 'txt'
		}, {
			display : '邮箱',
			name : 'email',
			tclass : 'txt'
		}, {
			display : '项目预计开始时间',
			name : 'exceptStart',
			tclass : 'txtshort',
			type : 'date'
		}, {
			display : '项目预计结束时间',
			name : 'exceptEnd',
			tclass : 'txtshort',
			type : 'date'
		}, {
			display : '工价(元)',
			name : 'price',
			tclass : 'txtshort'
		}, {
			display : '结算方式',
			name : 'payWay',
			tclass : 'txt'
		}, {
			display : '结算说明',
			name : 'payExplain',
			tclass : 'txt'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	});

 })