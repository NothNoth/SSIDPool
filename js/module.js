registerController('SSIDPoolController', ['$api', '$scope', function($api, $scope) {
    $scope.ssidCount = 0;
    $scope.backupSuccess = "";
    // Init
    $api.request({
        module: 'SSIDPool',
        action: 'getSSIDCount'
    }, function(response) 
       {
        $scope.refreshHistory();
        $scope.ssidCount = response.ssidCount;
       }
       );

    //Save current SSID list
    $scope.backupCurrent = (function() {
        $api.request({
            module: 'SSIDPool',
            action: 'backupCurrent'
        }, function(response) {
            $scope.refreshHistory();
            $scope.backupSuccess = response.error;
        });
    });

    //Save current SSID list
    $scope.refreshHistory = (function() {
        $api.request({
            module: 'SSIDPool',
            action: 'getHistory'
        }, function(response) {
            $scope.history = response;
        });
    });


}]);