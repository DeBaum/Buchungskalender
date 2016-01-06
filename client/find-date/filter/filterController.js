(function () {
    angular.module('bkClient')
        .controller('FilterController', FilterController);

    FilterController.$inject = ['bookingDataFactory', '$rootScope', 'ReservationService', 'CategoryService', 'ObjectService'];
    function FilterController(bookingDataFactory, $rootScope, ReservationService, CategoryService, ObjectService) {
        var vm = this;

        vm.bookingData = bookingDataFactory;

        vm.categories = CategoryService.categories;
        vm.availableObjects = ObjectService.objects;
        vm.matchedObjects = [];
        vm.getCategoryName = _.constant(_.result(_.find(vm.categories, {id: vm.categoryId}), 'title'));
        vm.object = null;
        vm.getBookingParams = getBookingParams;
        vm.onFilterChanged = onFilterChanged;

        ////////////

        $rootScope.$on('dateSelected', onDateSelected);

        function onDateSelected() {
            if (bookingDataFactory.start == null && bookingDataFactory.end == null) {
                vm.matchedObjects = [];
            } else {
                vm.matchedObjects = ReservationService.getAviableObjects(bookingDataFactory.start, bookingDataFactory.end);
            }
        }

        function onFilterChanged(attr, val) {
            $rootScope.$emit('filterChanged', attr, val);
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