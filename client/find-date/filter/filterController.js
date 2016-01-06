(function () {
    angular.module('bkClient')
        .controller('FilterController', FilterController);

    FilterController.$inject = ['bookingDataFactory', '$rootScope'];
    function FilterController(bookingDataFactory, $rootScope) {
        var vm = this;

        vm.bookingData = bookingDataFactory;

        vm.categories = [{id: 1, title: 'Autos'}, {id: 2, title: 'Räume'}];
        vm.availableObjects = [{id: 1, title: 'Ford'}, {id: 2, title: 'Opel'}];
        vm.matchedObjects = [];
        vm.getCategoryName = _.constant(_.result(_.find(vm.categories, {id: vm.categoryId}), 'title'));
        vm.getBookingParams = getBookingParams;

        ////////////

        $rootScope.$on('dateSelected', onDateSelected);

        function onDateSelected() {
            if (bookingDataFactory.start == null && bookingDataFactory.end == null) {
                vm.matchedObjects = [];
            } else {
                vm.matchedObjects = vm.availableObjects;
            }
        }

        function getBookingParams(object) {
            if (bookingDataFactory.hasTime()) {
                return {
                    categoryId: bookingDataFactory.categoryId,
                    objectId: object.id,
                    start: bookingDataFactory.start.format(),
                    end: bookingDataFactory.end.format()
                };
            } else return null;
        }
    }
})();