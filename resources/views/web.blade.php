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
      
      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css" />
      <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>
      <script src="https://tiles.unwiredmaps.com/js/leaflet-unwired.js"></script>
      <link rel="stylesheet" href="{{asset('css/leaflet.extra-markers.min.css')}}">
      <script src="{{asset('js/leaflet.extra-markers.min.js')}}"></script>



        <style>
    #mapid { height: 180px; }
        #map {
  margin: auto;
  padding: 10px;
  border: 1px solid black;
  width: 100%;
  height: 50vh;
}</style>

    </head>
    <body>
        <div id="app"></div>
        <!-- **** All JS Files ***** -->
        <!-- jQuery 2.2.4 -->
        <script src="{{asset('js/app.js')}}"></script> 
        
    </body>
</html>
