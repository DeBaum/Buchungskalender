(function () {
    angular.module('bkClient')
        .controller('BookObjectController', BookObjectController);

    BookObjectController.$inject = ['$scope', 'bookingDataFactory'];
    function BookObjectController($scope, bookingDataFactory) {
        bookingDataFactory.update();

        $scope.bookingData = bookingDataFactory;

        $scope.time = {
            start: bookingDataFactory.start,
            end: bookingDataFactory.end
        };

        $scope.$watch('event.before', setEventTimeStr);
        $scope.$watch('event.after', setEventTimeStr);

        $scope.objects = [{id: 1, title: 'Ford'}, {id: 2, title: 'Opel'}];
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