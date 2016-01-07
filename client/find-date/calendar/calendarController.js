(function () {
    angular.module('bkClient')
        .controller('CalendarController', CalendarController);

    CalendarController.$inject = ['bookingDataFactory', '$rootScope', '$state', 'ReservationService'];
    function CalendarController(bookingDataFactory, $rootScope, $state, ReservationService) {
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
            timeFormat: 'H(:mm)',
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
            eventClick: reservationClicked,
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

        function reservationClicked(event) {
            $state.go('edit-reservation', {reservationId: event.id});
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

            var aviableObjects = [];
            if (eventFilter.object != null) {
                aviableObjects = ReservationService.getForObject(eventFilter.object);
            }

            for (var i = 0; i < aviableObjects.length; i++) {
                vm.events[0].push(aviableObjects[i]);
            }
        }
    }
})();