(function () {
    angular.module('bkClient')
        .factory('bookingDataFactory', bookingDataFactory);

    bookingDataFactory.$inject = ['$stateParams', '$state', 'ReservationService', 'ObjectService'];
    function bookingDataFactory($stateParams, $state, ReservationService, ObjectService) {
        var r = {
            categoryId: null,
            objectId: null,
            start: null,
            end: null,
            timeAround: {before: 0, after: 0},

            hasTime: hasTime,
            update: readParams
        };
        readParams();
        return r;

        //////////

        function hasTime() {
            return r.start && r.end;
        }

        function readParams() {
            if ($state.is('edit-reservation')) {
                var reservation = ReservationService.getById($stateParams.reservationId);
                var object = ObjectService.getById(reservation.object.id);
                r.categoryId = object.categoryId || r.categoryId;
                r.objectId = object.id || r.objectId;
                r.start = moment(reservation.start) ? moment($stateParams.start) : r.start;
                r.end = moment(reservation.end) ? moment($stateParams.end) : r.end;
            } else {
                r.categoryId = $stateParams.categoryId || r.categoryId;
                r.objectId = $stateParams.objectId || r.objectId;
                r.start = $stateParams.start ? moment($stateParams.start) : r.start;
                r.end = $stateParams.end ? moment($stateParams.end) : r.end;
            }
        }
    }
})();