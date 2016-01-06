(function () {
    angular.module('bkClient')
        .controller('CalendarController', CalendarController);

    CalendarController.$inject = ['bookingDataFactory', '$rootScope'];
    function CalendarController(bookingDataFactory, $rootScope) {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        var allEvents = [ // TODO: Demodaten entfernen?
            {title: 'All Day Event', start: new Date(y, m, 1)},
            {title: 'Long Event', start: new Date(y, m, d - 5), end: new Date(y, m, d - 2)},
            {id: 999, title: 'Repeating Event', start: new Date(y, m, d - 3, 16, 0), allDay: false},
            {id: 999, title: 'Repeating Event', start: new Date(y, m, d + 4, 16, 0), allDay: false},
            {
                title: 'Birthday Party',
                start: new Date(y, m, d + 1, 19, 0),
                end: new Date(y, m, d + 1, 22, 30),
                allDay: false
            }
        ];

        var vm = this;
        vm.bookingData = bookingDataFactory;
        vm.showEvents = true; // TODO: ggf. ganz rausnehmen?
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
                month: 'Zurück',
                agendaWeek: 'Woche',
                agendaDay: 'Tag'
            },
            allDayText: '',
            slotLabelFormat: 'H:mm',
            views: {
                agendaWeek: {
                    columnFormat: 'ddd D.M'
                }
            },
            selectable: true,
            dayClick: dayClicked,
            select: onSelect,
            viewRender: onViewChange
        };

        $rootScope.$on('filterChanged', onFilterChanged);

        ////////////

        //noinspection JSUnusedLocalSymbols
        function onFilterChanged(event, attr, val) {
            if (attr == 'object') {
                vm.showEvents = val != null;
            }
            filterEvents();
        }

        function dayClicked(date) {
            date.startOf('day').add(8, 'h');
            bookingDataFactory.start = date.clone();
            bookingDataFactory.end = date.clone().add(2, 'h');

            _.merge(vm.config, {
                defaultDate: date,
                defaultView: 'agendaWeek',
                header: {
                    left: 'month agendaWeek'
                },
                buttonText: {
                    month: 'Zurück'
                }
            });
        }

        function onSelect(start, end) {
            if (end.diff(start, 'h') % 24 > 0) {
                bookingDataFactory.start = start;
                bookingDataFactory.end = end;
                $rootScope.$emit('dateSelected');
            } else {
                bookingDataFactory.start = start.add(8, 'h');
                bookingDataFactory.end = end.subtract(6, 'h');
                bookingDataFactory.start._ambigTime = false;
                bookingDataFactory.end._ambigTime = false;

                $('[ui-calendar="cal.config"]').fullCalendar('select', bookingDataFactory.start, bookingDataFactory.end);
            }
        }

        function onViewChange(view) {
            if ('month'.indexOf(view.name) >= 0) {
                _.merge(vm.config, {
                    defaultView: 'month',
                    buttonText: {
                        month: 'Monat'
                    },
                    header: {
                        left: ''
                    }
                });
            }
        }

        function filterEvents(state) {
            if (typeof state === 'boolean') {
                vm.showEvents = state;
            }
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