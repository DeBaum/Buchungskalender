(function () {
    angular.module('bkClient')
        .controller('BookObjectController', BookObjectController);

    BookObjectController.$inject = ['$scope', 'bookingDataFactory', 'ObjectService'];
    function BookObjectController($scope, bookingDataFactory, ObjectService) {
        bookingDataFactory.update();

        $scope.bookingData = bookingDataFactory;

        $scope.time = {
            start: bookingDataFactory.start,
            end: bookingDataFactory.end
        };

        $scope.$watch('event.before', setEventTimeStr);
        $scope.$watch('event.after', setEventTimeStr);

        $scope.objects = ObjectService.getForCategory(bookingDataFactory.categoryId);
        $scope.object = _.find($scope.objects, {id: bookingDataFactory.objectId});

        ////////////

        function setEventTimeStr() {
            if ($scope.event) {
                if ($scope.time.start) {
                    $scope.event.beforeStr = $scope.time.start.clone().add($scope.event.before, 'm').format('H:mm');
                }
                if ($scope.time.end) {
                    $scope.event.afterStr = $scope.time.end.clone().subtract($scope.event.after, 'm').format('H:mm');
                }
            }
        }
    }
})();