(function () {
    angular.module('bkClient')
        .config(routes);

    routes.$inject = ['$stateProvider', '$urlRouterProvider'];
    function routes($stateProvider, $urlRouterProvider) {
        $urlRouterProvider.otherwise('/category');

        $stateProvider.state('choose-category', {
            url: '/category',
            templateUrl: bkRootPath + 'category-selection/categoryView.html'
        });

        $stateProvider.state('find-date', {
            url: '/category/{categoryId:int}/date',
            templateUrl: bkRootPath + 'find-date/findDateView.html'
        });

        $stateProvider.state('book-object', {
            url: '/category/{categoryId:int}/object/{objectId:int}/book?start&end',
            templateUrl: bkRootPath + 'book-object/bookObjectView.html'
        });

        $stateProvider.state('edit-reservation', {
            url: '/reservation/{reservationId:int}/edit',
            templateUrl: bkRootPath + 'book-object/bookObjectView.html'
        });
    }
})();