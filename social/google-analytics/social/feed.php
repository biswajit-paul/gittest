<html>
<head>
<title>Feed</title>

</head>
<body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
$.getJSON('json_data.php', function(data) {
  $.each(data, function(i) {
    console.log( data[i] );
    
    // do something with tweet
  });
});
</script>
</body>
</html>