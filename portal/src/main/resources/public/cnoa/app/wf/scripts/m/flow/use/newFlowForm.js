	/*                             
	*  方法:Array.remove(obj)      
	*  功能:删除数组元素.         
	*  参数:要删除的对象.     
	*  返回:在原数组上修改数组    
	*/  
	Array.prototype.remove=function(obj){  
		for(var i =0;i <this.length;i++){  
			var temp = this[i];  
			if(!isNaN(obj)){  
				temp=i;  
			}  
			if(temp == obj){  
				for(var j = i;j <this.length;j++){  
					this[j]=this[j+1];  
				}  
				this.length = this.length-1;  
			}     
		}  
	}
	// 替换所有相同的字符串
	String.prototype.replaceAll = function(reallyDo, replaceWith, ignoreCase) {  
	   if (!RegExp.prototype.isPrototypeOf(reallyDo)) {  
	       return this.replace(new RegExp(reallyDo, (ignoreCase ? "gi": "g")), replaceWith);  
	   } else {  
	       return this.replace(reallyDo, replaceWith);  
	   }  
	}

	//判断当前客户端Android和ios
	if(/android/ig.test(navigator.userAgent)){ //安卓
	var userAgent = 'android';
	}else{ //ios或者其他
	var userAgent = 'ios';
	}

	//全局变量`函数
	var dsPageNow = 1, jxcPageNow = 1; //数据源分页, 进销存分页
	var nextStepScroll, userDataScroll, userScroll, stationDataScroll, detailtableScroll,
	signatureScroll, otherScroll, sendeeArr = [], 
	  stationArr = [], jobArr = [], deptArr = []; //全局对象和数组 
	var ue; //百度编辑器
	var isAllowBlank = '';
	//myScroll.refresh(); //数据加载完成后调用界面更新方法

	var myFns = {
	/*
	 *	方法: myFns.loaded()
	 *	功能: 初始化iScroll控件
	 */
	 loaded:function(){
	 	myScroll = new IScroll('#wrapper', {
			scrollbars: false, //隐藏滚动条
			preventDefault: false, //阻止默认事件
			preventDefaultException: { tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|A)$/ } //这个后面加|A,因为iscroll阻止了A的默认事件
		});
	 	document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	 },
	/**
	 *	方法: myFns.nextStepLoaded()
	 *	功能: 初始化下一步办理人iScroll控件
	 *	参数: <string> HTML属性ID 
	 */
	 nextStepLoaded:function(){
	 	nextStepScroll = new IScroll('#nextStep-wrapper', {
	 		scrollbars: false,
	 		freeScroll: true,
	 		scrollY: true,
			//click: true,
			preventDefault: false,
			preventDefaultException: { tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|A)$/ }
		});
	 	document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	 },
	/**
	 *	方法: myFns.userDataLoaded()
	 *	功能: 初初始化人员选择器用户部门iScroll控件
	 */
	 userDataLoaded: function(){
	 	userDataScroll = new IScroll('#userDataWrapper', {
	 		scrollbars: false,
	 		click: true
	 	});
	 	document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	 },
	/**
	 *	方法: myFns.userLoaded()
	 *	功能: 初始化人员选择器删除区域iScroll控件
	 */
	 userLoaded: function(){
	 	userScroll = new IScroll('#userWrapper', { 
	 		scrollbars: false,
	 		freeScroll: true,
	 		scrollX: true,
	 		scrollY: true,
	 		click: true
	 	});
	 	document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	 },
	/**
	 *	方法: myFns.stationLoaded()
	 *	功能: 初始化岗位选择器iScroll控件
	 */
	 stationLoaded: function(){
	 	stationDataScroll = new IScroll('#stationDataWrapper', {
	 		scrollbars: false,
	 		click: true
	 	});
	 	document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	 },
	/**
	 *	方法: myFns.detailtableScroll()
	 *	功能: 初始化明细表iScroll控件
	 */
	 detailtableLoaded: function(){
	 	detailtableScroll = new IScroll('#detailtable-wrapper', {
	 		scrollbars: false,
	 		scrollY: true,
	 		scrollX: true,
			//click: true,
			preventDefault: false, //阻止默认事件
			preventDefaultException: { tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|A)$/ } //这个后面加|A,因为iscroll阻止了A的默认事件
		});
	 	document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	 },
	/**
	 *	方法: myFns.signatureLoaded()
	 *	功能: 初始化签章iScroll控件
	 */
	 signatureLoaded: function(){
	 	signatureScroll = new IScroll('#signature-wrapper', {
	 		scrollbars: false,
	 		scrollY: true,
			//click: true,
			preventDefault: false, //阻止默认事件
			preventDefaultException: { tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|A)$/ } //这个后面加|A,因为iscroll阻止了A的默认事件
		});
	 	document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	 },
	/**
	 *	方法: myFns.otherLoaded()
	 *	功能: 初始化签章iScroll控件
	 */
	 otherLoaded: function(){
	 	otherScroll = new IScroll('#other-wrapper', {
	 		scrollbars: false,
	 		scrollY: true,
			//click: true,
			preventDefault: false, //阻止默认事件
			preventDefaultException: { tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|A)$/ } //这个后面加|A,因为iscroll阻止了A的默认事件
		});
	 	document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	 },
	/**
	 *	方法: myFns.getUriString(key)
	 *	功能: 获取url参数值
	 *	参数: <string> key URL键
	 *	返回: <string> URL 对应的键值
	 */
	 getUriString: function(key){
	 	var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
	 	var r = window.location.search.substr(1).match(reg);
	 	if(r!=null)return  unescape(r[2]); return null;
	 },
	/**
	 *	方法: myFns.formatForm(obj)
	 *	功能: 格式化表单
	 *	参数: <Object> obj 当前遍历的表单
	 *	返回: <string> 格式化后的内容
	 */
	 formatForm: function(obj){
	 	if(obj.tag == '' || obj.tag == undefined || obj.tag == null){
	 		return null;
	 	}
	 	var str = '';
		if(obj.tag == 'checkbox'){ //checkbox控件
			var items = obj.items;
			str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
			$.each(items, function(i, arr){
				if(!arr['checked']){ //不扣选
					str += '<input type="checkbox" tname="'+obj.name+'" name="'+obj.name+'_'+arr['value']+'" value="'+arr['value']+'" class="chklist '+(obj.must?'must':'')+'" '+(obj.readOnly?'disabled':'')+'>';
					str += '<label class="chkbox"><span class="check-image"></span><span class="radiobox-content">'+arr['value']+'</span></label>';
				}else{ //扣选
					str += '<input type="checkbox" tname="'+obj.name+'" name="'+obj.name+'_'+arr['value']+'" value="'+arr['value']+'" class="chklist '+(obj.must?'must':'')+'" '+(obj.readOnly?'disabled':'')+' checked >';
					str += '<label class="chkbox"><span class="check-image"></span><span class="radiobox-content">'+arr['value']+'</span></label>';
				}
			})
			str += '</div>';
		}else if(obj.tag == 'macro'){ //宏控件
			if (obj.dataType == 'moneyconvert') {
				str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
				str += '<input type="text" class="form-control input-m '+(obj.must?'must':'')+'" id="'+obj.name+'" name="'+obj.name+'" value="'+(obj.displayValue?obj.displayValue:obj.value)+'" moneyconvert="true" from="'+obj.from+'" readonly>';
				str += '</div>';
			}else if (obj.dataType == 'attLeave') { //请假时间控件
				if (!obj.readOnly) {
					var style = 'style="background-color: #fff;";';
				};
				str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
				str += '<input type="text" class="form-control input-m '+(obj.must?'must':'')+'" id="'+obj.name+'" name="'+obj.name+'" value="'+(obj.displayValue?obj.displayValue:obj.value)+'" data-readonly="'+(obj.readOnly?'readonly':'')+'" data-type="'+obj.dataType+'" '+style+' readonly>';
				str += '</div>';
			}else if(obj.dataType == 'attTime'){
				if (!obj.readOnly) {
					var style = 'style="background-color: #fff;";';
				};
				str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
				str += '<input type="text" class="form-control input-m '+(obj.must?'must':'')+'" id="'+obj.name+'" name="'+obj.name+'" value="'+(obj.displayValue?obj.displayValue:obj.value)+'" data-readonly="'+(obj.readOnly?'readonly':'')+'" data-type="'+obj.dataType+'" '+style+' readonly>';
				str += '</div>';
			}else if (obj.dataType == 'attEvectionAllDays') { //外出时间控件
				if (!obj.readOnly) {
					var style = 'style="background-color: #fff;";';
				};
				str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
				str += '<input type="text" class="form-control input-m '+(obj.must?'must':'')+'" id="'+obj.name+'" name="'+obj.name+'" value="'+(obj.displayValue?obj.displayValue:obj.value)+'" data-readonly="'+(obj.readOnly?'readonly':'')+'" data-type="'+obj.dataType+'" '+style+' readonly>';
				str += '</div>';
			} else if(obj.dataType == 'loginname'){
				if (!obj.readOnly) {
					var style = 'style="background-color: #fff;";';
				};
				str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
				str += '<input type="text" class="form-control input-m '+(obj.must?'must':'')+'" value="'+obj.displayValue+'" '+(obj.readOnly?'readonly':'')+' data-type="'+obj.dataType+'">';
				str += '<input type="hidden" class="form-control input-m '+(obj.must?'must':'')+'" id="'+obj.name+'" name="'+obj.name+'" value="'+obj.value+'" '+(obj.readOnly?'readonly':'')+' data-type="'+obj.dataType+'">';
				str += '</div>';
			} else {
				str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
				str += '<input type="text" class="form-control input-m '+(obj.must?'must':'')+'" id="'+obj.name+'" name="'+obj.name+'" value="'+(obj.displayValue?obj.displayValue:obj.value)+'" '+(obj.readOnly?'readonly':'')+' data-type="'+obj.dataType+'">';
				str += '</div>';
			}
		}else if(obj.tag == 'textarea'){ //textarea
			str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
			if (obj.readOnly) {
				str += '<textarea class="form-control '+(obj.must?'must':'')+'" id="'+obj.name+'" name="'+obj.name+'" readonly="readonly">'+(obj.displayValue?obj.displayValue:obj.value)+'</textarea>';
			} else {
				str += '<textarea class="form-control '+(obj.must?'must':'')+'" id="'+obj.name+'" name="'+obj.name+'">'+(obj.displayValue?obj.displayValue:obj.value)+'</textarea>';
			}
			str += '</div>';
		}else if(obj.tag == 'choice'){ //选择器
			if(obj.dataType == 'date'){ //日期选择器
				if (obj.readOnly) { //只读
					str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
					str += '<input dtype="date" type="text" id="'+obj.name+'" name="'+obj.name+'" class="form-control Wdate WdatePicker input-m '+(obj.must?'must':'')+'" format="'+obj.format+'" dtfmt="'+obj.dtfmt+'" data-readonly="true" name="'+obj.name+'" value="'+(obj.displayValue ? obj.displayValue : obj.value)+'" readonly>';
					str += '</div>';
				} else { //可写
					var style = 'style="background-color: #fff;"';
					str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
					str += '<input type="text" dtype="date" id="'+obj.name+'" name="'+obj.name+'" class="form-control Wdate WdatePicker input-m '+(obj.must?'must':'')+'" '+style+' format="'+obj.format+'" dtfmt="'+obj.dtfmt+'" data-readonly="false" name="'+obj.name+'" value="'+(obj.displayValue ? obj.displayValue : obj.value)+'" onchange="return true;" readonly>';
					str += '</div>';
				}
			}else if(obj.dataType == 'time'){ //时间选择器
				if (obj.readOnly) { //只读
					str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
					str += '<input type="text" id="'+obj.name+'" name="'+obj.name+'" class="form-control Wdate WtimePicker input-m '+(obj.must?'must':'')+'" format="'+obj.format+'" dtfmt="'+obj.dtfmt+'" data-readonly="true" name="'+obj.name+'" value="'+(obj.displayValue ? obj.displayValue : obj.value)+'" readonly>';
					str += '</div>';
				} else {
					var style = 'style="background-color: #fff;"';
					str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
					str += '<input type="text" id="'+obj.name+'" name="'+obj.name+'" class="form-control Wdate WtimePicker input-m '+(obj.must?'must':'')+'" '+style+' format="'+obj.format+'" dtfmt="'+obj.dtfmt+'" data-readonly="false" name="'+obj.name+'" value="'+(obj.displayValue ? obj.displayValue : obj.value)+'" readonly>';
					str += '</div>';
				}
			}else if(obj.dataType == 'user'){ //人员选择器
				var style = 'style="';
				if (obj.readOnly) { //只读
					style  += 'background-color: #eee;';
				};
				if (obj.must) { //必填
					style += 'border-color: #a94442;';
				};
				style += '"';
				//此步骤支持选人 判断多选还是单选(obj.multi?'true':'false')
				str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
				str += '<div class="form-control choice sendee '+(obj.must?'must':'')+'" id="'+obj.name+'" data-name="'+obj.name+'" data-readonly="'+obj.readOnly+'" data-datatype="user_sel" data-checktype="'+(obj.multi?'true':'false')+'" data-toggle="modal" '+(obj.readOnly?'':'data-target="#userDataModal"')+' '+style+'><span class="name" data-uid="'+obj.value+'">'+obj.displayValue+'</span></div>';
				str += '<input type="hidden" name="'+obj.name+'" value="'+obj.value+'">';
				str += '</div>';
			}else if(obj.dataType == 'station'){ //岗位选择器
				var style = 'style="';
				if (obj.readOnly) { //只读
					style  += 'background-color: #eee;';
				};
				if (obj.must) { //必填
					style += 'border-color: #a94442;';
				};
				style += '"';
				str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
				str += '<div class="form-control choice stationChoice '+(obj.must?'must':'')+'" id="'+obj.name+'" data-name="'+obj.name+'" data-readonly="'+obj.readOnly+'" data-datatype="station_sel" data-checktype="'+(obj.multi?'true':'false')+'" data-toggle="modal" '+(obj.readOnly?'':'data-target="#stationDataModal"')+' '+style+'><span class="name" data-sid="'+obj.value+'">'+obj.displayValue+'</span></div>';
				str += '<input type="hidden" name="'+obj.name+'" value="'+obj.value+'">';
				str += '</div>';
			}else if(obj.dataType == 'job'){ //职位选择器
				var style = 'style="';
				if (obj.readOnly) { //只读
					style  += 'background-color: #eee;';
				};
				if (obj.must) { //必填
					style += 'border-color: #a94442;';
				};
				style += '"';
				str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
				str += '<div class="form-control choice jobChoice '+(obj.must?'must':'')+'" id="'+obj.name+'" data-name="'+obj.name+'" data-readonly="'+obj.readOnly+'" data-datatype="job_sel" data-checktype="'+(obj.multi?'true':'false')+'" data-toggle="modal" '+(obj.readOnly?'':'data-target="#stationDataModal"')+' '+style+'><span class="name" data-jid="'+obj.value+'">'+obj.displayValue+'</span></div>';
				str += '<input type="hidden" name="'+obj.name+'" value="'+obj.value+'">';
				str += '</div>';
			}else if(obj.dataType == 'dept'){ //部门选择器
				var style = 'style="';
				if (obj.readOnly) { //只读
					style  += 'background-color: #eee;';
				};
				if (obj.must) { //必填
					style += 'border-color: #a94442;';
				};
				style += '"';
				str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
				str += '<div class="form-control choice deptChoice '+(obj.must?'must':'')+'" id="'+obj.name+'" data-name="'+obj.name+'" data-readonly="'+obj.readOnly+'" data-datatype="dept_sel" data-checktype="'+(obj.multi?'true':'false')+'" data-toggle="modal" '+(obj.readOnly?'':'data-target="#stationDataModal"')+' '+style+'><span class="name" data-did="'+obj.value+'">'+obj.displayValue+'</span></div>';
				str += '<input type="hidden" name="'+obj.name+'" value="'+obj.value+'">';
				str += '</div>';
			}else{ //客户外出记录和图片上传`暂无此功能
				if(obj.name == 'wf_field_10059'){ //上传图片
					str += '<div class="form-group choiceControl'+(obj.must?'has-error':'')+'">';
					str += '<img src="'+(obj.displayValue?obj.displayValue:obj.value)+'">';
					str += '<input type="hidden" id="'+obj.name+'" name="wf_fieldpic_10059" value="'+obj.value+'">';
					str += '</div>';
				}else{ //客户外出记录
					str += '<div class="form-group choiceControl'+(obj.must?'has-error':'')+'">';
					str += obj.displayValue?obj.displayValue:obj.value;
					str += '<input type="hidden" id="'+obj.name+'" name="'+obj.name+'" value="'+obj.value+'">';
					str += '</div>';
				}
			}
		}else if(obj.tag == 'select'){ //审批
			str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
			str += '<select class="form-control input-m '+(obj.must?'must':'')+'" id="'+obj.name+'" name="'+obj.name+'" '+(obj.readOnly?'disabled':'')+'>'+myFns.set_select(obj.items)+'</option></select>';
			str += '</div>';
		}else if(obj.tag == 'textfield'){
			if(obj.dataType == 'str'){ //付款金额小写
				str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
				str += '<input type="text" class="form-control input-m '+(obj.must?'must':'')+'" id="'+obj.name+'" name="'+obj.name+'" value="'+(obj.displayValue?obj.displayValue:obj.value)+'" '+(obj.readOnly?'disabled':'')+'>';
				str += '</div>';
			}else if(obj.dataType == 'float'){ //1、浮点型计算字段
				//手机端暂时未做小数点限制
				str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
				str += '<input type="text" class="form-control input-m float '+(obj.must?'must':'')+'" id="'+obj.name+'" name="'+obj.name+'" data-precision="'+obj.decimalPrecision+'" value="'+(obj.displayValue?obj.displayValue:obj.value)+'" '+(obj.readOnly?'disabled':'')+'>';
				str += '</div>';
			}else if(obj.dataType == 'int'){
				str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
				str += '<input type="text" class="form-control input-m int '+(obj.must?'must':'')+'" id="'+obj.name+'" name="'+obj.name+'" data-precision="'+obj.decimalPrecision+'" value="'+(obj.displayValue?obj.displayValue:obj.value)+'" '+(obj.readOnly?'disabled':'')+'>';
				str += '</div>';
			}else{
				str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
				if (obj.readOnly) {
					str += '<div style="background-color: #eee;padding: 9px 12px;border-radius:4px;border: 1px solid #ccc;min-height:40px;">'+(obj.displayValue?obj.displayValue:obj.value)+'</div>';
					str += '<input type="hidden" class="form-control input-m '+(obj.must?'must':'')+'" id="'+obj.name+'" name="'+obj.name+'" value="'+(obj.displayValue?obj.displayValue:obj.value)+'">';
				} else {
					str += '<input type="text" class="form-control input-m '+(obj.must?'must':'')+'" id="'+obj.name+'" name="'+obj.name+'" value="'+(obj.displayValue?obj.displayValue:obj.value)+'">';
				}
				str += '</div>';
			}
		}else if(obj.tag == 'radio'){ //单选框
			var items = obj.items;
			str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
			$.each(items, function(i, arr){
				if(!arr['checked']){ //不扣选
					str += '<span class="radio-box"><lable class="radio-btn"><i></i><input type="radio" class="rdolist '+(obj.must?'must':'')+'" name="'+obj.name+'" value="'+arr['value']+'" '+(obj.readOnly?'disabled':'')+'></lable>';
					str += '<span class="name">'+arr['value']+'</span></span>';
				}else{ //扣选
					str += '<span class="radio-box"><lable class="radio-btn"><i></i><input type="radio" class="rdolist '+(obj.must?'must':'')+'" name="'+obj.name+'" value="'+arr['value']+'" '+(obj.readOnly?'disabled':'')+' checked ></lable>';
					str += '<span class="name">'+arr['value']+'</span></span>';
				}
			})
			str += '</div>';
		}else if(obj.tag == 'detailtable'){ //明细表
			str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
			str += '<botton type="button" class="btn btn-default btn-sm detailtable '+(obj.must?'must':'')+'" name="'+obj.name+'" aria-hidden="true" data-toggle="modal" data-target="#detailtable" data-tableId="'+obj.tableId+'" style="width:100%;border-radius:20px;">查看明细表</botton>';
			str += '<input type="hidden" name="detailid_'+obj.tableId+'" value="">';
			str += '</div>';
		}else if(obj.tag == 'calculate'){ //自动计算字段
			str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
			str += '<input type="text" class="form-control calculate input-m '+(obj.must?'must':'')+'" id="'+obj.name+'" name="'+obj.name+'" value="'+(obj.displayValue?obj.displayValue:obj.value)+'" category="ordinary" baoliu="'+obj.baoliu+'" roundtype="'+obj.roundtype+'" gongshi="'+obj.gongshi+'" readonly>';
			str += '</div>';
		}else if (obj.tag == 'datasource') { // 数据源控件
			str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
			str += '<botton type="button" class="btn btn-default btn-sm datasource '+(obj.must?'must':'')+'" id="'+obj.name+'" maps="'+obj.maps+'" datasource="'+obj.datasource+'" aria-hidden="true" data-toggle="modal" data-target="#datasource" style="width:100%;border-radius:20px;">'+obj.label+'</botton>';
			str += '<input type="hidden" name="'+obj.name+'" value="">';
			str += '</div>';
		}else if (obj.tag == 'signature'){ //图形和手写签章
			var style = 'style="width:100%;border-radius:20px;';
			if (obj.must) { //必填样式
				style += 'border-color: #a94442;';
			}
			style += '"';
			if (obj.readOnly || obj.value != '') { //只读
				if (obj.value != '') {
					var valueObj = JSON.parse(obj.value);
					str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
					str += '<img src="'+valueObj.url+'" alt="'+obj.label+'" class="img-rounded">';
					str += '</div>';
				};
			} else { 
				str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
				str += '<botton type="button" class="btn btn-default btn-sm signature '+(obj.must?'must':'')+'" id="'+obj.name+'" otype="'+obj.tag+'" signaturetype="'+obj.signaturetype+'" width="'+obj.width+'" height="'+obj.height+'" data-toggle="modal" data-target=".signature-modal-sm" '+style+'>'+obj.label+'</botton>';
				str += '<input type="hidden" name="'+obj.name+'" signaturetype="'+obj.signaturetype+'" value="">';
				str += '</div>';
			};
		} else if(obj.tag == 'attach'){ //流程附件
			var style = 'style="width:100%;border-radius:20px;';
			if (obj.must) { //必填样式
				style += 'border-color: #a94442;';
			}
			style += '"';
			var opt = obj.wfAttachConfig; //操作按钮
			var attachs = obj.attach;
			if (attachs.length > 0) {
				var attachGroup = $('#attachGroup');
				if(!attachGroup.length){ //判断文件信息区域是否存在
					var attachGroupStr = '<div id="attachGroup"><div class="row formInfoTitle"><span>附件信息</span></div></div>';
					$('#myTabContent').append(attachGroupStr);
				}
			};
			str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
			if (obj.readOnly) {
				if (attachs.length > 0) { //附件数量
					$.each(attachs, function(){
						str += '<div class="panel-heading panel-attach-box" data-attachid="'+this.attachid+'" data-name="'+this.filename+'" data-ext="'+this.ext+'" style="padding: 4px 0px;">';
						str += '<span class="fileName">'+this.filename+'</span>';
						str += '<div class="panel-attach">';
						if (myFns.is_images(this.ext) && opt.allowAttachView == 1) {
							str += '<botton type="botton" class="btn btn-info btn-xs btnBrowse" data-src="'+this.filepath+'">浏览</botton>';
						};
						if (!myFns.is_images(this.ext) && opt.allowAttachView == 1) {
							str += '<botton type="botton" class="btn btn-info btn-xs btnFileView" data-src="'+this.filepath+'">查看</botton>';
						};
						if (opt.allowAttachDown == 1) {
							if (userAgent == 'android') {
								str += '<botton type="botton" class="btn btn-success btn-xs btnDownload" data-src="'+this.filepath+'" >下载</botton>';
							};
						};
						str += '</div>'; 
						str += '</div>';
					})
				};
			} else {
				str += '<botton type="button" class="btn btn-default btn-sm wfUploadFieldType '+(obj.must?'must':'')+'" id="'+obj.name+'" field="'+obj.field+'" otype="'+obj.tag+'" '+style+'>'+obj.label+'</botton>';
				if (attachs.length > 0) { //附件数量
					$.each(attachs, function(){ //流程附件
						str += '<div class="panel-heading panel-attach-box" data-attachid="'+this.attachid+'" data-name="'+this.filename+'" data-ext="'+this.ext+'" style="padding: 4px 0px;">';
						str += '<span class="fileName">'+this.filename+'</span>';
						str += '<div class="panel-attach">';
						if (myFns.is_images(this.ext) && opt.allowAttachView == 1) {
							str += '<botton type="botton" class="btn btn-info btn-xs btnBrowse" data-src="'+this.filepath+'">浏览</botton>';
						};
						if (!myFns.is_images(this.ext) && opt.allowAttachView == 1) {
							str += '<botton type="botton" class="btn btn-info btn-xs btnFileView" data-src="'+this.filepath+'">查看</botton>';
						};
						if (opt.allowAttachDown == 1) {
							if (userAgent == 'android') {
								str += '<botton type="botton" class="btn btn-success btn-xs btnDownload" data-src="'+this.filepath+'" >下载</botton>';
							};
						};
						str += '</div>'; 
						str += '</div>';
					})
				}
			}
			str += '</div>';
		} else {
			str += '<div class="form-group '+(obj.must?'has-error':'')+'">';
			str += obj.displayValue?obj.displayValue:obj.value;
			str += '</div>';
		}
		return str;
	},
	/**
	 *	方法: myFns.set_select(opts)
	 *	功能: 设置表单select控件
	 *	参数: <Object> opts 要遍历的下拉框对象数据
	 */
	 set_select: function(opts){
	 	var str = '';
	 	$.each(opts, function(i, arr){
	 		str += '<option value="'+arr['label']+'" '+(arr['selected']?'selected':'')+'>'+arr['value']+'</option>';
	 	})
	 	return str;
	 },
	/**
	 *	方法: myFns.is_images(ext)
	 *	功能: 判断一个值是否在数组中 图片
	 *	参数: <string> ext 文件后缀 
	 *	返回: <boolean> 返回true false
	 */
	 is_images:function(ext){
		var ext = ext.toLowerCase(); //大写转小写
		var view = ['jpg','jpeg','png','gif','bmp']; //ios支持在线查看类型
		if($.inArray(ext,view) < 0){
			return false;
		}
		return true;
	},
	/**
	 *	方法: myFns.inspectControl()
	 *	功能: 检查必填控件是否为空
	 *	返回: <bool> true:跳出循环体 false:往下执行
	 */
	 inspectControl: function(){
		//遍历必填控件是否已填写内容
		var flag = false; //是否跳出循环
		var mustVal = ''; //必填控件的值
		$('.must').each(function(i){
		  	//控件类型
		  	var dataType = $(this).attr('data-datatype');
		  	if(dataType != undefined){ //非input控件
		  		if(dataType === 'user_sel'){ //人员选择器
		  			mustVal = $(this).children().attr('data-uid');
		  		}else if(dataType === 'dept_sel'){
		  			mustVal = $(this).children().attr('data-did');
		  		}
		  	}else if($(this).attr('otype')=='signature'){ //图文签章
		  		mustVal = $(this).next().val();
		  	}else if($(this).attr('otype')=='attach'){ //流程附件
		  		var brothers = $(this).nextAll().length;
		  		mustVal = brothers > 0 ? brothers : '';
		  	}else{ //属于input控件
		  		mustVal = $(this).val();
		  		//如果是单选和复选的话，mustVal需要另外判断
		  		// 单选
		  		if($(this).hasClass('rdolist')){
		  			var name = $(this).attr('name');
		  			if($("input[name='"+name+"']:checked").length>0){
	    				mustVal = $(this).val();
	    			}else{
	    				mustVal = '';
	    			}
			  	}
			  	//复选
	    		if($(this).hasClass('chklist')){
	    			var tname = $(this).attr('tname');
	    			if($("input[tname='"+tname+"']:checked").length>0){
	    				mustVal = $(this).val();
	    			}else{
	    				mustVal = '';
	    			}
	    		}
		  	}
		  	//当前必选控件值是否为空
		  	if(mustVal == '' || mustVal == undefined){
		  		var panelTitleVal = $(this).closest('.panel-body').prev().children('.panel-title').html();
		  		jError('['+panelTitleVal+']必填',{
					autoHide : true,                // 是否自动隐藏提示条 
					clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
					MinWidth : 20,                    // 最小宽度 
					TimeShown : 1500,                 // 显示时间：毫秒 
					ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
					HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
					LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
					HorizontalPosition : "center",     // 水平位置:left, center, right 
					VerticalPosition : "center",     // 垂直位置：top, center, bottom 
					ShowOverlay : false,                // 是否显示遮罩层 
					ColorOverlay : "#000",            // 设置遮罩层的颜色 
					OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
				flag = true; //跳出循环的标记
				return false;
			}
		});
		return flag;
	},
	/**
	 *	方法: myFns.textTypeControl()
	 *	功能: 检查控件类型是否为正确
	 *	返回: <bool> true:跳出循环体 false:往下执行
	 */
	textTypeControl: function(){
		var res = '';
		$('.float').each(function(){
			if(this.value){
				var floatRule=/^\d+(\.)?\d+$/g;
	
				if(floatRule.test(this.value)){
					
				}else{
					var name = $(this).parent().parent().parent().find('.panel-title').html();
					name = name+"控件输入格式为小数";
					res = name;
				}
			}
		});
		$('.int').each(function(){
			if(this.value){
				var intRule=/^\d+$/g;
	
				if(intRule.test(this.value)){
					
				}else{
					var name = $(this).parent().parent().parent().find('.panel-title').html();
					name = name+"控件输入格式为整数";
					res = name;
				}
			}
		});
		return res;
		
	},
	/**
	 *	方法: myFns.wfDetailInspectControl()
	 *	功能: 检查明细表必填控件是否为空
	 *	返回: <bool> true:跳出循环体 false:往下执行
	 */
	 wfDetailInspectControl: function(){
		//遍历必填控件是否已填写内容
		var flag = false; //是否跳出循环
		var mustVal = ''; //必填控件的值
		$('.wf_field_must').each(function(i){
		  	//控件类型
		  	var dataType = $(this).attr('data-datatype');
		  	if(dataType != undefined){ //非input控件
		  		if(dataType === 'user_sel'){ //人员选择器
		  			mustVal = $(this).children().attr('data-uid');
		  		}
		  	}else{ //属于input控件
		  		mustVal = $(this).val();
		  	}
		  	//当前必选控件值是否为空
		  	if(mustVal == '' || mustVal == undefined){
		  		var panelTitleVal = $(this).attr('qtip');
		  		jError('明细表['+panelTitleVal+']必填',{
					autoHide : true,                // 是否自动隐藏提示条 
					clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
					MinWidth : 20,                    // 最小宽度 
					TimeShown : 1500,                 // 显示时间：毫秒 
					ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
					HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
					LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
					HorizontalPosition : "center",     // 水平位置:left, center, right 
					VerticalPosition : "center",     // 垂直位置：top, center, bottom 
					ShowOverlay : false,                // 是否显示遮罩层 
					ColorOverlay : "#000",            // 设置遮罩层的颜色 
					OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
				flag = true; //跳出循环的标记
				return false;
			}
		});
	return flag;
	},
	/**
	 *	方法: myFns.changeMoneyToChinese(numberValue)
	 *	功能: 金额小写转大写
	 *  参数: numberValue 金额小写
	 *	返回: 金额小写转大写结果
	 */
	 changeMoneyToChinese: function(numberValue){
		var numberValue = new String(Math.round(numberValue*100)); // 数字金额
		var chineseValue = ""; // 转换后的汉字金额
		var String1 = "零壹贰叁肆伍陆柒捌玖"; // 汉字数字
		var String2 = "万仟佰拾亿仟佰拾万仟佰拾元角分"; // 对应单位
		var len = numberValue.length; // numberValue 的字符串长度
		var Ch1; // 数字的汉语读法
		var Ch2; // 数字位的汉字读法
		var nZero = 0; // 用来计算连续的零值的个数
		var String3; // 指定位置的数值
		if(len > 15){  
			jError('超出计算范围',{
				autoHide : true,                // 是否自动隐藏提示条 
				clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
				MinWidth : 20,                    // 最小宽度 
				TimeShown : 1500,                 // 显示时间：毫秒 
				ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
				HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
				LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
				HorizontalPosition : "center",     // 水平位置:left, center, right 
				VerticalPosition : "center",     // 垂直位置：top, center, bottom 
				ShowOverlay : false,                // 是否显示遮罩层 
				ColorOverlay : "#000",            // 设置遮罩层的颜色 
				OpacityOverlay : 0.3            // 设置遮罩层的透明度 
			});
			return "";  
		}  
		if (numberValue == 0){  
			chineseValue = "零元整";  
			return chineseValue;  
		}  
		String2 = String2.substr(String2.length-len, len); // 取出对应位数的STRING2的值
		for(var i=0; i<len; i++){  
			String3 = parseInt(numberValue.substr(i, 1),10); // 取出需转换的某一位的值
			if ( i != (len - 3) && i != (len - 7) && i != (len - 11) && i !=(len - 15) ){  
				if ( String3 == 0 ){  
					Ch1 = "";  
					Ch2 = "";  
					nZero = nZero + 1;  
				} else if ( String3 != 0 && nZero != 0 ){  
					Ch1 = "零" + String1.substr(String3, 1);  
					Ch2 = String2.substr(i, 1);  
					nZero = 0;  
				} else{  
					Ch1 = String1.substr(String3, 1);  
					Ch2 = String2.substr(i, 1);  
					nZero = 0;  
				}  
			} else{ // 该位是万亿，亿，万，元位等关键位
				if( String3 != 0 && nZero != 0 ){  
					Ch1 = "零" + String1.substr(String3, 1);  
					Ch2 = String2.substr(i, 1);  
					nZero = 0;  
				} else if ( String3 != 0 && nZero == 0 ){  
					Ch1 = String1.substr(String3, 1);  
					Ch2 = String2.substr(i, 1);  
					nZero = 0;  
				} else if( String3 == 0 && nZero >= 3 ){  
					Ch1 = "";  
					Ch2 = "";  
					nZero = nZero + 1;  
				} else{  
					Ch1 = "";  
					Ch2 = String2.substr(i, 1);  
					nZero = nZero + 1;  
				}  
				if( i == (len - 11) || i == (len - 3)){ // 如果该位是亿位或元位，则必须写上
					Ch2 = String2.substr(i, 1);  
				}  
			}  
			chineseValue = chineseValue + Ch1 + Ch2;  
		}  
		if ( String3 == 0 ){ // 最后一位（分）为0时，加上“整”
			chineseValue = chineseValue + "整";  
		}  
		return chineseValue;  
	},
	/**
	 *	方法: myFns.roundResult(result, roundtype, baoliu)
	 *	功能: 计算结果转换
	 *  参数: result 计算结果, roundtype 转换类型, baoliu 保留位数
	 *	返回: 计算值转换之后的结果
	 */
	 roundResult: function(result, roundtype, baoliu){
		var result = myFns.to1bits(result); //解决丢失经度
		
		if(roundtype == 'round'){
			return result.toFixed(baoliu);
		}else if(roundtype == 'add'){
			var diejiaNum = 1;
			for(var i=0; i<baoliu;i++){
				diejiaNum = diejiaNum*10;
			}
			result = Math.ceil(result*diejiaNum)/diejiaNum;
			return result;
		}else if(roundtype == 'day'){
			result = parseInt(result/24/60/60/1000);
			return result;
		}else if(roundtype == 'hour'){
			result = parseInt(result/60/60/1000);
			return result;
		}else if(roundtype == 'dayhour'){
			return false;
		}else{
			return result;
		}
	},
	/**
	 * 解决js计算丢失经度
	 * @param flt 需要解决丢失经度的值
	 * return 最终准确结果
	 */
	 to1bits: function(flt) {    
	 	if(parseFloat(flt) == flt){
	 		return Math.round(flt * 1000000) / 1000000;    
	 	}
	 },
	/**
	 * 整理带有符号的值 转化成正规的纯数字
	 * @param {string} val 字符转值
	 */
	 cleanSymbol: function(val){
	 	val = val + "";
	 	try{
	 		val = val.replace(/,/g, '');
	 	}catch(e){alert(e);
	 		val = 0;
	 	}
	 	return Number(val);
	 }, 
	/**
	 *	方法: myFns.pullUpAction()
	 *	功能: 数据源上拉加载更多数据
	 *	返回: 数据源数据
	 */
	 pullUpAction: function(){
		var limit = 15; //每次取15条
		var start = (dsPageNow-1) * limit;//设置开始取数据
		var datasource = $('#datasourceGroup').attr('datasource') //从哪里取数据
		switch(datasource){
			case "customerInfo": //客户信息
			myFns.pullUpActionSetData(datasource, true, "post", start, limit);
			break;
			case "customerLinkman": //客户联系人
			myFns.pullUpActionSetData(datasource, true, "post", start, limit);
			break;
			case "archives": //公文档案
			myFns.pullUpActionSetData(datasource, true, "post", start, limit);
			break;
		}
	},
	/**
	 *	方法: myFns.pull_down_refresh()
	 *	功能: 数据源下拉刷新数据
	 *	返回: 数据源数据
	 */
	 pull_down_refresh: function(){
	 	dsPageNow = 1;
		var datasource = $('#datasourceGroup').attr('datasource') //从哪里取数据
		$('#datasourceGroup tbody').remove();
		switch(datasource){
			case "customerInfo": //客户信息
			myFns.pull_down_refresh_set_data(datasource);
			break;
			case "customerLinkman": //客户联系人
			myFns.pull_down_refresh_set_data(datasource);
			break;
			case "archives": //公文档案
			myFns.pull_down_refresh_set_data(datasource);
			break;
		}
	},
	//加载数据源数据
	getDsDataFns: function(datasource, async, type, start, limit, callback){
		var search = $('#dsSearch').val();
		$.ajax({
			dataType: "json",
			type: type,
			async: async,
			data: {"start":start, "limit":limit, "name":search},
			url: 'm.php?app=wf&func=flow&action=use&modul=new&task=getDsData&version=mm&dsTag='+datasource+'',
			success: function(json){
				if (json.success) {
					callback(json.data);
				} else {
					callback(false);
				}
			}
		})
	},
	//把undefined类型和null转换为:''
	convertNullData: function(value){
		if (value == '' || value == undefined || value == null || value == 'null') {
			return '';
		} else {
			return value;
		}
	},
	//数据源上拉加载更多数据
	pullUpActionSetData: function(datasource, async, type, start, limit){
		myFns.getDsDataFns(datasource, async, type, start, limit, function(data){
			var ths = $('#datasourceGroup .field .ckbox').nextAll();
			var trNum = $('#datasourceGroup').find('tr').length;
			$.each(data, function(k, dsData){
				dataStr = '<tr>';
				dataStr += '<td class="bianhao">'+(k+trNum)+'</td>';
				dataStr += '<td class="chbox"><input type="checkbox" name="dsChk" class="dsChk"></td>';
				$.each(ths, function(){
					var th = $(this);
					for (x in dsData) {
						if (th.attr('class') == x) {
							dataStr += '<td class="'+x+'">'+myFns.convertNullData(dsData[x])+'</td>';
						};
					};
				})
				dataStr += '</tr>';
				$('#datasourceGroup .table tbody').append(dataStr);
			})
			dsPageNow += 1;
			var dsWidth = $('#datasourceGroup > table').width();
			$('#datasource-scroller').css('width', dsWidth+22);
			datasourceScroll.refresh();
		});
	},
	//数据源下拉刷新追加数据
	pull_down_refresh_set_data: function(datasource){
		var ths = $('#datasourceGroup .field .ckbox').nextAll();
		myFns.getDsDataFns(datasource, true, "post", 0, 14, function(data){
			dataStr = '<tbody>';
			$.each(data, function(k, dsData){
				dataStr += '<tr>';
				dataStr += '<td class="bianhao">'+(k+1)+'</td>';
				dataStr += '<td class="chbox"><input type="checkbox" name="dsChk" class="dsChk"></td>';
				$.each(ths, function(){
					var th = $(this);
					for (x in dsData) {
						if (th.attr('class') == x) {
							dataStr += '<td class="'+x+'">'+myFns.convertNullData(dsData[x])+'</td>';
						};
					};
				})
				dataStr += '</tr>';
			})
			dataStr += '</tbody>';
			dsPageNow += 1;
			$('#datasourceGroup .table').append(dataStr);
			var dsWidth = $('#datasourceGroup > table').width();
			$('#datasource-scroller').css('width', dsWidth+22);
			datasourceScroll.refresh();
		});
	},
	//出入库进销存上拉加载更多
	pullUpActionJxc: function(){
		myFns.getGoodsList("POST", false);
	},
	//出入库进销存下拉刷新
	pullDownRefreshJxc: function(){
		jxcPageNow = 1;
		myFns.getGoodsList("POST", true);
	},
	//获取进销存字段
	getGoodsCustomField: function(){
		var querykey = $('#jxcGoodsGroup').attr('querykey');
		if (querykey == "jxcGoods") { //仓库进销存
			var bindfunc = $('#jxcGoodsGroup').attr('bindfunc');
			var baseUrl = "m.php?app=wf&func=flow&action=use&modul=new&&task=getQueryList&bindfunction="+bindfunc;
			$.ajax({
				dataType: "JSON",
				type: "GET",
				url: baseUrl+"&operate=getGoodsCustomField&version=mm",
				success: function(fields){
					myFns.setGoodsCustomField(fields);
				}
			})
		} else if (querykey == "admarticles") { //办公明细
			myFns.setGoodsCustomField();
		};
	},
	//把进销存字段设置在网页上
	setGoodsCustomField: function(fields){
		$('#jxcGoodsGroup').empty(); //清空数据区
		var querykey = $('#jxcGoodsGroup').attr('querykey');
		if (querykey == "jxcGoods") { //仓库进销存
			var titleStr  = '<table class="table table-bordered"><thead><tr class="active jxcField">';
			titleStr += '<th class="bianhao">#</th>';
			titleStr += '<th class="jxcCheckAllTd"><input type="checkbox" name="jxcChkAll" class="jxcChkAll"></th>';
			$.each(fields, function(i, el){
				titleStr += '<th class="'+el.field+'" valueFieldName="'+(el.valueField ? el.valueField : '')+'">'+el.fieldname+'</th>';
			})
			titleStr += '</thead></tr></table>';
		} else if (querykey == "admarticles") { //办公明细
			var titleStr  = '<table class="table table-bordered"><thead><tr class="active jxcField">';
			titleStr += '<th class="bianhao">#</th>';
			titleStr += '<th class="jxcCheckAllTd"><input type="checkbox" name="jxcChkAll" class="jxcChkAll"></th>';
			titleStr += '<th class="name">物品名称</th><th class="number">物品编号</th>';
			titleStr += '<th class="lib">所在库</th><th class="sort">分类</th>';
			titleStr += '<th class="guige">规格</th><th class="price">单价</th>';
			titleStr += '<th class="danwei">单位</th><th class="kucun">库存数量</th>';
			titleStr += '</thead></tr></table>';
		};
		$('#jxcGoodsGroup').append(titleStr);
		myFns.getGoodsList("GET", true); //获取流程引擎数据
		jxcGoodsScroll.refresh();
	},
	//获取进销存数据
	getGoodsList: function(httpType, emptyData){
		var limit = 15; //每次取15条
		var start = (jxcPageNow-1) * limit;//设置开始取数据
		var bindfunc = $('#jxcGoodsGroup').attr('bindfunc');
		var querykey = $('#jxcGoodsGroup').attr('querykey');
		if (querykey == "jxcGoods") { //仓库进销存
			var storageId = $('#jxcGoodsGroup').attr('wf_engine_value'); //流程引擎的字段的值
			var baseUrl = "m.php?app=wf&func=flow&action=use&modul=new&task=getQueryList&bindfunction="+bindfunc+"&version=mm&operate=getGoodsList&storageId="+storageId;
		} else if (querykey == "admarticles") { //办公明细
			var baseUrl = "m.php?app=wf&func=flow&action=use&modul=new&task=getQueryList&bindfunction="+bindfunc+"&version=mm&from=list";
		};
		var HttpData = {}; 
		if ((httpType == undefined) || httpType == "GET") {
			var httpType = "GET";
		} else { // POST 请求
			if (querykey == "jxcGoods") { //仓库进销存
				var goodsname = $('#jxcGoodsSearch').val();
				HttpData = {"start":start, "limit":limit, "goodsname": goodsname};
			} else if (querykey == "admarticles") { //办公明细
				var title = $('#jxcGoodsSearch').val();
				HttpData = {"start":start, "limit":limit, "title": title};
			};
		}
		$.ajax({
			dataType: "JSON",
			type: httpType,
			data: HttpData,
			url: baseUrl,
			success: function(msg){
				if (msg.success) {
					if (querykey == "jxcGoods") { //仓库进销存
						myFns.setGoodsData(msg, emptyData);
					} else if (querykey == "admarticles") { //办公明细
						myFns.setGoodsData(msg, emptyData);
					};
				} else {
					jNotify('未检索到数据!',{
						autoHide : true,                // 是否自动隐藏提示条 
						clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
						MinWidth : 20,                    // 最小宽度 
						TimeShown : 1500,                 // 显示时间：毫秒 
						ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
						HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
						LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
						HorizontalPosition : "center",     // 水平位置:left, center, right 
						VerticalPosition : "center",     // 垂直位置：top, center, bottom 
						ShowOverlay : false,                // 是否显示遮罩层 
						ColorOverlay : "#000",            // 设置遮罩层的颜色 
						OpacityOverlay : 0.3            // 设置遮罩层的透明度 
					});
					return false;
				}
			}
		})
	},
	//把进销存数据设置在网页上
	setGoodsData: function(msg, emptyData){
		var querykey = $('#jxcGoodsGroup').attr('querykey');
		if (emptyData) {
			$('#jxcGoodsGroup > table > tbody').remove();
		};
		var goodsData = msg.data;
		var jxcThs = $('#jxcGoodsGroup .jxcField .jxcCheckAllTd').nextAll();
		if (goodsData.length > 0) {
			goodsHtml = '';
			if (emptyData) {
				goodsHtml = '<tbody>';
			};
			var ordinal = $('#jxcGoodsGroup > table > tbody > tr').length;
			if (querykey == "jxcGoods") {
				$.each(goodsData,function(i, jxcData){
					goodsHtml += '<tr rowid="'+jxcData.id+'">';
					goodsHtml += '<td class="bianhao">'+(ordinal != 0 ? (ordinal+i+1) : (i+1))+'</td>';
					goodsHtml += '<td class="jxcChkTd"><input type="checkbox" name="jxcChk" class="jxcChk"></td>';
					$.each(jxcThs, function(){
						var jxcTh = $(this);
						if(jxcData.hasOwnProperty(jxcTh.attr('class'))){
							var valuefieldname = jxcTh.attr('valuefieldname'); //需要显示的提交的值对应字段
							goodsHtml += '<td class="'+jxcTh.attr('class')+'" valueFieldName="'+(valuefieldname != '' ? myFns.convertNullData(valuefieldname) : '')+'" valuefield="'+(valuefieldname != '' ? myFns.convertNullData(jxcData[valuefieldname]) : '')+'" >'+myFns.convertNullData(jxcData[jxcTh.attr('class')])+'</td>';
							return ;
						} else {
							goodsHtml += '<td class="'+jxcTh.attr('class')+'"></td>';
						}
					})
					goodsHtml += '</tr>';
				})
			} else if (querykey == "admarticles") {
				$.each(goodsData, function(i, jxcData){
					goodsHtml += '<tr rowid="'+jxcData.id+'">';
					goodsHtml += '<td class="bianhao">'+(ordinal != 0 ? (ordinal+i+1) : (i+1))+'</td>';
					goodsHtml += '<td class="jxcChkTd"><input type="checkbox" name="jxcChk" class="jxcChk"></td>';
					goodsHtml += '<td class="name">'+jxcData.name+'</td><td class="number">'+jxcData.number+'</td>';
					goodsHtml += '<td class="lib">'+jxcData.libraryName+'</td><td class="sort">'+jxcData.typeName+'</td>';
					goodsHtml += '<td class="guige">'+jxcData.standard+'</td><td class="price">'+jxcData.price+'</td>';
					goodsHtml += '<td class="danwei">'+jxcData.unit+'</td><td class="kucun">'+jxcData.stock+'</td></tr>';
				})
			};
			if (emptyData) {
				goodsHtml += '</tbody>';
				$('#jxcGoodsGroup > table').append(goodsHtml);
			} else {
				$('#jxcGoodsGroup > table > tbody').append(goodsHtml);
			}
			jxcPageNow += 1; //分页变为2
			var jxcWidth = $('#jxcGoodsGroup > table').width();
			$('#jxcGoods-scroller').css('width', jxcWidth+22);
			jxcGoodsScroll.refresh();
		} else {
			jNotify('未检索到数据!',{
				autoHide : true,                // 是否自动隐藏提示条 
				clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
				MinWidth : 20,                    // 最小宽度 
				TimeShown : 1500,                 // 显示时间：毫秒 
				ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
				HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
				LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
				HorizontalPosition : "center",     // 水平位置:left, center, right 
				VerticalPosition : "center",     // 垂直位置：top, center, bottom 
				ShowOverlay : false,                // 是否显示遮罩层 
				ColorOverlay : "#000",            // 设置遮罩层的颜色 
				OpacityOverlay : 0.3            // 设置遮罩层的透明度 
			});
			jxcGoodsScroll.refresh();
			return false;
		}
	},
	//生成随机字符串
	rndNum: function (n){
		var rnd="";
		for(var i=0;i<n;i++){
			rnd+=Math.floor(Math.random()*10);
		}
		return rnd;
	}
	}

	jQuery.fn.extend({
		autoHeight: function(){
			return this.each(function(){
				var $this = jQuery(this);
				if( !$this.attr('_initAdjustHeight') ){
					$this.attr('_initAdjustHeight', $this.outerHeight());
				}
				_adjustH(this).on('input', function(){
					_adjustH(this);
				});
			});
			function _adjustH(elem){
				var $obj = jQuery(elem);	
				return $obj.css({height: $obj.attr('_initAdjustHeight'), 'overflow-y': 'hidden'})
				.height(elem.scrollHeight);

			}
		}
	});


	$(function(){

	//底部添加计划
	$(document).on('touchstart','#btn_actionsheet',function(){
		$('#jingle_popup').slideDown(100);
		$('#jingle_popup_mask').show();
		return false;
	});

	//关闭弹出窗口 
	$(document).on('touchstart','#jingle_popup_mask',function(event){
		if( $(event.target).is(this)) { 
			$(this).hide();
			$('#jingle_popup').slideUp(100);
		}
		return false;
	});

	$(document).on('touchstart','#btnCancel',function(){
		$('#jingle_popup_mask').hide();
		$('#jingle_popup').slideUp(100);
		return false;
	});

	//返回发起流程列表
	$(document).on('touchstart','#btnBack',function(){
		window.location.href='m.php?app=wf&func=flow&action=use&modul=new&task=loadPage&from=newflow&version=mm';
		return false;
	})

	//发起流程初始化控件
	function loadInit(){
		//页面加载完成调用`初始化页面
		myFns.loaded(); 
		//明细表
		myFns.detailtableLoaded();
		$('#detailtable-wrapper').css('height', $(document).height()-52);
		//下一步办理人列表
		myFns.nextStepLoaded(); 
		$('#nextStep-wrapper').css('height', $(document).height()-52); //下一步办理人列表
		//人员选择器
		myFns.userDataLoaded(); //人员和部门区域
		var userDataWrapperH = $(document).height()-151;
		$('.userDataWrapper').css('height', userDataWrapperH); //人员和部门区域高度
		myFns.userLoaded(); //人员删除操作区域
		var userWrapperTop = $(document).height()-146; //上线后改为107  147
		$('.userWrapper').css('top', userWrapperTop); //人员删除操作区域高度
		var userWrapperW = $(document).width() - 84; //userWrapper宽度
		$('#userWrapper').css('width', userWrapperW);
		var userScrollerW = $('#userGroup li').length * $('#userGroup li').width(); //userScroller宽度
		$('#userScroller').css('width', userScrollerW);
		var optsBoxH = $(document).height() - 54;
		$('#userDataModal .opts-box').css('top', optsBoxH);
		$('#userDataModal .opts-box').css('width', $(document).width());
		//岗位选择器
		myFns.stationLoaded();
		$('#stationDataWrapper').css('height', $(document).height()-52);
		//明细表
		myFns.detailtableLoaded();
		$('#detailtable-wrapper').css('height', $(document).height()-52);
		//数据源
		datasourceLoaded();
		$('#datasource-wrapper').css('height', $(document).height()-52);
		//进销存
		jxcGoodsLoaded();
		$('#jxcGoods-wrapper').css('height', $(document).height()-52);
		//图文签章
		myFns.signatureLoaded();
		$('#signature-wrapper').css('height', $(document).height()-52);
		//其他弹窗
		myFns.otherLoaded();
		$('#other-wrapper').css('height', $(window).height()-52);
	}

	//调用开始初始化
	loadInit();

	/**
	 *	方法: radio_init()
	 *	功能: 请假类型radio初始化是否选中
	 */
	 function radio_init(){
	 	$('.rdolist').each(function(i){
	 		var radioBtn = $(this).parent('.radio-btn');
			if($(this).prop('checked')){ //扣选
				$(radioBtn).addClass('checkedRadio');
			}
		})
	 }

	//点击请假类型radio
	$(document).on('click','.radio-btn', function(){
		var _this = $(this),
		block = _this.parent().parent();
	  if($(block).find('.rdolist').prop('disabled')){ //状态disabled 用户禁止操作
	  	return false;
	  }
	  block.find('input:radio').prop('checked', false);
	  block.find(".radio-btn").removeClass('checkedRadio');
	  _this.addClass('checkedRadio');
	  _this.find('input:radio').prop('checked', true);
	});

	//下一步步骤办理人选择类型radio
	$(document).on('click','.stepUser', function(){
		var _this = $(this),
		block = _this.closest('.stepUserList'),
		radio = _this.find('.radio-btn-step'),
	  		dealway = block.attr('data-dealWay'), //当前步骤单选，多选标志
	  		rdolist = radio.find('.rdolist');
	  if($(block).find('.rdolist').prop('disabled')){ //状态disabled 用户禁止操作
	  	return false;
	  }
	  if(dealway == 1){ //多选
	  	if(rdolist.prop('checked')){ //当前点击的radio已选
	  		rdolist.prop('checked',false); //更改成为选状态
	  		radio.removeClass('checkedRadio'); 
	  	}else{
	  		rdolist.prop('checked',true); //更改成为选状态
	  		radio.addClass('checkedRadio');
	  	}
	  }else{ //单选
	  	if(rdolist.prop('checked')){ //当前点击的radio已选
	  		rdolist.prop('checked',false); //更改成为选状态
	  		radio.removeClass('checkedRadio'); 
	  	}else{
	  		block.find('input:radio').prop('checked', false);
	  		block.find(".radio-btn-step").removeClass('checkedRadio');
	  		radio.addClass('checkedRadio');
	  		radio.find('input:radio').prop('checked', true);
	  	}
	  }
	});

	//请假类别checkbox初始化是否选中
	function checkbox_init(){
		$('.chklist').each(function(i){
			var chkbox = $(this).next();
		  //var chkbox = $('.rdolist').eq(i).next(); //获取当前rdolist的下一个兄弟节点
		  if($(this).prop('checked')){
		  	$(this).removeClass("unchecked");
		  	$(chkbox).addClass("checked");
		  	$(chkbox).find(".check-image").css("background", "url(resources/images/m/ico/input-checked.png)");
		  }else{
		  	$(this).removeClass("checked");
		  	$(chkbox).addClass("unchecked");
		  	$(chkbox).find(".check-image").css("background", "url(resources/images/m/ico/input-unchecked.png)");
		  }
		})
	}

	//点击请假类型checkbox
	$(document).on('click','.chkbox',function(){
		var chklist = $(this).prev();
		if($('.chklist').prop('disabled')){ //判断是否可操作
			return false;
		}
		if($(chklist).prop('checked')){ //当前点击的checkbox是否已扣选
			$(this).removeClass("checked");
			$(this).addClass("unchecked");
			$(chklist).prop("checked", false);
			$(this).find(".check-image").css("background", "url(resources/images/m/ico/input-unchecked.png)");
		}else{ //当前处于未扣选状态
			$(this).removeClass("unchecked");
			$(this).addClass("checked");
			$(chklist).prop("checked", true);
			$(this).find(".check-image").css("background", "url(resources/images/m/ico/input-checked.png)");
		}
	})

	//转下一步骤办理人全选反选
	$(document).on('click', 'input.selectAllStepUser', function(){
		var ulEl = $(this).closest('ul'), radios = ulEl.find('.radio-btn-step');
		if ($(this).prop('checked')) {
			ulEl.find('input:radio').prop('checked', true);
			radios.addClass('checkedRadio');
		} else {
			ulEl.find('input:radio').prop('checked', false);
			radios.removeClass('checkedRadio');
		}
	})

	//加载发起流程表单数据
	function loadFlowInfoFns(){
		var flowId = myFns.getUriString('flowId'), flowType = myFns.getUriString('flowType'), tplSort = myFns.getUriString('tplSort');
		$.ajax({
			dataType: "json",
			type: "post",
			url: 'm.php?app=wf&func=flow&action=use&modul=form&task=loadFlowInfo&version=mm',
			data: {"flowId":flowId, "flowType":flowType, "tplSort":tplSort},
			beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				hideLoading();
				$('#myTabContent').empty();
				isAllowBlank = json.permit.nameDisallowBlank;
				if(json.flowInfo){
					//流程信息
					var flowInfo = json.flowInfo;
					$('#wfBasicInfo .flowName').val(flowInfo.flowName); //流程名称
					$('#wfBasicInfo .flowNumber').val(flowInfo.flowNumber); //流程编号
					$('#wfBasicInfo .level').val(flowInfo.level); //流程重要等级
					$('#wfBasicInfo .reason').val(flowInfo.reason); //流程申请理由
					
					// 如果流程有绑定业务引擎的话(车辆申请)
					if(flowInfo.engine){
						queryBusinessData(flowInfo.engine);
					}
					
					//表单信息
					var formInfoTitle = '<div class="row formInfoTitle"><span>表单内容</span></div>';
					$('#myTabContent').append(formInfoTitle);
					if (flowType == '1') { //自由顺序流
						//流程信息
						var strHtml  = '<div id="flowStepBox"><div class="panel panel-info">';
						strHtml += '<div class="panel-heading"><h3 class="panel-title">流程信息</h3></div>';
						strHtml += '<div class="panel-body"><div class="input-group">';
						strHtml += '<span class="input-group-addon">流程步骤</span> ';
						strHtml += '<input type="text" class="form-control input-m uname " id="uname" aria-describedby="basic-addon2" name="uname"  value="" data-belong="flowStep" data-name="uname" data-readonly="false" data-datatype="user_sel" data-checktype="true" readonly>';  
						strHtml += '<input type="hidden" name="getname" value="">';
						strHtml += '<input type="hidden" name="uid" data-face="" value="">';
						strHtml += '<input type="hidden" name="stepname" value=""></div>';
						strHtml += '<div class="alert alert-danger flowStepExplain" role="alert">';
						strHtml += '<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>';
						strHtml += '<span>指定主办人模式下按照列表中人员的排列顺序进行流转，最后一个人为该流程结束人！</span></div>';  
						strHtml += '<div class="row changeFlowInfoBox"><div class="col-xs-3">';
						strHtml += '<input type="checkbox" name="changeFlowInfo" class="chklist">';
						strHtml += '<label class="chkbox unchecked"><span class="check-image" style="background: url(resources/images/m/ico/input-unchecked.png);"></span></label></div>';
						strHtml += '<div class="col-xs-9"><span><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>是否禁止流程步骤修改表单信息</span></iv>';
						strHtml += '</div></div></div></div>';
						$('#wfBasicInfo').before(strHtml);  
			      // 表单内容
			      // 加载编辑器的容器
			      var containerStr = '<script id="htmlFormContent" name="htmlFormContent" type="text/plain" style="width:'+$(window).width()+'px;height:240px;"></script>';
			      $('#myTabContent').append(containerStr);     
			      // 实例化编辑器
			      ue = UM.getEditor('htmlFormContent',{
			      	focus: true
			      });

	      		//编辑器加载完成之后执行
	      		ue.ready(function() {
	      			//ue.setContent('hello');
	      		})
					} else if (flowType == '2') { //自由流程
						// 实例化编辑器
						var containerStr = '<script id="htmlFormContent" name="htmlFormContent" type="text/plain" style="width:'+$(window).width()+'px;height:240px;"></script>';
						$('#myTabContent').append(containerStr);
						ue = UM.getEditor('htmlFormContent',{
							focus: true
						});
	      		//办理理由
	      		var handleStr  = '<div class="row formInfoTitle banliTitle"><span>办理理由</span></div>';
	      		handleStr += '<div class="form-group" style="padding: 0px 15px;"><textarea class="form-control say" rows="2" name="say" placeholder="请填写你的办理理由"></textarea></div>';
	      		$('#myTabContent').append(handleStr);
					} else { //固定流程
						var formInfo = json.formInfo['items'];
						if(formInfo.length > 0){
							$.each(formInfo, function(index,array){
								var strFormData  = '<div class="panel panel-default">';
								strFormData += '<div class="panel-heading">';
								strFormData += '<h3 class="panel-title">'+array['label']+'</h3></div>';
								strFormData += '<div class="panel-body">'+myFns.formatForm(this)+'</div>';
								strFormData += '</div>';
								if ($('#attachGroup').length) {
									$('#attachGroup').before(strFormData);
								} else {
									$(' #myTabContent').append(strFormData);
								}
								$('textarea[readonly]:last').autoHeight();

								// $('textarea').on('focus',function(){
								// 	$(this).autoHeight();
								// });
								// $('textarea').on('blur',function(){
								// 	$('#wrapper').scrollTop(10);
								// 	myScroll.refresh();
								// });
							})
						}

						//请假类型radio初始化是否选中
						radio_init();

						//请假类别checkbox初始化是否选中
						checkbox_init();

						//有明细表的时候初始化明细表`把明细表加载进来
						loadDetailtable();
					}
				}

				//重新调整页面
				window.setTimeout(function(){
					
					initTimeDropper();
					myScroll.refresh();
				},200)
			}
		})
	}

	//流程信息
	loadFlowInfoFns();

	//初始化明细表
	function loadDetailtable() {
		var detailtables = $('botton.detailtable'), detailtableLen = $('botton.detailtable').length;
		for (var i = 0; i < detailtableLen; i++) {
			detailtables.eq(i).click();
		};
	}

	//百度编辑器获取焦点
	$(document).on('touchstart','#htmlFormContent', function(){
		window.setTimeout(function(){
			UM.getEditor('htmlFormContent').focus();
		},400)
	})

	//添加附件`初始化上传附件表单
	$(document).on('click','#fileupload',function(){
		$('#jingle_popup').css('display','none');
		$('#jingle_popup_mask').css('display','none');
		var myUpload = $("#myUpload");
		var url = 'm.php?action=commonJob&task=upFile';
		if(!myUpload.length){//判断表单form是否存在
			$("#fileupload").wrap("<form id='myUpload' action='' method='post' enctype='multipart/form-data'></form>"); 
			$("#myUpload").attr("action",url);
		}
	});

	//file改变上传文件`附件
	$(document).on('change','#fileupload',function(){
		var filePath = $('#fileupload').val();
		if(!filePath){
			return false;
		}
		var filename  = filePath.replace(/.*(\/|\\)/, ""); //文件名带后缀
		var fileSplit = (/[.]/.exec(filename)) ? /[^.]+$/.exec(filename.toLowerCase()) : ''; //文件后缀fileExt[0]
		var fileArr 	= ['jpg','jpeg','gif','png','bmp','rar','zip','doc','wps','wpt','ppt','xls','txt','csv','et','ett','pdf'];
		var fileExt 	= fileSplit[0].toLowerCase(); //文件后缀名转小写
		if($.inArray(fileExt,fileArr) < 0){
			jError("不支持上传此类型文件!",{
				autoHide : true,                // 是否自动隐藏提示条 
				clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
				MinWidth : 20,                    // 最小宽度 
				TimeShown : 1500,                 // 显示时间：毫秒 
				ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
				HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
				LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
				HorizontalPosition : "center",     // 水平位置:left, center, right 
				VerticalPosition : "center",     // 垂直位置：top, center, bottom 
				ShowOverlay : false,                // 是否显示遮罩层 
				ColorOverlay : "#000",            // 设置遮罩层的颜色 
				OpacityOverlay : 0.3            // 设置遮罩层的透明度 
			});
			return false;
		}

		$("#myUpload").ajaxSubmit({
			dataType:  'json',
			beforeSend: function() {
				showLoading();
	    $('#fileupload').attr('disabled',"true"); //添加disabled属性 
	  },
	  success: function(json){
	  	hideLoading();
	  	$('#fileupload').removeAttr("disabled"); //移除disabled属性 
	  	if(json.success){ 
	  		if(!$('#attachGroup').length){
	  			var attachGroupStr = '<div id="attachGroup"><div class="row formInfoTitle"><span>附件信息</span></div></div>';
	  			$('#myTabContent').append(attachGroupStr);
	  		}

	  		var data = json.msg;
	  		var filesUploadData = '||'+data.oldname+'||'+data.name+'||'+data.size;
	  		var fileBox  = '<div class="panel panel-default panel-attach-box">';
	  		fileBox += '<div class="panel-heading">';
	  		fileBox += ''+data.oldname+'';
	  		fileBox += '<div class="panel-attach">';
	  		fileBox += '<botton type="botton" class="btn btn-danger btn-xs btnDel">删除</botton>';
	  		fileBox += '<input type="hidden" name="filesUpload[]" value="'+filesUploadData+'" disabled>';
	  		fileBox += '</div></div></div>';
	  		$('#attachGroup').append(fileBox);		
	  	}else{ //上传失败
	  		jError(json.msg,{
					autoHide : true,                // 是否自动隐藏提示条 
					clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
					MinWidth : 20,                    // 最小宽度 
					TimeShown : 1500,                 // 显示时间：毫秒 
					ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
					HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
					LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
					HorizontalPosition : "center",     // 水平位置:left, center, right 
					VerticalPosition : "center",     // 垂直位置：top, center, bottom 
					ShowOverlay : false,                // 是否显示遮罩层 
					ColorOverlay : "#000",            // 设置遮罩层的颜色 
					OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
	  		return false;
	  	}
	  	myScroll.refresh();
	  },
	  error:function(xhr){
	    $('#fileupload').removeAttr("disabled"); //移除disabled属性 
	    jError(xhr.responseText,{
				autoHide : true,                // 是否自动隐藏提示条 
				clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
				MinWidth : 20,                    // 最小宽度 
				TimeShown : 1500,                 // 显示时间：毫秒 
				ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
				HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
				LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
				HorizontalPosition : "center",     // 水平位置:left, center, right 
				VerticalPosition : "center",     // 垂直位置：top, center, bottom 
				ShowOverlay : false,                // 是否显示遮罩层 
				ColorOverlay : "#000",            // 设置遮罩层的颜色 
				OpacityOverlay : 0.3           // 设置遮罩层的透明度 
			});
	    return false;
	  }
	});
	})

	//删除文件
	$(document).on('click','.btnDel',function(){
		var delFile = $(this);
		$.confirm({
			title: '提示',
			content: '确定删除此附件吗？',
			animation: "top",
			cancelButtonClass: 'btn-danger',
			confirmButton: '确定',
			cancelButton: '取消',
			confirm: function(){
				$('.buttons .btn').closest('.jconfirm').hide();
		  	var wfAttachId = delFile.closest('.panel-attach-box').attr('data-attachid'); //流程附件对应的id
		  	var wfRandomId = delFile.closest('.panel-attach-box').attr('data-random'); //上传文件的删除按钮
		  	delFile.closest('.panel-attach-box').remove();
		  	if (wfAttachId != undefined) { //存在流程附件id就删除对应文件
		  		$('#myTabContent .panel-attach-box[data-attachid='+wfAttachId+']').remove();
		  	};
		  	if (wfRandomId != undefined) { //上传文件后返回的随机数，提供删除对应文件
		  		$('#myTabContent .panel-attach-box[data-random='+wfRandomId+']').remove();
		  	};
		  	var attachGroupChildren = $('#attachGroup').find('.panel-attach-box'); //有多少附件存在
		  	if (attachGroupChildren.length < 1) {
		  		$('#attachGroup').remove();
		  	};
		  	myScroll.refresh();
		  }
		});
	})

	/**
	 *获取转下一步的信息
	 *@param <int> uFlowId
	 *@param <int> flowId
	 *@param <int> step
	 */
	 function getNextStepData(uFlowId,flowId,step){

	 	var flowType = myFns.getUriString('flowType'), tplSort = myFns.getUriString('tplSort');
		if (flowType == '1') { // 自由顺序流程
			var stepUids = $('#flowStepBox input[name=uid]').val();
			if (stepUids == '') {
				jError('流程办理步骤为空，不能提交!',{
					autoHide : true,                // 是否自动隐藏提示条 
					clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
					MinWidth : 20,                    // 最小宽度 
					TimeShown : 1500,                 // 显示时间：毫秒 
					ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
					HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
					LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
					HorizontalPosition : "center",     // 水平位置:left, center, right 
					VerticalPosition : "center",     // 垂直位置：top, center, bottom 
					ShowOverlay : false,                // 是否显示遮罩层 
					ColorOverlay : "#000",            // 设置遮罩层的颜色 
					OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
				return false;
			};

			//附件信息
			var upload_attach = '';
			$("input[name='filesUpload[]']").each(function(i){
				if(i==0){
					upload_attach += '0' + $(this).val();
				}else{
					upload_attach += ',' + '0' + $(this).val();
				}
			})

			$.confirm({
				title: '提示',
				content: '确定转交下一步吗？',
				animation: "top",
				cancelButtonClass: 'btn-danger',
				confirmButton: '确定',
				cancelButton: '取消',
				confirm: function(){
		    	//var htmlFormContent = ue.getContent();
		    	$("#myTableFrom").ajaxSubmit({
		    		dataType: 'json',
		    		type: 'post', 
		    		url: 'm.php?app=wf&func=flow&action=use&modul=newfree&task=seqFlowSendNextStep&flowType='+flowType+'&tplSort='+tplSort+'&version=mm',
		    		data: {"flowId":flowId, "flowType":flowType, "uFlowId":uFlowId, "tplSort":tplSort,"upload_attach":upload_attach},
		    		success: function(json){
		    			if(json.success){
		    				jSuccess('操作成功!',{
									autoHide : true,                // 是否自动隐藏提示条 
									clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
									MinWidth : 20,                    // 最小宽度 
									TimeShown : 1500,                 // 显示时间：毫秒 
									ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
									HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
									LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
									HorizontalPosition : "center",     // 水平位置:left, center, right 
									VerticalPosition : "center",     // 垂直位置：top, center, bottom 
									ShowOverlay : false,                // 是否显示遮罩层 
									ColorOverlay : "#000",            // 设置遮罩层的颜色 
									OpacityOverlay : 0.3            // 设置遮罩层的透明度 
								});
		    			}else{
		    				jError(json.msg,{
									autoHide : true,                // 是否自动隐藏提示条 
									clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
									MinWidth : 20,                    // 最小宽度 
									TimeShown : 1500,                 // 显示时间：毫秒 
									ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
									HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
									LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
									HorizontalPosition : "center",     // 水平位置:left, center, right 
									VerticalPosition : "center",     // 垂直位置：top, center, bottom 
									ShowOverlay : false,                // 是否显示遮罩层 
									ColorOverlay : "#000",            // 设置遮罩层的颜色 
									OpacityOverlay : 0.3            // 设置遮罩层的透明度 
								});
		    			}
		    			setTimeout(function(){
		    				window.location.href = 'm.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&type=show&from=list&version=mm';
		    			},1400)
		    		}
		    	}) 	
	}
	});
		} else if (flowType == '2') { // 自由流程
			var editorValue = ue.getContent();
			var content  = '<botton type="botton" class="btn btn-default modSendee " id="freeFlowStepDeal" data-checktype="false" data-name="freeFlowStepDeal" data-toggle="modal" data-target="#userDataModal" readonly><span class="dealNameBox" style="color:#d7d7d7;">选择人员</span></botton>';
			swal({   
				title: "请选择下一步骤办理人",   
				text: content,
				html: true,
				confirmButtonText: "确认",   
				type: "input",   
				showCancelButton: true,   
				closeOnConfirm: false,   
				//animation: "slide-from-top",   
				inputPlaceholder: "流程步骤" 
			}, function(inputValue){
				if (inputValue === false){ //取消按钮
					return false;      
				}

				var dealuid = $('#freeFlowStepDeal').children('.name').attr('data-uid'),
				stepname = $('#freeFlowStepDeal').children('.name').text();
				if (dealuid == '') {
					jError('人员不能为空!',{
						autoHide : true,                // 是否自动隐藏提示条 
						clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
						MinWidth : 20,                    // 最小宽度 
						TimeShown : 1500,                 // 显示时间：毫秒 
						ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
						HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
						LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
						HorizontalPosition : "center",     // 水平位置:left, center, right 
						VerticalPosition : "center",     // 垂直位置：top, center, bottom 
						ShowOverlay : false,                // 是否显示遮罩层 
						ColorOverlay : "#000",            // 设置遮罩层的颜色 
						OpacityOverlay : 0.3            // 设置遮罩层的透明度 
					});
					return false;
				};
				if (stepname == '') {
					jError('流程步骤办理人不能为空!',{
						autoHide : true,                // 是否自动隐藏提示条 
						clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
						MinWidth : 20,                    // 最小宽度 
						TimeShown : 1500,                 // 显示时间：毫秒 
						ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
						HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
						LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
						HorizontalPosition : "center",     // 水平位置:left, center, right 
						VerticalPosition : "center",     // 垂直位置：top, center, bottom 
						ShowOverlay : false,                // 是否显示遮罩层 
						ColorOverlay : "#000",            // 设置遮罩层的颜色 
						OpacityOverlay : 0.3            // 设置遮罩层的透明度 
					});
					return false;
				};
				$('.sweet-overlay').css('display','none'); //隐藏输入框
				$('.sweet-alert').css('display','none');

				//附件信息
				var upload_attach = '';
				$("input[name='filesUpload[]']").each(function(i){
					if(i==0){
						upload_attach += '0' + $(this).val();
					}else{
						upload_attach += ',' + '0' + $(this).val();
					}
				})

				$("#myTableFrom").ajaxSubmit({
					dataType: 'json',
					type: 'post', 
					url: 'm.php?app=wf&func=flow&action=use&modul=newfree&flowId='+flowId+'&tplSort='+tplSort+'&flowType='+flowType+'&task=freeFlowSendNextStep&version=mm',
					data: { "flowType":flowType, "uFlowId":uFlowId, "tplSort":tplSort, "dealuid":dealuid, "stepname":stepname+'(办理)',
					"editorValue":editorValue, "upload_attach":upload_attach
				},
				success: function(json){
					if(json.success){
						jSuccess('操作成功!',{
								autoHide : true,                // 是否自动隐藏提示条 
								clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
								MinWidth : 20,                    // 最小宽度 
								TimeShown : 1500,                 // 显示时间：毫秒 
								ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
								HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
								LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
								HorizontalPosition : "center",     // 水平位置:left, center, right 
								VerticalPosition : "center",     // 垂直位置：top, center, bottom 
								ShowOverlay : false,                // 是否显示遮罩层 
								ColorOverlay : "#000",            // 设置遮罩层的颜色 
								OpacityOverlay : 0.3            // 设置遮罩层的透明度 
							});
					}else{
						jError(json.msg,{
								autoHide : true,                // 是否自动隐藏提示条 
								clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
								MinWidth : 20,                    // 最小宽度 
								TimeShown : 1500,                 // 显示时间：毫秒 
								ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
								HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
								LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
								HorizontalPosition : "center",     // 水平位置:left, center, right 
								VerticalPosition : "center",     // 垂直位置：top, center, bottom 
								ShowOverlay : false,                // 是否显示遮罩层 
								ColorOverlay : "#000",            // 设置遮罩层的颜色 
								OpacityOverlay : 0.3            // 设置遮罩层的透明度 
							});
					}
					setTimeout(function(){
						window.location.href = 'm.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&type=show&from=list&version=mm';
					},1400)
				}
			})
	});
		} else { //固定流程
			var user_sel = '';
			$('.choice.sendee').each(function(i){
				var userSelId = $(this).attr('id');
				var userSelVal = $(this).children('.name').attr('data-uid') == undefined ? '' : $(this).children('.name').attr('data-uid');
				if(i==0){
					user_sel += userSelId + '=' + userSelVal;
				}else{
					user_sel += '|' + userSelId + '=' + userSelVal;
				}			
			})

			$("#myTableFrom").ajaxSubmit({
				dataType: 'json',
				type: 'post', 
				url: 'm.php?app=wf&func=flow&action=use&modul=new&task=getSendNextData&version=mm',
				data: {"uFlowId":uFlowId, "flowId":flowId, "step":step, "user_sel":user_sel},
				success: function(json){
					if(json.success){
						alertStepData(json.data);
					}else{
						jError(json.msg,{
							autoHide : true,                // 是否自动隐藏提示条 
							clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
							MinWidth : 20,                    // 最小宽度 
							TimeShown : 1500,                 // 显示时间：毫秒 
							ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
							HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
							LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
							HorizontalPosition : "center",     // 水平位置:left, center, right 
							VerticalPosition : "center",     // 垂直位置：top, center, bottom 
							ShowOverlay : false,                // 是否显示遮罩层 
							ColorOverlay : "#000",            // 设置遮罩层的颜色 
							OpacityOverlay : 0.3            // 设置遮罩层的透明度 
						});
					}
				}
			})
	}
	}

	//转下一步
	$(document).on('touchstart','#btnTurnNextStep',function(){
		$('#jingle_popup').slideUp(100);
		$('#jingle_popup_mask').hide();
		var flowName = $('#wfBasicInfo .flowName').val();
		if(isAllowBlank){
			if (flowName == '') {
				jNotify('请填写流程标题!',{
					autoHide : true,                // 是否自动隐藏提示条 
					clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
					MinWidth : 20,                    // 最小宽度 
					TimeShown : 1500,                 // 显示时间：毫秒 
					ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
					HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
					LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
					HorizontalPosition : "center",     // 水平位置:left, center, right 
					VerticalPosition : "center",     // 垂直位置：top, center, bottom 
					ShowOverlay : false,                // 是否显示遮罩层 
					ColorOverlay : "#000",            // 设置遮罩层的颜色 
					OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
				return false;
			};
		}
	  //检查必填控件是否为空，为空跳出函数体
	  if(myFns.inspectControl()){
	  	return false;
	  }
	  //检查控件类型是否为对应的类型
	  var typeRes = '';
	  typeRes = myFns.textTypeControl();
	  if(typeRes){
		  jNotify(typeRes,{
				autoHide : true,                // 是否自动隐藏提示条 
				clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
				MinWidth : 20,                    // 最小宽度 
				TimeShown : 1500,                 // 显示时间：毫秒 
				ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
				HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
				LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
				HorizontalPosition : "center",     // 水平位置:left, center, right 
				VerticalPosition : "center",     // 垂直位置：top, center, bottom 
				ShowOverlay : false,                // 是否显示遮罩层 
				ColorOverlay : "#000",            // 设置遮罩层的颜色 
				OpacityOverlay : 0.3            // 设置遮罩层的透明度 
			});
		return false;
	  }
	  var  detailtableLen = $("#detailtableGroup > table").length;
	  if (detailtableLen > 0) {
	  	if (myFns.wfDetailInspectControl()) {
	  		return false;
	  	};
	  };

	  var flowId = myFns.getUriString('flowId');
	  var uFlowId = 0, step = 0;
	  getNextStepData(uFlowId,flowId,step);
	  return false;
	})

	//关闭下一步办理人操作页
	$(document).on('touchstart','#btnClose',function(){
		$('#nextStepList').css('display','none');
		return false;
	})

	//下一步步骤选择
	$(document).on('click','#nextStepId .nextStepItem',function(){
		var _this = $(this);
		var nextStepId = $(_this).attr('data-stepid');
		$('.stepUserList').css('display','none');
		$('.uitem'+nextStepId).css('display','block');
		$('.rdolist').prop('checked',false);
		$('input.selectAllStepUser').prop('checked', false);
		$('.radio-btn-step').removeClass('checkedRadio');
		$('#nextStepId .nextStepItem').removeClass('selected');
		$(_this).addClass('selected');
		nextStepScroll.refresh();
	})

	//确定下一步
	$(document).on('touchstart','#btnMenu',function(){
		//var nextStepId = $('#nextStepId').val();
		//var isEnd = $('#nextStepId').find("option:selected").attr("isEnd");
		var nextStepId = $('#nextStepId .selected').attr('data-stepid');
		var isEnd = $('#nextStepId .selected').attr("data-isend");
		var dealway = $('#nextStepId .selected').attr('data-dealway'); //当前步骤是否支持多人办理
		var nextUid = '';
		if(isEnd!='true'){ //流程没结束
			var flag = false;
			$('#nextStepGroup .rdolist').each(function(i){
				var grouptype = $(this).closest('.stepUserList').attr('data-grouptype');
				var stepId = $(this).closest('.stepUserList').attr('data-stepid');
				if(grouptype == 'department'){ //部门下的用户
					if($(this).prop('checked')){
						if(!flag){
							flag = true;
							nextUid += stepId + ',' + $(this).val();
						}else{
							nextUid += ',' + stepId + ',' + $(this).val();
						}
					}
				}else if(grouptype == 'user'){ //直接用户`没有所在部门
					if($(this).prop('checked')){
						if (dealway == 1 || dealway == 'true') { //多人办理
							if(!flag){ 
								flag = true;
								nextUid += $(this).attr('data-stepid') + ',' + $(this).val();
							}else{
								nextUid += ',' + $(this).attr('data-stepid') + ',' + $(this).val();
							}
						} else { //单人办理
							if(!flag){
								flag = true;
								nextUid += $(this).val();
							}else{
								nextUid += ',' + $(this).val();
							}
						}
					}
				}
			})

			if(nextUid == ''){
				jNotify('请选择步骤办理人!',{
					autoHide : true,                // 是否自动隐藏提示条 
					clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
					MinWidth : 20,                    // 最小宽度 
					TimeShown : 1500,                 // 显示时间：毫秒 
					ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
					HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
					LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
					HorizontalPosition : "center",     // 水平位置:left, center, right 
					VerticalPosition : "center",     // 垂直位置：top, center, bottom 
					ShowOverlay : false,                // 是否显示遮罩层 
					ColorOverlay : "#000",            // 设置遮罩层的颜色 
					OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
				return false;
			}
		}

		//汇聚办理人
		var selitemObj = 'selitem'+nextStepId; //汇聚办理人HTML元素属性
		if($('.convergenenUname').hasClass(selitemObj)){ //存在当前步骤的汇聚办理人
			var opts = $('.'+selitemObj+' option').length;
			if(opts <= 1){
				jError('会聚节点无人员可供选择!',{
					autoHide : true,                // 是否自动隐藏提示条 
					clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
					MinWidth : 20,                    // 最小宽度 
					TimeShown : 1500,                 // 显示时间：毫秒 
					ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
					HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
					LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
					HorizontalPosition : "center",     // 水平位置:left, center, right 
					VerticalPosition : "center",     // 垂直位置：top, center, bottom 
					ShowOverlay : false,                // 是否显示遮罩层 
					ColorOverlay : "#000",            // 设置遮罩层的颜色 
					OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
				return false;
			}
			var convergenenUid = $('.'+selitemObj).val();
			if(convergenenUid == 0){ 
				jNotify('请选汇聚步骤办理人!',{
					autoHide : true,                // 是否自动隐藏提示条 
					clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
					MinWidth : 20,                    // 最小宽度 
					TimeShown : 1500,                 // 显示时间：毫秒 
					ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
					HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
					LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
					HorizontalPosition : "center",     // 水平位置:left, center, right 
					VerticalPosition : "center",     // 垂直位置：top, center, bottom 
					ShowOverlay : false,                // 是否显示遮罩层 
					ColorOverlay : "#000",            // 设置遮罩层的颜色 
					OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
				return false;
			}
		}

		//附件信息
		var upload_attach = '';
		$("input[name='filesUpload[]']").each(function(i){
			if(i==0){
				upload_attach += '0' + $(this).val();
			}else{
				upload_attach += ',' + '0' + $(this).val();
			}
		})
		
		//流程表单参数
		var flowId 	= myFns.getUriString('flowId');
		var uStepId = 0, uFlowId = 0;
		$("#myTableFrom").ajaxSubmit({
			dataType: 'json',
			type: 'post', 
			url: 'm.php?app=wf&func=flow&action=use&modul=new&task=sendNextStep&version=mm',
			data: {
				"nextStepId": nextStepId, "nextUid": nextUid, "uFlowId": uFlowId,
				"flowId":flowId, "uStepId":uStepId, "convergenenUid":convergenenUid,
				"upload_attach":upload_attach
			},
			beforeSend: function() {
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				hideLoading();
				if(json.success){
					jSuccess(json.msg,{
						autoHide : true,                // 是否自动隐藏提示条 
						clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
						MinWidth : 20,                    // 最小宽度 
						TimeShown : 1500,                 // 显示时间：毫秒 
						ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
						HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
						LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
						HorizontalPosition : "center",     // 水平位置:left, center, right 
						VerticalPosition : "center",     // 垂直位置：top, center, bottom 
						ShowOverlay : false,                // 是否显示遮罩层 
						ColorOverlay : "#000",            // 设置遮罩层的颜色 
						OpacityOverlay : 0.3            // 设置遮罩层的透明度 
					});
				}else{
					jError(json.msg,{
						autoHide : true,                // 是否自动隐藏提示条 
						clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
						MinWidth : 20,                    // 最小宽度 
						TimeShown : 1500,                 // 显示时间：毫秒 
						ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
						HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
						LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
						HorizontalPosition : "center",     // 水平位置:left, center, right 
						VerticalPosition : "center",     // 垂直位置：top, center, bottom 
						ShowOverlay : false,                // 是否显示遮罩层 
						ColorOverlay : "#000",            // 设置遮罩层的颜色 
						OpacityOverlay : 0.3            // 设置遮罩层的透明度 
					});
				}
				setTimeout(function(){
					window.location.href = 'm.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&type=show&from=list&version=mm';
				},1400)
			}
		})
	})

	//弹出下一步办理人操作页
	function alertStepData(data){
		if (userAgent == 'ios') { //ios需要在弹出的层加绝对定位才能滚动
			$('.fenfa-wrapper,.fenfa-scroller').css('position','absolute');
		} else {
			$('.fenfa-wrapper,.fenfa-scroller').css('position','');
		}
		if(data.length < 1){
			jError('没有下一步办理人',{
				autoHide : true,                // 是否自动隐藏提示条 
				clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
				MinWidth : 20,                    // 最小宽度 
				TimeShown : 1500,                 // 显示时间：毫秒 
				ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
				HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
				LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
				HorizontalPosition : "center",     // 水平位置:left, center, right 
				VerticalPosition : "center",     // 垂直位置：top, center, bottom 
				ShowOverlay : false,                // 是否显示遮罩层 
				ColorOverlay : "#000",            // 设置遮罩层的颜色 
				OpacityOverlay : 0.3            // 设置遮罩层的透明度 
			});
			return false;
		}

		$('#nextStepList').css('display','block'); //下一步骤办理人model
		$('.modalTitle').html('选择步骤及办理人');
		$('#nextStepGroup').empty();
		var stepStr  = '<ul class="list-group" id="nextStepId">';
		stepStr += '<li class="list-group-item active nextStepTitle">请选择要转到的步骤</li>';
		$.each(data, function(index, array){
			stepStr += '<li class="list-group-item nextStepItem '+(index==0?'selected':'')+'" data-isend="'+array['isEnd']+'" data-dealWay="'+array['dealWay']+'" data-stepid="'+array['stepId']+'">'+array['stepName']+'</li>';
		})
		stepStr += '</ul>';
		$('#nextStepGroup').append(stepStr);

		//当前select控件步骤id
		var nextStepId = $('#nextStepId').children('.selected').attr('data-stepid');

		var departmentStr = '';
		var operator1Str = '';
		$.each(data, function(index,array){
			//部门中的子部门
			var child = array['child'];
			if(child.length > 0){
				$.each(child,function(i,arr){
					if(!arr['isEnd']){
						departmentStr += '<ul class="list-group stepUserList '+(array['stepId']?('uitem'+array['stepId']):'')+'" data-stepid="'+arr['stepId']+'" data-dealWay="'+array['dealWay']+'" data-grouptype="department">';
						departmentStr += '<li class="list-group-item list-group-item-info"><span class="department" data-stepId='+arr['stepId']+'>'+arr['stepName']+'</span>'+(array['dealWay'] !=1 ? '' : '<span class="selectAllStepUserBox"><input type="checkbox" class="selectAllStepUser"></span>')+'</li>';
						var operator = arr['operator'];
						if(operator.length > 0){
							$.each(operator,function(j,optArr){
								departmentStr += '<li class="list-group-item stepUser"><span class="truename">'+optArr['name']+'</span>';
								departmentStr += '<span class="radio-box-step"><lable class="radio-btn-step"><i></i>';
								departmentStr += '<input type="radio" class="rdolist nextUid" value="'+optArr['uid']+'">';
								departmentStr += '</lable></span></li>';
							})
						}
						departmentStr += '</ul>';
					}
				})
	$('#nextStepGroup').append(departmentStr);
	}

			//直接办理人
			var operator1 = array['operator'];
			if(operator1.length > 0){
				operator1Str  = '<ul class="list-group stepUserList '+(array['stepId']?('uitem'+array['stepId']):'')+'" data-dealWay="'+array['dealWay']+'" data-grouptype="user">';
				operator1Str += '<li class="list-group-item list-group-item-info"><span>办理人员</span>'+(array['dealWay'] !=1 ? '' : '<span class="selectAllStepUserBox"><input type="checkbox" class="selectAllStepUser"></span>')+'</li>';
				$.each(operator1,function(k,optArr1){
					operator1Str += '<li class="list-group-item stepUser"><span class="truename">'+optArr1['name']+'</span>';
					operator1Str += '<span class="radio-box-step"><lable class="radio-btn-step"><i></i>';
					operator1Str += '<input type="radio" class="rdolist nextUid" data-stepid="'+optArr1['uid']+'" value="'+optArr1['uid']+'">';
					operator1Str += '</lable></span></li>';
				})
				operator1Str += '</ul>';
				$('#nextStepGroup').append(operator1Str);
			}

			//会聚步骤办理人
			if(array['convergenenUname']){
				var convergenenUname = array['convergenenUname'];
				var convergenenStr  = '<div class="form-group has-error stepUserList '+(array['stepId']?('uitem'+array['stepId']):'')+'"><select class="form-control stepUserList fch convergenenUname '+(array['stepId']?('uitem'+array['stepId']):'')+' '+(array['stepId']?('selitem'+array['stepId']):'')+'">';
				convergenenStr += '<option value="0">请选择会聚步骤办理人</option>';
				$.each(convergenenUname,function(f,ogArr){
					if(ogArr['uid'] != 0){ //排除掉不是办理人的控件
						convergenenStr += '<option value="'+ogArr['uid']+'">'+ogArr['name']+'</option>';
					}
				})
				convergenenStr += '</div>';
				$('#nextStepGroup').append(convergenenStr);
			}
		})

		//显示隐藏当前步骤人员
		$('.uitem'+nextStepId).css('display','block');
		nextStepScroll.refresh();
	}

	//弹出发起自由顺序流程`设置流程步骤办理人
	$(document).on('click', '#flowStepBox input[name=uname]', function(){
		if (userAgent == 'ios') { //ios需要在弹出的层加绝对定位才能滚动
			$('.other-wrapper,.other-scroller').css('position','absolute');
		} else {
			$('.other-wrapper,.other-scroller').css('position','');
		}
		$('#otherGroup').empty();
		var htmlStr  = '<span class="glyphicon glyphicon-plus btn-all sendee " id="oAddUser" data-belong="flowStep" data-name="oAddUser" data-readonly="false" data-datatype="user_sel" data-checktype="true" data-toggle="modal" data-target="#userDataModal"></span>';
		htmlStr += '<span class="glyphicon glyphicon-ok btn-all" id="oFinish" aria-hidden="true"></span>';
		$('#otherList .modal-header').append(htmlStr);

		var transactorBodyHtml  = '<div class="table-responsive"><table class="table">';
		transactorBodyHtml += '<thead class="transactorHead" id="transactorHead"><tr><th>#</th> <th>办理人员</th><th>步骤名称</th><th>操作</th></tr></thead>';
		transactorBodyHtml += '<tbody class="transactorBody" id="transactorBody"></tbody></table></div>';
		$('#otherGroup').append(transactorBodyHtml);
		$('#otherList').css('display','block');
		initStepUserListFns(); //初始化流程步骤办理人列表数据
	})

	//关闭发起自由顺序流程`设置流程步骤办理人
	$(document).on('touchstart', '#btnOTClose', function(){
		$('otherList .modalTitle').html('流程步骤办理人员');
		$('#oFinish,#oAddUser').remove(); //删除完成按钮
		$('#otherList').css('display','none');
		return false;
	})

	//发起自由顺序流程`设置流程步骤办理人`完成按钮
	$(document).on('click','#oFinish', function(){
		$('otherList .modalTitle').html('流程步骤办理人员');
		$('#oFinish,#oAddUser').remove(); //删除完成按钮
		$('#otherList').css('display','none');
		setFlowStepUserFns();
		return false;
	})

	//点击弹出已选的步骤办理人初始化列表
	function initStepUserListFns(){
		var getname = uid = '';
		getname = $('#flowStepBox input[name=getname]').val();
		uid = $('#flowStepBox input[name=uid]').val();
		if (uid != '') {
			uid = uid.split(",");
			getname = getname.split(",");
		};
		var stepUserArr = [];
		for (var i = 0; i < uid.length; i++) {
			var stepUserObj = {'uid':uid[i], "getname":getname[i]};
			stepUserArr.push(stepUserObj);
		};

		//把数据追加到步骤办理人列表
		var transactorBody = $('#transactorBody');
		$.each(stepUserArr, function(index, array){
			var htmlStr  = '<tr uid="'+array['uid']+'" name="'+array['getname']+'"><td>'+(index+1)+'</td><td>'+array['getname']+'</td><td>'+array['getname']+'(办理)</td>';
			htmlStr += '<td><button type="button" class="btn btn-default btn-sm delStepUser">';
			htmlStr += '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td></tr>';
			transactorBody.append(htmlStr);
		})
	}

	//把已经选好的流程步骤办理人追加到指定区域
	function setFlowStepUserFns(){
		var trRows = $('#transactorBody tr');
		var uname = getname = uid = stepname = '';

		if (trRows.length == 1) {
			uname += '起始人[' + trRows.attr('name') + ']结束人';
			getname += ''+trRows.attr('name')+'';
			uid += ''+trRows.attr('uid')+'';
			stepname += ''+trRows.attr('name')+'(办理)'; 
		} else {
			trRows.each(function(index, element){
				if (index == 0) { //第一个
					uname += '起始人[' + $(element).attr('name') + ']->';
					getname += ''+$(element).attr('name')+',';
					uid += ''+$(element).attr('uid')+',';
					stepname += ''+$(element).attr('name')+'(办理),'; 
				} else if (index == (trRows.length-1)) { //最后一个
					uname += '[' + $(element).attr('name') + ']结束人';
					getname += ''+$(element).attr('name')+'';
					uid += ''+$(element).attr('uid')+'';
					stepname += ''+$(element).attr('name')+'(办理)'; 
				} else {
					uname += '['+$(element).attr('name')+']->';
					getname += ''+$(element).attr('name')+',';
					uid += ''+$(element).attr('uid')+',';
					stepname += ''+$(element).attr('name')+'(办理),'; 
				}
			})
		}
		
		$('#flowStepBox input[name=uname]').val(uname);
		$('#flowStepBox input[name=getname]').val(getname);
		$('#flowStepBox input[name=uid]').val(uid);
		$('#flowStepBox input[name=stepname]').val(stepname);
	}

	//触发人员选择器
	$(document).on('click','#modSendee,#freeFlowStepDeal',function(){
		var source = $(this).attr('data-name') == undefined ? '': $(this).attr('data-name');
		$('#userDataModal').attr('data-belong','freeFlowStepDeal');
		if (source != '') {
			$('#userDataModal').attr('data-source', source);
		} else {
			$('#userDataModal').attr('data-source','modSendee');
		}
		
		var checktype = $(this).attr('data-checktype');
		$('#userDataModal').attr('data-checktype', checktype);
		var uid  = $(this).children('.name').attr('data-uid');
		var name = $(this).children('.name').text();
		var face = $(this).children('.name').attr('data-face');
		if(face == undefined){ //默认头像
			face = 'file/common/face/default-face.jpg';
		}
		if(uid != undefined){
			var userObj = {'uid':uid, "name":name, "face":face};
			sendeeArr.push(userObj);
		};
	  //请求人员数据
	  getSelectorData();
	})

	//触发人员选择器
	$(document).on('click', '.sendee', function(){
		if (userAgent == 'ios') { //ios需要在弹出的层加绝对定位才能滚动
			$('.userDataWrapper,.userDataScroller').css('position','absolute');
		} else {
			$('.userDataWrapper,.userDataScroller').css('position','');
		}
		if($(this).attr('data-readonly') == 'true'){ //TRUE 只读 FALSE 可选择
			return false;
		}	
		var source = $(this).attr('data-name');
		$('#userDataModal').attr('data-source',source);
		var checktype = $(this).attr('data-checktype'); //判断当前控件为单选还是多选
		$('#userDataModal').attr('data-checktype',checktype); //类型标记在人员选择器userDataModal之上

		if ($(this).attr('data-belong') == 'flowStep') { //自由流程步骤办理人
			$('#userDataModal').attr('data-belong', 'flowStep'); //标记是哪里使用这个选择器
			getSelectorData();
			return false;
		} else { //其他通用人员选择器 
			var uids  = $(this).find('.name').attr('data-uid') == undefined ? '' : $(this).find('.name').attr('data-uid');
			var names = $(this).find('.name').text() == undefined ? '' : $(this).find('.name').text();
			var faces = $(this).find('.name').attr('data-face') == undefined ? '' : $(this).find('.name').attr('data-face');
		}

		if(uids == ''){ //没有已选的人员
			sendeeArr = [];
		}else{
			if ($('#userDataModal').attr('data-belong') != 'flowStep') {
	  		var uidArr  = uids.split(',').reverse();  //注意：后台处理数据时候把顺序反了，妈的
	  	}
	  	var uidArr  = uids.split(',');
	  	var nameArr = names.split(',');
	  }
	  if(faces == ''){
	  	var faceArr = [];
	  }else{
	  	var faceArr = faces.split(',');
	  }
	  if(uids != ''){
	  	for (var i = 0; i < uidArr.length; i++) {
	  		if(faceArr[i] == '' || faceArr[i] == undefined){
	  			faceArr[i] = 'file/common/face/default-face.jpg';
	  		}
	  		var userObj = {'uid':uidArr[i], 'name':nameArr[i], 'face':faceArr[i]};
	  		sendeeArr.push(userObj);
	  	};
	  }
	  //请求人员数据
	  getSelectorData();
	})

	//请求人员选择器数据
	function getSelectorData(){
		$.ajax({
			dataType: "json",
			type: "get",
			url: "m.php?action=commonJob&task=getSelectorData&target=getUserDatamm",
			beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				hideLoading();
				if(json.success){ //请求成功
					if(json.paterId == 0){ //上级部门id
						$('#sback').css('display','none');
					}else{
						$('#sback').css('display','block');
					}
					var data  	= json.data;
					var users 	= data.users; //人员数据
					var structs = data.structs; //部门数据
					if(users.length == 0 && structs.length == 0){
						jNotify('没有检索到数据!',{
							autoHide : true,                // 是否自动隐藏提示条 
							clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
							MinWidth : 20,                    // 最小宽度 
							TimeShown : 1500,                 // 显示时间：毫秒 
							ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
							HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
							LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
							HorizontalPosition : "center",     // 水平位置:left, center, right 
							VerticalPosition : "center",     // 垂直位置：top, center, bottom 
							ShowOverlay : false,                // 是否显示遮罩层 
							ColorOverlay : "#000",            // 设置遮罩层的颜色 
							OpacityOverlay : 0.3            // 设置遮罩层的透明度 
						});

						return false;
					} 
					$('#sback').attr('data-fid',json.paterId); //有数据才改变上级部门id
					$('#userDataGroup').empty();//清空数据区
					if(users.length > 0){ //处理人员数据
						$.each(users, function(i,arr){
							var userStr  = '<li class="list-group-item userList" id="uid-'+arr['uid']+'" data-uid="'+arr['uid']+'" data-name="'+arr['truename']+'" data-face="'+arr['face']+'">';
							userStr += '<span class="tools pull-left">';
							userStr += '<input type="checkbox" class="ipt-hide">';
							userStr += '<label class="checkbox"></label>';
							userStr += '</span>';
							userStr += '<div class="name-box">';
							userStr += '<img src="'+arr['face']+'" class="img-circle face">';
							userStr += '<span class="truename">'+arr['truename']+'</span>';
							userStr += '</div>';
							userStr += '<span class="job">'+arr['jobName']+'</span>';
							userStr += '</li>';
							$('#userDataGroup').append(userStr);
						}) 
						//自动勾选
						setChecked('userList', sendeeArr, 'user');            
					}
					if(structs.length > 0){ //处理部门数据
						$.each(structs, function(index,array){
							var structStr  = '<li class="list-group-item structList" data-sid="'+array['structId']+'">';
							structStr += '<span class="fs1 struct-ico-left" aria-hidden="true" data-icon="&#xe109;"></span>';
							structStr += '<div class="name-box">';
							structStr += '<span class="struct">'+array['structName']+'</span></div>';
							structStr += '<div class="fs1 struct-ico-right" aria-hidden="true" data-icon="5"></div>';
							structStr += '<div class="userTotal">'+array['userTotal']+'</div>';
							structStr += '</li>';
							$('#userDataGroup').append(structStr);  	
						})
					}
				}
				//已选人员区域操作
				setUserOpts();

				//重新调整页面
				userDataScroll.refresh(); 
			}
		})
	}

	/*                             
	 *  方法:viewUserData(structId)      
	 *  功能:加载用户和部门数据.         
	 *  参数:部门ID.     
	 */
	 function viewUserData(structId){
	 	$.ajax({
	 		dataType: "json",
	 		type: "post",
	 		url: "m.php?action=commonJob&task=getSelectorData&target=getUserDatamm",
	 		data: {'structId':structId},
	 		beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				hideLoading();
				if(json.success){ //请求成功
					if(json.paterId == 0){ //上级部门id
						$('#sback').css('display','none');
					}else{
						$('#sback').css('display','block');
					}
					if(json.msg != 0){
						$('#userDataModal').attr('data-sid', json.msg);
					}else{
						if(json.paterId == 1){
							$('#sback').css('display','none');
						};
					}
					var data  	= json.data;
					var users 	= data.users; //人员数据
					var structs = data.structs; //部门数据
					if(users.length == 0 && structs.length == 0){
						jNotify('没有检索到数据!',{
							autoHide : true,                // 是否自动隐藏提示条 
							clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
							MinWidth : 20,                    // 最小宽度 
							TimeShown : 1500,                 // 显示时间：毫秒 
							ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
							HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
							LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
							HorizontalPosition : "center",     // 水平位置:left, center, right 
							VerticalPosition : "center",     // 垂直位置：top, center, bottom 
							ShowOverlay : false,                // 是否显示遮罩层 
							ColorOverlay : "#000",            // 设置遮罩层的颜色 
							OpacityOverlay : 0.3            // 设置遮罩层的透明度 
						});
						return false;
					} 
					$('#sback').attr('data-fid',json.paterId); //有数据才改变上级部门id
					$('#userDataGroup').empty();//清空数据区
					if(users.length > 0){ //处理人员数据
						$.each(users, function(i,arr){
							var userStr  = '<li class="list-group-item userList" id="uid-'+arr['uid']+'" data-uid="'+arr['uid']+'" data-name="'+arr['truename']+'" data-face="'+arr['face']+'">';
							userStr += '<span class="tools pull-left">';
							userStr += '<input type="checkbox" class="ipt-hide">';
							userStr += '<label class="checkbox"></label>';
							userStr += '</span>';
							userStr += '<div class="name-box">';
							userStr += '<img src="'+arr['face']+'" class="img-circle face">';
							userStr += '<span class="truename">'+arr['truename']+'</span>';
							userStr += '</div>';
							userStr += '<span class="job">'+arr['jobName']+'</span>';
							userStr += '</li>';
							$('#userDataGroup').append(userStr);
						}) 
						//自动勾选
						setChecked('userList', sendeeArr, 'user');            
					}
					if(structs.length > 0){ //处理部门数据
						$.each(structs, function(index,array){
							var structStr  = '<li class="list-group-item structList" data-sid="'+array['structId']+'">';
							structStr += '<span class="fs1 struct-ico-left" aria-hidden="true" data-icon="&#xe109;"></span>';
							structStr += '<div class="name-box">';
							structStr += '<span class="struct">'+array['structName']+'</span></div>';
							structStr += '<div class="fs1 struct-ico-right" aria-hidden="true" data-icon="5"></div>';
							structStr += '<div class="userTotal">'+array['userTotal']+'</div>';
							structStr += '</li>';
							$('#userDataGroup').append(structStr);  	
						})
					}	
				}
				//已选人员区域操作
				setUserOpts();

				//重新调整页面
				userDataScroll.refresh(); 
			}
		})
	}

	//进入下一级部门
	$(document).on('click','.structList',function(){
		var structId = $(this).attr('data-sid');
		viewUserData(structId);
	})

	//返回上一级部门
	$(document).on('click','#sback',function(){
		$('#search').val(''); //清空搜索框
		var structId = $(this).attr('data-fid'); //部门id
		if(structId == 0){
			return false;
		}
		viewUserData(structId);
	})

	//关闭人员选择器
	$(document).on('touchstart','#sClose',function(){
		initPickerFns();
		sendeeArr = []; //切记`关闭选择器时需重置全局数组
	});

	//完成人员选择
	$(document).on('click','#sFinish',function(){
		var type = $('#btnAdd').attr('data-type');
		var belong = $('#userDataModal').attr('data-belong');
		if(type === 'fenfa'){ //分发方式
			var uFlowId = myFns.getUriString('uFlowId');
			var stepId = myFns.getUriString('step');
			var toUids = '';
			for (var i = 0; i < sendeeArr.length; i++) {
				if (i == 0) {
					toUids += sendeeArr[i].uid;
				}else {
					toUids += ',' + sendeeArr[i].uid;
				}
			};
			if(toUids == ''){
				return false;
			}
			addFenfaFns(uFlowId,stepId,toUids);
		}else if(type === 'huiqian'){ //会签
			var uFlowId = myFns.getUriString('uFlowId');
			var stepId = myFns.getUriString('step');
			var huiqianUids = '';
			var huiqianNames = '';
			for (var i = 0; i < sendeeArr.length; i++) {
				if (i == 0) {
					huiqianUids  += sendeeArr[i].uid;
					huiqianNames += sendeeArr[i].name;
				}else {
					huiqianUids  += ',' + sendeeArr[i].uid;
					huiqianNames += ',' + sendeeArr[i].name;
				}
			};
			if(huiqianUids == ''){
				return false;
			}
			submitHuiQianInfoFns(uFlowId,stepId,huiqianUids,huiqianNames);
		}else{ //其他方式
			if (belong == 'flowStep') { //自由顺序流程处选择步骤办理人
				freeFlowSetData();
			} else { 
				if (belong == 'freeFlowStepDeal') { //自由流程选择步骤办理人
					var stepname = sendeeArr[0].name;
					if (stepname != '') {
						stepname += '(办理)';
					};
					$('.sweet-alert fieldset input').val(stepname);
				}
				recordData(); //其他通用方式
			}
		}

		initPickerFns(); //删除选择器弹框动态附上去的属性`恢复到原始状态

		sendeeArr = []; //切记`关闭选择器时需重置全局数组
	})

	//人员选择器`恢复HTML原始格式
	function initPickerFns(){
		$('#userDataModal').removeAttr('data-belong');
	}

	//自由顺序流程`把数据记录到指定位置
	function freeFlowSetData(){
		var source = $('#userDataModal').attr('data-source');
		if ( source == 'oAddUser') { //设置流程步骤办理人
			var transactorBody = $('#transactorBody');
			for (var i = 0; i < sendeeArr.length; i++) {
				var thLength = transactorBody.children().length;
				var htmlStr  = '<tr uid="'+sendeeArr[i].uid+'" name="'+sendeeArr[i].name+'"><td>'+((thLength+1) > 0 ? (thLength+1) : (i+1))+'</td>';
				htmlStr += '<td>'+sendeeArr[i].name+'</td>';
				htmlStr += '<td>'+(sendeeArr[i].name+'(办理)')+'</td>';
				htmlStr += '<td><button type="button" class="btn btn-default btn-sm delStepUser">';
				htmlStr += '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>';
				htmlStr += '</button></td></tr>';
				transactorBody.append(htmlStr);
			};
		};
		otherScroll.refresh();
	}

	//删除自由顺序流程已选的人员
	$(document).on('click','.delStepUser', function(){
		var row = $(this).closest('tr');
		row.remove();
		reorderingFns();
	});

	//流程步骤办理人列表重新排序编号
	function reorderingFns(){
		var trs = $('#transactorBody > tr');
		var trsLen = trs.length;
		for (var i = 0; i < trsLen; i++) {
			trs.eq(i).children().first().html(i+1);
		};
		otherScroll.refresh();
	}

	//完成人员选择`把数据记录到指定位置
	function recordData(){ 
		var source = $('#userDataModal').attr('data-source'); //要把数据到的位置
		$('#' + source).empty(); //先清空数据区再遍历数据
		var uids  = ''; //用户id
		var names = ''; //用户名
		var faces = ''; //用户头像
		//把存放在全局数组中的人员数据遍历到指定位置
		for (var i = 0; i < sendeeArr.length; i++) {
			if (i == 0) {
				uids  += sendeeArr[i].uid;
				names += sendeeArr[i].name;
				faces += sendeeArr[i].face;
			}else {
				uids  += ',' + sendeeArr[i].uid;
				names += ',' + sendeeArr[i].name;
				faces += ',' + sendeeArr[i].face;
			}
		};
		var userStr = '<span class="name" data-uid="'+uids+'" data-face="'+faces+'">'+names+'</span>';
		$('#' + source).append(userStr);
		$('#'+source).next().val(uids); //隐藏input赋值
	}

	//按钮被松开时触发搜索人员事件
	$(document).on('keyup','#search',getSearchUserData);

	function getSearchUserData(){
		var search = $('#search').val();
		if(search != ''){ 
			var httpUrl = 'm.php?action=commonJob&task=getSelectorData&target=getUserDatamm&search='+search;
		}else{
			var structId = $('#userDataModal').attr('data-sid');
			var httpUrl = 'm.php?action=commonJob&task=getSelectorData&target=getUserDatamm&structId='+structId;
		}
		$.ajax({
			dataType: "json",
			type: "post",
			url: httpUrl,
			beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				hideLoading();
				$('#userDataGroup').empty();//清空数据区
				if(json.success){
					var data  = json.data;
					var users = data.users; //人员数据
					if(users.length > 0){
						$.each(users,function(i, arr){
							var userStr  = '<li class="list-group-item userList" id="uid-'+arr['uid']+'" data-uid="'+arr['uid']+'" data-name="'+arr['truename']+'" data-face="'+arr['face']+'">';
							userStr += '<span class="tools pull-left">';
							userStr += '<input type="checkbox" class="ipt-hide">';
							userStr += '<label class="checkbox"></label>';
							userStr += '</span>';
							userStr += '<div class="name-box">';
							userStr += '<img src="'+arr['face']+'" class="img-circle face">';
							userStr += '<span class="truename">'+arr['truename']+'</span>';
							userStr += '</div>';
							userStr += '<span class="job">'+arr['jobName']+'</span>';
							userStr += '</li>';
							$('#userDataGroup').append(userStr);
						})
						//自动扣选
						setChecked('userList', sendeeArr, 'user');
					}

					if(data.hasOwnProperty('structs')){
						var structs = data.structs; //部门数据
						if(structs.length > 0){ //处理部门数据
							$.each(structs, function(index,array){
								var structStr  = '<li class="list-group-item structList" data-sid="'+array['structId']+'">';
								structStr += '<span class="fs1 struct-ico-left" aria-hidden="true" data-icon="&#xe109;"></span>';
								structStr += '<div class="name-box">';
								structStr += '<span class="struct">'+array['structName']+'</span></div>';
								structStr += '<div class="fs1 struct-ico-right" aria-hidden="true" data-icon="5"></div>';
								structStr += '<div class="userTotal">'+array['userTotal']+'</div>';
								structStr += '</li>';
								$('#userDataGroup').append(structStr);  	
							})
						}
					};
				}

				//已选人员区域操作
				setUserOpts();

				//重新调整页面
				userDataScroll.refresh(); 
			}
		})
	}

	/**
	 *	方法: setChecked(element)
	 *	功能: 自动扣选
	 *	参数1: <string> element 需要遍历的元素 
	 *	参数2: <array> 存放已扣选的数据`数组 
	 *	参数3: <string> choiceType 选择器类型
	 */
	 function setChecked(element,dataArr,choiceType){
		if(choiceType == 'user'){ //人员选择器
			$('.'+element+' :checkbox').each(function(){
				var uid = $(this).closest('.'+element).attr("data-uid"); //选择框当前行的员工id
				var _this = $(this);
				if(dataArr.length > 0){
					for (var i = 0; i < dataArr.length; i++) {
						if (dataArr[i].uid == uid) {
							$(_this).prop("checked", true);
							$(_this).siblings('.checkbox').addClass('cur');
							break;
						}
					}
				}
			})
		}else if(choiceType == 'job'){ //职位选择器
			$('.'+element+' :checkbox').each(function(){
				var jid = $(this).closest('.'+element).attr("data-jid"); //选择框当前行的员工id
				var _this = $(this);
				if(dataArr.length > 0){
					for (var i = 0; i < dataArr.length; i++) {
						if (dataArr[i] == jid) {
							$(_this).prop("checked", true);
							$(_this).siblings('.checkbox').addClass('cur');
							break;
						}
					}
				}
			})
		}else if(choiceType == 'station'){ //岗位选择器
			$('.'+element+' :checkbox').each(function(){
				var sid = $(this).closest('.'+element).attr("data-sid"); //选择框当前行的员工id
				var _this = $(this);
				if(dataArr.length > 0){
					for (var i = 0; i < dataArr.length; i++) {
						if (dataArr[i] == sid) {
							$(_this).prop("checked", true);
							$(_this).siblings('.checkbox').addClass('cur');
							break;
						}
					}
				}
			})
		}else if(choiceType == 'dept'){ //部门选择器
			$('.'+element+' :checkbox').each(function(){
				var did = $(this).closest('.'+element).attr("data-did"); //选择框当前行的员工id
				var _this = $(this);
				if(dataArr.length > 0){
					for (var i = 0; i < dataArr.length; i++) {
						if (dataArr[i] == did) {
							$(_this).prop("checked", true);
							$(_this).siblings('.checkbox').addClass('cur');
							break;
						}
					}
				}
			})
		}
	}

	//删除人员操作区域
	function setUserOpts(){
		$('#userGroup').empty();
		var sendeeArrLength = sendeeArr.length;
		if (sendeeArrLength > 0) {
			for (var i = 0; i < sendeeArrLength; i++) {
				var faceStr = '<li class="delFace" data-uid="'+sendeeArr[i].uid+'" data-name="'+sendeeArr[i].name+'"><img src="'+sendeeArr[i].face+'" class="img-circle face"></li>';
				$('#userGroup').append(faceStr);
			};
			$('#sFinish').prop('disabled', false);
			$('#sFinish').html('完成(' + sendeeArrLength + ')');
		}else { //是否满足完成操作
			$('#sFinish').prop('disabled', true);
			$('#sFinish').html('完成');
		}
		var userScrollerW = $('#userGroup li').length * $('#userGroup li').width(); //userScroller宽度
		$('#userScroller').css('width', userScrollerW);
		userScroll.refresh();
	}

	//checkbox扣选
	$(document).on('click','.userList',function(){
		//触发事件只能单选
		var source = $("#userDataModal").attr("data-source"); //请求来源
		var checktype = $("#"+source).attr('data-checktype'); //checkbox类型：false单选 、true多选
		if(checktype == "false"){ //checktype：false`单选  true`多选
			checkOnly(this, 'userList');
		}
		if($(this).find('.ipt-hide').prop('checked')){
			$(this).find('.checkbox').removeClass('cur');
			$(this).find('.ipt-hide').prop('checked', false)
		}else{
			$(this).find('.checkbox').addClass('cur');
			$(this).find('.ipt-hide').prop('checked', true)
		}
	  //改变存放人员的数组
	  changeSendeeArr(this);
	  //删除人员区域设置
	  setUserOpts();
	})

	//点击删除人员区域头像
	$(document).on('click','.delFace',function(){
		var uid = $(this).attr('data-uid');
		var userListId = 'uid-' + uid;
		$('#' + userListId).find('.ipt-hide').prop('checked', false);
		$('#' + userListId).find('.checkbox').removeClass('cur');
		$(this).remove(); //删除当前用户头像
		for (var i = 0; i < sendeeArr.length; i++) {
			if (sendeeArr[i].uid == uid) {
				sendeeArr.remove(i); //删除全局数组中存放着的用户
			}
		}
		if(sendeeArr.length > 0){
			$('#sFinish').prop('disabled', false);
			$('#sFinish').html('完成(' + sendeeArr.length + ')');
		}else{
			$('#sFinish').prop('disabled', true);
			$('#sFinish').html('完成');
		}
		var userScrollerW = $('#userGroup li').length * $('#userGroup li').width(); //userScroller宽度
		$('#userScroller').css('width', userScrollerW);
		userScroll.refresh();
	})

	/**
	 *	方法: checkOnly(obj,element)
	 *	功能: 限制只能单选
	 *	参数: <Object> obj 当前鼠标点击的HTML元素对象 <string> element 需要遍历的元素
	 */
	 function checkOnly(obj, element){
	 	$('.'+element).each(function(){
	 		if (this != obj){
	 			$(this).find('.checkbox').removeClass('cur');
	 			$(this).find('.ipt-hide').prop('checked', false)
	 		}else{
	 			if($(this).find('.ipt-hide').prop('checked')){
	 				$(this).find('.ipt-hide').prop('checked', true)
	 				$(this).find('.checkbox').addClass('cur');
	 			}else{
	 				$(this).find('.checkbox').removeClass('cur');
	 				$(this).find('.ipt-hide').prop('checked',false)
	 			}
	 		}
	 	})
	 }

	//改变存放人员的全局变量对象
	function changeSendeeArr(userList){
		var uid  = $(userList).attr('data-uid');
		var name = $(userList).attr('data-name');
		var face = $(userList).attr('data-face');
		var userObj = {'uid':uid, 'name':name, 'face':face};
		var chk  = $(userList).find('.ipt-hide');
		var chkStu = $(chk).prop('checked'); //状态
		if($('#userDataModal').attr('data-checktype') == 'true'){ //允许多选
			if(chkStu){ //扣选
				var flag = false;
				for (var i = 0; i < sendeeArr.length; i++) {
					if (sendeeArr[i].uid == uid) {
						flag = true;
						break;
					}
				}
				if(!flag){ //不存在
					sendeeArr.push(userObj);
				}
			}else{ //未扣选
				for (var i = 0; i < sendeeArr.length; i++) {
					if (sendeeArr[i].uid == uid) {
						sendeeArr.remove(i);
					}
				}
			}
		}else{ //只允许单选
			if(chkStu){ //扣选
				sendeeArr = [];
				sendeeArr.push(userObj);
			}else{ 
				sendeeArr = [];
			}
		}
	}

	//岗位选择器
	$(document).on('click','.stationChoice',function(){
		if (userAgent == 'ios') { //ios需要在弹出的层加绝对定位才能滚动
			$('.stationDataWrapper,.stationDataScroller').css('position','absolute');
		} else {
			$('.stationDataWrapper,.stationDataScroller').css('position','');
		}
		if($(this).attr('data-readonly') == 'true'){ //TRUE 只读 FALSE 可选择
			return false;
		}	
		$('.choiceName').html('请选择岗位');
		$('#stationDataModal').attr('data-choicetype','stationChoice'); //标记为岗位选择器
		var source = $(this).attr('data-name');
		$('#stationDataModal').attr('data-source',source);
		var checktype = $(this).attr('data-checktype'); //判断当前控件为单选还是多选
		$('#stationDataModal').attr('data-checktype',checktype); //类型标记在岗位选择器stationDataModal之上
		var sids = $(this).find('.name').attr('data-sid');
		if(sids == undefined){
			stationArr = [];
		}else{
			stationArr = sids.split(",");
		}
	  //请求人员数据
	  getStationDataFns();
	})

	//岗位选择器方法
	function getStationDataFns(){
		$.ajax({
			dataType: "json",
			type: "post",
			url: "m.php?action=commonJob&task=getSelectorData&target=stationmm",
			beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				hideLoading();
				if(json.success){
					$('#stationDataGroup').empty();
					var data = json.data;
					if(data.length > 0){
						$.each(data, function(i, arr){
							var stationStr  = '<li class="list-group-item stationList" data-sid="'+arr['sid']+'" data-name="'+arr['name']+'">';
							stationStr += '<span class="tools pull-left">';
							stationStr += '<input type="checkbox" class="ipt-hide">';
							stationStr += '<label class="checkbox"></label></span>';
							stationStr += '<div class="name-box">';
							stationStr += '<span class="job" data-sid="'+arr['sid']+'">'+arr['name']+'</span>';
							stationStr += '</div></li>';
							$('#stationDataGroup').append(stationStr);
						})
						//自动扣选
						setChecked('stationList', stationArr, 'station');
					}
					stationDataScroll.refresh();
				}
			}
		})
	}

	//选择岗位
	$(document).on('click','.stationList',function(){
		//触发事件只能单选
		var source = $("#stationDataModal").attr("data-source"); //请求来源
		var checktype = $("#"+source).attr('data-checktype'); //checkbox类型：false单选 、true多选
		if(checktype == "false"){ //checktype：false`单选  true`多选
			checkOnly(this, 'stationList');
		}
		if($(this).find('.ipt-hide').prop('checked')){
			$(this).find('.checkbox').removeClass('cur');
			$(this).find('.ipt-hide').prop('checked', false)
		}else{
			$(this).find('.checkbox').addClass('cur');
			$(this).find('.ipt-hide').prop('checked', true)
		}
	})

	//确认选择
	$(document).on('click','#stnOk',function(){
		var source 		 = $('#stationDataModal').attr('data-source'); //把数据追加到的位置
		var choiceType = $('#stationDataModal').attr('data-choicetype'); //选择器类型
		if(choiceType == 'stationChoice'){ //岗位选择器
			var flag  = false;
	  	var sids  = ''; //岗位id
	  	var names = ''; //岗位名称
	  	$('#'+source).empty();
	  	$('#stationDataGroup .ipt-hide').each(function(i){
	  		if($(this).prop('checked')){
	  			var sid  = $(this).closest('.stationList').attr('data-sid');
	  			var name = $(this).closest('.stationList').attr('data-name');
	  			if(!flag){
	  				flag   = true;
	  				sids  += sid; 
	  				names += name;
	  			}else{
	  				sids  += ',' + sid;
	  				names += ',' + name;
	  			}
	  		}
	  	})
	  	var stationStr = '<span class="name" data-sid="'+sids+'">'+names+'</span>';
	  	$('#'+source).append(stationStr);
	  	$('#'+source).next().val(sids); //隐藏input赋值
	  }else if(choiceType == 'jobChoice'){ //职位选择器
	  	var flag  = false;
	  	var jids  = '';
	  	var names = '';
	  	$('#'+source).empty();
	  	$('#stationDataGroup .ipt-hide').each(function(i){
	  		if($(this).prop('checked')){
	  			var jid  = $(this).closest('.jobList').attr('data-jid');
	  			var name = $(this).closest('.jobList').attr('data-name');
	  			if(!flag){
	  				flag   = true;
	  				jids  += jid;
	  				names += name;
	  			}else{
	  				jids  += ',' + jid;
	  				names += ',' + name;
	  			}
	  		}
	  	})
	  	var jobStr = '<span class="name" data-jid="'+jids+'">'+names+'</span>';
	  	$('#'+source).append(jobStr);
	  	$('#'+source).next().val(jids); //隐藏input赋值
	  }else if(choiceType == 'deptChoice'){ //部门选择器
	  	var flag  = false;
	  	var dids  = '';
	  	var names = '';
	  	$('#'+source).empty();
	  	$('#stationDataGroup .ipt-hide').each(function(i){
	  		if($(this).prop('checked')){
	  			var did  = $(this).closest('.deptList').attr('data-did');
	  			var name = $(this).closest('.deptList').attr('data-name');
	  			if(!flag){
	  				flag   = true;
	  				dids  += did;
	  				names += name;
	  			}else{
	  				dids  += ',' + did;
	  				names += ',' + name;
	  			}
	  		}
	  	})
	  	var jobStr = '<span class="name" data-did="'+dids+'">'+names+'</span>';
	  	$('#'+source).append(jobStr);
	  	$('#'+source).next().val(dids); //隐藏input赋值
	  }
	  $('#stationDataModal').attr('data-choicetype',''); //选择器类型标记恢复到初始状态
	})

	//职位选择器
	$(document).on('click','.jobChoice',function(){
		if (userAgent == 'ios') { //ios需要在弹出的层加绝对定位才能滚动
			$('.stationDataWrapper,.stationDataScroller').css('position','absolute');
		} else {
			$('.stationDataWrapper,.stationDataScroller').css('position','');
		}
		if($(this).attr('data-readonly') == 'true'){ //TRUE 只读 FALSE 可选择
			return false;
		}	
		$('.choiceName').html('请选择职位');
		$('#stationDataModal').attr('data-choicetype','jobChoice'); //标记为职位选择器
		var source = $(this).attr('data-name');
		$('#stationDataModal').attr('data-source',source);
		var checktype = $(this).attr('data-checktype'); //判断当前控件为单选还是多选
		$('#stationDataModal').attr('data-checktype',checktype); //类型标记在职位选择器stationDataModal之上
		var jids  = $(this).find('.name').attr('data-jid');
		if(jids == undefined){
			jobArr = [];
		}else{
			jobArr = jids.split(",");
		}
	  //请求职位数据
	  getJobDataFns();
	})

	//职位选择器方法
	function getJobDataFns(){
		$.ajax({
			dataType: "json",
			type: "post",
			url: "m.php?action=commonJob&task=getSelectorData&target=jobmm",
			beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				hideLoading();
				if(json.success){
					$('#stationDataGroup').empty();
					var data = json.data;
					if(data.length > 0){
						$.each(data, function(i, arr){
							var stationStr  = '<li class="list-group-item jobList" data-jid="'+arr['jid']+'" data-name="'+arr['name']+'">';
							stationStr += '<span class="tools pull-left">';
							stationStr += '<input type="checkbox" class="ipt-hide">';
							stationStr += '<label class="checkbox"></label></span>';
							stationStr += '<div class="name-box">';
							stationStr += '<span class="job" data-jid="'+arr['jid']+'">'+arr['name']+'</span>';
							stationStr += '</div></li>';
							$('#stationDataGroup').append(stationStr);
						})
						//自动勾选
						setChecked('jobList', jobArr, 'job'); 
					}
					stationDataScroll.refresh();
				}
			}
		})
	}

	//选择职位
	$(document).on('click','.jobList',function(){
		//触发事件只能单选
		var source = $("#stationDataModal").attr("data-source"); //请求来源
		var checktype = $("#"+source).attr('data-checktype'); //checkbox类型：false单选 、true多选
		if(checktype == "false"){ //checktype：false`单选  true`多选
			checkOnly(this, 'jobList');
		}
		if($(this).find('.ipt-hide').prop('checked')){
			$(this).find('.checkbox').removeClass('cur');
			$(this).find('.ipt-hide').prop('checked', false)
		}else{
			$(this).find('.checkbox').addClass('cur');
			$(this).find('.ipt-hide').prop('checked', true)
		}
	})

	//部门选择器
	$(document).on('click','.deptChoice',function(){
		if($(this).attr('data-readonly') == 'true'){ //TRUE 只读 FALSE 可选择
			return false;
		}	
		$('.choiceName').html('请选择部门');
		$('#stationDataModal').attr('data-choicetype','deptChoice'); //标记为职位选择器
		var source = $(this).attr('data-name');
		$('#stationDataModal').attr('data-source',source);
		var checktype = $(this).attr('data-checktype'); //判断当前控件为单选还是多选
		$('#stationDataModal').attr('data-checktype',checktype); //类型标记在职位选择器stationDataModal之上
		var dids = $(this).find('.name').attr('data-did');
		if(dids == undefined){
	  	deptArr = []; //没有已选的部门
	  }else{
	  	deptArr = dids.split(",");
	  }
	  //请求职位数据
	  getDeptDataFns();
	})

	//部门选择器方法
	function getDeptDataFns(){
		$.ajax({
			dataType: "json",
			type: "post",
			url: "m.php?action=commonJob&task=getSelectorData&target=deptmm",
			beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				hideLoading();
				if(json.success){
					$('#stationDataGroup').empty();
					var data = json.data;
					if(data.length > 0){
						$.each(data, function(i, arr){
							var deptStr  = '<li class="list-group-item deptList" data-did="'+arr['did']+'" data-name="'+arr['name']+'">';
							deptStr += '<span class="tools pull-left">';
							deptStr += '<input type="checkbox" class="ipt-hide">';
							deptStr += '<label class="checkbox"></label></span>';
							deptStr += '<div class="name-box">';
							deptStr += '<span class="job" data-did="'+arr['did']+'">'+arr['name']+'</span>';
							deptStr += '</div></li>';
							$('#stationDataGroup').append(deptStr);
						})
						//自动扣选
						setChecked('deptList', deptArr, 'dept');
					}
					stationDataScroll.refresh();
				}
			}
		})
	}

	//选择部门
	$(document).on('click','.deptList',function(){
		//触发事件只能单选
		var source = $("#stationDataModal").attr("data-source"); //请求来源
		var checktype = $("#"+source).attr('data-checktype'); //checkbox类型：false单选 、true多选
		if(checktype == "false"){ //checktype：false`单选  true`多选
			checkOnly(this, 'deptList');
		}
		if($(this).find('.ipt-hide').prop('checked')){
			$(this).find('.checkbox').removeClass('cur');
			$(this).find('.ipt-hide').prop('checked', false)
		}else{
			$(this).find('.checkbox').addClass('cur');
			$(this).find('.ipt-hide').prop('checked', true)
		}
	})

	//表单选择请假时间
	$(document).on('click',"input[data-type=attLeave]",function(){
		var _this = $(this);
		if (_this.attr('data-readonly') == 'readonly') {
			return false;
		};
		$('#jingle_popup').hide();
		$('#jingle_popup_mask').hide();
		var fieldsId = $(this).attr('id').substr(9);
		var content  = '<div class="form-group datepick-control"><span class="date-span">开始时间</span><input type="text" class="datetimepick-input WdatePicker" dtfmt="yyyy-MM-dd HH:mm:ss" name="stime" id="stime" readonly=""></div>';
		content += '<div class="form-group datepick-control"><span class="date-span">结束时间</span><input type="text" class="datetimepick-input WdatePicker" dtfmt="yyyy-MM-dd HH:mm:ss" name="etime" id="etime" readonly=""></div>';
		window.setTimeout(function(){
			var value = _this.val();
			if (value != '') {
				var valueS = value.split(' 至 ');
				$('#stime').val(valueS[0]);
				$('#etime').val(valueS[1]);
			};
		},200)
		swal({   
			title: "选择时间",   
			confirmButtonText: "确认",   
			html: true,
			text: content,
			showCancelButton: true,    
			//animation: "slide-from-top",
			closeOnConfirm: false  
		}, function(){ //点击确定 
			var stime = $('#stime').val();
			var etime = $('#etime').val();
			if(stime == '' || stime == undefined){
				jNotify('请选择开始时间!',{
					autoHide : true,                // 是否自动隐藏提示条 
					clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
					MinWidth : 20,                    // 最小宽度 
					TimeShown : 1500,                 // 显示时间：毫秒 
					ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
					HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
					LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
					HorizontalPosition : "center",     // 水平位置:left, center, right 
					VerticalPosition : "center",     // 垂直位置：top, center, bottom 
					ShowOverlay : false,                // 是否显示遮罩层 
					ColorOverlay : "#000",            // 设置遮罩层的颜色 
					OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
				return false;
			}else if(etime == '' || etime == undefined){
				jNotify('请选择结束时间!',{
					autoHide : true,                // 是否自动隐藏提示条 
					clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
					MinWidth : 20,                    // 最小宽度 
					TimeShown : 1500,                 // 显示时间：毫秒 
					ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
					HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
					LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
					HorizontalPosition : "center",     // 水平位置:left, center, right 
					VerticalPosition : "center",     // 垂直位置：top, center, bottom 
					ShowOverlay : false,                // 是否显示遮罩层 
					ColorOverlay : "#000",            // 设置遮罩层的颜色 
					OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
				return false;
			}
			$('.sweet-overlay').css('display','none'); //隐藏输入框
			$('.sweet-alert').css('display','none');
			getAttLeaveDays(fieldsId,fieldsId,stime,etime);
		});
	})

	//表单选择考勤时间
	$(document).on('click',"input[data-type=attTime]",function(){
		var _this = $(this);
		if (_this.attr('data-readonly') == 'readonly') {
			return false;
		};
		$('#jingle_popup').hide();
		$('#jingle_popup_mask').hide();
		var fieldsId = $(this).attr('id').substr(9);
		var content  = '<div class="form-group datepick-control"><span class="date-span">开始时间</span><input type="text" class="datetimepick-input WdatePicker" dtfmt="yyyy-MM-dd HH:mm:ss" name="stime" id="stime" readonly=""></div>';
		content += '<div class="form-group datepick-control"><span class="date-span">结束时间</span><input type="text" class="datetimepick-input WdatePicker" dtfmt="yyyy-MM-dd HH:mm:ss" name="etime" id="etime" readonly=""></div>';
		window.setTimeout(function(){
			var value = _this.val();
			if (value != '') {
				var valueS = value.split(' 至 ');
				$('#stime').val(valueS[0]);
				$('#etime').val(valueS[1]);
			};
		},200)
		swal({   
			title: "选择时间",   
			confirmButtonText: "确认",   
			html: true,
			text: content,
			showCancelButton: true,    
			//animation: "slide-from-top",
			closeOnConfirm: false  
		}, function(){ //点击确定 
			var stime = $('#stime').val();
			var etime = $('#etime').val();
			if(stime == '' || stime == undefined){
				jNotify('请选择开始时间!',{
					autoHide : true,                // 是否自动隐藏提示条 
					clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
					MinWidth : 20,                    // 最小宽度 
					TimeShown : 1500,                 // 显示时间：毫秒 
					ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
					HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
					LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
					HorizontalPosition : "center",     // 水平位置:left, center, right 
					VerticalPosition : "center",     // 垂直位置：top, center, bottom 
					ShowOverlay : false,                // 是否显示遮罩层 
					ColorOverlay : "#000",            // 设置遮罩层的颜色 
					OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
				return false;
			}else if(etime == '' || etime == undefined){
				jNotify('请选择结束时间!',{
					autoHide : true,                // 是否自动隐藏提示条 
					clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
					MinWidth : 20,                    // 最小宽度 
					TimeShown : 1500,                 // 显示时间：毫秒 
					ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
					HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
					LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
					HorizontalPosition : "center",     // 水平位置:left, center, right 
					VerticalPosition : "center",     // 垂直位置：top, center, bottom 
					ShowOverlay : false,                // 是否显示遮罩层 
					ColorOverlay : "#000",            // 设置遮罩层的颜色 
					OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
				return false;
			}
			$('.sweet-overlay').css('display','none'); //隐藏输入框
			$('.sweet-alert').css('display','none');
			getAttTime(fieldsId,fieldsId,stime,etime);
		});
	})

	/**
	 *获取请假天数
	 *@param <int> fields1 请假控件id
	 *@param <int> fieldsId 请假天数id
	 *@param <string> stime 请假开始时间
	 *@param <string> etime 请假结束时间
	 */
	 function getAttLeaveDays(fields1,fieldsId,stime,etime){
	 	$.ajax({
	 		dataType: "json",
	 		type: "post",
	 		url: 'm.php?app=wf&func=flow&action=use&modul=new&task=getAttLeaveDays&version=mm',
	 		data: {'fieldsId':fieldsId, 'stime':stime, 'etime':etime},
	 		beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				hideLoading();
				if(json.success){
					var data = json.data;
					var fieldId = 'wf_field_'+data.fieldId;
					$('#'+fieldId).val(data.days); //请假天数
					$('#wf_field_'+fields1).val(data.stime.substr(0,16) + ' 至 ' + data.etime.substr(0,16));
				}else if(json.failure){
					jError(json.msg,{
						autoHide : true,                // 是否自动隐藏提示条 
						clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
						MinWidth : 20,                    // 最小宽度 
						TimeShown : 1500,                 // 显示时间：毫秒 
						ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
						HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
						LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
						HorizontalPosition : "center",     // 水平位置:left, center, right 
						VerticalPosition : "center",     // 垂直位置：top, center, bottom 
						ShowOverlay : false,                // 是否显示遮罩层 
						ColorOverlay : "#000",            // 设置遮罩层的颜色 
						OpacityOverlay : 0.3            // 设置遮罩层的透明度 
					});
					return false;
				}
			}
		})
	}


	function getAttTime(fields1,fieldsId,stime,etime){
	 	$.ajax({
	 		dataType: "json",
	 		type: "post",
	 		url: 'index.php?app=wf&func=flow&action=use&modul=getAttTime',
	 		data: {'fieldsId':fieldsId, 'stime':stime, 'etime':etime},
	 		beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				hideLoading();
				if(json.success){
					var data = json.data;
					var fieldId = 'wf_field_'+data.fieldId;
					$('#'+fieldId).val(data.days); //请假天数
					var fieldId2 = 'wf_field_'+data.fieldId2;
					$('#'+fieldId2).val(data.hour); //请假小时
					$('#wf_field_'+fields1).val(data.stime.substr(0,16) + ' 至 ' + data.etime.substr(0,16));
				}else if(json.failure){
					jError(json.msg,{
						autoHide : true,                // 是否自动隐藏提示条 
						clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
						MinWidth : 20,                    // 最小宽度 
						TimeShown : 1500,                 // 显示时间：毫秒 
						ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
						HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
						LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
						HorizontalPosition : "center",     // 水平位置:left, center, right 
						VerticalPosition : "center",     // 垂直位置：top, center, bottom 
						ShowOverlay : false,                // 是否显示遮罩层 
						ColorOverlay : "#000",            // 设置遮罩层的颜色 
						OpacityOverlay : 0.3            // 设置遮罩层的透明度 
					});
					return false;
				}
			}
		})
	}

	//表单选择外出时间
	$(document).on('click',"input[data-type=attEvection]",function(){
		var _this = $(this);
		if (_this.attr('data-readonly') == 'readonly') {
			return false;
		};
		$('#jingle_popup').hide();
		$('#jingle_popup_mask').hide();
		var fieldsId = $(this).attr('id').substr(9);
		var content  = '<div class="form-group datepick-control"><span class="date-span">开始时间</span><input type="text" class="datetimepick-input WdatePicker" dtfmt="yyyy-MM-dd HH:mm:ss" name="stime" id="stime" readonly=""></div>';
		content += '<div class="form-group datepick-control"><span class="date-span">结束时间</span><input type="text" class="datetimepick-input WdatePicker" dtfmt="yyyy-MM-dd HH:mm:ss" name="etime" id="etime" readonly=""></div>';
		window.setTimeout(function(){
			var value = _this.val();
			if (value != '') {
				var valueS = value.split(' 至 ');
				$('#stime').val(valueS[0]);
				$('#etime').val(valueS[1]);
			};
		},200)
		swal({   
			title: "选择时间",   
			confirmButtonText: "确认",   
			html: true,
			text: content,
			showCancelButton: true,    
			//animation: "slide-from-top",
			closeOnConfirm: false  
		}, function(){ //点击确定 
			var stime = $('#stime').val();
			var etime = $('#etime').val();
			if(stime == '' || stime == undefined){
				jNotify('请选择开始时间!',{
					autoHide : true,                // 是否自动隐藏提示条 
					clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
					MinWidth : 20,                    // 最小宽度 
					TimeShown : 1500,                 // 显示时间：毫秒 
					ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
					HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
					LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
					HorizontalPosition : "center",     // 水平位置:left, center, right 
					VerticalPosition : "center",     // 垂直位置：top, center, bottom 
					ShowOverlay : false,                // 是否显示遮罩层 
					ColorOverlay : "#000",            // 设置遮罩层的颜色 
					OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
				return false;
			}else if(etime == '' || etime == undefined){
				jNotify('请选择结束时间!',{
					autoHide : true,                // 是否自动隐藏提示条 
					clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
					MinWidth : 20,                    // 最小宽度 
					TimeShown : 1500,                 // 显示时间：毫秒 
					ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
					HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
					LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
					HorizontalPosition : "center",     // 水平位置:left, center, right 
					VerticalPosition : "center",     // 垂直位置：top, center, bottom 
					ShowOverlay : false,                // 是否显示遮罩层 
					ColorOverlay : "#000",            // 设置遮罩层的颜色 
					OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
				return false;
			}
			$('.sweet-overlay').css('display','none'); //隐藏输入框
			$('.sweet-alert').css('display','none');
			getAttEvectionDays(fieldsId,fieldsId,stime,etime);
		});
	})

	/**
	 *获取请假天数
	 *@param <int> fields1 请假控件id
	 *@param <int> fieldsId 请假天数id
	 *@param <string> stime 请假开始时间
	 *@param <string> etime 请假结束时间
	 */
	 function getAttEvectionDays(fields1,fieldsId,stime,etime){
	 	$.ajax({
	 		dataType: "json",
	 		type: "post",
	 		url: 'm.php?app=wf&func=flow&action=use&modul=new&task=getAttEvectionDays&version=mm',
	 		data: {'fieldsId':fieldsId, 'stime':stime, 'etime':etime},
	 		beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				hideLoading();
				if(json.success){
					var data = json.data;
					var fieldId = 'wf_field_'+data.fieldId;
					$('#'+fieldId).val(data.days); //请假天数
					$('#wf_field_'+fields1).val(data.stime.substr(0,16) + ' 至 ' + data.etime.substr(0,16));
				}else if(json.failure){
					jError(json.msg,{
						autoHide : true,                // 是否自动隐藏提示条 
						clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
						MinWidth : 20,                    // 最小宽度 
						TimeShown : 1500,                 // 显示时间：毫秒 
						ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
						HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
						LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
						HorizontalPosition : "center",     // 水平位置:left, center, right 
						VerticalPosition : "center",     // 垂直位置：top, center, bottom 
						ShowOverlay : false,                // 是否显示遮罩层 
						ColorOverlay : "#000",            // 设置遮罩层的颜色 
						OpacityOverlay : 0.3            // 设置遮罩层的透明度 
					});
					return false;
				}
			}
		})
	}

	//查看明细表
	var firstLoad = true; //进销存是不是第一次初始化
	$(document).on('click','.detailtable',function(e){
		if (userAgent == 'ios') { //ios需要在弹出的层加绝对定位才能滚动
			$('.detailtable-wrapper,.detailtable-scroller').css('position','absolute');
		} else {
			$('.detailtable-wrapper,.detailtable-scroller').css('position','');
		}
		if (!e.isTrigger) {
			$('#detailtable').css('display','block');
		} else {
			$('#detailtable').css('display','none');
			$('.modal-backdrop').remove();
		}
		$('.modalTitle').html('查看明细表');
		var detailTableName = $(this).closest('.panel').find('.panel-title').html();
		var detailTableId = $(this).data('tableid');
		var tableId = $(this).attr('data-tableid');
		var flowId = myFns.getUriString('flowId');
		$.ajax({
			dataType: "json",
			type: "post",
			url: "m.php?app=wf&func=flow&action=use&modul=new&task=loadForDetailTable&version=mm",
			data: {"flowId":flowId, "tableId":tableId},
			beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				hideLoading();
				var data = json.data;
				if(data.formHtml != ''){
					var dtb = $('#detailtableGroup').find('.detailTableBody').hasClass('wfdt'+detailTableId);
					if (!dtb) {
						var detailTableHead = '<h3 class="detailTableBody wfdt'+detailTableId+'" style="text-align:center;margin-bottom:10px;" data-tableid="'+detailTableId+'">'+detailTableName+'</h3>';
						$('#detailtableGroup').append(detailTableHead);
						$('#detailtableGroup').append(data.formHtml);
					};
				}
				var tableW = [];
				$('#detailtableGroup table').each(function(){
					tableW.push($(this).width());
				})
				tableW.sort();
				$('#detailtable-scroller').css('width', tableW[tableW.length-1]+20);	//明细表的宽度 + 20像素边距
				window.setTimeout(function(){
					detailtableScroll.refresh(); //重新调整页面
				},400);
				//查找并处理流程引擎
				var wfEngine = $('#detailtableGroup p .engineSign');
				if (wfEngine.length < 1 ) { //明细表未绑定业务引擎
					return false;
				};
				var bindfunc = wfEngine.attr('value'); //引擎类型 比如：JxcRuku
				var engineId = wfEngine.attr('value');
				var uFlowId = myFns.getUriString('uFlowId'); //此条流程ID
				if (firstLoad) { //点击查看明细表第一次才初始化进销存
					queryBusinessData(bindfunc, engineId, uFlowId); //进销存货品查询
					firstLoad = false;
				};
			}
		})
	})

	//明细表内计算字段控件
	$(document).on('change keyup', '#detailtableGroup :input', function(){
		//明细表内的计算控件
		var _this = $(this);
		dealInCalculate(_this);

		//明细表外的计算控件
		dealOutCalculate();

		//明细表内的单元格自动格式化
		//changeAutoFormat(_this, false, false);
	})

	$(document).on('touchstart', '#detailtableGroup input', function(){
		window.setTimeout(function(){
			$(this).focus();
		},2000)
	})

	//明细表内的计算控件字段
	function dealInCalculate(currentElement){
		var detailLine = currentElement.closest('.detail-line');
		var rowNum = detailLine.attr('data-rownum');
		var calculate = detailLine.find('.calculate');
		var round = calculate.attr('roundtype');
		var baoliu = calculate.attr('baoliu');
		if (calculate.length > 0) { //明细表计算控件
			calculate.each(function(i, el){
				var gongshi = JSON.parse($(this).attr('gongshi').replace(/'/g, "\""));
				var gongshiStr = '';
				for (var i = 0; i < gongshi.length; i++) {
					var field = gongshi[i].replace("wfd|", "wf_detail_").replace("_detail_", "_detail_"+rowNum+"_");
					if (field == "+" || field == "-" || field == "*" 
						|| field == "/" || field == "(" || field == ")") {
						gongshiStr += field;
				}else {
					if (!isNaN(field)) {
						gongshiStr += field;
					} else {
						var val = myFns.cleanSymbol($("#"+field).val());
						gongshiStr += val == '' ? 0 : val;
					}
				}
			}
			if (gongshiStr != '') {
				try{
						//计算公式计算
						var rt = eval(gongshiStr);
					}catch(e){
						var rt = '计算出错';
					}
					if (isNaN(rt)) {
						rt = 0;
					};
					if(rt == Infinity){
						rt = 0;
					}
					var result = myFns.roundResult(rt, round, baoliu);
					$(this).val(result);
				};
			})
		}
	}

	//明细表之外的计算控件字段
	function dealOutCalculate(){
		var wfCalculate = $('#myTabContent').find('.calculate');
		var type = '';

		if (wfCalculate.length > 0) { //明细表外的计算控件
			wfCalculate.each(function(index, array){
				var wfRound = $(this).attr('roundtype');
				var wfBaoliu = Number($(this).attr('baoliu'));
				var wfCategory = $(this).attr('category');
				var wfGongshi = JSON.parse($(this).attr('gongshi').replace(/'/g, "\""));
				var wfGongshiStr = '';
				for (var i = 0; i < wfGongshi.length; i++) {
					var strs = wfGongshi[i].split('|');
					if (wfGongshi[i].indexOf('wfd|') != -1) { //存在
						var wf_field = $('#detailtableGroup').find('.wf_field');
						if (wf_field.length > 0) {
							var flag = false;
							wf_field.each(function(){
								if ($(this).attr('field') == strs[1]) {
									var val = myFns.cleanSymbol($(this).val());
									if (!flag) {
										wfGongshiStr += val == '' ? 0 : val;
										flag = true;
									} else {
										wfGongshiStr += '+' + (val == '' ? 0 : val);
									}
								};
							})
						};
					} else if (wfGongshi[i].indexOf('wf|') != -1) {
						var wfField = wfGongshi[i].replace("wf|", "wf_field_");
						if (wfField == "+" || wfField == "-" || wfField == "*" || 
							wfField == "/" || wfField == "(" || wfField == ")") {
							wfGongshiStr += wfField;
						} else {
							var val = $('#'+wfField).val();
							// 日期选择器
							if($('#'+wfField).attr('dtype') == 'date'){
								type = 'date';
								var aa = val.replaceAll('-','/');
								var myDate = new Date(aa);
								var time = myDate.getTime();
								wfGongshiStr += time == '' ? 0 : time;
							}else{
								wfGongshiStr += val == '' ? 0 : val;
							}
						}
					} else if (wfGongshi[i].indexOf('wfo|') != -1){
						continue;
					} else {
						wfGongshiStr += wfGongshi[i];
					}
			};
			if (wfGongshiStr != '') {
				try{
						//计算公式计算
						var rt = eval(wfGongshiStr);
						if(type == 'date'){
							if(rt<0){
								jNotify('开始时间不能大于结束时间',{
									autoHide : true,                // 是否自动隐藏提示条 
									clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
									MinWidth : 20,                    // 最小宽度 
									TimeShown : 1500,                 // 显示时间：毫秒 
									ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
									HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
									LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
									HorizontalPosition : "center",     // 水平位置:left, center, right 
									VerticalPosition : "center",     // 垂直位置：top, center, bottom 
									ShowOverlay : false,                // 是否显示遮罩层 
									ColorOverlay : "#000",            // 设置遮罩层的颜色 
									OpacityOverlay : 0.3            // 设置遮罩层的透明度 
								});
								return false;
							}
						}
					}catch(e){
						var rt = '计算出错';
					}
					if (isNaN(rt)) {
						rt = 0;
					};
					if(rt == Infinity){
						rt = 0;
					}
					
					var result = myFns.roundResult(rt, wfRound, wfBaoliu);
					$(this).val(result);
				}
			})
	};

		//金额大小写转换控件
		var moneyconverts = $("#myTabContent input[moneyconvert=true]");
		moneyconverts.each(function(){
			var from = $("#"+$(this).attr('from'));
			var fromVal = from.val();
			$(this).val(myFns.changeMoneyToChinese(fromVal));
		})
	}
 
	//明细表外计算控件字段
	$(document).on('keyup change', '#myTabContent input', dealOutCalculate);

	//明细表添加一行和删除一行
	$(document).on('click', '.wf_row_jia', function(){
		var _this = $(this), detailLine = _this.closest('.detail-line'),
		tbody = _this.closest('tbody'), rowNum = detailLine.attr('data-rownum'),
		rowNum = detailLine.attr('data-rownum'),
		rowNumLast = Number(tbody.children('.detail-line').last().attr('data-rownum'));
		
		if (rowNum == 1) {
			//克隆第一行数据并标记当前行数和操作按钮
			var detailLineHtml = _this.closest('.detail-line').clone();
			tbody.children('.detail-line').last().after(detailLineHtml);
			tbody.children('.detail-line').last().attr('data-rownum', rowNumLast + 1);
			detailLine.nextAll().find('.wf_row_jia').attr('data-icon','O').css({'color':'#d43f3a','display':'block'});

			//重新格式化新增行数据
			tbody.children('.detail-line').last().find("input,textarea,select,div").each(function(){
				var el = $(this), otype = el.attr('otype'), type = el.attr("type"),
				dvalue = el.attr("dvalue");
				
				//choice选择器
				if (otype == 'choice') {
					el.empty();
				};

				//非宏控件
				if (otype != 'macro' && type != 'hidden') {
					if (otype == 'calculate') { //计算控件
						el.val(0);
					} else if (el.is('INPUT') && (type == "checkbox" || type == "radio")) { //多选框和单选框
						if (dvalue == "true") {
							el.attr("checked", true);
						} else {
							el.attr("checked", false);
						};
					} else if (el.is('SELECT')) { //下拉框
						el.find("option").each(function(){
							if($(this).attr("dvalue") == "true"){
								$(this).attr("selected", "selected");
							}else{
								$(this).removeAttr("selected");
							};
						});
					} else if ((el.is('INPUT') && type == "text") || el.is('TEXTAREA')) { //输入框
						if(dvalue != undefined){
							el.val(dvalue);
						}else{
							el.val('');
						};
					} else {
						el.val('');
					}
				};

				//处理隐藏域数据 
				if(el.attr("detailbindid")){
					el.val('');
				}
			})

			//动态改变明细表新增行格式属性
			tbody.children('.detail-line').last().find('td').each(function(i, element){
				var divId = $(element).find('div').attr('id') == undefined ? '' : $(element).find('div').attr('id');
	      if (divId != '') { //针对选择器控件之类的控件
	      	var newDivId = divId.replace(/(_)\d+(_)/, "$1"+(rowNumLast+1)+"$2");
	      	$(element).find('div').attr({'id':newDivId, 'name':newDivId, 'data-name':newDivId, 'to':newDivId});
	      	$(element).find('input').attr('name',newDivId);
	      } else {
	      	if ($(element).children().length == 1) { //当前td内只有一个input
	      		var inputId = $(element).children().first().attr('name') == undefined ? '' : $(element).children().first().attr('name');
	      		if (inputId != '') {
	      			var newInputId = inputId.replace(/(_)\d+(_)/, "$1"+(rowNumLast+1)+"$2");
	      			$(element).children().first().attr('id',newInputId);
	      			$(element).children().first().attr('name',newInputId);
	      		};
	      	} else { //当前td内有多个input
	      		var inputs = $(element).children();
	      		inputs.each(function(){
	      			var inputId = $(this).attr('name') == undefined ? '' : $(this).attr('name');
	      			if (inputId != '') {
	      				var newInputId = inputId.replace(/(_)\d+(_)/, "$1"+(rowNumLast+1)+"$2");
	      				$(this).attr({'id': newInputId, 'name':newInputId});
	      			};
	      		})
	      	}
	      }
	    })
	}else {
	detailLine.remove();
	};

		dealOutCalculate(); //重新计算明细表之外的计算控件字段

		//调整明细表宽度
		var tableW = [];
		$('#detailtableGroup table').each(function(){
			tableW.push($(this).width());
		})
		tableW.sort();
		$('#detailtable-scroller').css('width', tableW[tableW.length-1]+20);	//明细表的宽度 + 20像素边距
		detailtableScroll.refresh();
	}) 

	//明细表删除一行
	$(document).on('touchstart','.wf_row_jian',function(){
		var detailLine = $(this).closest('.detail-line');
		detailLine.remove();

		dealOutCalculate(); //重新计算明细表之外的计算控件字段

		detailtableScroll.refresh();
	})

	//编辑明细表确认保存
	$(document).on('touchstart','#btnMXOk',function(){
		if (!myFns.wfDetailInspectControl()) {
			$('#detailtable').css('display','none');
			$('.modal-backdrop').remove();
		};
		return false;
	})

	//关闭明细表模态框
	$(document).on('touchstart', '#btnMXClose', function(){
		$('#detailtable').css('display','none');
		$('.modal-backdrop').remove();
		return false;
	})

	//获取业务引擎数据`明细表流程引擎
	queryBusinessData = function(bindfunc, engineId, uFlowId){
		if ( bindfunc == 'salaryApprove' ) {
			//专门为薪酬管理模块使用，
			var baseUrl = "m.php?app=wf&func=flow&action=use&modul=new&task=getBusinessData&bindfunc=" + bindfunc
			+ '&salaryApproveId=' + engineId + '&uFlowId=' + uFlowId + '&version=mm';
		} else if ( bindfunc == 'userReadlySubmit' || bindfunc == 'userSubmit' ) {
			var baseUrl = "m.php?app=wf&func=flow&action=use&modul=new&task=getBusinessData&bindfunc=" + bindfunc + '&userCid=' + engineId + '&uFlowId=' + uFlowId + '&version=mm';
		} else {
			var baseUrl = "m.php?app=wf&func=flow&action=use&modul=new&task=getBusinessData&bindfunc=" + bindfunc + '&version=mm';
		}

		//生成option选项
		var formatOption = function(data, display, value){
			var options = '';
			options += '<option>&nbsp;</option>';
			if(data.length === undefined) return ''; 
			for(var i=0,len=data.length;i<len;++i){
				var obj = data[i];
				options += '<option value="'+obj[value]+'">'+obj[display]+'</option>';
			}
			return options;
		};

		//绑定事件
		var bindEvent = function(obj, field, fieldId){
			obj.change(function(){
				$('#wf_field_'+fieldId).val($(this).find('option:selected').text());
				change(field, fieldId);
			});
		};

		//改变有依赖关系的值
		var change = function(field, fieldId){
			if(field.relative !== undefined){
				var relative=field.relative, level=relative.level;
				data = relative.keys+"="+$('#wf_engine_field_'+fieldId).val();
				$.ajax({
					url:baseUrl+"&level="+level,
					type: 'POST',
					data: data,
					success: function(msg){
						dealMsg(msg);
					}
				});
			}
		};

		//绑定事件。选择车牌号时 自动加载对应的司机，若没有则加载全部，
		var bindChangeEvent = function(obj, field, fieldId, bindFieldId){
			obj.bind('change', function(){
				var value = obj.find('option:selected').attr('value');
				$.ajax({
					dataType: "JSON",
					type: "POST",
					url: "index.php?app=wf&func=flow&action=use&modul=getBindDriver",
					data: {"cid": value},
					success: function(response){
						var data = response.data;
						if (data != undefined) {
							var options = '';
							options += '<option>&nbsp;</option>';
							$('#wf_engine_field_' + bindFieldId +' option').length = 0;
							
							for (var i = 0, len = data.length; i < len; i++) {
								var temp = data[i];

								options += '<option value="'+temp.value+'">'+temp.display+'</option>';
							}

							$('#wf_engine_field_' + bindFieldId).html(options);
							// 多了个空值
						}
					}
				});
			})
		}

		//处理业务引擎返回值
		var dealMsg = function(msg){
			if(bindfunc == 'meeting'){
				if(typeof(msg)=='string'){
					msg = jQuery.parseJSON(msg);
				}
			}
			if(typeof msg !== "object") return ;
			for(var fieldId in msg){
				var field = msg[fieldId], ID_field = 'wf_field_'+fieldId, ID_engine_field='wf_engine_field_'+fieldId;
				var fieldObj = $('#'+ID_field);
				if(fieldObj.length==0) continue;
				if(fieldObj.attr('disabled')|| fieldObj.attr('readonly')){
					//插入隐藏input
					var val = fieldObj.val(), data=field['data'], value;
					for(var i in data){
						if(eval("data[i]."+field['display']+"==val")){
							eval("value=data[i]."+field['value']+";");
							break;
						}
					}
					fieldObj.after('<input type="hidden" id="'+ID_engine_field+'" name="'+ID_engine_field+'" value="'+value+'" />');
					change(field, fieldId);
				}else{
					//判断表单是否初次加载
					if(fieldObj.get(0).nodeName==='SELECT'){
						//插入隐藏input
						if (field['data'].length > 0) { //SELECT控件的第一个值
							var wf_engine_field_value = field['data'][0].storagename;
						};
						fieldObj.after('<input type="hidden" id="'+ID_field+'" name="'+ID_field+'" value="'+wf_engine_field_value+'" />');
						fieldObj.attr('id', ID_engine_field);
						fieldObj.attr('name', ID_engine_field);
					}else{
						//清空数据
						fieldObj.val('');
						fieldObj = $('#'+ID_engine_field);
					}
					//插入选项
					var options = formatOption(field['data'], field['display'], field['value']);
					if (field['engineField'] == 'admCarNum') {
						bindChangeEvent(fieldObj, field, fieldId, field['bindFieldId']);
					}
					fieldObj.html(options);
					//绑定事件
					bindEvent(fieldObj, field, fieldId);
				}
			}
		};

		//薪酬管理，重新赋值操作。
		var dealActual = function(actualSum, actualFieldId){
			var fieldObj = $('#wf_field_' + actualFieldId);

			if ( ! fieldObj.attr('readonly') ) {
				fieldObj.val(actualSum);
			}
		};

		//薪酬管理，重新赋值操作。
		var dealShould = function(shouldSum, shouldFieldId){
			var fieldObj = $('#wf_field_' + shouldFieldId);

			if ( ! fieldObj.attr('readonly') ) {
				fieldObj.val(shouldSum);
			}
		};

		//初始化表单选择数据
		$.ajax({
			dataType: "JSON",
			type: "GET",
			url: baseUrl+"&level=init",
			success: function(msg){
				if ( bindfunc == 'salaryApprove' ) {
					var msg = msg,
					checked = msg.checkIdea,
					actual = msg.actual,
					actualSum = actual.actualSum,
					actualFieldId = actual.fieldId,
					should = msg.should,
					shouldSum = should.shouldSum,
					shouldFieldId = should.fieldId;

					dealMsg(checked);
					if ( actualFieldId != 0 ) {
						dealActual(actualSum, actualFieldId);
					} 
					if ( shouldFieldId != 0 ) {
						dealShould(shouldSum, shouldFieldId);
					}
				} else if( bindfunc == 'userReadlySubmit' || bindfunc == 'userSubmit' ) {
					var msg = msg,
					checked = msg.checkIdea,
					result = msg.result;

					dealMsg(checked);

					for (var i in result ) {
						var fieldObj = $('#wf_field_' + i);

						if ( ! fieldObj.attr('readonly') ) {
							fieldObj.val(result[i]);
						}
					}
				} else { 
					dealMsg(msg);
				}
			}
		});
	}

	//进销存货物
	$(document).on('touchstart','.wf_list_query',function(){
		if (userAgent == 'ios') { //ios需要在弹出的层加绝对定位才能滚动
			$('.jxcGoods-wrapper,.jxcGoods-scroller').css('position','absolute');
		} else {
			$('.jxcGoods-wrapper,.jxcGoods-scroller').css('position','');
		}
		jxcPageNow = 1; //进销存页数置为1
		$('#jxcGoodsSearch').val(''); //搜索按钮重置为空
		$('#jxcGoodsList').css('display','block');
		$('.modalTitle').html('进销存货品查询');
		var bindfunc = $(this).attr('bindfunc'); 
		var detailid = $(this).attr('detail');
		var querykey = $(this).attr('querykey');
		$('#jxcGoodsGroup').attr({"bindfunc":bindfunc, "detailid":detailid, "querykey":querykey});
		//目前暂时分两种类型：1、jxcGoods 2、admarticles
		var querykey = $(this).attr('querykey'); 
		if (querykey == "jxcGoods") { //进销存
			jxcGoodsInitComponent(); //初始化进销存控件
		} else if (querykey == "admarticles"){ //办公用品
			myFns.getGoodsCustomField();
		}
		return false;
	});

	//初始化进销存组件
	function jxcGoodsInitComponent(){
		var bindfunc = $('#jxcGoodsGroup').attr('bindfunc');
		var baseUrl = "m.php?app=wf&func=flow&action=use&modul=new&&task=getQueryList&bindfunction="+bindfunc;
		$.ajax({
			dataType: "JSON",
			type: "GET",
			url: baseUrl+"&operate=getStorageBindWidgetId&version=mm",
			success: function(response){
				var widgetId = response;
				var storageId = $('#wf_engine_field_'+widgetId).val();
				$('#jxcGoodsGroup').attr('wf_engine_value',storageId);
				myFns.getGoodsCustomField(); //获取进销存字段
			}
		})
	}

	//进销存查询数据
	$(document).on('click','#jxcGoodsBtnSearch',function(){
		jxcPageNow = 1; //分页页码重置为1
		myFns.getGoodsList("POST", true);
	})

	//进销存把数据设置到明细表
	function setRowDataForDea(){
		var detailid = $('#detailtableGroup .detailTableBody').attr('data-tableid');
		var dt = [], tr, im, bi, hasFirstLine, le, bindid;
		var rows = $("input:checked[class=jxcChk]").closest('tr');

		if (rows.length > 0) {
			var length = rows.length;
			for (var i=0; i<length; i++) {
				var tds = rows.eq(i).children();
				var td = {};
				$.each(tds, function(j){
					if (j < 2) {
						return true;
					}
					//类型1：显示的是名称，但是提交的是需要对应的ID
					var valueFieldName = $(this).attr('valuefieldname');
					if ( valueFieldName != '') {
						var valuefield = $(this).attr('valuefield'); //最终需要提交的值
						if (valuefield != undefined) {
							var valuefieldname = $(this).attr('valuefieldname'); //最终要提交的值所对应的字段
							td[valuefieldname] = valuefield;
						};
					};
					//类型2：显示的是名称，提交的也是名称
					var className = $(this).attr('class');
					var value = $(this).text();
					td[className] = value;
					td['id'] = rows.eq(i).attr('rowid');
				});
				dt.push(td);
			}
		};

		//给明细表添加新行
		if(dt.length>0){
			tr = $("#detailtableGroup tr[class=detail-line][detail="+detailid+"]");
			im = $(tr.find(".wf_row_jia[queryadd=true]")[0]);
			bi = $(tr[0]).find("input:hidden[id^=wf_detailbid_]");
			hasFirstLine = bi.val() != '' ? false : true;
			le = !hasFirstLine ? (dt.length-1) : dt.length;
			le = dt.length;
			for (var i=0; i<le; i++){
				im.click();
			}
		}

		//给添加的新行填充数据
		if(dt.length>0){ //找出最后几行
			var trs = $("#detailtableGroup tr[class=detail-line][detail="+detailid+"]");
			var j=0;
			//手机版暂时不做替换掉第一行数据，从第二行开始替换
			for (var i=1; i<trs.length; i++){
				var tr = $(trs[i]);
				bindid = tr.find("input:hidden[id^=wf_detailbid_]");
				var isEmpty = bindid.val() != '' ? false : true;
				if(isEmpty){
					for(var key in dt[j]){
						var input = tr.find("[bindfield="+key+"]");
						input.val(dt[j][key]).change();
					}
					bindid.val(dt[j]['id']);
					j++;
				}
				tr.find("[bindfield=count]").val(1).change();
			}
		}
	}

	//进销存全选反选
	$(document).on('click','.jxcChkAll',function(){
		if ($(this).prop('checked')) {
			$('.jxcChk').prop('checked', true);
		} else {
			$('.jxcChk').prop('checked', false);
		}
	})

	//关闭进销存弹窗
	$(document).on('touchstart','#btnJxcGoodsClose',function(){
		$('#jxcGoodsList').css('display','none');
		return false;
	})

	//确认进销存按钮并关闭弹窗
	$(document).on('touchstart','#btnJxcGoodsOk',function(){
		setRowDataForDea(); //设置数据到明细表
		$('#jxcGoodsList').css('display','none');
		return false;
	})

	//日期选择器控件
	$(document).on('click','.WdatePicker',function(){
		var dateFmt = $(this).attr('dtfmt'); //时间格式
		var readonly = $(this).attr('data-readonly');
		if (readonly == 'true') {
			return false;
		};
		WdatePicker({
			lang:'zh-cn', //繁体中文zh-tw, 英文en, 简体中文zh-cn
			skin:'default', //默认皮肤default: skin:'default', 1、whyGreen 2、twoer
			isShowClear: true, //清空按钮, isShowClear 默认值都是true
			isShowToday: true,//今天按钮,isShowToday 默认值都是true
			//readOnly: true, //设置readOnly属性 true 或 false 可指定日期框是否只读 
			dateFmt: dateFmt //自定义格式
		});
	})

	//时间选择器控件
	/*$(document).on('click','.WtimePicker',function(){
		var _this = $(this), value = _this.val(), timeFmt = _this.attr('dtfmt'), 
		readonly = _this.attr('data-readonly');
		if (readonly == 'true') {
			return false;
		};
		
		if (timeFmt == '100') { //时间选择器24小时制
			WdatePicker({
				lang:'zh-cn', //繁体中文zh-tw, 英文en, 简体中文zh-cn
				skin:'default', //默认皮肤default: skin:'default', 1、whyGreen 2、twoer
				isShowClear: true, //清空按钮, isShowClear 默认值都是true
				isShowToday: true,//今天按钮,isShowToday 默认值都是true
				//readOnly: true, //设置readOnly属性 true 或 false 可指定日期框是否只读 
				dateFmt:'HH:mm'
			})
		} else if (timeFmt == '200') { //时间选择器12小时制 英文
			
		} else { //时间选择器12小时制 中文
			
		}
	})*/

	//初始化时间滴管
	function initTimeDropper() {
		var picktimes = $(".WtimePicker"), picktimesLen = picktimes.length;
		for (var i = 0; i < picktimesLen; i++) {
		  var _this = picktimes.eq(i), dtfmt = _this.attr('dtfmt'), isReadOnly = _this.attr("data-readonly");
		  if (isReadOnly !== "false") {
		    continue;
		  };
		  if (dtfmt == "100") {
		    _this.timeDropper({
		      meridians: false,
		      format: 'HH:mm',
		      init_animation: "bounce", //fadeIn(default), bounce, dropDown.
		      autoswitch: false, //Automatically change hour-minute or minute-hour on mouseup/touchend. (Default: false)
		      setCurrentTime: false //Automatically set current time by default.(Default: true)
		    });
		  } else if (dtfmt == "200") {
		    _this.timeDropper({
		      meridians: true,
		      format: 'HH:mm A',
		      init_animation: "bounce", //fadeIn(default), bounce, dropDown.
		      autoswitch: false, //Automatically change hour-minute or minute-hour on mouseup/touchend. (Default: false)
		      setCurrentTime: false, //Automatically set current time by default.(Default: true)
		      setChineseFormat: false 
		    });
		  } else {
		    _this.timeDropper({
		      meridians: true,
		      format: 'HH:mm A',
		      init_animation: "bounce", //fadeIn(default), bounce, dropDown.
		      autoswitch: false, //Automatically change hour-minute or minute-hour on mouseup/touchend. (Default: false)
		      setCurrentTime: false, //Automatically set current time by default.(Default: true)
		      setChineseFormat: true 
		    });
		  }
		};
	}	

	$(document).on('touchstart', '.timePickerClose', function(){
	$('#timePicker').css('display', 'none');
	return false;
	})

	//数据源启动模态框
	$(document).on('click','.datasource',function(){
		if (userAgent == 'ios') { //ios需要在弹出的层加绝对定位才能滚动
			$('.datasource-wrapper,.datasource-scroller').css('position','absolute');
		} else {
			$('.datasource-wrapper,.datasource-scroller').css('position','');
		}
		$('#datasource').css('display','block');
		$('.modalTitle').html('查询面板');
		var datasource = $(this).attr('datasource');
		$('#datasourceGroup').attr('to', $(this).attr('id')); //记录要把数据追加的位置
		$('#datasourceGroup').attr('datasource', datasource); //记录数据来源
		$('#datasourceGroup').empty(); //清空数据区
		var dataStr = '';
		//数据源字段
		getDsFieldsFns(datasource, function(fields){
			var titleStr  = '<table class="table table-bordered"><thead><tr class="active field">';
			titleStr += '<th class="bianhao">#</th>';
			titleStr += '<th class="ckbox">##</th>';
			$.each(fields, function(i, el){
				titleStr += '<th class="'+el['field']+'">'+el['fieldname']+'</th>';
			})
			titleStr += '</thead></tr></table>';
			$('#datasourceGroup').append(titleStr);
			if (datasource === 'archives') { //公文档案
				forMatData(datasource, function(data){
					dataStr += data; //把数据追加到指定的位置
				}); 
			} else if (datasource === 'customerLinkman') { //客户联系人
				forMatData(datasource, function(data){
					dataStr += data; //把数据追加到指定的位置
				}); 
			} else { //客户信息
				forMatData(datasource, function(data){
					dataStr += data; //把数据追加到指定的位置
				}); 
			}
			dsPageNow += 1; //数据源分页
			if (dataStr != '') {
				$('#datasourceGroup .table').append(dataStr);
			};
			var dsWidth = $('#datasourceGroup > table').width();
			$('#datasource-scroller').css('width', dsWidth+22);
			datasourceScroll.refresh();
		});
	})

	//把数据源数据整理追加到对应的位置
	function forMatData(datasource, callback){
		var ths = $('#datasourceGroup .field .ckbox').nextAll();
		myFns.getDsDataFns(datasource, false, "post", 0, 14, function(data){
			dataStr = '<tbody>';
			$.each(data, function(k, dsData){
				dataStr += '<tr>';
				dataStr += '<td class="bianhao">'+(k+1)+'</td>';
				dataStr += '<td class="chbox"><input type="checkbox" name="dsChk" class="dsChk"></td>';
				$.each(ths, function(){
					var th = $(this);
					for (x in dsData) {
						if (th.attr('class') == x) {
							dataStr += '<td class="'+x+'">'+myFns.convertNullData(dsData[x])+'</td>';
							break;
						};
					};
				})
				dataStr += '</tr>';
			})
			dataStr += '</tbody>';
			callback(dataStr);
		});
	}

	//加载数据源字段
	function getDsFieldsFns(datasource, callback){
		$.ajax({
			dataType: "json",
			type: "get",
			url: 'm.php?app=wf&func=flow&action=use&modul=new&task=getDsFields&version=mm&dsTag='+datasource+'',
			async: false,
			success: function(json){
				callback(json);
			}
		})
	}

	//数据源搜索功能
	$(document).on('click','#dsBtnSearch',function(){
		dsPageNow = 1;
		var datasource = $('#datasourceGroup').attr('datasource'); //数据源数据类型
		myFns.getDsDataFns(datasource, true, 'POST', 0, 14, function(data){
			var ths = $('#datasourceGroup .field .ckbox').nextAll();
			$('#datasourceGroup .table tbody').empty();
			$.each(data, function(k, dsData){
				dataStr = '<tr>';
				dataStr += '<td class="bianhao">'+(k+1)+'</td>';
				dataStr += '<td class="chbox"><input type="checkbox" name="dsChk" class="dsChk"></td>';
				$.each(ths, function(){
					var th = $(this);
					for (x in dsData) {
						if (th.attr('class') == x) {
							dataStr += '<td class="'+x+'">'+myFns.convertNullData(dsData[x])+'</td>';
						};
					};
				})
				dataStr += '</tr>';
				$('#datasourceGroup .table tbody').append(dataStr);
			})
			dsPageNow += 1;
			var dsWidth = $('#datasourceGroup > table').width();
			$('#datasource-scroller').css('width', dsWidth+22);
			datasourceScroll.refresh();
		})
	})

	//数据源扣选
	$(document).on('click','.dsChk', function(){
		$('.dsChk').prop('checked',false);
		$(this).prop('checked',true);
	})

	//数据源确定数据
	$(document).on('touchstart', '#btnDSOk', function(){
		dsPageNow = 1; //重置数据源分页
		$('#dsSearch').val(''); //清空数据源搜索框数据
		var to = $('#datasourceGroup').attr('to');
		var maps = JSON.parse($('#'+to).attr('maps').replace(/'/g, "\""));
		var td = $('#datasourceGroup input[name="dsChk"]:checked').closest('tr').find('td');
		if (td.length < 1) { //没有选择数据
			jNotify('请至少选择一条数据!',{
				autoHide : true,                // 是否自动隐藏提示条 
				clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
				MinWidth : 20,                    // 最小宽度 
				TimeShown : 1500,                 // 显示时间：毫秒 
				ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
				HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
				LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
				HorizontalPosition : "center",     // 水平位置:left, center, right 
				VerticalPosition : "center",     // 垂直位置：top, center, bottom 
				ShowOverlay : false,                // 是否显示遮罩层 
				ColorOverlay : "#000",            // 设置遮罩层的颜色 
				OpacityOverlay : 0.3            // 设置遮罩层的透明度 
			});
			return false;
		};
		$.each(maps, function(i, el){
			$.each(td, function(j, element){
				if ($(element).attr('class') == el.src) {
					$('#'+el.des).val($(element).text());
				};
			})
		})
		//关闭数据源弹框
		$('#datasource').css('display','none');
		$('.modal-backdrop').remove();
		return false;
	})

	//数据源关闭按钮
	$(document).on('touchstart','#btnDSClose',function(){
		$('#dsSearch').val(''); //清空数据源搜索框数据
		dsPageNow = 1;
		$('#datasource').css('display','none');
		$('.modal-backdrop').remove();
		return false;
	})

	//启动图文签章模态框并请求签章数据
	$(document).on('click','.signature',function(){
		if (userAgent == 'ios') { //ios需要在弹出的层加绝对定位才能滚动
			$('.signature-wrapper,.signature-scroller').css('position','absolute');
		} else {
			$('.signature-wrapper,.signature-scroller').css('position','');
		}
		var	text  = '<div class="row" style="padding-top:20px;">';
		text += '<div class="col-xs-6" style="text-align:center;"><botton type="botton" class="btn btn-success" id="graphSignature">图形签章</botton></div>';
		text += '<div class="col-xs-6" style="text-align:center;"><botton type="botton" class="btn btn-info" id="handSignature">手写签章</botton></div>';
		text += '</div>';
		swal({   
			title: "请选择签章类型",   
			text: text,   
			imageUrl: "",
			html: true,  
			showConfirmButton: false
		});
		var to = $(this).attr('id');
		var width = $(this).attr('width');
		var height = $(this).attr('height');
		$('#signature-scroller').attr('to',to); //签章插入的位置
		$('#signature-scroller').attr('width',width); //签章宽度
		$('#signature-scroller').attr('height',height); //签章高度
	})

	//手写板全局变量
	var canvas, board, mousePress=false, last=null;

	//跳转到手写签章`手写板
	$(document).on('click','#handSignature',function(){
		$('.sweet-overlay').css('display','none'); //隐藏输入框
		$('.sweet-alert').css('display','none');
		handSignature();
	})

	//手写签章手写板
	function handSignature(){
		$('#signature').css('display','block');
		$('.modalTitle').html('手写签章');
		$('#signatureList').empty();
		var	str  = '<div class="col-xs-12"><canvas id="handGignatureCanvas"></canvas></div>';
		str += '<div class="col-xs-12" style="margin-top:5px;"><button type="button" class="btn btn-primary btn-lg btn-block" id="handGignatureClean">重 写</button></div>';
		str += '<div class="col-xs-12" style="margin-top:10px;"><button type="button" class="btn btn-success btn-lg btn-block" id="handGignatureSave">确 认</button></div>';
		$('#signatureList').append(str);
		//调用`初始化手写板
		canvas = document.getElementById('handGignatureCanvas');
		canvasInit();
		signatureScroll.refresh();
	}

	//初始化手写画板
	function canvasInit(){
		canvas.height = $(document).height()/2;
		canvas.width = $(document).width()-30;
		board = canvas.getContext('2d');
		board.lineWidth = 4;
		board.strokeStyle = "#000000";
		//鼠标画笔
		canvas.onmousedown = beginDraw;
		canvas.onmousemove = drawing;
		canvas.onmouseup = endDraw;
		//手机画笔
		canvas.addEventListener('touchstart',beginDraw,false);
		canvas.addEventListener('touchmove',drawing,false);
		canvas.addEventListener('touchend',endDraw,false);
	}

	//开始画
	function beginDraw(){
		mousePress = true;
	}

	//绘画
	function drawing(event){
		event.preventDefault();
		if(!mousePress)return;
		var xy = pos(event);
		if(last!=null){
			board.beginPath();
			board.moveTo(last.x,last.y);
			board.lineTo(xy.x,xy.y);
			board.stroke();
		}
		last = xy;
	}

	//停止绘画
	function endDraw(event){
		mousePress = false;
		event.preventDefault();
		last = null;
	}

	//手写画笔纠偏
	function pos(event){
		var x, y;
		if(isTouch(event)){ 
			x = event.touches[0].pageX-16;
			y = event.touches[0].pageY-66;
		}else{
			x = event.offsetX+event.target.offsetLeft;
			y = event.offsetY+event.target.offsetTop;
		}
		//log('x='+x+' y='+y);
		return {x:x,y:y};
	}

	function log(msg){
		var log = document.getElementById('log');
		var val = log.value;
		log.value = msg+'n'+val; 
	}

	function isTouch(event){
		var type = event.type;
		if(type.indexOf('touch')>=0){
			return true;
		}else{
			return false;
		}
	}

	//保存并生成图片
	function handGignatureSave(){
		var imgUrl = canvas.toDataURL(); //base64
		// imgUrl = imgUrl.replace("image/png","image/octet-stream");
		uploadHandGignature(imgUrl, function(data){ //接收回调函数`并处理
			var to = $('#signature-scroller').attr('to');
			$('#'+to).css('display','none');
			var imgUrlStr = '<img src="'+data+'">';
			$('#'+to).before(imgUrlStr);
			window.setTimeout(function(){
				myScroll.refresh();
			},400)
		});
	}

	//上传手写签章到服务端
	function uploadHandGignature(imgUrl, callback){
		var to = $('#signature-scroller').attr('to'); 
		var width = $('#signature-scroller').attr('width');
		var height = $('#signature-scroller').attr('height');
		var flowId = myFns.getUriString('flowId');
		var postUrl = 'm.php?action=commonJob&task=uploadSignaturePic';
		showLoading();
		$.post(postUrl, {"imgUrl":imgUrl, "flowId":flowId, "width":width,"height":height}, function(data){
			hideLoading();
			var valueObj = '';
			if (data != '') { //手机上签章设定默认定位值
				valueObj += 'url:'+data+';left:-30;top:-38';
				callback(data); //异步回调
			};
			$('input[name='+to+']').val(valueObj);
		},'json');
	}

	//手写板重写方法
	function handGignatureClean(){
		board.clearRect(0,0,canvas.width,canvas.height);
	}

	//手写板重写
	$(document).on('click','#handGignatureClean',handGignatureClean);

	//手写确认按钮
	$(document).on('click','#handGignatureSave',function(){
		$('#signature').css('display','none');
		$('.modal-backdrop').remove();
		handGignatureSave();
	})

	//签章关闭按钮
	$(document).on('touchstart','#btnSTClose',function(){
		$('#signature').css('display','none');
		$('.modal-backdrop').remove();
		return false;
	})

	//关闭swal弹框
	$(document).on('click','.sweet-overlay',function(){
		$('.sweet-overlay').css('display','none'); //隐藏输入框
		$('.sweet-alert').css('display','none');
	})

	//跳转到图形签章列表
	$(document).on('click','#graphSignature',function(){
		$('.sweet-overlay').css('display','none'); //隐藏输入框
		$('.sweet-alert').css('display','none');
		graphSignature();
	})

	//图形签章列表
	function graphSignature(){
		$('#signature').css('display','block');
		$('.modalTitle').html('签章列表');
		$.ajax({
			dataType: "json",
			type: "get",
			url: 'm.php?app=wf&func=flow&action=use&modul=new&task=getSignature&version=mm',
			success: function(json){
				$('#signatureList').empty();
				if(json.length > 0){
					$.each(json, function(i,el){
						var str  = '<div class="col-xs-4 thumb" isUsePwd="'+el['isUsePwd']+'">';
						str += '<img src="'+el['url']+'" class="img-rounded">';
						str += '<div class="signatureName">'+el['signature']+'</div>';
						str += '</div>';
						$('#signatureList').append(str);
					})
				};
				signatureScroll.refresh();
			}
		})
	}

	//选择签章盖章
	$(document).on('click','#signatureList .thumb',function(){
		var thumb = $(this);
		var isUsePwd = thumb.attr('isUsePwd'); //是否需要密码
		if (isUsePwd === '1') { //需要密码
			window.setTimeout(function(){
				$('.sweet-overlay').css('z-index','1060');
				$('.sweet-alert').children('fieldset').children('input').attr('type','password');
			},100)
			swal({   
				title: "请输入用户密码",
				html: true,  
				type: 'input',
				confirmButtonText: "确认",   
				showCancelButton: true,   
				closeOnConfirm: false,  
				//animation: "slide-from-top",   
				//confirmButtonColor: "#2a6496",
				inputPlaceholder: "请输入用户密码"
			}, function(inputValue){
				if(inputValue === false){ //取消按钮
					return false;      
				} 
				if(inputValue === ''){
					swal.showInputError("请输入用户密码!");
					return false;
				}
				checkPassword(inputValue, function(result){
					if (!result){
						jError('你输入的密码有误!',{
							autoHide : true,                // 是否自动隐藏提示条 
							clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
							MinWidth : 20,                    // 最小宽度 
							TimeShown : 1500,                 // 显示时间：毫秒 
							ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
							HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
							LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
							HorizontalPosition : "center",     // 水平位置:left, center, right 
							VerticalPosition : "center",     // 垂直位置：top, center, bottom 
							ShowOverlay : false,                // 是否显示遮罩层 
							ColorOverlay : "#000",            // 设置遮罩层的颜色 
							OpacityOverlay : 0.3            // 设置遮罩层的透明度 
						});
						return false;
					} else {
						setSignature(thumb);
					}
				});
			})
	} else {
	setSignature(thumb);
	}
	});

	//盖章
	function setSignature(thumb){
		$('.modal-backdrop').remove();
		var to = $('#signature-scroller').attr('to');
		$('#'+to).css('display','none');
		var imgUrl = thumb.children('img').attr('src');
		var imgUrlStr = '<img src="'+imgUrl+'">';
		$('#'+to).before(imgUrlStr);
		var valueObj = '';
		if (imgUrl != '') { //手机上签章设定默认定位值
			valueObj += 'url:'+imgUrl+';left:-30;top:-38';
		};
		$('input[name='+to+']').val(valueObj);
		$('#signature').css('display','none');
		$('.sweet-overlay').css('display','none'); //隐藏输入框
		$('.sweet-alert').css('display','none');
		myScroll.refresh();
	}

	//验证签章密码
	function checkPassword(pwd, callback){
		var httpUrl = 'index.php?app=my&func=info&action=index&task=checkPassword';
		$.post(httpUrl, {"text":pwd}, function(data){
			if (data.success) {
				callback(true);
			} else {
				callback(false);
			}	
		},'json')
	}

	//流程附件`上传附件来源
	$(document).on('click','.wfUploadFieldType',function(){
		var field = $(this).attr('field');
		$('#myTabContent').attr('wfFileId',field); //记录当前流程附件id
		var	text  = '<div class="row" style="padding-top:20px;">';
		text += '<div class="col-xs-6" style="text-align:center;"><div class="btn btn-success wfbtnUpload"><span>添加附件</span><input id="wfAttachForLocal" type="file" name="upload_file"></div></div>';
		text += '<div class="col-xs-6" style="text-align:center;"><botton type="botton" class="btn btn-info" id="wfAttachForDisk">我的硬盘</botton></div>';
		text += '</div>';
		swal({   
			title: "上传附件",   
			text: text,   
			imageUrl: "",
			html: true,  
			showConfirmButton: false
		});
	})

	//添加流程附件`初始化上传附件表单
	$(document).on('click','#wfAttachForLocal',function(){
		var myWfUpload = $("#myWfUpload");
		var url = 'm.php?action=commonJob&task=upFile';
		if(!myWfUpload.length){ //判断表单form是否存在
			$("#wfAttachForLocal").wrap("<form id='myWfUpload' action='' method='post' enctype='multipart/form-data'></form>"); 
			$("#myWfUpload").attr("action",url);
		}
		$('.sweet-overlay').css('display','none'); //隐藏输入框
		$('.sweet-alert').css('display','none');
	});

	//field改变上传文件`附件
	$(document).on('change','#wfAttachForLocal',function(){
		var filePath = $('#wfAttachForLocal').val();
		if(!filePath){
			return false;
		}
		var filename  = filePath.replace(/.*(\/|\\)/, ""); //文件名带后缀
		var fileSplit = (/[.]/.exec(filename)) ? /[^.]+$/.exec(filename.toLowerCase()) : ''; //文件后缀fileExt[0]
		var fileArr 	= ['jpg','jpeg','gif','png','bmp','rar','zip','doc','wps','wpt','ppt','xls','txt','csv','et','ett','pdf'];
		var fileExt 	= fileSplit[0].toLowerCase(); //文件后缀名转小写
		if($.inArray(fileExt,fileArr) < 0){
			jError('不支持上传此类型文件!',{
				autoHide : true,                // 是否自动隐藏提示条 
				clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
				MinWidth : 20,                    // 最小宽度 
				TimeShown : 1500,                 // 显示时间：毫秒 
				ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
				HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
				LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
				HorizontalPosition : "center",     // 水平位置:left, center, right 
				VerticalPosition : "center",     // 垂直位置：top, center, bottom 
				ShowOverlay : false,                // 是否显示遮罩层 
				ColorOverlay : "#000",            // 设置遮罩层的颜色 
				OpacityOverlay : 0.3            // 设置遮罩层的透明度 
			});
			return false;
		}

		$("#myWfUpload").ajaxSubmit({
			dataType:  'json',
			beforeSend: function() {
				showLoading();
	      $('#wfAttachForLocal').attr('disabled',"true"); //添加disabled属性 
	    },
	    success: function(json){
	    	hideLoading();
	    	$('#wfAttachForLocal').removeAttr("disabled"); //移除disabled属性 
	    	if(json.success){
	    		//附件信息位置 
	    		if(!$('#attachGroup').length){
	    			var attachGroupStr = '<div id="attachGroup"><div class="row formInfoTitle"><span>附件信息</span></div></div>';
	    			$('#myTabContent').append(attachGroupStr);
	    		}

	    		var data = json.msg;
	    		var wfFileId = $('#myTabContent').attr('wfFileId'); //流程附件控件所属id
	    		var filesUploadData = '||'+data.oldname+'||'+data.name+'||'+data.size;
	    		var fileBox  = '<div class="panel panel-default panel-attach-box" data-random="'+data.random+'">';
	    		fileBox += '<div class="panel-heading">';
	    		fileBox += ''+data.oldname+'';
	    		fileBox += '<div class="panel-attach">';
	    		fileBox += '<botton type="botton" class="btn btn-danger btn-xs btnDel">删除</botton>';
	    		fileBox += '<input type="hidden" name="filesUpload['+wfFileId+'][]" value="'+filesUploadData+'">';
	    		fileBox += '</div></div></div>';
	    		$('#attachGroup').append(fileBox);

	  			//流程附件控件下方位置
	  			var wfAttachId = $('#myTabContent').attr('wffileid'); //流程附件控件id
	  			wfFileBox  = '<div class="panel-heading panel-attach-box" data-attachid="'+wfAttachId+'" data-random="'+data.random+'" style="padding: 4px 0px;">';
	  			wfFileBox += '<span class="fileName">'+data.oldname+'</span>';
	  			wfFileBox += '<div class="panel-attach">';
	  			wfFileBox += '<botton type="botton" class="btn btn-danger btn-xs btnDel">删除</botton>';
	  			wfFileBox += '</div>'; 
	  			wfFileBox += '</div>';
	  			$('#wf_field_'+wfAttachId).after(wfFileBox);
	    	}else{ //上传失败
	    		jError(json.msg,{
						autoHide : true,                // 是否自动隐藏提示条 
						clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
						MinWidth : 20,                    // 最小宽度 
						TimeShown : 1500,                 // 显示时间：毫秒 
						ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
						HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
						LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
						HorizontalPosition : "center",     // 水平位置:left, center, right 
						VerticalPosition : "center",     // 垂直位置：top, center, bottom 
						ShowOverlay : false,                // 是否显示遮罩层 
						ColorOverlay : "#000",            // 设置遮罩层的颜色 
						OpacityOverlay : 0.3            // 设置遮罩层的透明度 
					});
	    		return false;
	    	}
	    	myScroll.refresh();
	    },
	    error:function(xhr){
	      $('#wfAttachForLocal').removeAttr("disabled"); //移除disabled属性 
	      jNotify(xhr.responseText,{
					autoHide : true,                // 是否自动隐藏提示条 
					clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
					MinWidth : 20,                    // 最小宽度 
					TimeShown : 1500,                 // 显示时间：毫秒 
					ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
					HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
					LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
					HorizontalPosition : "center",     // 水平位置:left, center, right 
					VerticalPosition : "center",     // 垂直位置：top, center, bottom 
					ShowOverlay : false,                // 是否显示遮罩层 
					ColorOverlay : "#000",            // 设置遮罩层的颜色 
					OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
	      return false;
	    }
	  });
	})
	})