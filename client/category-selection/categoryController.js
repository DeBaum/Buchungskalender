(function () {
    angular.module('bkClient')
        .controller('CategoryController', CategoryController);

    CategoryController.$inject = ['bookingDataFactory', 'CategoryService'];
    function CategoryController(bookingDataFactory, CategoryService) {
        var vm = this;
        vm.categories = CategoryService.categories;
        CategoryService.load();

        bookingDataFactory.start = null;
        bookingDataFactory.end = null;
        ////////////
    }
})();