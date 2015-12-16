(function () {
    angular.module('bkClient')
        .controller('CalendarController', CalendarController);

    CalendarController.$inject = ['bookingDataFactory'];
    function CalendarController(bookingDataFactory) {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        var allEvents = [
            {title: 'All Day Event', start: new Date(y, m, 1)},
            {title: 'Long Event', start: new Date(y, m, d - 5), end: new Date(y, m, d - 2)},
            {id: 999, title: 'Repeating Event', start: new Date(y, m, d - 3, 16, 0), allDay: false},
            {id: 999, title: 'Repeating Event', start: new Date(y, m, d + 4, 16, 0), allDay: false},
            {title: 'Birthday Party', start: new Date(y, m, d + 1, 19, 0), end: new Date(y, m, d + 1, 22, 30), allDay: false}
        ];

        var vm = this;
        vm.bookingData = bookingDataFactory;
        vm.showEvents = false;
        vm.events = [[]];
        vm.filterEvents = filterEvents;
        vm.config = {
            lang: 'de',
            height: 480,
            editable: false,
            weekNumbers: true,
            header: {
                left: '',
                center: 'title',
                right: 'today prev,next'
            },
            buttonText: {
                today: 'Heute',
                month: 'Zurück'
            },
            allDayText: '',
            slotLabelFormat: 'H:mm',
            views: {
                agendaWeek: {
                    columnFormat: 'ddd, D. MMM'
                }
            },
            selectable: true,
            dayClick: dayClicked,
            select: onSelect,
            viewDestroy: onViewChange

        };

        ////////////

        function dayClicked(date) {
            bookingDataFactory.start = date.clone();
            bookingDataFactory.end = date.clone().add(1, 'day');

            vm.config.defaultDate = date;
            vm.config.defaultView = 'agendaDay';
            vm.config.header.left = 'month';
        }

        function onSelect(start, end) {
            bookingDataFactory.start = start;
            bookingDataFactory.end = end;
        }

        function onViewChange(view) {
            if (view.name === 'agendaDay') {
                vm.config.defaultView = 'month';
                vm.config.header.left = '';
            }
        }

        function filterEvents() {
            if (vm.showEvents) {
                for (var i = 0; i < allEvents.length; i++) {
                    vm.events[0].push(allEvents[i]);
                }
            } else {
                while (vm.events[0].length) {
                    vm.events[0].pop();
                }
            }
        }
    }
})();