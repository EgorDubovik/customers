(function($) {
    $.fn.appointmentScheduler = function(options) {
      // Default options

    var defaultSettings = {
        startTime: '05:00', // Default start time
        endTime: '21:00', // Default end time
        timeSlotInterval: 60, // Default time slot interval in minutes
        currentWeeek :0,
        dayOrWeek : 'week',
        appointments : [],
        isAmPm : true,

    };
    
    var settings = $.extend({}, defaultSettings, options);

    var selectedDay = moment();
    var startTime = moment(settings.startTime,'HH:mm');
    var endTime = moment(settings.endTime,'HH:mm');
    var totalDuration = moment.duration(endTime.diff(startTime)).asMinutes();

      return this.each(function() {
        var $scheduler = $(this);
        var $calendar = $('<div>').addClass('main-container');
        var $navBarConteiner = $('<div>').addClass("nav-bar-conteiner");
        var $headerWrapper = $('<div>').addClass('header-wrapper');
        // Initialize the scheduler
        initializeScheduler();
  
        function initializeScheduler() {
          
          initMainConteiners();

          createNavBar();
          
          createCalendarView();
          
          fetchAppointments();
  
          
          $scheduler.on('click', '.prev-page', prefClickHandel);
          $scheduler.on('click', '.today-page', todayClickHandel);
          $scheduler.on('click', '.next-page', nextClickHandel);
        }
        
        function initMainConteiners(){

          $calendar.append($navBarConteiner);
          $calendar.append($headerWrapper);

          $scheduler.append($calendar);

        }

        function createNavBar(){
          var $nextPageButton = $('<div>').attr('type', 'button').addClass('next-page').text('>');
          var $prevPageButton = $('<div>').attr('type', 'button').addClass('prev-page').text('<');
          var $todayPageButton = $('<div>').attr('type', 'button').addClass('today-page').text('Today');
          var $navBarMonth = $('<div>').addClass('nav-bar-month').append($('<span>').addClass('text-conteiner').text(selectedDay.format('MMMM YYYY')));
          var $navBarInner = $('<div>').addClass('nav-bar-inner')
                                      .append($navBarMonth)
                                      .append($prevPageButton)
                                      .append($todayPageButton)
                                      .append($nextPageButton);
          $navBarConteiner.html($navBarInner);
          
        }

        function createCalendarView() {

            // Create the header row with day names
            
            createDaysHeader();
            
            
            // Create Schedule container

            var $scheduleWrapper = $('<div>').addClass('schedule-wrapper');

            var $timeSlots = $('<div>').addClass('time-slots');
            var startTime = moment(settings.startTime, 'HH:mm');
            var endTime = moment(settings.endTime, 'HH:mm');
            var timeSlotInterval = settings.timeSlotInterval;
            var currentTime = startTime.clone();
            
            var dayColumInnerItems = '<div class="day-events-conteiner"></div>';

            while (currentTime.isSameOrBefore(endTime)) {
              var $timeSlotText = $('<div>').addClass('time-slot-text').text(currentTime.format(((settings.isAmPm) ? 'hh A' : 'HH:mm')));  
              var $timeSlot = $('<div>').addClass('time-slot');
              $timeSlot.append($timeSlotText);
              $timeSlots.append($timeSlot);  
              if(!currentTime.isSame(endTime))
                dayColumInnerItems+='<div class="day-colum-inner-item"></div>';

              currentTime.add(timeSlotInterval, 'minutes');
            }
            var $timeSlotsInner = $('<div>').addClass('time-slots-inner').append($timeSlots);
            var $timeSlotsInnerConteiner = $('<div>').addClass('time-slots-inner-conteainer').append($timeSlotsInner);
            $scheduleWrapper.append($timeSlotsInnerConteiner);

            var $dayColum = $('<div>').addClass('day-colum').attr('data-i',i);
            $dayColum.append($dayColumInner);
            

            var $scheduleContainerInner = $('<div>').addClass('schedule-conteiner-inner');

            if(settings.dayOrWeek == "week"){
              for (var i = 0; i < 7; i++) {
                var $dayColumInner = $('<div>').addClass('day-colum-inner');
                $dayColumInner.append(dayColumInnerItems);
                var $dayColum = $('<div>').addClass('day-colum').append($dayColumInner);

                $scheduleContainerInner.append($dayColum);
              }
            } else if(settings.dayOrWeek == "days"){
              var $dayColumInner = $('<div>').addClass('day-colum-inner');
              $dayColumInner.append(dayColumInnerItems);
              var $dayColum = $('<div>').addClass('day-colum').append($dayColumInner);

              $scheduleContainerInner.append($dayColum);
            }
            var $scheduleContainer = $('<div>').addClass('schedule-container').append($scheduleContainerInner);
            $scheduleWrapper.append($scheduleContainer);
            $calendar.append($scheduleWrapper);
            
            $scheduler.append($calendar);
        }
        
        function createDaysHeader(){
            $headerWrapper.empty();
            $headerWrapper.append($('<div>').addClass('header-first-item'));
              
            var $headerContainer = $('<div>').addClass('header-container');
            var $headerItems = $('<div>').addClass('header-items');
            for (var i = 0; i < 7; i++) {
                
                var dayName = selectedDay.clone().weekday(i).format('ddd');
                var dayNumber = selectedDay.clone().weekday(i).format('DD');
                var $headerItem = $('<div>').addClass('header-item')
                if(settings.dayOrWeek == "week"){
                  if(selectedDay.clone().weekday(i).startOf('day').isSame(moment().startOf('day'))){
                    $headerItem.addClass('today');
                  } 
                }else if(settings.dayOrWeek == "days"){
                  if(selectedDay.clone().weekday(i).startOf('day').isSame(selectedDay.clone().startOf('day'))){
                    $headerItem.addClass('today');
                  } 
                } 
                $headerItem.append($('<div>').addClass('header-item-text').text(dayName+' '+dayNumber));
                $headerItems.append($headerItem);
            }
            $headerContainer.append($headerItems);
            $headerWrapper.append($headerContainer);
        }
  
        function fetchAppointments() {

          cleanEvents();
          var selectedAppointments = getAppointments();
          var groupedAppointments = groupAppointments(selectedAppointments);

          groupedAppointments.forEach(group => {
            
            for(var i = 0; i < group.appointments.length; i++ ){
              
              var event = createEvent(group.appointments[i],i,group.appointments.length);
              if(settings.dayOrWeek == "week")
                var weekIndex  = group.appointments[i].startTime.isoWeekday();
              else if(settings.dayOrWeek == "days")
                var weekIndex  = 0;
              weekIndex = (weekIndex == 7) ? 0 : weekIndex;
              console.log('view:', group.appointments[i], 'wi:',weekIndex);
              $scheduler.find('.day-events-conteiner').eq(weekIndex).append(event);  
            }
          }); 
        }

        function cleanEvents(){
          $calendar.find('.day-events-conteiner').each(function(){
            $(this).empty();
          })
        }

        function getAppointments(){
          var appointments = settings.appointments;
          if(settings.dayOrWeek == "week"){
            var selectedStartDay = selectedDay.clone().weekday(0).set({hour:00,minute:00});
            var selectedEndDay = selectedDay.clone().weekday(6).set({hour:23,minute:59});
          } else if(settings.dayOrWeek == "days"){
            var selectedStartDay = selectedDay.clone().set({hour:00,minute:00});
            var selectedEndDay = selectedDay.clone().set({hour:23,minute:59});
          }

          var selectedAppointments = appointments.filter(appointment => {
            console.log('between',appointment,appointment.startTime.isBetween(selectedStartDay, selectedEndDay))
            return (
              appointment.startTime.isBetween(selectedStartDay, selectedEndDay) ||
              appointment.endTime.isBetween(selectedStartDay, selectedEndDay) ||
              (appointment.startTime.isBefore(selectedStartDay) && appointment.endTime.isAfter(selectedEndDay))
            );
          });
          return selectedAppointments;
          
        }
        
        function createEvent(appintment, groupIndex, groupAppointmentsLength){
          var $eventTitle = $('<div>').addClass('event-title').addClass((appintment.status) ? appintment.status : "" ).html('<a href="'+((appintment.href) ? appintment.href : '#')+'">'+appintment.title+'</a>');
          var $eventTime = $('<div>').addClass('event-title').addClass((appintment.status) ? appintment.status : "" ).text(appintment.startTime.format('HH:mm')+' - '+appintment.endTime.format('HH:mm'));
          var $eventConteinerInner = $('<div>').addClass('event-conteiner-inner');
          $eventConteinerInner.append($eventTitle).append($eventTime);

          var eventBackground = '#3f87ed'; // Default background color
          
          if(appintment.status == "pending")
            eventBackground = appintment.background;
          else if (appintment.status == "done")
            eventBackground = "#c2c2c2";

          var $eventConteinerBackground = $('<div>').addClass('event-conteiner-background').css('background',eventBackground);
          
          var percentage = calculateEventTop(appintment);
          var width = (100/groupAppointmentsLength);
          var $eventConteiner = $('<div>').addClass('event-conteiner')
                                          .css('height',percentage.height+'%')
                                          .css('top',percentage.top+'%')
                                          .css('width',width+'%')
                                          .css('left',(width*groupIndex)+'%')
                                          .css('right','auto');

          $eventConteiner.append($eventConteinerBackground).append($eventConteinerInner);
          return $eventConteiner;
        }

        function calculateEventTop(appointment){
          var curentTime = moment(appointment.startTime.format('HH:mm'),'HH:mm');
          var curentTimeEnd = moment(appointment.endTime.format('HH:mm'),'HH:mm');
          var elapsedDuration = moment.duration(curentTime.diff(startTime)).asMinutes();
          var heightDuration = moment.duration(curentTimeEnd.diff(curentTime)).asMinutes();
          var percentage = (elapsedDuration / totalDuration) * 100;
          var percentageHeight = (heightDuration / totalDuration) * 100;
          return { top : percentage, height: percentageHeight};
        }


        function groupAppointments(appointments) {
          const groups = [];
        
          appointments.forEach(appointment => {
            const matchingGroup = groups.find(group =>
              appointment.startTime.isBetween(group.start, group.end, null, '[)') ||
              appointment.endTime.isBetween(group.start, group.end, null, '(]')
            );
        
            if (matchingGroup) {
              matchingGroup.appointments.push(appointment);
            } else {
              groups.push({
                start: appointment.startTime,
                end: appointment.endTime,
                appointments: [appointment]
              });
            }
          });
          
          return groups;

        }
        

        function handleTimeslotClick() {
          // Handle the click event on a timeslot
          // This could involve opening a modal for scheduling an appointment
        }

        function prefClickHandel(){
            selectedDay.subtract(1,settings.dayOrWeek);
            createNavBar();
            createDaysHeader();
            fetchAppointments();
        }
        function nextClickHandel(){
            selectedDay.subtract(-1,settings.dayOrWeek);
            createNavBar();
            createDaysHeader();
            fetchAppointments();
        }
        function todayClickHandel(){
            selectedDay = moment();
            createNavBar();
            createDaysHeader();
            fetchAppointments();
        }
        
        // Add any additional helper functions or methods here
      });
    };
  }(jQuery));
  