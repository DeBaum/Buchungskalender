(function () {
    angular.module('bkClient')
        .controller('CategoryController', CategoryController);

    CategoryController.$inject = ['bookingDataFactory'];
    function CategoryController(bookingDataFactory) {
        var vm = this;
        vm.categories = [{id: 1, title: 'Autos'}, {id: 2, title: 'Räume'}];

        bookingDataFactory.start = null;
        bookingDataFactory.end = null;
        ////////////
    }
})();