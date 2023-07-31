!function(e){e.fn.appointmentScheduler=function(a){var n,i=e.extend({},{startTime:"00:00",endTime:"23:00",timeSlotInterval:60,currentWeeek:0,dayOrWeek:"week",appointments:[],isAmPm:!0,eventBackground:"#3f87ed",timeIndicator:!0,endDrag:null,isModayFirst:!0},a);i.isModayFirst&&moment.updateLocale("en",{week:{dow:1}});var d=moment(),s=moment(i.startTime,"HH:mm"),r=moment(i.endTime,"HH:mm"),o=moment.duration(r.diff(s)).asMinutes(),p=e(this),m=e("<div>").addClass("main-container").addClass("disable-select"),c=e("<div>").addClass("nav-bar-conteiner"),l=e("<div>").addClass("header-wrapper"),v=0,f=1500/o,u=0,h=0,y=0,C=0;this.init=function(){return $(),this};var $=function(){k.init(),T.init(),g()},T={selectedAppointments:[],init:function(){this.cleanAppointments(),this.fetchAppointments()},fetchAppointments:function(){this.selectedAppointments=this.getAppointments(),this.selectedAppointments=this.selectedAppointments.sort((e,a)=>moment(e.startTime).diff(moment(a.startTime))),this.groupAppointments(this.selectedAppointments,[]).forEach(e=>{for(var a=0;a<e.appointments.length;a++){var n=this.selectedAppointments.indexOf(e.appointments[a]),d=this.createEvent(e.appointments[a],a,e.appointments.length,n);if("week"==i.dayOrWeek)var s=e.appointments[a].startTime.weekday();else if("days"==i.dayOrWeek)var s=0;p.find(".day-events-conteiner").eq(s).append(d)}})},getAppointments:function(){var e=i.appointments;if("week"==i.dayOrWeek)var a=d.clone().weekday(0).set({hour:"00",minute:"00"}),n=d.clone().weekday(6).set({hour:23,minute:59});else if("days"==i.dayOrWeek)var a=d.clone().set({hour:"00",minute:"00"}),n=d.clone().set({hour:23,minute:59});return e.filter(e=>(e.startTime=moment(e.startTime),e.endTime=moment(e.endTime),e.startTime.isBetween(a,n)||e.endTime.isBetween(a,n)||e.startTime.isBefore(a)&&e.endTime.isAfter(n)))},createEvent:function(a,n,d,s){var r=e("<div>").addClass("event-conteiner");a.addClass&&r.addClass(a.addClass);var o=e("<div>").addClass("event-title").addClass("disable-select").html('<a href="'+(a.href?a.href:"#")+'">'+a.title+"</a>"),p=e("<div>").addClass("event-title").addClass("event-time").text(a.startTime.format("HH:mm")+" - "+a.endTime.format("HH:mm")),m=e("<div>").addClass("event-conteiner-inner").addClass("disable-select");m.append(o).append(p);var c=a.background?a.background:i.eventBackground,l=e("<div>").addClass("event-conteiner-background").css("background",c),v=this.calculateEventTop(a),f=100/d;return r.attr("data-index",s).attr("data-height",v.height).css("height",v.height+"%").css("top",v.top+"%").css("width",f+"%").css("left",f*n+"%").css("right","auto"),r.append(l).append(m),r},calculateEventTop:function(e){var a=w.getTopProcentByTime(e.startTime);return{top:a,height:w.getTopProcentByTime(e.endTime)-a}},groupAppointments:function(e,a){return e.forEach(e=>{let n=a.find(a=>e.startTime.isBetween(a.start,a.end,null,"[)")||e.endTime.isBetween(a.start,a.end,null,"(]"));n?n.appointments.push(e):a.push({start:e.startTime,end:e.endTime,appointments:[e]})}),console.log(a),a},cleanAppointments:function(){m.find(".day-events-conteiner").each(function(){e(this).empty()})}},k={init:function(){console.log(d),this.clearCalendar(),this.initMainConteiners(),this.createNavBar(),this.createCalendarView(),w.initVariables()},clearCalendar:function(){m=e("<div>").addClass("main-container").addClass("disable-select"),c=e("<div>").addClass("nav-bar-conteiner"),l=e("<div>").addClass("header-wrapper"),p.empty()},initMainConteiners:function(){m.append(c),m.append(l),p.append(m)},createNavBar:function(){var a=e("<div>").attr("type","button").addClass("next-page").text(">"),n=e("<div>").attr("type","button").addClass("prev-page").text("<"),i=e("<div>").attr("type","button").addClass("today-page").text("Today"),s=e("<div>").addClass("nav-bar-month").append(e("<span>").addClass("text-conteiner").text(d.format("MMMM YYYY"))),r=e("<div>").addClass("nav-bar-inner").append(s).append(n).append(i).append(a);c.html(r)},createCalendarView:function(){this.createDaysHeader();for(var a=e("<div>").addClass("schedule-wrapper"),n=e("<div>").addClass("time-slots"),d=moment(i.startTime,"HH:mm"),s=moment(i.endTime,"HH:mm"),r=i.timeSlotInterval,o=d.clone(),c='<div class="day-events-conteiner"></div>';o.isSameOrBefore(s);){var l=e("<div>").addClass("time-slot-text").text(o.format(i.isAmPm?"hh A":"HH:mm")),v=e("<div>").addClass("time-slot");v.append(l),n.append(v),o.isSame(s)||(c+='<div class="day-colum-inner-item"></div>'),o.add(r,"minutes")}i.timeIndicator&&this.createIndicatorNow(n);var f=e("<div>").addClass("time-slots-inner").append(n),u=e("<div>").addClass("time-slots-inner-conteainer").append(f);a.append(u);var h=e("<div>").addClass("day-colum").attr("data-i",C);h.append($);var y=e("<div>").addClass("schedule-conteiner-inner");if("week"==i.dayOrWeek)for(var C=0;C<7;C++){var $=e("<div>").addClass("day-colum-inner");$.append(c);var h=e("<div>").addClass("day-colum").append($);y.append(h)}else if("days"==i.dayOrWeek){var $=e("<div>").addClass("day-colum-inner");$.append(c);var h=e("<div>").addClass("day-colum").append($);y.append(h)}var T=e("<div>").addClass("schedule-container").append(y);a.append(T),m.append(a),p.append(m)},createDaysHeader:function(){l.empty(),l.append(e("<div>").addClass("header-first-item"));for(var a=e("<div>").addClass("header-container"),n=e("<div>").addClass("header-items"),s=0;s<7;s++){var r="week"==i.dayOrWeek?d.clone().weekday(s).format("ddd"):d.clone().weekday(s).format("dd"),o=d.clone().weekday(s).format("DD"),p=e("<div>").addClass("header-item");"week"==i.dayOrWeek?d.clone().weekday(s).startOf("day").isSame(moment().startOf("day"))&&p.addClass("today"):"days"==i.dayOrWeek&&d.clone().weekday(s).startOf("day").isSame(d.clone().startOf("day"))&&p.addClass("today"),p.append(e("<div>").addClass("header-item-text").text(r+" "+o)),n.append(p)}a.append(n),l.append(a)},createIndicatorNow:function(e){var a=moment();if(a.isBetween(moment(i.startTime,"hh:mm"),moment(i.endTime,"hh:mm"))){var n=w.getTopProcentByTime(moment()),d=a.format(i.isAmPm?"hh:mm A":"HH:mm");e.append('<div aria-hidden="true" class="now-line" style="top: '+n+'%;"><div class="time-now-ind">'+d+"</div></div>")}}},g=function(){var a=!1,r=null,o=!1;function m(i){let d=i.target.closest("a");if(d){window.location=d.href;return}a=!0,o=!1,(r=e(this)).css("width","100%").css("left","0%").css("z-index",9999),n=r.parent().parent().parent().index(),C=r.height(),v=i.pageY-r.offset().top,y=p.find(".day-colum-inner").offset().top}function c(i){if(a){var d=i.pageY-y-v,m=e(this).parent().index();if(m!=n&&(o=!0,r.remove(),p.find(".day-colum-inner").eq(m).find(".day-events-conteiner").append(r),n=m),d>=0&&C+d<=h&&(t=Math.round(d/u),d>=t*u-2&&d<=t*u+2)){o=!0;var c=100*(d=t*u)/h;r.css("top",c+"%");var l=w.procentToMinutes(c),f=w.procentToMinutes(r.attr("data-height")),$=s.clone().add(l,"minutes"),T=s.clone().add(l+f,"minutes");r.find(".event-time").text($.format("HH:mm")+" - "+T.format("HH:mm"))}}}function l(e){if(a=!1,r){if(o){var d=r.find(".event-time").text().split(" - "),s=T.selectedAppointments[r.attr("data-index")];s.startTime="week"==i.dayOrWeek?moment(s.startTime.weekday(n).format("YYYY-MM-DD")+" "+d[0]):moment(s.startTime.format("YYYY-MM-DD")+" "+d[0]),s.endTime="week"==i.dayOrWeek?moment(s.startTime.weekday(n).format("YYYY-MM-DD")+" "+d[1]):moment(s.startTime.format("YYYY-MM-DD")+" "+d[1]),T.init(),null!=i.endDrag&&i.endDrag()}T.init()}o=!1}function f(){console.log(i.dayOrWeek),d.subtract(1,i.dayOrWeek),H()}function $(){d.subtract(-1,i.dayOrWeek),H()}function g(){d=moment(),H()}function H(){k.createNavBar(),k.createDaysHeader(),T.init()}p.off(),p.on("click",".prev-page",f),p.on("click",".today-page",g),p.on("click",".next-page",$),p.on("mousedown",".event-conteiner",m),p.on("mousemove",".day-colum-inner",c),p.on("mouseup",".event-conteiner",l)},w={getTopProcentByTime:function(e){var a,n=moment(e.format("HH:mm"),"HH:mm");return moment.duration(n.diff(s)).asMinutes()/o*100},procentToMinutes:function(e){return Math.round(o*e/100)},initVariables:function(){u=f*(h=p.find(".day-colum-inner").height())/100}};return this.changeView=function(e){i.dayOrWeek=e,$()},this.init()}}(jQuery);