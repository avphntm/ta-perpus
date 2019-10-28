@extends('../template')

@section('title','Koleksi Buku')
@section('page')
<div ng-app="tesApp" ng-controller="tesCtrl" class="container shadow-lg">
    <br>
    <div ng-init="idny='{{$idnya}}'" style="padding: 8px;">
        <h3 class="mt-3">Koleksi Buku</h3>

        <!-- Trigger the modal with a button -->
        <button type="button" class="btn btn-info mt-3" data-toggle="modal" data-target="#Modalinsert"><i class="fas fa-plus-circle fa-fw"></i>&nbsp;Tambah</button>

        <!-- modal insert -->
        <div id="Modalinsert" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>
                            Tambah Koleksi
                        </h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form>
                            @csrf
                            <div class="wrap">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="">Kategori</label>
                                            <select id="jenis_id" ng-model="jenis_id" name="jenis_id" class="form-control" required>
                                                @foreach($category as $c)
                                                <option value="{{$c->id_category}}" nama="{{$c->category}}">{{$c->category}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="">Judul</label>
                                            <input type="text" class="form-control" id="judul" ng-model="judul" name="judul" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="">Penulis</label>
                                            <input type="text" class="form-control" id="penulis" ng-model="penulis" name="penulis" required>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="">Penerbit</label>
                                            <input type="text" class="form-control" id="penerbit" ng-model="penerbit" name="penerbit" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-success" ng-click="simpan()">Simpan</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                </div>

            </div>
        </div>
        <br><br>
        <table class="table table-striped mt-5" id="myTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Kode Buku</th>
                    <th>Judul</th>
                    <th>Penerbit</th>
                    <th>Penulis</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bukux as $key => $b)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$b->category}}</td>
                    <td>{{$b->kodebuku}}</td>
                    <td>{{$b->judul}}</td>
                    <td>{{$b->penerbit}}</td>
                    <td>{{$b->penulis}}</td>
                    <td ng-if="{{$b->status == 0}}">Tersedia</td>
                    <td ng-if="{{$b->status == 1}}">Dipinjam</td>
                    <td>
                        <a href="editBuku/{{$b->id_buku}}" class="btn btn-primary"><i class="fas fa-pencil-alt fa-fw"></i>
                        </a>

                        <button ng-click="hapus({{$b->id_buku}})" idnya="{{$b->id_buku}}" id="delbtn" class="btn btn-danger"><i class="fas fa-trash fa-fw"></i></button><!-- href="delete/{{$b->id_buku}}" -->
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <br><br>
</div>
<!-- App ctrl angular -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#myTable').DataTable({
            // "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            // dom: 'Blfrtip',
            // buttons: ['excel','print'],
            // "lengthChange": true
        });
    });

    var app = angular.module('tesApp', []);
    app.controller('tesCtrl', function($scope, $http, $window) {
        //vars 
        var allb = document.getElementById("jenis_id");
        var delbtn = document.getElementById("delbtn");

        // vars input
        $scope.idbuku; //var addens kodebuku
        $scope.kode;
        $scope.judul = "";
        $scope.penulis;
        $scope.penerbit;

        $scope.simpan = function() {
            if ($scope.judul == null || $scope.jenis_id == null || $scope.penulis == null || $scope.penerbit == null) {
                $.growl.error({
                    message: "Isi semua field!"
                });
            } else {
                //generate kode
                var idbk = JSON.parse($scope.idny);
                $scope.idbuku = idbk + 1;
                $scope.kode = $scope.judul.substring(0, 4).toUpperCase() + "-" + $scope.idbuku;
                //nmcat
                $scope.nmcat = allb.options[allb.selectedIndex].getAttribute("nama");
                //saving
                $http.post('{{url("inserBuku")}}', {
                    kode: $scope.kode,
                    jenis_id: $scope.jenis_id,
                    judul: $scope.judul,
                    penulis: $scope.penulis,
                    penerbit: $scope.penerbit,
                    nmcat: $scope.nmcat,
                    _token: '{{csrf_token()}}'

                }).then(function(reply) {
                    //alert("Data Buku sudah disimpan");
                    $.growl.notice({
                        message: "Data Buku sudah disimpan"
                    });
                    $window.location.replace("viewBuku");
                });
            }
        }

        $scope.hapus = function(id) {
            $scope.delid = id;
            console.log(id);
            //deleting
            $http.post('{{url("deleteBuku")}}', {
                id: $scope.delid
            }).then(function(reply) {
                //alert("Data Buku sudah disimpan");
                $.growl.notice({
                    message: "Data Buku sudah dihapus"
                });
                $window.location.replace("viewBuku");
            });
        }

        //

    });
</script>
@endsection