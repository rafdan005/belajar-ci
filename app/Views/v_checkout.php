<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-6">
        <?= form_open('buy', 'class="row g-3"') ?>

<?= form_hidden('username', session()->get('username')) ?>

<?= form_input([
    'type'  => 'hidden',
    'name'  => 'total_harga',
    'id'    => 'total_harga',
    'value' => '']) ?>

<div class="col-12">
    <?= form_label('Nama', 'nama', ['class' => 'form-label']) ?>
    <?= form_input([
        'name'     => 'nama',
        'id'       => 'nama',
        'class'    => 'form-control',
        'value'    => session()->get('username'),
        'readonly' => true]) ?>
</div>
<div class="col-12">
    <?= form_label('Alamat', 'alamat', ['class' => 'form-label']) ?>
    <?= form_input([
        'name'  => 'alamat',
        'id'    => 'alamat',
        'class' => 'form-control']) ?>
</div> 
<div class="col-12"> 
    <?= form_label('Kelurahan', 'kelurahan', ['class' => 'form-label']) ?>
    <?= form_dropdown('kelurahan', [], '', ['id' => 'kelurahan', 'class' => 'form-control']) ?>
</div>
<div class="col-12"> 
    <?= form_label('Layanan', 'layanan', ['class' => 'form-label']) ?>
    <?= form_dropdown('layanan', [], '', ['id' => 'layanan', 'class' => 'form-control']) ?> 
</div>
<div class="col-12">
    <?= form_label('Ongkir', 'ongkir', ['class' => 'form-label']) ?>
    <?= form_input([
        'name'     => 'ongkir',
        'id'       => 'ongkir',
        'class'    => 'form-control',
        'readonly' => true]) ?>
</div>
<div class="col-12">
    <?= form_label('Kode Voucher', 'voucher_code', ['class' => 'form-label']) ?>
    <?= form_input([
        'name'        => 'voucher_code',
        'id'          => 'voucher_code',
        'class'       => 'form-control',
        'placeholder' => 'Contoh: PROMO2026']) ?>
    <small class="text-muted">
        Tersedia:
        <?php
        $labels = [];
        foreach ($voucher_list as $code => $rate) {
            $labels[] = $code . ' (' . ($rate * 100) . '%)';
        }
        echo implode(', ', $labels);
        ?>
    </small>
    <div id="voucher_feedback" class="small mt-1"></div>
</div>
<div class="col-12">
    <?= form_submit(
        'submit',
        'Buat Pesanan',
        ['class' => 'btn btn-primary']) ?>
</div>

<?= form_close() ?>
    </div>
    <div class="col-lg-6">
        <table class="table">
  <thead>
      <tr>
          <th scope="col">Nama</th>
          <th scope="col">Harga</th>
          <th scope="col">Jumlah</th>
          <th scope="col">Sub Total</th>
      </tr>
  </thead>
  <tbody>
      <?php 
      if (!empty($items)) :
          foreach ($items as $index => $item) :
      ?>
              <tr>
                  <td><?= $item['name'] ?></td>
                  <td><?= number_to_currency($item['price'], 'IDR') ?></td>
                  <td><?= $item['qty'] ?></td>
                  <td><?= number_to_currency($item['price'] * $item['qty'], 'IDR') ?></td>
              </tr>
      <?php
          endforeach;
      endif;
      ?>
      <tr>
          <td colspan="2"></td>
          <td>Subtotal</td>
          <td><?= number_to_currency($total, 'IDR') ?></td>
      </tr>
      <tr>
          <td colspan="2"></td>
          <td class="text-danger">Diskon Voucher</td>
          <td class="text-danger"><span id="diskon_voucher">-<?= number_to_currency(0, 'IDR') ?></span> <span id="diskon_persen" class="text-muted"></span></td>
      </tr>
      <tr>
          <td colspan="2"></td>
          <td>Biaya Jasa</td>
          <td><span id="biaya_jasa"><?= number_to_currency($biaya_jasa, 'IDR') ?></span></td>
      </tr>
      <tr>
          <td colspan="2"></td>
          <td class="text-success">Free Mouse</td>
          <td class="text-success"><span id="free_mouse"><?= $free_mouse > 0 ? '-' . number_to_currency($free_mouse, 'IDR') : number_to_currency(0, 'IDR') ?></span></td>
      </tr>
      <tr>
          <td colspan="2"></td>
          <td><strong>Subtotal (+Jasa-Voucher-FreeMouse)</strong></td>
          <td><strong><span id="subtotal_akhir"><?= number_to_currency($total + $biaya_jasa - $free_mouse, 'IDR') ?></span></strong></td>
      </tr>
      <tr>
          <td colspan="2"></td>
          <td><strong>Grand Total (incl. Ongkir)</strong></td>
          <td><strong><span id="total"><?= number_to_currency($total + $biaya_jasa - $free_mouse, 'IDR') ?></span></strong></td>
      </tr>
  </tbody>
</table>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
$(document).ready(function() {
    let ongkir = 0;
    let subtotal = <?= $total ?>;
    let hitungTimer = null;

    hitungTotal();

    function formatIDR(angka) {
        return 'IDR ' + Math.round(angka).toLocaleString('id-ID');
    }

    function hitungTotal() {
        $("#ongkir").val(ongkir);

        $.ajax({
            url: "<?= site_url('ajax/hitung') ?>",
            dataType: "json",
            data: {
                total_harga: subtotal,
                voucher_code: $('#voucher_code').val(),
                ongkir: ongkir
            },
            success: function (data) {
                $("#biaya_jasa").text(formatIDR(data.biaya_jasa));
                $("#diskon_voucher").text('-' + formatIDR(data.diskon_voucher));
                $("#free_mouse").text(data.free_mouse > 0 ? '-' + formatIDR(data.free_mouse) : formatIDR(0));
                $("#subtotal_akhir").text(formatIDR(data.subtotal_akhir));
                $("#total").text(formatIDR(data.grand_total));
                $("#total_harga").val(data.grand_total);

                let kode = $('#voucher_code').val().trim();
                if (kode === '') {
                    $('#diskon_persen').text('');
                    $('#voucher_feedback').html('');
                } else if (data.voucher_valid) {
                    let persen = subtotal > 0 ? Math.round((data.diskon_voucher / subtotal) * 100) : 0;
                    $('#diskon_persen').text('(' + persen + '%)');
                    $('#voucher_feedback').html('<span class="text-success">Voucher berhasil diterapkan</span>');
                } else {
                    $('#diskon_persen').text('');
                    $('#voucher_feedback').html('<span class="text-danger">Kode voucher tidak valid</span>');
                }
            }
        });
    }

    $('#kelurahan').select2({
        placeholder: 'Cari daerah tujuan',
        minimumInputLength: 3,
        ajax: {
            url: '<?= site_url('ajax/destinations') ?>',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                return data;
            },
            cache: true
        } 
    });
    $("#kelurahan").on('change', function () {
        let id_kelurahan = $(this).val();

        $("#layanan").empty();
        ongkir = 0;
        hitungTotal(); 

        $.ajax({
            url: "<?= site_url('ajax/costs') ?>", 
            dataType: "json",
            data: {
                destination: id_kelurahan
            },
            success: function (data) { 
                data.forEach(function (item) {
                    $("#layanan").append(
                        $('<option>', {
                            value: item.cost,
                            text: `${item.description} (${item.service}) : estimasi ${item.etd}`
                        })
                    );
                });
            }
        });
    });
    $("#layanan").on('change', function() {
        ongkir = parseInt($(this).val());
        hitungTotal();
    });

    $('#voucher_code').on('input', function() {
        clearTimeout(hitungTimer);
        hitungTimer = setTimeout(hitungTotal, 400);
    });
});
</script>
<?= $this->endSection() ?>
