<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>DSWD WEB</title>
      <link rel= "shortcut icon" type="image/png" href="{{('/Logo/logo.png')}}">
      <!-- Fonts -->
      <link rel="preconnect" href="https://fonts.bunny.net">
      <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
      <!-- Styles -->
      <style>
         *, *:before, *:after {
         -moz-box-sizing: border-box;
         -webkit-box-sizing: border-box;
         box-sizing: border-box;
         }
         body {
         font-family: 'HelveticaNeue-UltraLight', 'Helvetica Neue UltraLight', 'Helvetica Neue', Arial, Helvetica, sans-serif;
         font-weight: 100;
         color: black;
         margin: 0;
         padding: 0;
         -webkit-touch-callout: none;
         -webkit-user-select: none;
         -khtml-user-select: none;
         -moz-user-select: none;
         -ms-user-select: none;
         user-select: none;
         }
         #calendar {
         -webkit-transform: translate3d(0, 0, 0);
         -moz-transform: translate3d(0, 0, 0);
         transform: translate3d(0, 0, 0);
         width: 1200px;
         margin: 0 auto;
         height: 570px;
         overflow: hidden;
         background-color: white;
         }
         .header {
         height: 50px;
         width: 1200px;
         background: rgba(230, 230, 230, 1);
         text-align: center;
         position: relative;
         z-index: 100;
         }
         .header h1 {
         margin: 0;
         padding: 0;
         font-size: 20px;
         line-height: 50px;
         font-weight: 100;
         letter-spacing: 1px;
         color: black;
         }
         .left, .right {
         position: absolute;
         width: 0px;
         height: 0px;
         border-style: solid;
         top: 50%;
         margin-top: -7.5px;
         cursor: pointer;
         }
         .left {
         border-width: 7.5px 10px 7.5px 0;
         border-color: transparent rgba(100, 100, 100, 1) transparent transparent;
         left: 20px;
         }
         .right {
         border-width: 7.5px 0 7.5px 10px;
         border-color: transparent transparent transparent rgba(100, 100, 100, 1);
         right: 20px;
         }
         .month {
         opacity: 0;
         }
         .month.new {
         -webkit-animation: fadeIn 1s ease-out;
         opacity: 1;
         }
         .month.in.next {
         -webkit-animation: moveFromTopFadeMonth .4s ease-out;
         -moz-animation: moveFromTopFadeMonth .4s ease-out;
         animation: moveFromTopFadeMonth .4s ease-out;
         opacity: 1;
         }
         .month.out.next {
         -webkit-animation: moveToTopFadeMonth .4s ease-in;
         -moz-animation: moveToTopFadeMonth .4s ease-in;
         animation: moveToTopFadeMonth .4s ease-in;
         opacity: 1;
         }
         .month.in.prev {
         -webkit-animation: moveFromBottomFadeMonth .4s ease-out;
         -moz-animation: moveFromBottomFadeMonth .4s ease-out;
         animation: moveFromBottomFadeMonth .4s ease-out;
         opacity: 1;
         }
         .month.out.prev {
         -webkit-animation: moveToBottomFadeMonth .4s ease-in;
         -moz-animation: moveToBottomFadeMonth .4s ease-in;
         animation: moveToBottomFadeMonth .4s ease-in;
         opacity: 1;
         }
         .week {
         background: #E0E0E0;
         width: 1200px;
         }
         .day {
         display: inline-block;
         width: 171.4px;
         padding: 10px;
         text-align: center;
         vertical-align: top;
         cursor: pointer;
         background: white;
         position: relative;
         z-index: 100;
         }
         .day.other {
         color: rgba(0, 0, 0, .3);
         }
         .day.today {
         color: rgba(56, 128, 168, 1);
         }
         .day-name {
         font-size: 9px;
         text-transform: uppercase;
         margin-bottom: 5px;
         color: rgba(0, 0, 0, .5);
         letter-spacing: .7px;
         }
         .day-number {
         font-size: 24px;
         letter-spacing: 1.5px;
         }
         .day .day-events {
         list-style: none;
         margin-top: 3px;
         text-align: center;
         height: 12px;
         line-height: 6px;
         overflow: hidden;
         }
         .day .day-events span {
         vertical-align: top;
         display: inline-block;
         padding: 0;
         margin: 0;
         width: 5px;
         height: 5px;
         line-height: 5px;
         margin: 0 1px;
         }
         .blue { background: rgba(56, 128, 168, 1); }
         .yellow { background: rgba(249, 233, 0, 1); }
         .details {
         position: relative;
         width: 1200px;
         height: 75px;
         background: rgba(210, 210, 210, 1);
         margin-top: 5px;
         border-radius: 4px;
         }
         .details.in {
         -webkit-animation: moveFromTopFade .5s ease both;
         -moz-animation: moveFromTopFade .5s ease both;
         animation: moveFromTopFade .5s ease both;
         }
         .details.out {
         -webkit-animation: moveToTopFade .5s ease both;
         -moz-animation: moveToTopFade .5s ease both;
         animation: moveToTopFade .5s ease both;
         }
         .arrow {
         position: absolute;
         top: -5px;
         left: 50%;
         margin-left: -2px;
         width: 0px;
         height: 0px;
         border-style: solid;
         border-width: 0 5px 5px 5px;
         border-color: transparent transparent rgba(210, 210, 210, 1) transparent;
         transition: all 0.7s ease;
         }
         .events {
         height: 75px;
         padding: 7px 0;
         overflow-y: auto;
         overflow-x: hidden;
         }
         .events.in {
         -webkit-animation: fadeIn .3s ease both;
         -moz-animation: fadeIn .3s ease both;
         animation: fadeIn .3s ease both;
         }
         .events.in {
         -webkit-animation-delay: .3s;
         -moz-animation-delay: .3s;
         animation-delay: .3s;
         }
         .details.out .events {
         -webkit-animation: fadeOutShrink .4s ease both;
         -moz-animation: fadeOutShink .4s ease both;
         animation: fadeOutShink .4s ease both;
         }
         .events.out {
         -webkit-animation: fadeOut .3s ease both;
         -moz-animation: fadeOut .3s ease both;
         animation: fadeOut .3s ease both;
         }
         .event {
         font-size: 16px;
         line-height: 22px;
         letter-spacing: .5px;
         padding: 2px 16px;
         vertical-align: top;
         }
         .event.empty {
         color: black;
         }
         .event-category {
         height: 10px;
         width: 10px;
         display: inline-block;
         margin: 6px 0 0;
         vertical-align: top;
         }
         .event span {
         display: inline-block;
         padding: 0 0 0 7px;
         }
         .legend {
         position: absolute;
         bottom: 0;
         width: 100%;
         height: 30px;
         background: rgba(240, 240, 240, 1);
         line-height: 30px;
         }
         .entry {
         position: relative;
         padding: 0 0 0 25px;
         font-size: 13px;
         display: inline-block;
         line-height: 30px;
         background: transparent;
         }
         .entry:after {
         position: absolute;
         content: '';
         height: 5px;
         width: 5px;
         top: 12px;
         left: 14px;
         }
         .entry.blue:after { background: rgba(56, 128, 168, 1); }
         .entry.yellow:after { background: rgba(249, 233, 0, 1); }
         /* Animations are cool!  */
         @-webkit-keyframes moveFromTopFade {
         from { opacity: .3; height:0px; margin-top:0px; -webkit-transform: translateY(-100%); }
         }
         @-moz-keyframes moveFromTopFade {
         from { height:0px; margin-top:0px; -moz-transform: translateY(-100%); }
         }
         @keyframes moveFromTopFade {
         from { height:0px; margin-top:0px; transform: translateY(-100%); }
         }
         @-webkit-keyframes moveToTopFade {
         to { opacity: .3; height:0px; margin-top:0px; opacity: 0.3; -webkit-transform: translateY(-100%); }
         }
         @-moz-keyframes moveToTopFade {
         to { height:0px; -moz-transform: translateY(-100%); }
         }
         @keyframes moveToTopFade {
         to { height:0px; transform: translateY(-100%); }
         }
         @-webkit-keyframes moveToTopFadeMonth {
         to { opacity: 0; -webkit-transform: translateY(-30%) scale(.95); }
         }
         @-moz-keyframes moveToTopFadeMonth {
         to { opacity: 0; -moz-transform: translateY(-30%); }
         }
         @keyframes moveToTopFadeMonth {
         to { opacity: 0; -moz-transform: translateY(-30%); }
         }
         @-webkit-keyframes moveFromTopFadeMonth {
         from { opacity: 0; -webkit-transform: translateY(30%) scale(.95); }
         }
         @-moz-keyframes moveFromTopFadeMonth {
         from { opacity: 0; -moz-transform: translateY(30%); }
         }
         @keyframes moveFromTopFadeMonth {
         from { opacity: 0; -moz-transform: translateY(30%); }
         }
         @-webkit-keyframes moveToBottomFadeMonth {
         to { opacity: 0; -webkit-transform: translateY(30%) scale(.95); }
         }
         @-moz-keyframes moveToBottomFadeMonth {
         to { opacity: 0; -webkit-transform: translateY(30%); }
         }
         @keyframes moveToBottomFadeMonth {
         to { opacity: 0; -webkit-transform: translateY(30%); }
         }
         @-webkit-keyframes moveFromBottomFadeMonth {
         from { opacity: 0; -webkit-transform: translateY(-30%) scale(.95); }
         }
         @-moz-keyframes moveFromBottomFadeMonth {
         from { opacity: 0; -webkit-transform: translateY(-30%); }
         }
         @keyframes moveFromBottomFadeMonth {
         from { opacity: 0; -webkit-transform: translateY(-30%); }
         }
         @-webkit-keyframes fadeIn  {
         from { opacity: 0; }
         }
         @-moz-keyframes fadeIn  {
         from { opacity: 0; }
         }
         @keyframes fadeIn  {
         from { opacity: 0; }
         }
         @-webkit-keyframes fadeOut  {
         to { opacity: 0; }
         }
         @-moz-keyframes fadeOut  {
         to { opacity: 0; }
         }
         @keyframes fadeOut  {
         to { opacity: 0; }
         }
         @-webkit-keyframes fadeOutShink  {
         to { opacity: 0; padding: 0px; height: 0px; }
         }
         @-moz-keyframes fadeOutShink  {
         to { opacity: 0; padding: 0px; height: 0px; }
         }
         @keyframes fadeOutShink  {
         to { opacity: 0; padding: 0px; height: 0px; }
         }
         @media only screen and (max-width: 658px) {
         #calendar, .header, .details {
         width:300px;
         }
         .day{
         width:42.8px;
         }
         }
         @media only screen and (min-width: 658px) and (max-width: 1302px) {
         #calendar, .header, .details {
         width: 570px;
         }
         .day {
         width: 81.5px;
         }
         }
      </style>
   </head>
   <body>
      <div class="requests">
      <div class="filter">
         <div class="tableactions">
            <div id="divide">
               <div class="dropdown" style="float:right;">
                  <button class="dropbtn"><i class="bi bi-filter"></i></button>
                  <div class="dropdown-content">
                     <p id="filterlabel">Filter By</p>
                     <hr>
                     <p>Status</p>
                     <a>
                        <div class="form-check">
                           <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                           <label class="form-check-label" for="flexCheckDefault">
                           Pending
                           </label>
                        </div>
                     </a>
                     <a>
                        <div class="form-check" id="margincheck">
                           <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                           <label class="form-check-label" for="flexCheckDefault">
                           Approved and Ongoing
                           </label>
                        </div>
                     </a>
                     <hr>
                     <div class="buttons">
                        <button class="cancelbtn">Remove</button>
                        <button class="applybtn">Filter</button>
                     </div>
                  </div>
               </div>
               <div id="calendar"></div>
            </div>
         </div>
      </div>
      <br>
      <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js"></script>
      <script>
         !function() {
         
         var today = moment();
         
         function Calendar(selector, events) {
         this.el = document.querySelector(selector);
         this.events = events;
         this.current = moment().date(1);
         this.draw();
         var current = document.querySelector('.today');
         if(current) {
         var self = this;
         window.setTimeout(function() {
         self.openDay(current);
         }, 500);
         }
         }
         
         Calendar.prototype.draw = function() {
         //Create Header
         this.drawHeader();
         
         //Draw Month
         this.drawMonth();
         
         this.drawLegend();
         }
         
         Calendar.prototype.drawHeader = function() {
         var self = this;
         if(!this.header) {
         //Create the header elements
         this.header = createElement('div', 'header');
         this.header.className = 'header';
         
         this.title = createElement('h1');
         
         var right = createElement('div', 'right');
         right.addEventListener('click', function() { self.nextMonth(); });
         
         var left = createElement('div', 'left');
         left.addEventListener('click', function() { self.prevMonth(); });
         
         //Append the Elements
         this.header.appendChild(this.title); 
         this.header.appendChild(right);
         this.header.appendChild(left);
         this.el.appendChild(this.header);
         }
         
         this.title.innerHTML = this.current.format('MMMM YYYY');
         }
         
         Calendar.prototype.drawMonth = function() {
         var self = this;
         
         this.events.forEach(function(ev) {
         ev.date = self.current.clone().date(Math.random() * (29 - 1) + 1);
         });
         
         
         if(this.month) {
         this.oldMonth = this.month;
         this.oldMonth.className = 'month out ' + (self.next ? 'next' : 'prev');
         this.oldMonth.addEventListener('webkitAnimationEnd', function() {
         self.oldMonth.parentNode.removeChild(self.oldMonth);
         self.month = createElement('div', 'month');
         self.backFill();
         self.currentMonth();
         self.fowardFill();
         self.el.appendChild(self.month);
         window.setTimeout(function() {
         self.month.className = 'month in ' + (self.next ? 'next' : 'prev');
         }, 16);
         });
         } else {
         this.month = createElement('div', 'month');
         this.el.appendChild(this.month);
         this.backFill();
         this.currentMonth();
         this.fowardFill();
         this.month.className = 'month new';
         }
         }
         
         Calendar.prototype.backFill = function() {
         var clone = this.current.clone();
         var dayOfWeek = clone.day();
         
         if(!dayOfWeek) { return; }
         
         clone.subtract('days', dayOfWeek+1);
         
         for(var i = dayOfWeek; i > 0 ; i--) {
         this.drawDay(clone.add('days', 1));
         }
         }
         
         Calendar.prototype.fowardFill = function() {
         var clone = this.current.clone().add('months', 1).subtract('days', 1);
         var dayOfWeek = clone.day();
         
         if(dayOfWeek === 6) { return; }
         
         for(var i = dayOfWeek; i < 6 ; i++) {
         this.drawDay(clone.add('days', 1));
         }
         }
         
         Calendar.prototype.currentMonth = function() {
         var clone = this.current.clone();
         
         while(clone.month() === this.current.month()) {
         this.drawDay(clone);
         clone.add('days', 1);
         }
         }
         
         Calendar.prototype.getWeek = function(day) {
         if(!this.week || day.day() === 0) {
         this.week = createElement('div', 'week');
         this.month.appendChild(this.week);
         }
         }
         
         Calendar.prototype.drawDay = function(day) {
         var self = this;
         this.getWeek(day);
         
         //Outer Day
         var outer = createElement('div', this.getDayClass(day));
         outer.addEventListener('click', function() {
         self.openDay(this);
         });
         
         //Day Name
         var name = createElement('div', 'day-name', day.format('ddd'));
         
         //Day Number
         var number = createElement('div', 'day-number', day.format('DD'));
         
         
         //Events
         var events = createElement('div', 'day-events');
         this.drawEvents(day, events);
         
         outer.appendChild(name);
         outer.appendChild(number);
         outer.appendChild(events);
         this.week.appendChild(outer);
         }
         
         Calendar.prototype.drawEvents = function(day, element) {
         if(day.month() === this.current.month()) {
         var todaysEvents = this.events.reduce(function(memo, ev) {
         if(ev.date.isSame(day, 'day')) {
         memo.push(ev);
         }
         return memo;
         }, []);
         
         todaysEvents.forEach(function(ev) {
         var evSpan = createElement('span', ev.color);
         element.appendChild(evSpan);
         });
         }
         }
         
         Calendar.prototype.getDayClass = function(day) {
         classes = ['day'];
         if(day.month() !== this.current.month()) {
         classes.push('other');
         } else if (today.isSame(day, 'day')) {
         classes.push('today');
         }
         return classes.join(' ');
         }
         
         Calendar.prototype.openDay = function(el) {
         var details, arrow;
         var dayNumber = +el.querySelectorAll('.day-number')[0].innerText || +el.querySelectorAll('.day-number')[0].textContent;
         var day = this.current.clone().date(dayNumber);
         
         var currentOpened = document.querySelector('.details');
         
         //Check to see if there is an open detais box on the current row
         if(currentOpened && currentOpened.parentNode === el.parentNode) {
         details = currentOpened;
         arrow = document.querySelector('.arrow');
         } else {
         //Close the open events on differnt week row
         //currentOpened && currentOpened.parentNode.removeChild(currentOpened);
         if(currentOpened) {
         currentOpened.addEventListener('webkitAnimationEnd', function() {
         currentOpened.parentNode.removeChild(currentOpened);
         });
         currentOpened.addEventListener('oanimationend', function() {
         currentOpened.parentNode.removeChild(currentOpened);
         });
         currentOpened.addEventListener('msAnimationEnd', function() {
         currentOpened.parentNode.removeChild(currentOpened);
         });
         currentOpened.addEventListener('animationend', function() {
         currentOpened.parentNode.removeChild(currentOpened);
         });
         currentOpened.className = 'details out';
         }
         
         //Create the Details Container
         details = createElement('div', 'details in');
         
         //Create the arrow
         var arrow = createElement('div', 'arrow');
         
         //Create the event wrapper
         
         details.appendChild(arrow);
         el.parentNode.appendChild(details);
         }
         
         var todaysEvents = this.events.reduce(function(memo, ev) {
         if(ev.date.isSame(day, 'day')) {
         memo.push(ev);
         }
         return memo;
         }, []);
         
         this.renderEvents(todaysEvents, details);
         
         arrow.style.left = el.offsetLeft - el.parentNode.offsetLeft + 27 + 'px';
         }
         
         Calendar.prototype.renderEvents = function(events, ele) {
         //Remove any events in the current details element
         var currentWrapper = ele.querySelector('.events');
         var wrapper = createElement('div', 'events in' + (currentWrapper ? ' new' : ''));
         
         events.forEach(function(ev) {
         var div = createElement('div', 'event');
         var square = createElement('div', 'event-category ' + ev.color);
         var span = createElement('span', '', ev.eventName);
         
         div.appendChild(square);
         div.appendChild(span);
         wrapper.appendChild(div);
         });
         
         if(!events.length) {
         var div = createElement('div', 'event empty');
         var span = createElement('span', '', 'No Events');
         
         div.appendChild(span);
         wrapper.appendChild(div);
         }
         
         if(currentWrapper) {
         currentWrapper.className = 'events out';
         currentWrapper.addEventListener('webkitAnimationEnd', function() {
         currentWrapper.parentNode.removeChild(currentWrapper);
         ele.appendChild(wrapper);
         });
         currentWrapper.addEventListener('oanimationend', function() {
         currentWrapper.parentNode.removeChild(currentWrapper);
         ele.appendChild(wrapper);
         });
         currentWrapper.addEventListener('msAnimationEnd', function() {
         currentWrapper.parentNode.removeChild(currentWrapper);
         ele.appendChild(wrapper);
         });
         currentWrapper.addEventListener('animationend', function() {
         currentWrapper.parentNode.removeChild(currentWrapper);
         ele.appendChild(wrapper);
         });
         } else {
         ele.appendChild(wrapper);
         }
         }
         
         Calendar.prototype.drawLegend = function() {
         var legend = createElement('div', 'legend');
         var calendars = this.events.map(function(e) {
         return e.calendar + '|' + e.color;
         }).reduce(function(memo, e) {
         if(memo.indexOf(e) === -1) {
         memo.push(e);
         }
         return memo;
         }, []).forEach(function(e) {
         var parts = e.split('|');
         var entry = createElement('span', 'entry ' +  parts[1], parts[0]);
         legend.appendChild(entry);
         });
         this.el.appendChild(legend);
         }
         
         Calendar.prototype.nextMonth = function() {
         this.current.add('months', 1);
         this.next = true;
         this.draw();
         }
         
         Calendar.prototype.prevMonth = function() {
         this.current.subtract('months', 1);
         this.next = false;
         this.draw();
         }
         
         window.Calendar = Calendar;
         
         function createElement(tagName, className, innerText) {
         var ele = document.createElement(tagName);
         if(className) {
         ele.className = className;
         }
         if(innerText) {
         ele.innderText = ele.textContent = innerText;
         }
         return ele;
         }
         }();
         
         !function() {
         var data = [
         { eventName: 'Game vs Portalnd', calendar: 'Approved', color: 'blue' },
         { eventName: 'Game vs Houston', calendar: 'Approved', color: 'blue' },
         { eventName: 'Game vs Denver', calendar: 'Approved', color: 'blue' },
         { eventName: 'Game vs San Degio', calendar: 'Approved', color: 'blue' },
         
         { eventName: 'School Play', calendar: 'Pending', color: 'yellow' },
         { eventName: 'Parent/Teacher Conference', calendar: 'Pending', color: 'yellow' },
         { eventName: 'Pick up from Soccer Practice', calendar: 'Pending', color: 'yellow' },
         { eventName: 'Ice Cream Night', calendar: 'Pending', color: 'yellow' },
         ];
         
         
         
         function addDate(ev) {
         
         }
         
         var calendar = new Calendar('#calendar', data);
         
         }();
      </script>
   </body>
</html>