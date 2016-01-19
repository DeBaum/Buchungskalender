(function () {
    angular.module('bkApi')
        .factory('ObjectResource', ObjectResource);

    ObjectResource.$inject = ['$resource'];
    function ObjectResource($resource) {
        return $resource(bkApiRoot + '/resources/:id', {'id': '@id'}, {
            'create': {method: 'post'},
            'getAll': {method: 'get', params: {id: null}, isArray: true, cache: true},
            'get': {method: 'get', cache: true},
            'update': {method: 'put'},
            'delete': {method: 'delete'},
            'getExtras': {method: 'get', url: bkApiRoot + '/resources/:id/extras', isArray: true, cache: true}
        });
    }
})();