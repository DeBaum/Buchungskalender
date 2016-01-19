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
            nowIndicator: true,
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
            allDaySlot: false,
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
                    click: function () {
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
            if (attr == 'object' && val) {
                if (val.id >= 0) {
                    eventFilter.object = val;
                    vm.showEvents = true;
                } else if (val.id < 0) {
                    vm.showEvents = val.id === -1;
                    eventFilter.object = null;
                }
            }
            filterEvents();
            markSelected();
        }

        function dayClicked(date) {
            date.startOf('day').add(8, 'h');

            _.merge(vm.config, {
                defaultDate: date,
                defaultView: 'agendaWeek',
                header: {
                    left: 'month agendaDay'
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
            }
        }

        function markSelected() {
            if (bookingDataFactory.start && bookingDataFactory.end) {
                $('[ui-calendar="cal.config"]').fullCalendar('select', bookingDataFactory.start, bookingDataFactory.end);
            }
        }

        function onViewChange(view) {
            loadReservations(view.start, view.end);
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
            } else if ('agendaWeek,agendaDay'.indexOf(view.name) >= 0) {
                _.merge(vm.config, {
                    defaultView: view.name,
                    defaultDate: bookingDataFactory.start || vm.config.defaultDate,
                    header: {
                        left: 'month ' + (view.name == 'agendaDay' ? 'agendaWeek' : 'agendaDay')
                    }
                });
                markSelected();
            }
        }

        function filterEvents() {
            while (vm.events[0].length) {
                vm.events[0].pop();
            }

            if (vm.showEvents) {
                var reservations;
                if (eventFilter.object == null) {
                    reservations = ReservationService.getAll();
                } else {
                    reservations = ReservationService.getForObject(eventFilter.object);
                }
                _(reservations).forEach(function (o) {
                    vm.events[0].push(o);
                });
            }
        }

        function loadReservations(start, end) {
            ReservationService.loadForTime(start, end)
                .then(filterEvents);
        }
    }
})();