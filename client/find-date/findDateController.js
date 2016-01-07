(function () {
    angular.module('bkClient')
        .controller('FindDateController', FindDateController);

    FindDateController.$inject = ['bookingDataFactory', 'CategoryService'];
    function FindDateController(bookingDataFactory, categoryService) {
        var vm = this;
        vm.templates = {
            calendar: bkRootPath + 'find-date/calendar/calendarView.html',
            filter: bkRootPath + 'find-date/filter/filterView.html'
        };

        bookingDataFactory.update();

        vm.getCategoryName = function getCategoryName() {
            return categoryService.getById(bookingDataFactory.categoryId).title;
        };

        ////////////
    }
})();