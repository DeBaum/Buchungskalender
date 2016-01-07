(function () {
    angular.module('bkClient')
        .factory('ReservationService', ReservationService);

    ReservationService.$inject = ['ObjectService', 'bookingDataFactory'];
    function ReservationService(ObjectService, bookingDataFactory) {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        var service = {
            reservations: [
                {title: '_', object: {id: 3}, reservation: {name: 'Karl'}, start: new Date(y, m, d, 14, 0), end: new Date(y, m, d, 17, 0)},
                {title: '_', object: {id: 3}, reservation: {name: 'Franz'}, start: new Date(y, m, d + 3, 13, 0), end: new Date(y, m, d + 3, 16, 0)},
                {title: '_', object: {id: 3}, reservation: {name: 'Ute'}, start: new Date(y, m, d - 2, 10, 0), end: new Date(y, m, d - 2, 12, 0)},
                {title: '_', object: {id: 3}, reservation: {name: 'Walter'}, start: new Date(y, m, d + 7, 14, 0), end: new Date(y, m, d + 7, 17, 0)},
                {title: '_', object: {id: 3}, reservation: {name: 'Carsten'}, start: new Date(y, m, d + 8, 8, 30), end: new Date(y, m, d + 11, 18, 0)},
                {title: '_', object: {id: 4}, reservation: {name: 'Ferdinand'}, start: new Date(y, m, d - 1, 14, 0), end: new Date(y, m, d - 1, 17, 0)},
                {title: '_', object: {id: 4}, reservation: {name: 'Brigitte'}, start: new Date(y, m, d + 2, 11, 0), end: new Date(y, m, d + 2, 15, 0)},
                {title: '_', object: {id: 4}, reservation: {name: 'manuela'}, start: new Date(y, m, d - 2, 9, 0), end: new Date(y, m, d - 2, 13, 0)},
                {title: '_', object: {id: 4}, reservation: {name: 'Stephan'}, start: new Date(y, m, d + 7, 14, 0), end: new Date(y, m, d + 7, 17, 0)},
                {title: '_', object: {id: 4}, reservation: {name: 'Jan'}, start: new Date(y, m, d + 9, 8, 0), end: new Date(y, m, d + 12, 18, 0)}
            ],
            getAviableObjects: getAviableObjects,
            getForObject: getReservationsForObject
        };
        setupReservations();
        return service;

        //////////

        function setupReservations() {
            _.forEach(service.reservations, function (r) {
                r.title = r.reservation.name + '(' + ObjectService.getName(r.object) + ')'
            });
        }

        function getAviableObjects(start, end) {
            var range = moment.range(start, end);
            var objects = ObjectService.getForCategory(bookingDataFactory.categoryId);

            return _.filter(objects, function (obj) {
                return !_.some(getReservationsForObject(obj), function (reservation) {
                    return range.overlaps(moment.range(reservation.start, reservation.end));
                });
            });
        }

        function getReservationsForObject(object) {
            if (object == null) return [];

            return _.filter(service.reservations, function (reservation) {
                return reservation.object.id == object.id;
            })
        }
    }
})();