<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="colorlib.com">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500" rel="stylesheet" />
    <link href="css/main.css" rel="stylesheet" />
  </head>
  <body>
    <div class="s002"> 
      @if($pelanggan)
        
        <div>
          <div style="margin: 5px;padding:0px;background: rgba(51, 170, 51, .4)">
            <h2  style="text-align:center">HASIL PENCARIAN</h2>
            <table style="text-align:left;font-size:.9em">
              <tr>
                <th>ID Pelanggan   :</th>
                <td>{{ $pelanggan->id_pelanggan }}</td>
              </tr>
              <tr>
                <th>Nama   :</th>
                <td>{{ $pelanggan->nama_pelanggan }}</td>
              </tr>
              <tr>
                <th>Alamat :</th>
                <td>{{ $pelanggan->alamat }}</td>
              </tr>
              <tr>
                <th>Mulai langganan :</th>
                <td>{{ $pelanggan->tanggal_mendaftar }}</td>
              </tr>
              <tr>
                <th>Tagihan per tanggal :</th>
                <td>{{ $pelanggan->siklus }}</td>
              </tr>
            </table>
            <br>
            <h3  style="text-align:center">Tagihan</h3>
            <table style="width: 100%;text-align:center;font-size:.8em">
              <tr>
                <th>Bulan</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Keterangan</th>
              </tr>
              @foreach ($terbayar as $bayar)
              <tr>
                <td>{{ $bayar->bulan }}</td>
                <td>Rp {{ $bayar->jumlah }}</td>
                <td>{{ $bayar->status }}</td>
                <td>{{ $bayar->keterangan }}</td>
              </tr>
              @endforeach
            </table>
          </div>
          <br><br>
          <form url="/cari" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <div class="inner-form">
              <div class="input-field first-wrap">
                <div class="icon-wrap">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"></path>
                  </svg>
                </div>
                <input id="search" type="text" name="id_pelanggan" placeholder="Masukkan nomor/ID pelanggan anda" required />
              </div>
            
              <div class="input-field fifth-wrap">
                <button class="btn-search" type="submit">CARI</button>
              </div>
            </div>
          </form>
        </div> 
        
      @else
      <div>
        @if($pesan)
        <h2  style="text-align:center">Pelanggan tidak ditemukan !</h2>
        @endif
        <form url="/cari" method="POST">
          <fieldset>
            <legend>CARI PELANGGAN</legend>
          </fieldset>
          <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
          <div class="inner-form">
            <div class="input-field first-wrap">
              <div class="icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"></path>
                </svg>
              </div>
              <input id="search" type="text" name="id_pelanggan" placeholder="Masukkan nomor/ID pelanggan anda" required />
            </div>
          
            <div class="input-field fifth-wrap">
              <button class="btn-search" type="submit">CARI</button>
            </div>
          </div>
        </form>
      </div>
      @endif
    </div>
  </body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>
