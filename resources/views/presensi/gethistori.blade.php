@if ( $histori->isEmpty())
<div class="alert alert-warning">
    <p>Data Belum Ada</p>
</div>
@endif
@foreach ($histori as $d)
    <ul class="listview image-listview">
        <li>
            <div class="item">
                @php
                $path =  Storage::url('upload/absensi/'. $d->bukti_in);
                @endphp
                <img src="{{ url($path) }}" alt="image" class="image">
                <div class="in">
                    <div>
                        <b>{{ date("d-m-Y", strtotime($d->tgl_presensi)) }}</b><br>
                    </div>
                    <span class="badge {{ $d->jam_in < "09:00" ? "bg-success" : "bg-danger"}}">
                        {{ $d->jam_in }}
                    </span>
                    <span class="badge bg-secondary">{{ $d->jam_out }}</span>
                </div>
            </div>
        </li>
    </ul>
@endforeach