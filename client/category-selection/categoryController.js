(function () {
    angular.module('bkClient')
        .controller('CategoryController', CategoryController);

    CategoryController.$inject = [];
    function CategoryController() {
        var vm = this;
        vm.categories = [{id: 1, title: 'Autos'}, {id: 2, title: 'Räume'}];

        ////////////
    }
})();