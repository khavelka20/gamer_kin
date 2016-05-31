<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>GamerKin : @yield('title')</title>
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,500,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="{{URL::asset('fonts/font-awesome/css/font-awesome.min.css')}}" />
        <link rel="stylesheet" href="{{URL::asset('css/bootstrap.min.css')}}" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
        <link rel="stylesheet" href="{{URL::asset('css/simple-sidebar.css')}}" />
        <link rel="stylesheet" href="{{URL::asset('css/main.css')}}" />
    </head>
    <body ng-app="gamerkinApp">
        <div id="wrapper">
            <div id="sidebar-wrapper">
                <a class="sidebar-brand" href="#">
                    <img src="{{URL::asset('img/logo.png')}}"/>
                </a>
                <form role="search">
                    <div class="form-group">
                        <input type="text" style="width: 85%;margin-top: 20px;margin-left: 17px;" class="form-control" placeholder="Search For Games">
                    </div>
                </form>
                <ul class="sidebar-nav">
                    <li>
                        <a href="#/" ng-class="{'active' : globalVm.pages.myGames}">Your Games</a>
                    </li>
                    <li>
                        <a href="#/browse">Browse Games</a>
                    </li>
                    <li>
                        <a href="#">Your Recommendations</a>
                    </li>
                    <li>
                        <a href="#">Profile</a>
                    </li>
                </ul>
            </div>
            <div id="page-content-wrapper">
                <div id="quick-explanation">
                    <strong>gamerkin.com</strong> helps you to quickly find your next favorite game. Rate some of the games you have played to get better recommendations.
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{URL::asset('js/jquery.js')}}"></script>
        <script src="{{URL::asset('js/bootstrap.min.js')}}"></script>
        <script src="{{URL::asset('js/angular.min.js')}}"></script>
        <script src="{{URL::asset('js/angular-route.min.js')}}"></script>
        <script src="{{URL::asset('js/ui-bootstrap-tpls-1.3.3.min.js')}}"></script>
        <script src="{{URL::asset('js/app/gkApp.js')}}"></script>
    </body>
</html>