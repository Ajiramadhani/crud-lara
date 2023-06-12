<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable({
                processing:true,
                serverside:true,
                ajax:"{{ url('pegawaiAjax') }}",
                columns:[{
data:'DT_RowIndex',
name:'DT_RowIndex',
orderable:false,
searchable:false
                },{
                    data:'nama',
                    name:'Nama'
                },{
                    data:'email',
                    name:'Email'
            },{
                data:'aksi',
                name:'Aksi'
            }]
            });
        });
            // Global Setup
            $.ajaxSetup({
                headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // PROSES SIMPAN
            $('body').on('click','.btn-tambah',function (e) {
                e.preventDefault(); //agar tidak auto refresh
                $('#tambahModal').modal('show');
                $('.btn-simpan').click(function () {
                    simpan();
                });
            });
            // beda dari tutorial
            
            // PROSES EDIT
            $('body').on('click','.btn-edit',function (e) {
                var id = $(this).data('id');
                $.ajax({
                    url: 'pegawaiAjax/' + id + '/edit',
                    type:'GET',
                    success:function (response) {
                        $('#tambahModal').modal('show');
                        $('#nama').val(response.result.nama);
                        $('#email').val(response.result.email);
                        console.log(response.result);
                        $('.btn-simpan').click(function () {
                        simpan(id);
                        });
                    }
                });
            });

// PROSES DELETE
            $('body').on('click','.btn-del',function (e) {
                if (confirm('Yakin mau hapus data ini?') == true) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: 'pegawaiAjax/'+id,
                        type:'DELETE'
                    });
                    $('#myTable').DataTable().ajax.reload();
                }            
            });


// FUNGSI SIMPAN DAN UPDATE
            function simpan(id = '') {
                if (id == '') {
                    var var_url = 'pegawaiAjax';
                    var var_type = 'POST';
                } else {
                    var var_url = 'pegawaiAjax/'+id;
                    var var_type = 'PUT';
                }
                    $.ajax({
                    url: var_url,
                    type: var_type,
                    data:{
                    nama : $('#nama').val(),
                    email : $('#email').val()
                    },
                    success: function(response){
                        if (response.errors) {
                            console.log(response.errors);
                            $('.alert-danger').removeClass('d-none');
                            $('.alert-danger').append("<ul>");
                            $.each(response.errors, function(key, value) {
                            $('.alert-danger').find('ul').append("<li>"+value+"</li>");
                            });
                            $('.alert-danger').append("</ul>");
                        } else {
                            $('.alert-success').removeClass('d-none');
                            $('.alert-success').html(response.success);
                            
                        // REFRESH PAGE
                        $('#myTable').DataTable().ajax.reload();
                        $('#tambahModal').fadeOut(400, function () {
                            $('.modal-backdrop').remove();
                            $('#tambahModal').modal('hide');
                            $('#nama').val('');
                            $('#email').val('');

                            $('.alert-danger').addClass('d-none');
                            $('.alert-danger').html('');
                            $('.alert-success').addClass('d-none');
                            $('.alert-success').html('');
                        });
                    }
                }
                });            
            }
        // END PROSES SIMPAN
    </script>