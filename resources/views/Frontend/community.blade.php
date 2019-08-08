<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
      <meta charset="UTF-8">
      <meta name="description" content="">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <script>window.Laravel = { csrfToken : '{{ csrf_token() }}' }</script>  	
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <!-- Title -->
      <title>Trvel - Hotel Club</title>
      <!-- Favicon -->
      <link rel="icon" href="{{asset('blog/img/core-img/favicon.png')}}">
      <!-- Stylesheet -->      
      <link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons' rel="stylesheet">
      <!-- Styles -->      
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
      <link rel="stylesheet" href="{{asset('css/app.css')}}">
      <link rel="stylesheet" href="{{asset('css/community.css')}}">
    </head>
    <body>
        <div id="app"></div>
        <!-- **** All JS Files ***** -->
        <!-- jQuery 2.2.4 -->
        <script src="{{asset('js/community.js')}}"></script> 
    </body>
</html>
