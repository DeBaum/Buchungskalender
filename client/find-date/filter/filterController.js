(function () {
    angular.module('bkClient')
        .controller('FilterController', FilterController);

    FilterController.$inject = ['$stateParams'];
    function FilterController($stateParams) {
        var vm = this;
        vm.categoryId = $stateParams.categoryId;
        vm.categories = [{id: 1, title: 'Autos'}, {id: 2, title: 'Räume'}];
        vm.matchedObjects = [{id: 1, title: 'Ford'}, {id: 2, title: 'Opel'}];
        vm.getCategoryName = _.constant(_.result(_.find(vm.categories, {id: vm.categoryId}), 'title'));
        vm.start = Date.now();
        vm.end = Date.now() + 1000 * 60 * 60 * 2;
        vm.getBookingParams = getBookingparams;

        ////////////

        function getBookingparams(object) {
            return {
                categoryId: vm.categoryId,
                objectId: object.id,
                start: vm.start,
                end: vm.end
            }
        }
    }
})();