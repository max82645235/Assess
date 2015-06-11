(function($){
	var defaults={
		time:3000,//�Զ�����ʱ�� 3000(3��)
		animate:'left',//Ŀǰ��ʱֻ�� top|left �����������Ч��
		onClass:'Gfouce-on',//���к�ѡ����ʽ��
		auto:true, //�Ƿ��Զ����� true[�Զ�����] | false[���Զ�����]
		hasTit:true //�����Ƿ��б��� true[�б��⣬��ӦͼƬtitle] | false[�ޱ���]
	}
	$.fn.Gfouce=function(options){
		options = $.extend({},defaults,options);
		this.each(function(){
			var self = $(this),
				imgBox = self.find('.imgBox'),
				img = imgBox.children(),
				oImg = img.find('img, embed, object'),
				moveH = oImg.height(),
				moveW = oImg.width(),
				imgLen = img.length,
				current = 0,
				zIndex = 100,
				time = null;
				isHover=false;
			
			//������к�
			self.append("<ol class='num'></ol>");
			for(var i = 1;i<=imgLen;i++){
				self.find('.num').append("<li>"+i+"</li>")
			}
			
			var numBox = self.find('.num'),
				num = numBox.find('li');
				
			num.eq(current).addClass(options.onClass);
			
			if(options.hasTit){
				var titBgDom = "<div class='t-bg'></div>"
				var titTxtDom = "<span class='t-txt'></span>"
				$(titBgDom).appendTo(self);	
				$('.t-bg').css({
					
				});
				$(titTxtDom).appendTo(self);
				$('.t-txt').css({
					'position':'absolute',
					'font-weight':'800'
				})
			}
			
			if(options.hasTit){
				var firstTxt = oImg.eq(0).attr('title');
				self.find('.t-txt').text(firstTxt)
			}
				
			//����ģʽ����
			function animateWay(marWay,moveWay){
					var obj = {};obj[marWay] = '-=' + moveWay + "px";
					var obj2 ={};obj2[marWay] = '0px';
					imgBox.animate(obj);
					
					if(current>imgLen-2){
						imgBox.stop().animate(obj2)
						current=0;
					}else{
						++current;
					}
					labelTo(current);
					if(options.hasTit){
						var titTxt = oImg.eq(current).attr('title');
						self.find('.t-txt').text(titTxt)
					}
			}
			
			//����ֹͣ����
			function tachNum(marWay,moveWay){
				self.hover(function(){
					stopAuto()
				},function(){				
					setAuto(marWay,moveWay)
				})
				
				num.bind('click',function(event){
					if(event.type == 'click'){
						var index = num.index(this);
						var obj3 = {};obj3[marWay] = - moveWay * index + "px";
						current = index;
						labelTo(index);
						imgBox.animate(obj3);
						self.find('.t-txt').text(oImg.eq(current).attr('title'));
					}else{
						stopAuto()
					}
				})
			}
								
			if(options.animate=="top"){
				setAuto("marginTop",moveH)
				tachNum("marginTop",moveH)
			}
			
			
			if(options.animate=="left"){
				imgBox.css({"width": moveW * imgLen + "px"})
				img.css({"float":"left"})
				setAuto("marginLeft",moveW)
				tachNum("marginLeft",moveW)
			}
			
			
			//��λ��ǰ���к�
			function labelTo(index){
				num.eq(index).addClass(options.onClass).siblings().removeClass(options.onClass);
			};
			
			//�����Զ�����
			function setAuto(marWay,moveWay){
					if(options.auto === false){
						return;
					};
					var speed = options.time;
					stopAuto();
					time = setInterval(function(){
						animateWay(marWay,moveWay)
					},speed);
			};
			
			//ֹͣ�Զ�����
			function stopAuto(){
				if(time !== null){
					clearInterval(time);
					time = null;
				};
			};
			
		})
	}
})(jQuery);