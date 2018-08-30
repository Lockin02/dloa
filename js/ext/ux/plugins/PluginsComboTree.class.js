$import("Ext.util.DelayedTask");
$import("Ext.tree.TreeLoader");
$import("Ext.util.JSON");
$package("Ext.ux.plugins");
Ext.namespace('Ext.ux', 'Ext.ux.plugins');
/**
 * Remote Validator Makes remote (server) field validation easier Ajax检验表单控件输入
 * To be used by form fields like TextField, NubmerField, TextArea, ...
 */
Ext.ux.plugins.PluginsComboTree = {
	init : function(field) {
		// save original functions
		var isValid = field.isValid;
		var validate = field.validate;

		// apply remote validation to field
		Ext.apply(field, {
			remoteValid : true

			// private
			,
			isValid : function(preventMark) {
				return isValid.call(this, preventMark) && this.remoteValid;
			}

			// private
			,
			validate : function() {
				var clientValid = validate.call(this);
				if (!this.disabled && !clientValid) {
					return false;
				}
				if (this.disabled || (clientValid && this.remoteValid)) {
					this.clearInvalid();
					return true;
				}
				if (!this.remoteValid) {
					this.markInvalid(this.reason);
					return false;
				}
				return false;
			}

			// private - remote validation request
			,
			validateRemote : function() {
				// this.tree.destroy();
				if (!this.isExpanded()) {
					this.expand();
				}
				var mytree = this.tree;
				if (!Ext.isEmpty(this.getValue())) {
					mytree.loader = new Ext.tree.TreeLoader({
								url : this.keyUrl + this.getValue()
							});
				} else {
					mytree.loader = this.expandTreeLoader;
				}
				// mytree.loader.url = this.keyUrl;
				mytree.root.loader = mytree.loader;
				mytree.root.reload();
			}

			// private - remote validation request success handler
			,
			rvSuccess : function(response, options) {
				var o;
				try {
					o = Ext.decode(response.responseText);
				} catch (e) {
					throw this.cannotDecodeText;
				}
				if ('object' !== typeof o) {
					throw this.notObjectText;
				}
				// if (true !== o.success) {
				// throw this.serverErrorText + ': ' + o.error;
				// }
				var names = this.rvOptions.paramNames;
				this.remoteValid = true === o[names.valid];
				this.reason = o[names.reason];
				this.validate();
			}

			// private - remote validation request failure handler
			,
			rvFailure : function(response, options) {
				throw this.requestFailText
			}

			// private - runs from keyup event handler
			,
			filterRemoteValidation : function(e) {
				if (!e.isNavKeyPress()) {
					this.remoteValidationTask.delay(this.remoteValidationDelay);
				}
			}
		});

		// remote validation defaults
		Ext.applyIf(field, {
					remoteValidationDelay : 500, // 效验时间延迟
					reason : 'Server has not yet validated the value',
					cannotDecodeText : 'Cannot decode json object',
					notObjectText : 'Server response is not an object',
					serverErrorText : 'Server error',
					requestFailText : 'Server request failed'
				});

		// install event handlers on field render
		field.on({
					render : {
						single : true,
						scope : field,
						fn : function() {
							this.remoteValidationTask = new Ext.util.DelayedTask(
									this.validateRemote, this);
							this.el.on('keyup', this.filterRemoteValidation,
									this);
						}
					}
				});

		// setup remote validation request options
		field.rvOptions = field.rvOptions || {};
		Ext.applyIf(field.rvOptions, {
					method : 'post',
					scope : field,
					success : field.rvSuccess,
					failure : field.rvFailure,
					paramNames : {
						valid : 'success',
						reason : 'message'
					}
				});
	}
};

// end of file
