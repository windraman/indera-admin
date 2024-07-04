<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi STB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://api.hangover.id/public/css/remotestyle.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.2/jquery.min.js" integrity="sha512-tWHlutFnuG0C6nQRlpvrEhE4QpkG1nn2MOUMWmUeRePl4e3Aki0VB6W1v3oLjFtd0hVOtRQ9PHpSfN6u6/QXkQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
    <!--container-->
    <div class="container">
        <div class="d-flex flex-row justify-content-between px-3 py-4 align-items-center">
            <i class="fas fa-chevron-left" onclick="window.location.href='{{ CRUDBooster::adminPath('pelanggan') }}'"></i>
            <span>Pelanggan - {{ $pelanggan->nama_pelanggan }} ({{ $pelanggan->id_pelanggan }})</span>
            <!--<i class="fas fa-ellipsis-h"></i>-->
        </div>
        

            <label for="list-host">Detected host:</label><br>
            <div id="list-host" class="list-group">
              <li class="list-group-item" aria-current="true">Mencari ...</li>
            </div>


            <div>My IP Address : {{ getenv("REMOTE_ADDR") }}</div>

    </div>
</body>
    <script>
    var attemp = 0;
    setInterval(function() {
        if(attemp < 5 ){
            $.get('https://admin.diding.id/public/api/getnewstb?remoteip={!! getenv("REMOTE_ADDR") !!}', (data, status) => {
                // console.log(data);
                
                if(data.api_status==1){
                    if(data.data.length > 0){
                        var stbs = data.data;
                        $('#list-host').empty();
                        stbs.forEach(function(stb){
                            var ip = "'" +  stb.alamatip + "'";
                            $('#list-host').append('<button type="button" class="list-group-item list-group-item-action" onclick="registerHost(' + ip + ','+ stb.id +')">Host : ' + stb.alamatip + ' ('+ stb.id +' - '+ stb.hostname +') </button>');
                        });
                        attemp += 1;
                        
                        if(attemp >= 5){
                            console.log(data.data.length + ' STB ditemukan.');
                            attemp = 0;
                        }
                    }else{
                        attemp += 1;
                        console.log('attemp ' + attemp);
                        if(attemp >= 5){
                            // console.log('tidak ditemukan !');
                            $('#list-host').empty();
                            $('#list-host').append('<li class="list-group-item" aria-current="true">Tidak ditemukan !</li>');
                            attemp = 0;
                        }
                    }
                }
            });
        }
    }, 2000);
    
    function registerHost(ip,newid){
        $.ajax({
           url: 'http://'+ip+'/register?devid={!! $pelanggan->id_pelanggan !!}&new='+newid,
           succes: function(data, status){
              console.log(data);
               $('html').html(data);
            
           },
           complete: function(data, status){
                window.location.href="http://admin.diding.id/public/admin/pelanggan/remote/{!! $pelanggan->id_pelanggan !!}";
           },
         });
    }
        
    </script>
</html>