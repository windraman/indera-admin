<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remote Control</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://admin.diding.id/public/css/remotestyle.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.2/jquery.min.js" integrity="sha512-tWHlutFnuG0C6nQRlpvrEhE4QpkG1nn2MOUMWmUeRePl4e3Aki0VB6W1v3oLjFtd0hVOtRQ9PHpSfN6u6/QXkQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
    <!--container-->
    <div class="container">
        <div class="d-flex flex-row justify-content-between px-3 py-4 align-items-center">
            <!--<i class="fas fa-chevron-left" ></i>-->
            <span>Pelanggan - {{ $pelanggan->nama_pelanggan }} ({{ $pelanggan->id_pelanggan}})</span>
            <!--<i class="fas fa-ellipsis-h"></i>-->
        </div>
        
        
        <div class="d-flex flex-row justify-content-center">
            <div class="menu-grid">
                <div class="d-flex flex-column align-items-center" onclick="window.location.href='{{ CRUDBooster::adminPath('pelanggan') }}'">
                    <i class="fas fa-arrow-left"></i>
                    <span class="label">Back</span>
                </div>
                @if($pelanggan->power == "true")
                <div id="power{{$pelanggan->id_pelanggan}}" class="d-flex flex-column align-items-center" onclick="getRemote('power','false')">
                    <i class="fas fa-power-off active"></i>
                @else
                <div id="power{{$pelanggan->id_pelanggan}}" class="d-flex flex-column align-items-center" onclick="getRemote('power','true')">
                    <i class="fas fa-power-off"></i>
                @endif
                    <span class="label">Power</span>
                </div>
                @if($pelanggan->display == "true")
                <div id="display{{$pelanggan->id_pelanggan}}" class="d-flex flex-column align-items-center" onclick="getRemote('display','false')">
                    <i class="fas fa-sign-in-alt active"></i>
                @else
                <div id="display{{$pelanggan->id_pelanggan}}" class="d-flex flex-column align-items-center" onclick="getRemote('display','true')">
                    <i class="fas fa-sign-in-alt"></i>
                @endif
                    <span class="label">Video</span>
                </div>
                <div class="d-flex flex-column align-items-center" onclick="getRequest('reset')">
                    <i class="fas fa-circle"></i>
                    <span class="label">Rst(Loc)</span>
                </div>
                @if($pelanggan->power == "true")
                <div id="lpower{{$pelanggan->id_pelanggan}}" class="d-flex flex-column align-items-center" onclick="getRequest('poweroff')">
                    <i class="fas fa-power-off active"></i>
                @else
                <div id="lpower{{$pelanggan->id_pelanggan}}" class="d-flex flex-column align-items-center" onclick="getRequest('poweron')">
                    <i class="fas fa-power-off"></i>
                @endif
                    <span class="label">Pow(loc)</span>
                </div>
                @if($pelanggan->display == "true")
                <div id="ldisplay{{$pelanggan->id_pelanggan}}" class="d-flex flex-column align-items-center" onclick="getRequest('displayoff')">
                    <i class="fas fa-sign-in-alt active"></i>
                @else
                <div id="ldisplay{{$pelanggan->id_pelanggan}}" class="d-flex flex-column align-items-center" onclick="getRequest('displayon')">
                    <i class="fas fa-sign-in-alt"></i>
                @endif
                    <span class="label">Vid(loc)</span>
                </div>
                
                
            </div>
        </div>
        @if($pelanggan->id_pelanggan!=0)
        <div class="d-flex flex-row mt-4 justify-content-between px-2">
            <div class="d-flex flex-column rounded-bg py-3 px-4 justify-content-center align-items-center">
                <i class="fas fa-chevron-up py-3 control-icon"></i>
                <span class="label py-3">Channel</span>
                <i class="fas fa-chevron-down py-3 control-icon"></i>
            </div>
            <div class="d-flex flex-column align-items-center">
                <div class="d-flex flex-row grey-bg justify-content-center align-items-center">
                    <i class="fas fa-home p-3 home-icon"></i>
                </div>
                <span class="label">Home</span>
            </div>
            <div class="d-flex flex-column rounded-bg py-3 px-4 justify-content-center align-items-center">
                <i class="fas fa-plus py-3 control-icon"></i>
                <span class="label py-3">Volume</span>
                <i class="fas fa-minus py-3 control-icon"></i>
            </div>
        </div>

        <div class="mt-5 pt-4 position-relative d-flex flex-row justify-content-center align-items-center">
            <div class="circle ok-inner position-absolute">
                <span>OK</span>
            </div>
            <div class="circle ok-outer position-absolute"></div>
            <i class="fas fa-caret-right position-absolute control-icon right"></i>
            <i class="fas fa-caret-right position-absolute control-icon bottom"></i>
            <i class="fas fa-caret-right position-absolute control-icon left"></i>
            <i class="fas fa-caret-right position-absolute control-icon top"></i>
        </div>

        <div class="d-flex flex-row justify-content-between mt-5 pt-4 px-3">
            <div class="d-flex flex-row grey-bg">
                <i class="fas fa-ellipsis-h p-3 control-icon"></i>
            </div>
            <div class="d-flex flex-row grey-bg">
                <i class="fas fa-volume-mute p-3 control-icon"></i>
            </div>
        </div>
        @endif
        <div class="d-flex flex-row justify-content-between px-3 py-4 align-items-center">
            <span>IP Address - {{ $pelanggan->alamatip }}</span>
        </div>
    </div>
</body>
<script>
    function getRequest(cmd){
        $.get('http://{!! $pelanggan->alamatip !!}/' + cmd, (data, status) => {
            console.log(data);
            if(data.power=="false"){
                $("#lpower{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRequest('poweron')");
                $("#lpower{!! $pelanggan->id_pelanggan !!}").find('.fas').removeClass('active');
                $("#power{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRemote('power','true')");
                $("#power{!! $pelanggan->id_pelanggan !!}").find('.fas').removeClass('active');
            }else{
                $("#lpower{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRequest('poweroff')");
                $("#lpower{!! $pelanggan->id_pelanggan !!}").find('.fas').addClass('active');
                $("#power{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRemote('power','false')");
                $("#power{!! $pelanggan->id_pelanggan !!}").find('.fas').addClass('active');
            }
            if(data.display=="false"){
                $("#ldisplay{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRequest('displayon')");
                $("#ldisplay{!! $pelanggan->id_pelanggan !!}").find('.fas').removeClass('active');
                $("#display{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRemote('display','true')");
                $("#display{!! $pelanggan->id_pelanggan !!}").find('.fas').removeClass('active');
            }else{
                $("#ldisplay{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRequest('displayoff')");
                $("#ldisplay{!! $pelanggan->id_pelanggan !!}").find('.fas').addClass('active');
                $("#display{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRemote('display','false')");
                $("#display{!! $pelanggan->id_pelanggan !!}").find('.fas').addClass('active');
            }
            if(cmd=="reset"){
                window.location.href="http://admin.diding.id/public/admin/pelanggan";
            }
            
        });
        
    }
    
    function getRemote(target,cmd){
        $.get('https://admin.diding.id/public/api/stb?id_pelanggan={!! $pelanggan->id_pelanggan !!}&'+ target +'=' + cmd, (data, status) => {
            console.log(data);
            if(data.api_status==1){
                if(data.power=="false"){
                    $("#lpower{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRequest('poweron')");
                    $("#lpower{!! $pelanggan->id_pelanggan !!}").find('.fas').removeClass('active');
                    $("#power{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRemote('power','true')");
                    $("#power{!! $pelanggan->id_pelanggan !!}").find('.fas').removeClass('active');
                }else{
                    $("#lpower{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRequest('poweroff')");
                    $("#lpower{!! $pelanggan->id_pelanggan !!}").find('.fas').addClass('active');
                    $("#power{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRemote('power','false')");
                    $("#power{!! $pelanggan->id_pelanggan !!}").find('.fas').addClass('active');
                }
                if(data.display=="false"){
                    $("#ldisplay{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRequest('displayon')");
                    $("#ldisplay{!! $pelanggan->id_pelanggan !!}").find('.fas').removeClass('active');
                    $("#display{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRemote('display','true')");
                    $("#display{!! $pelanggan->id_pelanggan !!}").find('.fas').removeClass('active');
                }else{
                    $("#ldisplay{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRequest('displayoff')");
                    $("#ldisplay{!! $pelanggan->id_pelanggan !!}").find('.fas').addClass('active');
                    $("#display{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRemote('display','false')");
                    $("#display{!! $pelanggan->id_pelanggan !!}").find('.fas').addClass('active');
                }
            }else{
                alert("Pengiriman data gagal : " + data );
            }
        });
    }
    
    setInterval(function() {
            $.get('https://admin.diding.id/public/api/stb?id_pelanggan={!! $pelanggan->id_pelanggan !!}', (data, status) => {
                console.log(data.remote_update);
                if(data.api_status==1){
                    if(data.power=="false"){
                        $("#lpower{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRequest('poweron')");
                        $("#lpower{!! $pelanggan->id_pelanggan !!}").find('.fas').removeClass('active');
                        $("#power{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRemote('power','true')");
                        $("#power{!! $pelanggan->id_pelanggan !!}").find('.fas').removeClass('active');
                    }else{
                        $("#lpower{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRequest('poweroff')");
                        $("#lpower{!! $pelanggan->id_pelanggan !!}").find('.fas').addClass('active');
                        $("#power{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRemote('power','false')");
                        $("#power{!! $pelanggan->id_pelanggan !!}").find('.fas').addClass('active');
                    }
                    if(data.display=="false"){
                        $("#ldisplay{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRequest('displayon')");
                        $("#ldisplay{!! $pelanggan->id_pelanggan !!}").find('.fas').removeClass('active');
                        $("#display{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRemote('display','true')");
                        $("#display{!! $pelanggan->id_pelanggan !!}").find('.fas').removeClass('active');
                    }else{
                        $("#ldisplay{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRequest('displayoff')");
                        $("#ldisplay{!! $pelanggan->id_pelanggan !!}").find('.fas').addClass('active');
                        $("#display{!! $pelanggan->id_pelanggan !!}").attr("onclick","getRemote('display','false')");
                        $("#display{!! $pelanggan->id_pelanggan !!}").find('.fas').addClass('active');
                    }
                    if(data.remote_update > 3){
                        alert('Koneksi remote terputus !');
                    }
                }
            });
    }, 3000);
</script>

</html>