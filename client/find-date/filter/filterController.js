(function () {
    angular.module('bkClient')
        .controller('FilterController', FilterController);

    FilterController.$inject = ['bookingDataFactory'];
    function FilterController(bookingDataFactory) {
        var vm = this;

        vm.bookingData = bookingDataFactory;

        vm.categories = [{id: 1, title: 'Autos'}, {id: 2, title: 'Räume'}];
        vm.matchedObjects = [{id: 1, title: 'Ford'}, {id: 2, title: 'Opel'}];
        vm.getCategoryName = _.constant(_.result(_.find(vm.categories, {id: vm.categoryId}), 'title'));
        vm.getBookingParams = getBookingParams;

        ////////////

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