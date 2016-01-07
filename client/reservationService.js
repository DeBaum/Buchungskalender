(function () {
    angular.module('bkClient')
        .factory('ReservationService', ReservationService);

    ReservationService.$inject = ['ObjectService', '$stateParams'];
    function ReservationService(ObjectService, $stateParams) {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        var service = {
            reservations: [
                {id: 1, title: '_', object: {id: 3}, reservation: {name: 'Karl'}, start: new Date(y, m, d, 14, 0), end: new Date(y, m, d, 17, 0)},
                {id: 2, title: '_', object: {id: 3}, reservation: {name: 'Franz'}, start: new Date(y, m, d + 3, 13, 0), end: new Date(y, m, d + 3, 16, 0)},
                {id: 3, title: '_', object: {id: 3}, reservation: {name: 'Ute'}, start: new Date(y, m, d - 2, 10, 0), end: new Date(y, m, d - 2, 12, 0)},
                {id: 4, title: '_', object: {id: 3}, reservation: {name: 'Walter'}, start: new Date(y, m, d + 7, 14, 0), end: new Date(y, m, d + 7, 17, 0)},
                {id: 5, title: '_', object: {id: 3}, reservation: {name: 'Carsten'}, start: new Date(y, m, d + 8, 8, 30), end: new Date(y, m, d + 11, 18, 0)},
                {id: 6, title: '_', object: {id: 4}, reservation: {name: 'Ferdinand'}, start: new Date(y, m, d - 1, 14, 0), end: new Date(y, m, d - 1, 17, 0)},
                {id: 7, title: '_', object: {id: 4}, reservation: {name: 'Brigitte'}, start: new Date(y, m, d + 2, 11, 0), end: new Date(y, m, d + 2, 15, 0)},
                {id: 8, title: '_', object: {id: 4}, reservation: {name: 'manuela'}, start: new Date(y, m, d - 2, 9, 0), end: new Date(y, m, d - 2, 13, 0)},
                {id: 9, title: '_', object: {id: 4}, reservation: {name: 'Stephan'}, start: new Date(y, m, d + 7, 14, 0), end: new Date(y, m, d + 7, 17, 0)},
                {id: 11, title: '_', object: {id: 6}, reservation: {name: 'Jan'}, start: new Date(y, m, d - 7, 8, 0), end: new Date(y, m, d -7, 18, 0)},
                {id: 12, title: '_', object: {id: 6}, reservation: {name: 'Sebastian'}, start: new Date(y, m, d - 7, 12, 0), end: new Date(y, m, d - 7, 14, 0)},
                {id: 13, title: '_', object: {id: 6}, reservation: {name: 'Frida'}, start: new Date(y, m, d - 7, 10, 0), end: new Date(y, m, d - 7, 16, 0)},
                {id: 14, title: '_', object: {id: 6}, reservation: {name: 'Jürgen'}, start: new Date(y, m, d - 7, 12, 0), end: new Date(y, m, d - 7, 18, 0)}
            ],
            getAviableObjects: getAviableObjects,
            getForObject: getReservationsForObject,
            getById: getReservationById
        };
        setupReservations();
        return service;

        //////////

        function setupReservations() {
            _.forEach(service.reservations, function (r) {
                r.title = r.reservation.name + '(' + ObjectService.getName(r.object) + ')'
            });
        }

        function getReservationById(id) {
            return _.find(service.reservations, 'id', id);
        }

        function getAviableObjects(start, end) {
            var range = moment.range(start, end);
            var objects = ObjectService.getForCategory($stateParams.categoryId);

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