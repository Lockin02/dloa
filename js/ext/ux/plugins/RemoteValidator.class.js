$import("Ext.Ajax");
$import("Ext.util.DelayedTask");
$import("Ext.util.JSON");
$package("Ext.ux.plugins");
Ext.namespace('Ext.ux', 'Ext.ux.plugins');

/**
 * Remote Validator Makes remote (server) field validation easier Ajax检验表单控件输入
 * To be used by form fields like TextField, NubmerField, TextArea, ...
 */
Ext.ux.plugins.RemoteValidator = {
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
				this.rvOptions.params = this.rvOptions.params || {};
				// this.rvOptions.params.field = this.name;
//				this.rvOptions.params[this.name] = this.getValue();
//				if (this.rvOptions.fieldId && this.rvOptions.fieldName)
//					this.rvOptions.params[this.rvOptions.fieldName] = Ext
//							.getCmp(this.rvOptions.fieldId).getValue();
				var checkFieldName = [];
				var checkFieldValue = [];
				for(var i = 0;i<this.rvOptions.checkName.leng;i++){
					checkFieldName.push(this.rvOptions.checkName[i]);
					checkFieldValue.push(this.rvOptions.checkValue[i]);
				}
				//checkFieldName = this.rvOptions.checkName;
				//checkFieldValue = this.rvOptions.checkValue;
				checkFieldName.push(this.rvOptions.fieldName);
				checkFieldValue.push(this.getValue());
				this.rvOptions.params['checkFieldName'] = checkFieldName;
				this.rvOptions.params['checkFieldValue'] = checkFieldValue;
				
				this.rvOptions.params['checkObjName'] = this.rvOptions.objName;
				this.rvOptions.params['checkObjText'] = this.fieldLabel.replace("<font color='red'>[*]</font>", "");
				if(typeof(Ext.getCmp(this.rvOptions.fieldId)) !='')
					this.rvOptions.params['checkId'] = Ext.getCmp(this.rvOptions.fieldId).getValue();
				
				Ext.Ajax.request(this.rvOptions);
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
					remoteValidationDelay : 500,
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
