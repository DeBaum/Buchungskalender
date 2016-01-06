(function () {
    angular.module('bkClient')
        .controller('CalendarController', CalendarController);

    CalendarController.$inject = ['bookingDataFactory', '$rootScope', '$state'];
    function CalendarController(bookingDataFactory, $rootScope, $state) {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        var allEvents = [
            {title: 'Karl (Ford)', object: {id: 1}, start: new Date(y, m, d, 14, 0), end: new Date(y, m, d, 17, 0)},
            {title: 'Franz (Ford)', object: {id: 1}, start: new Date(y, m, d + 3, 13, 0), end: new Date(y, m, d + 3, 16, 0)},
            {title: 'Ute (Ford)', object: {id: 1}, start: new Date(y, m, d - 2, 10, 0), end: new Date(y, m, d - 2, 12, 0)},
            {title: 'Walter (Ford)', object: {id: 1}, start: new Date(y, m, d + 7, 14, 0), end: new Date(y, m, d + 7, 17, 0)},
            {title: 'Carsten (Ford)', object: {id: 1}, start: new Date(y, m, d + 8, 8, 0), end: new Date(y, m, d + 11, 18, 0)},
            {title: 'Ferdinand', object: {id: 2}, start: new Date(y, m, d - 1, 14, 0), end: new Date(y, m, d - 1, 17, 0)},
            {title: 'Brigitte', object: {id: 2}, start: new Date(y, m, d + 2, 11, 0), end: new Date(y, m, d + 2, 15, 0)},
            {title: 'Manuela', object: {id: 2}, start: new Date(y, m, d - 2, 9, 0), end: new Date(y, m, d - 2, 13, 0)},
            {title: 'Stephan', object: {id: 2}, start: new Date(y, m, d + 7, 14, 0), end: new Date(y, m, d + 7, 17, 0)},
            {title: 'Jan', object: {id: 2}, start: new Date(y, m, d + 9, 8, 0), end: new Date(y, m, d + 12, 18, 0)}
        ];

        var eventFilter = {
            object: null
        };

        var vm = this;
        vm.bookingData = bookingDataFactory;
        vm.events = [[]];
        vm.filterEvents = filterEvents;
        vm.config = {
            lang: 'de',
            height: 480,
            editable: false,
            weekNumbers: true,
            header: {
                left: 'goToCategory',
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
            customButtons: {
                goToCategory: {
                    text: 'Zurück',
                    click: function() {
                        $state.go('choose-category');
                    }
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
                eventFilter.object = val;
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
                        left: 'goToCategory'
                    }
                });
            }
        }

        function filterEvents() {
            while (vm.events[0].length) {
                vm.events[0].pop();
            }

            var filtered = [];
            if (eventFilter.object != null) {
                filtered = _.filter(allEvents, function (event) {
                    return event.object.id == eventFilter.object.id;
                });
            }

            for (var i = 0; i < filtered.length; i++) {
                vm.events[0].push(filtered[i]);
            }
        }
    }
})();