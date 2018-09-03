var datasourceScroll;  
var pullDownEl, pullDownL;  
var pullUpEl, pullUpL;  
var loadingStep = 0; //加载状态0默认，1显示加载状态，2执行加载数据，只有当为0时才能再次加载，这是防止过快拉动刷新  

function pullDownAction() { //下拉刷新事件  
    setTimeout(function() {  
        if(isExitsFunction('myFns.pull_down_refresh')){
            myFns.pull_down_refresh();//调用全局函数刷新
            pullDownEl.removeClass('loading');  
            pullDownL.html('下拉显示更多...');  
            pullDownEl['class'] = pullDownEl.attr('class');  
            pullDownEl.attr('class','').hide();  
            datasourceScroll.refresh();  
            loadingStep = 0;  
        }else{
            return false;
        }
    }, 500); //模拟网络堵塞1秒  
}  

function pullUpAction() { //上拉加载更多事件  
    setTimeout(function() {  
        //加载下一页内容和刷新
        if(isExitsFunction('myFns.pullUpAction')){ //先判断此函数是否存在
            myFns.pullUpAction();
            pullUpEl.removeClass('loading');  
            pullUpL.html('上拉显示更多...');  
            pullUpEl['class'] = pullUpEl.attr('class');  
            pullUpEl.attr('class','').hide();  
            datasourceScroll.refresh();  
            loadingStep = 0;  
        }else{
            return false;
        }
    }, 500); //模拟网络堵塞一秒
}  

function datasourceLoaded() {  
    pullDownEl = $('#pullDown');  
    pullDownL = pullDownEl.find('.pullDownLabel');  
    pullDownEl['class'] = pullDownEl.attr('class');  
    pullDownEl.attr('class','').hide();  
      
    pullUpEl = $('#pullUp');  
    pullUpL = pullUpEl.find('.pullUpLabel');  
    pullUpEl['class'] = pullUpEl.attr('class');  
    pullUpEl.attr('class','').hide();  
      
    datasourceScroll = new IScroll('#datasource-wrapper', {  
        probeType: 2, //probeType：1对性能没有影响。在滚动事件被触发时，滚动轴是不是忙着做它的东西。probeType：2总执行滚动，除了势头，反弹过程中的事件。这类似于原生的onscroll事件。probeType：3发出的滚动事件与到的像素精度。注意，滚动被迫requestAnimationFrame（即：useTransition：假）。  
        useTransition: false,
        scrollbars: false, //禁止滚动条  
        scrollX: true, //允许X轴滚动
        scrollY: true, //允许Y轴滚动
        click: false , //允许点击事件  
        preventDefault: false, //阻止默认事件
        preventDefaultException: { tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|A)$/ } //这个后面加|A,因为iscroll阻止了A的默认事件
    });  
    //滚动时  
    datasourceScroll.on('scroll', function(){  
        if(loadingStep == 0 && !pullDownEl.attr('class').match('flip|loading') && !pullUpEl.attr('class').match('flip|loading')){  
            if (this.y > 80) {  
                //下拉刷新效果  
                pullDownEl.attr('class',pullUpEl['class'])  
                pullDownEl.show();  
                datasourceScroll.refresh();  
                pullDownEl.addClass('flip');  
                pullDownL.html('松手开始刷新...');  
                loadingStep = 1;  
            }else if (this.y < (this.maxScrollY - 80)) {  
                //上拉刷新效果  
                pullUpEl.attr('class',pullUpEl['class'])  
                pullUpEl.show();  
                datasourceScroll.refresh();  
                pullUpEl.addClass('flip');  
                pullUpL.html('松手开始更新...');  
                loadingStep = 1;  
            }  
        }  
    });  
    //滚动完毕  
    datasourceScroll.on('scrollEnd',function(){  
        if(loadingStep == 1){  
            if (pullUpEl.attr('class').match('flip|loading')) {  
                pullUpEl.removeClass('flip').addClass('loading');  
                pullUpL.html('Loading...');  
                loadingStep = 2;  
                pullUpAction();  
            }else if(pullDownEl.attr('class').match('flip|loading')){  
                pullDownEl.removeClass('flip').addClass('loading');  
                pullDownL.html('Loading...');  
                loadingStep = 2;  
                pullDownAction();  
            }  
        }  
    });  
}  
document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);  

//是否存在指定函数 
function isExitsFunction(funcName){
  try {
    if (typeof(eval(funcName)) == "function") {
      return true;
    }
  } catch(e) {}
  return false;
}