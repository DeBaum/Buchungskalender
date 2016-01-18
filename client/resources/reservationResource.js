(function () {
    angular.module('bkApi')
        .factory('ReservationResource', ReservationResource);

    ReservationResource.$inject = ['$resource'];
    function ReservationResource($resource) {
        return $resource(bkApiRoot + '/reservations/:id', {id: '@id'}, {
            'create': {method: 'post'},
            'getAll': {method: 'get', params: {id: null}, isArray: true, cache: true},
            'get': {method: 'get', cache: true},
            'update': {method: 'put'},
            'delete': {method: 'delete'}
        });

        //////////


    }
})();