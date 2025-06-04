
<?php $__env->startSection('header'); ?>
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">E-Presensi</div>
        <div class="right"></div>
    </div>
    <style>
        .webcam-capture,
        .webcam-capture video {
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 15px;
            /* margin-top: 20px; */
        }

        #map {
            height: 200px;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row" style="margin-top: 70px;">
        <div class="col">
            <input type="hidden" id="lokasi">
            <div class="webcam-capture"></div>
        </div>
    </div>
    <div class="row">
        <div class="col">  
            <?php if($cek > 0): ?>
            <button id="takeabsen" class="btn btn-danger btn-block">
                <ion-icon name="camera-outline"></ion-icon>Absen Pulang</button>
            <?php else: ?>
            <button id="takeabsen" class="btn btn-primary btn-block">
                <ion-icon name="camera-outline"></ion-icon>Absen Masuk</button>
            <?php endif; ?>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <div id="map"></div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('myscript'); ?>
    <script>
        Webcam.set({
            height: 480,
            width: 640,
            image_format: 'jpeg',
            jpeg_quality: 80
        });

        Webcam.attach('.webcam-capture')

        var lokasi = document.getElementById('lokasi');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        } else {
            lokasi.value = "Geolocation tidak didukung oleh browser ini.";
        }

        function successCallback(position) {
            lokasi.value = position.coords.latitude + ", " + position.coords.longitude;
            var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 16);
            var lokasi_kantor = "<?php echo e($lok_kantor->lokasi_kantor); ?>";
            var lok = lokasi_kantor.split(",");
            var lat_kantor = lok[0];
            var long_kantor = lok[1];
            var radius = <?php echo e($lok_kantor->radius); ?>;
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
            var circle = L.circle([lat_kantor, long_kantor], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: radius
            }).addTo(map);
        }

        function errorCallback(error) {
            console.error(error);
            lokasi.value = "Tidak dapat mengambil lokasi.";
        }

        $("#takeabsen").click(function (e) {
            Webcam.snap(function (uri) {
                image = uri;
            });
            var lokasi = $("#lokasi").val();
            $.ajax({
                type: 'POST',
                url: '/presensi/store',
                data: {
                    _token: "<?php echo e(csrf_token()); ?>",
                    image: image,
                    lokasi: lokasi
                },
                cache: false,
                success: function (respond) {
                    var status = respond.split("|");
                    if (status[0] == "success") {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: status[1],
                            icon: 'success'
                        })
                        setTimeout("location.href='/dashboard'", 3000);
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: status[1],
                            icon: 'error'
                        })
                    }
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.attendance', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\attendance-ypb\resources\views/presensi/create.blade.php ENDPATH**/ ?>