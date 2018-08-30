Ext.onReady(function() {
	Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
	Ext.QuickTips.init();
	var prjId = -1;
	var filetree;
	var fileCombo;
	var taskGrid = {
		xtype : 'projectinfocombogrid',
		urlAction : 'index1.php?model=rdproject_project_rdproject&action=',
		listUrl : 'pageJsonByOnekey',
		listeners : {
			'dblclick' : function(e) { // mydelAll();
				var record = this.getSelectionModel().getSelected();
				// alert(record.get('name'))
				$("#projectName").val(record.get('projectName'));// 项目名称
				$("#projectId").val(record.get('id'));// 项目Id
				$("#projectCode").val(record.get('projectCode'));// 项目编码
				$("#planName").val('');// 计划名称
				$("#planCode").val('');// 计划ID
				$("#planId").val('');// 计划编码
				// Ext.get().reload()
//				 $('#belongPlan').show();

				var projectId = record.get('id');
				if (filetree) {
					fileCombo.reset();
					filetree.loader.baseParams['projectId'] = projectId;
					if (filetree.rendered) {
						filetree.root.reload();
					}
				} else {
					filetree = new Ext.ux.tree.MyTree({
						url : '?model=rdproject_uploadfile_uploadfiletype&action=tree&projectId='
								+ projectId + "&parentId=",
						rootId : -1,
						rootVisible : false,
						listeners : {
							click : function(node) {
								uploadfile.addPostParam("typeId", node.id);
								uploadfile.addPostParam("typeName", node.text);
							}
						}
					});
				}
				if (!fileCombo) {
					fileCombo = new Ext.ux.combox.ComboBoxTree({
						emptyText : '请选择附件类型...',
						applyTo : 'uploadfiletype',
						hiddenField : 'uploadfiletypeId',
						tree : filetree
					});
				}

				var planGridCmp = Ext.getCmp('planGrid');
				if (planGridCmp) {
					planGridCmp.store.proxy = new Ext.data.HttpProxy({
						url : 'index1.php?model=rdproject_plan_rdplan&action=pageJsonByOnekey&projectId='
								+ projectId
					});
					planGridCmp.store.reload();
				} else {
					planGrid.listUrl = 'pageJsonByOnekey&projectId='
							+ projectId
				}

			}
		}
	};
	var planGrid = {
		id : 'planGrid',
		xtype : 'planinfocombogrid',
		urlAction : 'index1.php?model=rdproject_plan_rdplan&action=',
		listUrl : 'pageJsonByOnekey&projectId=' + prjId,
		listeners : {
			'dblclick' : function(e) { // mydelAll();
				var record = this.getSelectionModel().getSelected();
				$("#planName").val(record.get('planName'));// 计划名称
				$("#planId").val(record.get('planId'));// 计划ID
			}
		}
	};

	var plg = new Ext.ux.combox.MyGridComboBox({
		applyTo : 'planName',
		gridName : 'planName',// 下拉表格显示的属性
		gridValue : 'id',
		hiddenFieldId : 'planId',
		myGrid : planGrid
	});
	var pg = new Ext.ux.combox.MyGridComboBox({
		applyTo : 'projectName',
		gridName : 'projectName',// 下拉表格显示的属性
		gridValue : 'id',
		hiddenFieldId : 'projectId',
		myGrid : taskGrid
	});
	if (pg.getValue() != '') {
		var projectId = $("#projectId").val();
		filetree = new Ext.ux.tree.MyTree({
			url : '?model=rdproject_uploadfile_uploadfiletype&action=tree&projectId='
					+ projectId + "&parentId=",
			rootId : -1,
			rootVisible : false,
			listeners : {
				click : function(node) {
					uploadfile.addPostParam("typeId", node.id);
					uploadfile.addPostParam("typeName", node.text);
				}
			}
		});
		fileCombo = new Ext.ux.combox.ComboBoxTree({
			emptyText : '请选择附件类型...',
			applyTo : 'uploadfiletype',
			hiddenField : 'uploadfiletypeId',
			tree : filetree
		});
	}

//	var teamGrid = {
//		id : 'teamGrid',
//		xtype : 'teamComboGrid',
//		urlAction : 'index1.php?model=rdproject_team_rdmember&action=',
//		listUrl : 'pageJson&projectId=' + $('#projectId').val(),
//		width : 200,
//		listeners : {
//			'dblclick' : function(e) { // mydelAll();
//				var record = this.getSelectionModel().getSelected();
//				$("#chargeId").val(record.get('chargeId'));// 计划名称
//				$("#chargeName").val(record.get('chargeName'));// 计划名称
//				mailTo();
//				$('#chargeName').focus();
//			}
//		}
//	};
//	var tg = new Ext.ux.combox.MyGridComboBox({
//		listWidth : 200,
//		applyTo : 'chargeName',
//		gridName : 'memberName',// 下拉表格显示的属性
//		gridValue : 'memberId',
//		hiddenFieldId : 'chargeId',
//		myGrid : teamGrid
//	});

//	var teamsGrid = {
//		id : 'teamGrid',
//		xtype : 'teamComboGrid',
//		selectType : 'check',
//		urlAction : 'index1.php?model=rdproject_team_rdmember&action=',
//		listUrl : 'pageJson&projectId=' + $('#projectId').val(),
//		width : 500,
//		listeners : {
//			'dblclick' : function(e) { // mydelAll();
//			},
//			'click' : function(e){
//				$('#withinActName').focus();
//			}
//		}
//	};
//	var tgs = new Ext.ux.combox.MyGridComboBox({
//		listWidth : 500,
//		applyTo : 'withinActName',
//		gridName : 'memberName',// 下拉表格显示的属性
//		gridValue : 'memberId',
//		hiddenFieldId : 'withinActId',
//		myGrid : teamsGrid
//	});

});
//function arrayCon(arr1, arr2) {
//	for (var i = 0; i < arr1.length; i++) {
//		for (var j = 0; j < arr2.length; j++) {
//			if (arr1[i] == arr2[j] || arr1[i] == '') {
//				arr1.splice(i, 1); // 利用splice函数删除元素，从第i个位置，截取长度为1的元素
//				i--;
//				break;
//			}
//		}
//	}
//	// alert(arr1.length)
//	for (var i = 0; i < arr2.length; i++) {
//		arr1.push(arr2[i]);
//	}
//	return arr1;
//}
//function actUserFun() {
//	//参与人id字符串
//	var withinId = $('#withinActId').val();
//	var withoutId = $('#withoutActId').val();
//	withinIdArr = withinId.split(',');
//	withoutIdArr = withoutId.split(',');
//	var actUserIdArr = new Array();
//	actUserIdArr = arrayCon(withinIdArr,withoutIdArr);
//	var actUserIdStr = actUserIdArr.join(',');
//
//	//参与人name字符串
//	var withinName = $('#withinActName').val();
//	var withoutName = $('#withoutActName').val();
//	withinNameArr = withinName.split(',');
//	withoutNameArr = withoutName.split(',');
//	var userNameArr = new Array();
//	userNameArr = arrayCon(withinNameArr,withoutNameArr);
//	var userNameStr = userNameArr.join(',');
//
//	$('#actUserId').val(actUserIdStr);
//	$('#userName').val(userNameStr);
//	mailTo();
//}