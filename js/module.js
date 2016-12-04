registerController('SSIDPoolController', ['$api', '$scope', function($api, $scope) {
    $scope.ssidCount = 0;
    $scope.backupSuccess = "";
    // Init
    $api.request({
        module: 'SSIDPool',
        action: 'getSSIDCount'
    }, function(response) 
       {
        $scope.refreshSSIDPool();
        $scope.ssidCount = response.ssidCount;
       }
       );

    //Save current SSID list
    $scope.backupCurrent = (function() {
        $api.request({
            module: 'SSIDPool',
            action: 'backupCurrent'
        }, function(response) {
            $scope.refreshSSIDPool();
            $scope.backupSuccess = response.error;
        });
    });

    //Save current SSID list
    $scope.refreshSSIDPool = (function() {
        $api.request({
            module: 'SSIDPool',
            action: 'getSSIDPool'
        }, function(response) {
            $scope.SSIDPool = response;
        });
    });
    //Download select file
    $scope.downloadSSIDFile = (function(param) {
                        $api.request({                                                                                                        
                                        module: 'SSIDPool',
                                        action: 'downloadSSIDFile',
                                        file: param
                        }, function(response) {
                                        if (response.error === undefined) {
                                                        window.location = '/api/?download=' + response.download;
                                        }
                        });
        });

    //Delete selected file
    $scope.deleteSSIDFile = (function(param) {
            $api.request({                                                                                                                                
            module: "SSIDPool",             
            action: "deleteSSIDFile",     
                    file: param     
        }, function(response) {
            $scope.refreshSSIDPool();    
        })                                
    });

    // View selected file
    $scope.viewSSIDFile = (function(param) {
            $api.request({                                                                                                                                
            module: "SSIDPool",             
            action: "viewSSIDFile",       
                    file: param     
        }, function(response) {
            $scope.SSIDFile = response.output;
        })                          
    });

    // Restore selected file
    $scope.restoreSSIDFile = (function(param) {
            $api.request({                                                                                                                                
            module: "SSIDPool",             
            action: "restoreSSIDFile",     
                    file: param     
        }, function(response) {
            $scope.ssidCount = response.ssidCount;
        })                                
    });

    // Merge all SSID
    $scope.mergeAllSSID = (function(param) {
            $api.request({                                                                                                                                
            module: "SSIDPool",             
            action: "mergeAllSSID",     
                    file: param     
        }, function(response) {
            $scope.ssidCount = response.ssidCount;
        })                                
    });

}]);
