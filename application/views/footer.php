</div>

<!-- jQuery -->
<script src="<?= site_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?= site_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?= site_url('assets/dist/js/adminlte.min.js') ?>"></script>
<script>
    setInterval(function () {
        $.ajax({
            url: '<?= site_url("cek_token") ?>',
            type: 'POST',
            success: function (res) {
                if (!res.valid) {
                    alert('Sesi anda habis, silakan login ulang');
                    window.location.href = '/login';
                }
            }
        });
    }, 60000);
</script>
</body>

</html>