<form action="/pegawai/{{ $pegawai->nik }}/update" method="POST" id="frmPegawai" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <input type="text" readonly value="{{ $pegawai->nik }}" class="form-control input-default" id="nik" name="nik"
            placeholder="NIK">
    </div>
    <div class="input-group">
        <input type="text" value="{{ $pegawai->username }}" id="username" name="username" class="form-control input-default" placeholder="Username">
    </div>
    <br>
    <div class="form-group">
        <input type="text" value="{{ $pegawai->nama_lengkap }}" class="form-control input-default" id="nama_lengkap"
            name="nama_lengkap" placeholder="Nama Lengkap">
    </div>
    <div class="form-group">
        <input type="text" value="{{ $pegawai->jabatan }}" class="form-control input-default" id="jabatan"
            name="jabatan" placeholder="Jabatan">
    </div>
    <div class="form-group">
        <input type="text" value="{{ $pegawai->no_hp }}" class="form-control input-default" id="no_hp" name="no_hp"
            placeholder="No HP">
    </div>
    <div class="form-group">
        <label for="foto">Foto</label>
        <input type="file" value="{{ $pegawai->foto }}" name="foto" class="form-control-file">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>