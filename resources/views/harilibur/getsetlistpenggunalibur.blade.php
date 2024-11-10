@foreach ($pengguna as $d)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $d->nik }}</td>
        <td>{{ $d->nama_lengkap }}</td>
        <td>{{ $d->jabatan }}</td>
        <td>
            @if (!empty($d->ceknik))
                <a href="#" class="btn btn-danger btn-sm removepengguna" nik="{{ $d->nik }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-x" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14z" />
                        <path d="M9 9l6 6m0 -6l-6 6" />
                    </svg>
                </a>
            @else
                <a href="#" class="btn btn-secondarys btn-sm tambahpengguna" nik="{{ $d->nik }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                </a>
            @endif

        </td>
    </tr>
@endforeach

<script>
    $(function() {
        function loadsetlistpenggunalibur() {
            var kode_libur = "{{ $kode_libur }}";
            $("#loadsetlistpenggunalibur").load('/konfigurasi/harilibur/' + kode_libur +
                '/getsetlistpenggunalibur');
        }

        function loadpenggunalibur() {
            var kode_libur = "{{ $kode_libur }}";
            $("#loadpenggunalibur").load('/konfigurasi/harilibur/' + kode_libur + '/getpenggunalibur');
        }

        $(".tambahpengguna").click(function(e) {
            var kode_libur = "{{ $kode_libur }}";
            var nik = $(this).attr('nik');
            $.ajax({
                type: 'POST',
                url: '/konfigurasi/harilibur/storepenggunalibur',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_libur: kode_libur,
                    nik: nik
                },
                cache: false,
                success: function(respond) {
                    if (respond === '0') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Data Berhasil Disimpan !',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        })
                        loadsetlistpenggunalibur();
                        loadpenggunalibur();
                    } else if (respond === '1') {
                        Swal.fire({
                            title: 'Warning!',
                            text: 'Data Sudah Ada!',
                            icon: 'warning',
                            confirmButtonText: 'Ok'
                        })
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: respond,
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        })
                    }
                }
            });
        });


        $(".removepengguna").click(function(e) {
            var kode_libur = "{{ $kode_libur }}";
            var nik = $(this).attr('nik');
            $.ajax({
                type: 'POST',
                url: '/konfigurasi/harilibur/removepenggunalibur',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_libur: kode_libur,
                    nik: nik
                },
                cache: false,
                success: function(respond) {
                    if (respond === '0') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Data Berhasil Di Hapus !',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        })
                        loadsetlistpenggunalibur();
                        loadpenggunalibur();
                    } else if (respond === '1') {
                        Swal.fire({
                            title: 'Warning!',
                            text: 'Data Sudah Ada!',
                            icon: 'warning',
                            confirmButtonText: 'Ok'
                        })
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: respond,
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        })
                    }
                }
            });
        });
    });
</script>
