<div class="row mt-2">
    <div class="col-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>NIK</th>
                    <th>Nama Pengguna</th>
                    <th>Jabatan</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody id="loadsetlistpenggunalibur"></tbody>
        </table>
    </div>
</div>

<script>
    $(function() {
        function loadsetlistpenggunalibur() {
            var kode_libur = "{{ $kode_libur }}";
            $("#loadsetlistpenggunalibur").load('/konfigurasi/harilibur/' + kode_libur +
                '/getsetlistpenggunalibur');
        }

        loadsetlistpenggunalibur();
    });
</script>
