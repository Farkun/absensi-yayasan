<form action="/cuti/{{ $cuti->kode_cuti }}/update" method="POST" id="frmCuti" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <input type="text" value="{{ $cuti->kode_cuti }}" class="form-control input-default" id="kode_cuti" name="kode_cuti" placeholder="Kode Cuti" readonly>
    </div>
    <div class="form-group">
        <input type="text" value="{{ $cuti->nama_cuti }}" class="form-control input-default" id="nama_cuti" name="nama_cuti" placeholder="Nama Cuti">
    </div>
    <div class="form-group">
        <input type="text" value="{{ $cuti->jml_hari }}" class="form-control input-default" id="jml_hari" name="jml_hari" placeholder="Jumlah Hari">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>