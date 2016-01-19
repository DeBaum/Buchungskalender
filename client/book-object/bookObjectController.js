(function () {
    angular.module('bkClient')
        .controller('BookObjectController', BookObjectController);

    BookObjectController.$inject = ['bookingDataFactory', 'ObjectService', '$state', '$scope', 'ReservationService'];
    function BookObjectController(bookingDataFactory, ObjectService, $state, $scope, ReservationService) {
        bookingDataFactory.update();

        var vm = this;
        vm.bookingData = bookingDataFactory;
        vm.reservation = {};
        vm.save = saveBooking;

        vm.time = {
            start: bookingDataFactory.start,
            end: bookingDataFactory.end
        };

        vm.objects = ObjectService.getForCategory(vm.bookingData.categoryId);
        vm.object = _.find(vm.objects, {id: bookingDataFactory.objectId});

        vm.isEditing = isEditing;

        ObjectService.load(bookingDataFactory.categoryId)
            .then(setSelectedObject);

        if (isEditing()) {
            loadReservation($state.reservationId);
        } else {
            initReservationObject();
        }

        ////////////

        function loadReservation(reservationId) {
            ReservationService.load(reservationId)
                .then(function (reservation) {
                    vm.reservation = reservation;
                });
        }

        function initReservationObject() {
            vm.reservation = {
                resource_id: bookingDataFactory.objectId,
                quantity: 1,
                time_from: bookingDataFactory.start,
                time_to: bookingDataFactory.end,
                creator_id: -1,
                event_from: bookingDataFactory.start,
                event_to: bookingDataFactory.end
            };
        }

        function setSelectedObject(objects) {
            vm.objects = objects || vm.object;
            vm.object = _.find(objects, {id: bookingDataFactory.objectId});
        }

        function isEditing() {
            return $state.is('edit-reservation');
        }

        function saveBooking() {
            vm.reservation.extras = $scope.$$childHead.extras.getForBooking();
            ReservationService.save(vm.reservation)
        }


    }
})();