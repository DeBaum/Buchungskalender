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
        categoryService.load();

        vm.getCategoryName = function getCategoryName() {
            var category = categoryService.getById(bookingDataFactory.categoryId);
            return (category || {title: ''}).title;
        };

        ////////////
    }
})();