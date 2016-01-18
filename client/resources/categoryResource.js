(function () {
    angular.module('bkApi')
        .factory('CategoryResource', CategoryResource);

    CategoryResource.$inject = ['$resource'];
    function CategoryResource($resource) {
        return $resource(bkApiRoot + '/categories/:id', {'id': '@id'}, {
            'create': {method: 'post'},
            'getAll': {method: 'get', params: {id: null}, isArray: true, cache: true},
            'get': {method: 'get', cache: true},
            'update': {method: 'put'},
            'delete': {method: 'delete'}
        });

        //////////


    }
})();