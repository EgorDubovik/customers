!function(e){e.fn.appointmentScheduler=function(a){var d={startTime:"00:00",endTime:"23:00",timeSlotInterval:60,currentWeeek:0,dayOrWeek:e(document).width()>=576?"week":"days",appointments:[],isAmPm:!0,eventBackground:"#3f87ed"},n=e.extend({},d,a),i=moment(),s=moment(n.startTime,"HH:mm"),r=moment(n.endTime,"HH:mm"),o=moment.duration(r.diff(s)).asMinutes();return this.each(function(){var a,d,r,p=e(this),m=e("<div>").addClass("main-container").addClass("disable-select"),l=e("<div>").addClass("nav-bar-conteiner"),c=e("<div>").addClass("header-wrapper"),v=!1,f=0,u=1500/o,h=0,y=0,$=0,C=0;function k(n){let i=n.target.closest("a");if(i){console.log(i.href),window.location=i.href;return}v=!0,(a=e(this)).css("width","100%").css("left","0%").css("z-index",9999),d=a.parent().parent().parent().index(),C=a.height(),f=n.pageY-a.offset().top,$=p.find(".day-colum-inner").offset().top}function g(n){if(v){var i=n.pageY-$-f,r=e(this).parent().index();if(r!=d&&(a.remove(),p.find(".day-colum-inner").eq(r).find(".day-events-conteiner").append(a),d=r),i>=0&&C+i<=y&&(t=Math.round(i/h),i>=t*h-2&&i<=t*h+2)){var o=100*(i=t*h)/y;a.css("top",o+"%");var m=T(o),l=T(a.attr("data-height")),c=s.clone().add(m,"minutes"),u=s.clone().add(m+l,"minutes");a.find(".event-time").text(c.format("HH:mm")+" - "+u.format("HH:mm"))}}}function H(e){if(v=!1,a){var i=a.find(".event-time").text().split(" - "),s=r[a.attr("data-index")];s.startTime=moment(s.startTime.weekday(d).format("YYYY-MM-DD")+" "+i[0]),s.endTime=moment(s.startTime.weekday(d).format("YYYY-MM-DD")+" "+i[1]),O(),n.callBack()}}function T(e){return Math.round(o*e/100)}function w(){var a=e("<div>").attr("type","button").addClass("next-page").text(">"),d=e("<div>").attr("type","button").addClass("prev-page").text("<"),n=e("<div>").attr("type","button").addClass("today-page").text("Today"),s=e("<div>").addClass("nav-bar-month").append(e("<span>").addClass("text-conteiner").text(i.format("MMMM YYYY"))),r=e("<div>").addClass("nav-bar-inner").append(s).append(d).append(n).append(a);l.html(r)}function x(){c.empty(),c.append(e("<div>").addClass("header-first-item"));for(var a=e("<div>").addClass("header-container"),d=e("<div>").addClass("header-items"),s=0;s<7;s++){var r=i.clone().weekday(s).format("ddd"),o=i.clone().weekday(s).format("DD"),p=e("<div>").addClass("header-item");"week"==n.dayOrWeek?i.clone().weekday(s).startOf("day").isSame(moment().startOf("day"))&&p.addClass("today"):"days"==n.dayOrWeek&&i.clone().weekday(s).startOf("day").isSame(i.clone().startOf("day"))&&p.addClass("today"),p.append(e("<div>").addClass("header-item-text").text(r+" "+o)),d.append(p)}a.append(d),c.append(a)}function O(){m.find(".day-events-conteiner").each(function(){e(this).empty()}),(function e(a){let d=[];return a.forEach(e=>{let a=d.find(a=>e.startTime.isBetween(a.start,a.end,null,"[)")||e.endTime.isBetween(a.start,a.end,null,"(]"));a?a.appointments.push(e):d.push({start:e.startTime,end:e.endTime,appointments:[e]})}),d})(r=function e(){var a=n.appointments;if("week"==n.dayOrWeek)var d=i.clone().weekday(0).set({hour:"00",minute:"00"}),s=i.clone().weekday(6).set({hour:23,minute:59});else if("days"==n.dayOrWeek)var d=i.clone().set({hour:"00",minute:"00"}),s=i.clone().set({hour:23,minute:59});return a.filter(e=>e.startTime.isBetween(d,s)||e.endTime.isBetween(d,s)||e.startTime.isBefore(d)&&e.endTime.isAfter(s))}()).forEach(e=>{for(var a=0;a<e.appointments.length;a++){var d=r.indexOf(e.appointments[a]),i=b(e.appointments[a],a,e.appointments.length,d);if("week"==n.dayOrWeek)var s=e.appointments[a].startTime.isoWeekday();else if("days"==n.dayOrWeek)var s=0;s=7==s?0:s,p.find(".day-events-conteiner").eq(s).append(i)}})}function b(a,d,i,r){var p=e("<div>").addClass("event-conteiner");a.addClass&&p.addClass(a.addClass);var m,l,c,v,f,u=e("<div>").addClass("event-title").addClass("disable-select").html('<a href="'+(a.href?a.href:"#")+'">'+a.title+"</a>"),h=e("<div>").addClass("event-title").addClass("event-time").text(a.startTime.format("HH:mm")+" - "+a.endTime.format("HH:mm")),y=e("<div>").addClass("event-conteiner-inner").addClass("disable-select");y.append(u).append(h);var $=a.background?a.background:n.eventBackground,C=e("<div>").addClass("event-conteiner-background").css("background",$),k=(m=a,l=moment(m.startTime.format("HH:mm"),"HH:mm"),c=moment(m.endTime.format("HH:mm"),"HH:mm"),v=moment.duration(l.diff(s)).asMinutes(),f=moment.duration(c.diff(l)).asMinutes(),{top:v/o*100,height:f/o*100}),g=100/i;return p.attr("data-index",r).attr("data-height",k.height).css("height",k.height+"%").css("top",k.top+"%").css("width",g+"%").css("left",g*d+"%").css("right","auto"),p.append(C).append(y),p}function Y(){}function W(){i.subtract(1,n.dayOrWeek),w(),x(),O()}function _(){i.subtract(-1,n.dayOrWeek),w(),x(),O()}function M(){i=moment(),w(),x(),O()}m.append(l),m.append(c),p.append(m),w(),function a(){x();for(var d=e("<div>").addClass("schedule-wrapper"),i=e("<div>").addClass("time-slots"),s=moment(n.startTime,"HH:mm"),r=moment(n.endTime,"HH:mm"),o=n.timeSlotInterval,l=s.clone(),c='<div class="day-events-conteiner"></div>';l.isSameOrBefore(r);){var v=e("<div>").addClass("time-slot-text").text(l.format(n.isAmPm?"hh A":"HH:mm")),f=e("<div>").addClass("time-slot");f.append(v),i.append(f),l.isSame(r)||(c+='<div class="day-colum-inner-item"></div>'),l.add(o,"minutes")}var u=e("<div>").addClass("time-slots-inner").append(i),h=e("<div>").addClass("time-slots-inner-conteainer").append(u);d.append(h);var y=e("<div>").addClass("day-colum").attr("data-i",C);y.append(k);var $=e("<div>").addClass("schedule-conteiner-inner");if("week"==n.dayOrWeek)for(var C=0;C<7;C++){var k=e("<div>").addClass("day-colum-inner");k.append(c);var y=e("<div>").addClass("day-colum").append(k);$.append(y)}else if("days"==n.dayOrWeek){var k=e("<div>").addClass("day-colum-inner");k.append(c);var y=e("<div>").addClass("day-colum").append(k);$.append(y)}var g=e("<div>").addClass("schedule-container").append($);d.append(g),m.append(d),p.append(m)}(),O(),p.on("click",".prev-page",W),p.on("click",".today-page",M),p.on("click",".next-page",_),h=u*(y=p.find(".day-colum-inner").height())/100,console.log("deltaPx",h),p.on("mousedown",".event-conteiner",k),p.on("mousemove",".day-colum-inner",g),p.on("mouseup",".event-conteiner",H)})}}(jQuery);