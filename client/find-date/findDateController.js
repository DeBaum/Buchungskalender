(function () {
    angular.module('bkClient')
        .controller('FindDateController', FindDateController);

    FindDateController.$inject = [];
    function FindDateController() {
        var vm = this;
        vm.templates = {
            calendar: bkRootPath +'find-date/calendar/calendarView.html',
            filter: bkRootPath +'find-date/filter/filterView.html'
        };

        ////////////
    }
})();