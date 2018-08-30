var percentage_id = 0;
CKEDITOR.dialog
		.add(
				'percentage',
				function(editor) {
					var _escape = function(value) {
						return value;
					};
					return {
						title : '百分比',
						resizable : CKEDITOR.DIALOG_RESIZE_BOTH,
						minWidth : 100,
						minHeight : 50,
						contents : [ {
							id : 'cb',
							name : 'cb',
							label : 'cb',
							title : 'cb',
							elements : [ {
								type : 'text',
								required : true,
								label : '数字：',
								style : 'width:100px;height:25px',
								rows : 6,
								id : 'num',
								'default' : ''
							} ]
						} ],
						onOk : function() {
							percentage_id++;
							var num = this.getValueOf('cb', 'num');
							num = num.replace(/%/g, '');
							if (!isNaN(num)&& Number(num) > 0) {
								editor.insertHtml('<span class="percentage" style="color:red;" id="percentage_'+ percentage_id+ '">'+ num + '%</span>');
								return true;
								/*
								var count=0;
								var all = editor.document.$.getElementsByTagName('span');
								for ( var i = 0; i < all.length; i++) {
									if (all[i].className == 'percentage') {
										count = (count+Number(all[i].innerText.replace(/%/g,'')));
									}
								}
								
								if ((100-count) >= num)
								{
									if (num > 0 && num <= 100) {
										editor.insertHtml('<span class="percentage" style="color:red;" id="percentage_'+ percentage_id+ '">'+ num + '%</span>');
										return true;
									} else {
										alert('百分比必须大于0且少于100之间！');
										return false;
									}
								}else{
									alert('您本次输入的百分比加上之前输入的百分比已经超出了100%，请从新输入！');
									return false;
								}*/
								
							} else {
								alert('百分比必须是大于0的数字！');
								return false;
							}
						},
						onLoad : function() {

						}
					};
				});