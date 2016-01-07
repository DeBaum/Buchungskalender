(function () {
    angular.module('bkClient')
        .factory('ReservationService', ReservationService);

    ReservationService.$inject = ['ObjectService'];
    function ReservationService(ObjectService) {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        var service = {
            reservations: [
                {title: 'Karl (Ford)', object: {id: 1}, start: new Date(y, m, d, 14, 0), end: new Date(y, m, d, 17, 0)},
                {title: 'Franz (Ford)', object: {id: 1}, start: new Date(y, m, d + 3, 13, 0), end: new Date(y, m, d + 3, 16, 0)},
                {title: 'Ute (Ford)', object: {id: 1}, start: new Date(y, m, d - 2, 10, 0), end: new Date(y, m, d - 2, 12, 0)},
                {title: 'Walter (Ford)', object: {id: 1}, start: new Date(y, m, d + 7, 14, 0), end: new Date(y, m, d + 7, 17, 0)},
                {title: 'Carsten (Ford)', object: {id: 1}, start: new Date(y, m, d + 8, 8, 30), end: new Date(y, m, d + 11, 18, 0)},
                {title: 'Ferdinand (Opel)', object: {id: 2}, start: new Date(y, m, d - 1, 14, 0), end: new Date(y, m, d - 1, 17, 0)},
                {title: 'Brigitte (Opel)', object: {id: 2}, start: new Date(y, m, d + 2, 11, 0), end: new Date(y, m, d + 2, 15, 0)},
                {title: 'Manuela (Opel)', object: {id: 2}, start: new Date(y, m, d - 2, 9, 0), end: new Date(y, m, d - 2, 13, 0)},
                {title: 'Stephan (Opel)', object: {id: 2}, start: new Date(y, m, d + 7, 14, 0), end: new Date(y, m, d + 7, 17, 0)},
                {title: 'Jan (Opel)', object: {id: 2}, start: new Date(y, m, d + 9, 8, 0), end: new Date(y, m, d + 12, 18, 0)}
            ],
            getAviableObjects: getAviableObjects,
            getForObject: getReservationsForObject
        };
        return service;

        //////////

        function getAviableObjects(start, end) {
            var range = moment.range(start, end);

            return _.filter(ObjectService.objects, function (obj) {
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