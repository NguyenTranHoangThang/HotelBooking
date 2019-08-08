<!DOCTYPE html>
<html lang="en">

<head>
    <link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons' rel="stylesheet">
    <!-- <link href="{{asset("")}}"> -->
    <link rel="stylesheet" href="{{asset('blog/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <!-- {{-- <link rel="stylesheet" href="../node_modules/vuetify/src/stylus/main"> --}} -->
    <!-- {{-- <script src="../node_modules/vuetify/src/stylus/main"> --}} -->
    @include('Backend.layouts.header')

</head>

<body>
    <div id="app">
    </div>

    @include('Backend.layouts.footer')
    <script src="{{asset('js/managerLogin.js')}}"></script>
</body>

</html>
<!-- end document-->