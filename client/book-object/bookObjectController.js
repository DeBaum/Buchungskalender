(function () {
    angular.module('bkClient')
        .controller('BookObjectController', BookObjectController);

    BookObjectController.$inject = ['bookingDataFactory', 'ObjectService', '$state'];
    function BookObjectController(bookingDataFactory, ObjectService, $state) {
        bookingDataFactory.update();

        var vm = this;
        vm.bookingData = bookingDataFactory;

        vm.time = {
            start: bookingDataFactory.start,
            end: bookingDataFactory.end
        };

        vm.objects = ObjectService.getForCategory(vm.bookingData.categoryId);
        vm.object = _.find(vm.objects, {id: bookingDataFactory.objectId});

        vm.isEditing = isEditing;

        ObjectService.load(bookingDataFactory.categoryId)
            .then(setSelectedObject);

        ////////////

        function setSelectedObject(objects) {
            vm.objects = objects || vm.object;
            vm.object = _.find(objects, {id: bookingDataFactory.objectId});
        }

        function isEditing() {
            return $state.is('edit-reservation');
        }
    }
})();