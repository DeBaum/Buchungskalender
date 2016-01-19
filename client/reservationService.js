(function () {
    angular.module('bkClient')
        .factory('ReservationService', ReservationService);

    ReservationService.$inject = ['ObjectService', '$stateParams', 'ReservationResource', '$q'];
    function ReservationService(ObjectService, $stateParams, ReservationR, $q) {
        var service = {
            reservations: [],
            getAviableObjects: getAviableObjects,
            getForObject: getReservationsForObject,
            getById: getReservationById,
            getAll: function () {
                return service.reservations
            },
            load: loadReservation,
            loadForTime: loadForTime,
            save: saveReservation
        };
        return service;

        //////////

        function loadForTime(from, to) {
            return $q(function (resolve) {
                ReservationR.getAll({from: from, to: to, category_id: $stateParams.categoryId}, function (objects) {
                    _.forEach(objects, insertReservation);
                    resolve(objects);
                });
            });
        }

        function loadReservation(id) {
            return $q(function (resolve) {
                var fn = ReservationR.get;
                if (!id) {
                    fn = ReservationR.getAll;
                }
                fn({id: id}, function (reservations) {
                    if (!_.isArray(reservations)) {
                        reservations = [reservations];
                    }
                    _.forEach(reservations, insertReservation);
                    resolve(id ? reservations[0] : reservations);
                });
            });
        }

        function insertReservation(reservation) {
            var index = _.findIndex(service.reservations, {id: reservation.id});
            if (index === -1) {
                service.reservations.push(reservation);
            } else {
                service.reservations[index] = reservation;
            }
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

        function saveReservation(reservation) {
            return $q(function (resolve) {
                if (reservation.id) {
                    ReservationR.update(reservation, resolve);
                } else {
                    ReservationR.create(reservation, resolve);
                }
            });
        }
    }
})();