<?php
/**
 * Plugin Name: WhatsApp İletişim
 * Description: Whatsapp iletişim bilgisi ve mesajı.
 * Version: 1.0
 * Author: Koray Ademoğlu
 */


add_action('admin_menu', 'whatsapp_eklentim_menu');

function whatsapp_eklentim_menu() {
    add_menu_page(
        'WhatsApp Eklentim',
        'WhatsApp Eklentim',
        'manage_options',
        'whatsapp-eklentim',
        'whatsapp_eklentim_ayarlar_sayfasi',
        'dashicons-whatsapp',
        100
    );
}

function whatsapp_eklentim_ayarlar_sayfasi() {
    ?>
    <div class="wrap">
        <h1>WhatsApp Eklentim Ayarları</h1>
        <p>Bu sayfa Koray Ademoğlu tarafından geliştirilmiştir.</p>
        <form method="post" action="options.php">
            <?php
                settings_fields('whatsapp_eklentim_ayarlar_grubu');
                do_settings_sections('whatsapp-eklentim');
                submit_button();
            ?>
        </form>
    </div>
    <?php
}

add_action('admin_init', 'whatsapp_eklentim_ayarlar_init');

function whatsapp_eklentim_ayarlar_init() {
    register_setting('whatsapp_eklentim_ayarlar_grubu', 'whatsapp_numarasi');
    register_setting('whatsapp_eklentim_ayarlar_grubu', 'whatsapp_mesaji');

    add_settings_section(
        'whatsapp_eklentim_bolumu',
        'WhatsApp Ayarları',
        'whatsapp_eklentim_bolum_callback',
        'whatsapp-eklentim'
    );

    add_settings_field(
        'whatsapp_numarasi',
        'WhatsApp Numarası',
        'whatsapp_numarasi_field_callback',
        'whatsapp-eklentim',
        'whatsapp_eklentim_bolumu'
    );

    add_settings_field(
        'whatsapp_mesaji',
        'WhatsApp Mesajı',
        'whatsapp_mesaji_field_callback',
        'whatsapp-eklentim',
        'whatsapp_eklentim_bolumu'
    );
}

function whatsapp_eklentim_bolum_callback() {
    echo 'WhatsApp ayarlarınızı buradan yapabilirsiniz:';
}

function whatsapp_numarasi_field_callback() {
    $whatsapp_numarasi = get_option('whatsapp_numarasi');
    echo '<input type="text" name="whatsapp_numarasi" value="' . esc_attr($whatsapp_numarasi) . '" />';
}

function whatsapp_mesaji_field_callback() {
    $whatsapp_mesaji = get_option('whatsapp_mesaji');
    echo '<textarea name="whatsapp_mesaji" rows="5">' . esc_textarea($whatsapp_mesaji) . '</textarea>';
}
add_action('wp_footer', 'whatsapp_butonunu_ekle');

function whatsapp_butonunu_ekle() {
    $whatsapp_numarasi = get_option('whatsapp_numarasi');
    $whatsapp_mesaji = get_option('whatsapp_mesaji');

    if ($whatsapp_numarasi && $whatsapp_mesaji) {
        $whatsapp_link = 'https://wa.me/' . urlencode($whatsapp_numarasi) . '?text=' . urlencode($whatsapp_mesaji);
        echo '
<style> 
.tooltip {
  position: fixed;
  right: 20px; /* Butonu sağ alt köşeye sabitliyoruz */
  bottom: 20px; /* Sağ alta dayalı */
  display: inline-block;
}

.tooltip .tooltiptext {
    visibility: hidden;
    width: 205px;
    background-color: black;
    color: #fff;
    text-align: center;
    border-radius: 20px;
    padding: 3px 0;
    position: absolute;
    z-index: 1;
    right: calc(100% + 70px);
    transform: translateY(-139%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
  opacity: 1;
}

.whatsapp-button {
  position: fixed; 
  right: 20px; /* Sağ alt köşeye sabitliyoruz */
  bottom: 20px; 
  background-color: #4ecb5a; 
  color: white; 
  padding: 10px; 
  border-radius: 50%; 
  display: flex; 
  align-items: center; 
  justify-content: center; 
  width: 60px; 
  height: 60px; 
  text-decoration: none; 
  transition: transform 0.3s ease;
}

.whatsapp-button:hover {
  transform: scale(1.25); /* Hover efekti */
}
</style>

<div class="tooltip">
    <a href="' . esc_url($whatsapp_link) . '" target="_blank" 
    class="whatsapp-button">
        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" style="width: 50px; height: 50px;"/>
    </a>
    <span class="tooltiptext">Bizimle iletişime geçin!</span>
</div>';

    }
}