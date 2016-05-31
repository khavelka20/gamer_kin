app = angular.module('gamerkinApp', ['ngRoute', 'ui.bootstrap'], function($interpolateProvider, $routeProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');

    $routeProvider.when('/', {
        templateUrl: 'views/home.html',
        controller: 'HomeController'
    }).when('/browse', {
        templateUrl: 'views/browse.html',
        controller: 'BrowseController'
    }).otherwise({
        redirectTo: '/'
    });
});

app.controller('BrowseController', ['$scope', 'browseService', function($scope, browseService) {

    $scope.globalVm = {};
    $scope.vm = {};

    function startUp() {
        browseService.startUp($scope);
        browseService.loadGamesToBrowse($scope, $scope.globalVm.gamer);
    }

    $scope.showPreview = function(game) {
        browseService.showPreview($scope, game);
    };

    $scope.hidePreview = function(game) {
        browseService.hidePreview(game);
    };

    startUp();
}]);

app.factory('browseService', ['dataService', 'stateService', 'messageService', '$timeout', function(dataService, stateService, messageService, $timeout) {

    var startUp = function($scope) {
            $scope.vm = stateService.getBrowseVm();
            $scope.vm = stateService.getGlobalVm();
        };

    var showPreview = function($scope, game) {
            hidePreviews($scope);
            game.showPreview = true;
            game.showHeader = false;
        };

    var hidePreview = function(game) {
            game.showPreview = true;
            game.showHeader = false;
        };

    var hidePreviews = function($scope) {
            angular.forEach($scope.vm.games, function(game) {
                game.showPreview = false;
                game.showHeader = true;
            });
        };

    var loadGamesToBrowse = function($scope, gamer) {
            $scope.globalVm.loading = true;
            dataService.loadGamesToBrowse(3).then(function(data) {
                $scope.globalVm.loading = false;
                $scope.vm.games = data;
            });
        };

    return ({
        startUp: startUp,
        showPreview: showPreview,
        hidePreview: hidePreview,
        loadGamesToBrowse: loadGamesToBrowse
    });
}]);

app.controller('HomeController', ['$scope', 'homeService', function($scope, homeService) {

    $scope.globalVm = {};
    $scope.vm = {};

    function startUp() {
        homeService.startUp($scope);
    }

    $scope.rateGame = function(gamerGame) {
        homeService.rateGame($scope, gamerGame);
    };

    startUp();
}]);

app.factory('homeService', ['dataService', 'stateService', 'messageService', 'messageService', function(dataService, stateService, messageService) {

    var startUp = function($scope) {
            $scope.globalVm = stateService.getGlobalVm();
            $scope.vm = stateService.getHomeVm();
            dataService.loadGamer($scope.globalVm.steamId).
            then(function(data) {
                $scope.globalVm.gamer = data;
                loadGamerGames($scope);
            });
        };

    var loadGamerGames = function($scope) {
            $scope.vm.loading = true;
            dataService.loadGamerGames($scope.globalVm.gamer).
            then(function(data) {
                $scope.vm.loading = false;
                $scope.globalVm.gamerGames = data;
                $scope.vm.librarySize = $scope.globalVm.gamerGames.length;
            });
        };

    var rateGame = function($scope, gamerGame) {
            $scope.vm.ratingPending = true;

            dataService.rateGame($scope.globalVm.gamer, gamerGame).then(function() {
                $scope.vm.ratingPending = false;
                messageService.showTimedMessage($scope, "success", "Game Rating Saved", 2000);
            });
        };

    return ({
        startUp: startUp,
        rateGame: rateGame
    });

}]);

app.factory('dataService', ['$http', function($http) {

    var apiBaseUrl = "http://127.0.0.1/edsa-gk/api/";

    var loadGamer = function(steamId) {
            return $http.get(apiBaseUrl + "loadGamer/" + steamId).then(function(response) {
                return response.data;
            });
        };

    var loadGamerGames = function(gamer) {
            return $http.get(apiBaseUrl + "loadGamerGames/" + gamer.id).then(function(response) {
                return response.data;
            });
        };

    var rateGame = function(gamer, gamerGame) {
            return $http.get(apiBaseUrl + "rateGamerGame/" + gamer.id + "/" + gamerGame.id + "/" + gamerGame.rating).then(function(response) {
                return response.data;
            });
        };

    var loadGamesToBrowse = function(gamerId) {
            return $http.get(apiBaseUrl + "loadGamesToBrowse/" + gamerId).then(function(response) {
                return response.data;
            });
        };

    return ({
        loadGamer: loadGamer,
        loadGamerGames: loadGamerGames,
        rateGame: rateGame,
        loadGamesToBrowse: loadGamesToBrowse
    });

}]);

app.factory('messageService', ['$timeout', '$sce', function($timeout, $sce) {

    var showTimedMessage = function($scope, messageType, message, delay) {
            var messageText = createMessageText(messageType, message);

            $scope.vm.alertMessage = $sce.trustAsHtml(messageText);

            $timeout(function() {
                $scope.vm.alertMessage = "";
            }, delay);
        };

    function createMessageText(messageType, message) {
        var messageText = "";

        if (messageType === "success") {
            messageText = '<i class="fa fa-check" aria-hidden="true"></i> <strong>' + message + '</strong>';
        }

        return messageText;
    }

    return ({
        showTimedMessage: showTimedMessage
    });

}]);

app.factory('stateService', [function() {

    var globalVm = {
        steamId: "76561197974127223",
        gamer: {},
        gamerGames: {}
    };

    var browseVm = {
        games: {},
        timer: {}
    };

    var homeVm = {
        loading: false,
        ratingPending: false,
        currentPage: 1,
        librarySize: 0,
        gamesPerPage: 12
    };

    var getGlobalVm = function() {
            return globalVm;
        };

    var getHomeVm = function() {
            return homeVm;
        };

    var getBrowseVm = function() {
            return browseVm;
        };

    return ({
        getGlobalVm: getGlobalVm,
        getHomeVm: getHomeVm,
        getBrowseVm: getBrowseVm
    });
}]);

app.filter('readableTime', function() {

    var conversions = {
        'ss': angular.identity,
        'mm': function(value) {
            return value * 60;
        },
        'hh': function(value) {
            return value * 3600;
        }
    };

    var padding = function(value, length) {
            var zeroes = length - ('' + (value)).length,
                pad = '';
            while (zeroes-- > 0)
            pad += '0';
            return pad + value;
        };

    return function(value, unit, format, isPadded) {
        var totalSeconds = conversions[unit || 'ss'](value),
            hh = Math.floor(totalSeconds / 3600),
            mm = Math.floor((totalSeconds % 3600) / 60),
            ss = totalSeconds % 60;

        format = format || 'hh:mm:ss';
        isPadded = angular.isDefined(isPadded) ? isPadded : true;
        hh = isPadded ? padding(hh, 2) : hh;
        mm = isPadded ? padding(mm, 2) : mm;
        ss = isPadded ? padding(ss, 2) : ss;

        return format.replace(/hh/, hh).replace(/mm/, mm).replace(/ss/, ss);
    };
});

app.directive('youtubeEmbed', function($sce) {
    return {
        restrict: 'EA',
        scope: {
            code: '='
        },
        replace: true,
        template: '<div style="height:400px;"><iframe style="overflow:hidden;height:100%;width:100%" width="100%" height="100%" src="{{url}}" frameborder="0" allowfullscreen></iframe></div>',
        link: function(scope) {
            scope.$watch('code', function(newVal) {
                if (newVal) {
                    scope.url = $sce.trustAsResourceUrl("http://www.youtube.com/embed/" + newVal + "?iv_load_policy=3");
                }
            });
        }
    };
});