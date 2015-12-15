(function () {
    angular.module('bkClient')
        .controller('CalendarController', CalendarController);

    CalendarController.$inject = [];
    function CalendarController() {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        var vm = this;
        vm.events = [
            [
                {title: 'All Day Event', start: new Date(y, m, 1)},
                {title: 'Long Event', start: new Date(y, m, d - 5), end: new Date(y, m, d - 2)},
                {id: 999, title: 'Repeating Event', start: new Date(y, m, d - 3, 16, 0), allDay: false},
                {id: 999, title: 'Repeating Event', start: new Date(y, m, d + 4, 16, 0), allDay: false},
                {title: 'Birthday Party', start: new Date(y, m, d + 1, 19, 0), end: new Date(y, m, d + 1, 22, 30), allDay: false }
            ]
        ];
        vm.config = {
            lang: 'de',
            height: 600,
            editable: true,
            weekNumbers: true,
            header: {
                left: 'month agendaWeek agendaDay',
                center: 'title',
                right: 'today prev,next'
            },
            buttonText: {
                today: 'Heute',
                month: 'Monat',
                agendaWeek: 'Woche',
                agendaDay: 'Tag'
            },
            allDayText: '',
            slotLabelFormat: 'H:mm',
            views: {
                agendaWeek: {
                    columnFormat: 'ddd, D. MMM'
                }
            },
            selectable: true,
            selectHelper: true
        };

        ////////////
    }
})();