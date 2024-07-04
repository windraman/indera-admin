@extends('crudbooster::admin_template')

@section('content')
<form method='post' action='{{ $next }}'>
    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

    <div class="form-group" >
        <label for="id_pelanggan">Pelanggan</label>
        <select class="form-control" id="id_pelanggan" name="id_pelanggan" required>
            @foreach($pelanggan as $pel)
                <option value="{{ $pel->id_pelanggan }}">{{ $pel->nama_pelanggan }} - {{ $pel->alamat }} - {{ $pel->no_telp }}</option>
            @endforeach
        </select>
      </div>
</form>
@endsection